<?php
/** miniOrange enables user to log in through mobile authentication as an additional layer of security over password.
    Copyright (C) 2015  miniOrange

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
* @package 		miniOrange OAuth
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
/**
This library is miniOrange Authentication Service. 
Contains Request Calls to Customer service.

**/
class MO2F_ShortCode{

function mo2f_is_customer_registered() {
	$email = get_site_option('mo2f_email');
	$customerKey = get_site_option('mo2f_customerKey');
	if(!$email || !$customerKey || !is_numeric(trim($customerKey))) {
		return 0;
	} else {
		return 1;
	}
}

function miniorange_auth_user_settings(){
	global $current_user;
	$current_user = wp_get_current_user();
		global $dbQueries;
	$mo2f_pass2fa = new Miniorange_Password_2Factor_Login();
		
	if(is_user_logged_in()){
		if(isset($_POST['mo2f_reconfig_validate_ga_nonce'])){  
			$nonce = $_POST['mo2f_reconfig_validate_ga_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-reconfig-google-auth-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', __('<strong>ERROR</strong>: Invalid Request.'));
				return $error;
			} else {
				global $current_user;
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_RECONFIG_GOOGLE';
				$mo2f_pass2fa->miniorange_pass2login_start_session();
				$otpToken = $_POST['google_auth_code'];
				
				$mo2f_google_auth = isset($_SESSION['mo2f_google_auth']) ? $_SESSION['mo2f_google_auth'] : null;
				$ga_secret = $mo2f_google_auth != null ? $mo2f_google_auth['ga_secret'] : null;
				
				
				if(MO2f_Utility::mo2f_check_number_length($otpToken)){
                        $email = $dbQueries->get_user_detail( 'mo2f_user_email', $current_user->ID );
						// get_user_meta($current_user->ID,'mo2f_user_email',true);
					$google_auth = new Miniorange_Rba_Attributes();
					$google_response = json_decode($google_auth->mo2f_validate_google_auth($email,$otpToken,$ga_secret),true);
					if(json_last_error() == JSON_ERROR_NONE) {
						if($google_response['status'] == 'SUCCESS'){
							$enduser = new Two_Factor_Setup();
                                // update_user_meta($current_user->ID, 'mo2f_selected_2factor_method', 'GOOGLE AUTHENTICATOR');
                          $dbQueries->update_user_details( $current_user->ID, array( 'mo2f_configured_2FA_method'=> 'GOOGLE AUTHENTICATOR'));
							$response = json_decode($enduser->mo2f_update_userinfo($email,'GOOGLE AUTHENTICATOR',null,null,null),true);
							if(json_last_error() == JSON_ERROR_NONE) { 
								if($response['status'] == 'SUCCESS'){
										 $dbQueries->update_user_details( $current_user->ID, array( 'mo2f_GoogleAuthenticator_config_status'=>true, 'mo2f_AuthyAuthenticator_config_status'=>false));
                                        // update_user_meta($current_user->ID,'mo2f_GoogleAuthenticator_config_status',true);
                                        // update_user_meta($current_user->ID,'mo2f_AuthyAuthenticator_config_status',false);
									update_user_meta($current_user->ID,'mo2f_external_app_type','GOOGLE AUTHENTICATOR');
									$mo2fa_login_status = 'MO2F_RECONFIGURE_SUCCESS_GOOGLE';
								}else{
									$mo2fa_login_message = 'An error occured while processing your request. Please Try again.';
								}
							}else{
								$mo2fa_login_message = 'An error occured while processing your request. Please Try again.';
							}
						}else{
							
							$mo2fa_login_message = 'Error occurred while validating the OTP. Please try again. Possible causes: <br />1. You have entered an invalid OTP.<br />2. Your App Time is not in sync.Go to settings and tap on tap on Sync Time now .';
						}
					}else{
						$mo2fa_login_message = 'Error occurred while validating the user. Please try again.';
						
					}
				}else{
					$mo2fa_login_message = 'Only digits are allowed. Please enter again.';
					
				}
				
				$this->prompt_user_for_reconfigure_google($current_user, $mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if(isset($_POST['mo2f_reconfig_save_kba_nonce'])){
			$nonce = $_POST['mo2f_reconfig_save_kba_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-reconfig-save-kba-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', __('<strong>ERROR</strong>: Invalid Request.'));
				return $error;
			} else {
				
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_RECONFIG_GOOGLE';
				$mo2f_pass2fa->miniorange_pass2login_start_session();
				
				$temp_array = array();
				$temp_array = isset($_POST['mo2f_kbaquestion']) ? $_POST['mo2f_kbaquestion'] : array();
				$kba_questions = array();
				foreach($temp_array as $question){
					if(MO2f_Utility::mo2f_check_empty_or_null( $question)){
						$mo2fa_login_message =  'All the fields are required. Please enter valid entries.';
						$mo2f_pass2fa->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
					}else{
						$ques = sanitize_text_field($question);
						$ques = addcslashes(stripslashes($ques), '"\\');
						array_push($kba_questions, $ques);
					}
				}
				
				if(!(array_unique($kba_questions) == $kba_questions)){
					$mo2fa_login_message = 'The questions you select must be unique.';
					$mo2f_pass2fa->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
				}
				
				$temp_array_ans = array();
				$temp_array_ans = isset($_POST['mo2f_kba_ans']) ? $_POST['mo2f_kba_ans'] : array();
				$kba_answers = array();
				foreach($temp_array_ans as $answer){
					if(MO2f_Utility::mo2f_check_empty_or_null( $answer)){
						$mo2fa_login_message =  'All the fields are required. Please enter valid entries.';
						$mo2f_pass2fa->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
					}else{
						$ques = sanitize_text_field($answer);
						array_push($kba_answers, $answer);
					}
				}
				
				$size = sizeof($kba_questions);
				$kba_q_a_list = array();
				for($c = 0; $c < $size; $c++){
					array_push($kba_q_a_list, $kba_questions[$c]);
					array_push($kba_q_a_list, $kba_answers[$c]);
				}
				
                    $email = $dbQueries->get_user_detail( 'mo2f_user_email', $current_user->ID );
					// get_user_meta($current_user->ID,'mo2f_user_email',true);
				$kba_registration = new Two_Factor_Setup();
				$kba_reg_reponse = json_decode($kba_registration->register_kba_details($email, $kba_q_a_list),true);
				if(json_last_error() == JSON_ERROR_NONE) { 
					if($kba_reg_reponse['status'] == 'SUCCESS'){
							 $dbQueries->update_user_details( $current_user->ID, array( 'mo2f_SecurityQuestions_config_status'=>true));
                            // update_user_meta($current_user->ID,'mo2f_KBA-SecurityQuestions_config_status',true);
						$mo2fa_login_message = 'Security Questions are configured successfully.';
						$mo2fa_login_status = 'MO2F_RECONFIGURE_SUCCESS_KBA';
					}else{
						$mo2fa_login_message = 'Error occured while saving your kba details. Please try again.';
					}
				}else{
					$mo2fa_login_message = 'Error occured while saving your kba details. Please try again.';
				}
				$this->prompt_user_for_reconfigure_kba($current_user, $mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if ( isset($_POST['mo2f_reconfig_ga_phone_type_nonce'])){	//select google phone type during reconfigure shortcode
			
			$nonce = $_POST['mo2f_reconfig_ga_phone_type_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-reconfig-ga-phone-type-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', __('<strong>ERROR</strong>: Invalid Request.'));
				return $error;
			} else {
				$mo2f_pass2fa->miniorange_pass2login_start_session();
				$phone_type = $_POST['google_phone_type'];
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_RECONFIG_GOOGLE';
				$google_auth = new Miniorange_Rba_Attributes();
				$google_response = json_decode($google_auth->mo2f_google_auth_service(get_user_meta($current_user->ID,'mo2f_user_email',true)),true);
				
				if(json_last_error() == JSON_ERROR_NONE) {
					if($google_response['status'] == 'SUCCESS'){
						
						$mo2f_google_auth = array();
						$mo2f_google_auth['ga_qrCode'] = $google_response['qrCodeData'];
						$mo2f_google_auth['ga_secret'] = $google_response['secret'];
						$mo2f_google_auth['ga_phone'] = $phone_type;
						$_SESSION['mo2f_google_auth'] = $mo2f_google_auth;
						
					}else{
						$mo2fa_login_message = 'Error occurred while registering the user for google authenticator. Please try again.';
					}
				}else{
					$mo2fa_login_message = 'Invalid request. Please try again.';
				}
				$mo2f_pass2fa->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
				
			}
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_2factor_reconfigure_form_google_save'){
			$current_selected_method = 'GOOGLE AUTHENTICATOR';
			$this-> mo2f_reconfig_2fa($current_user, $current_selected_method);
		}
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_2factor_reconfigure_form_kba_save'){
			$current_selected_method = 'KBA';
			$this-> mo2f_reconfig_2fa($current_user, $current_selected_method);
		}
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_2factor_enable_2fa'){
				$mo2f_2factor_enable_2fa_byusers=isset( $_POST['mo2f_2factor_enable_2fa_byusers']) ? 1 : 0;
			update_user_meta($current_user->ID, 'mo2f_2factor_enable_2fa_byusers', isset( $_POST['mo2f_2factor_enable_2fa_byusers']) ? 1 : 0);
				$dbQueries->update_user_details( $current_user->ID, array( 'mo2f_2factor_enable_2fa_byusers'=>$mo2f_2factor_enable_2fa_byusers));
			
		}
	}
		
}
	
function mo2f_reconfig_2fa($current_user, $mo2f_second_factor){
	$login_message = '';
	$login_status = '';
	if($mo2f_second_factor == 'GOOGLE AUTHENTICATOR' ){
		$this->prompt_user_for_reconfigure_google($current_user,$login_status, $login_message);
	}else if($mo2f_second_factor == 'KBA' ){
		$this-> prompt_user_for_reconfigure_kba($current_user,$login_status, $login_message);
	}	
}

function prompt_user_for_reconfigure_google($current_user, $login_status, $login_message){
	global $current_user;
	$current_user = wp_get_current_user();
	if($login_status == 'MO2F_RECONFIGURE_SUCCESS_GOOGLE'){
	?>
		<html>
		<head>  <meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?php
				echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
				echo '<script src="' . plugins_url('includes/js/bootstrap.min.js', __FILE__) . '" ></script>';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/bootstrap.min.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/front_end_login.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/style_settings.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/hide-login.css', __FILE__) . '" />';
			?>
		</head>
			<body>
			<div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
				<div class="mo2f-modal-backdrop"></div>
				<div class="mo2f_modal-dialog mo2f_modal-lg" style="width:999px !important;margin:0px auto !important;">
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h4><?php echo mo2f_lt('Reconfiguration successful');?></h4>
						</div>
						<div class="mo2f_modal-body center">
							<center>
								<h3><b style="color:#7EAFB7;"><?php echo mo2f_lt('Google Authenticator </b>has been reconfigured successfully..<br>');?></h3>
							</center>
							</br>
							<input type="button" name="back" id="back_btn" class="miniorange-button" onclick= "mologinback();" value="<?php echo mo2f_lt('Back');?>" />
						</div>
					</div>
				</div>
			</div>
	<form name="f" method="post" action="" id="mo2f_cancel_form">
				<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
			</body>
	<script>
			function mologinback(){
				jQuery('#mo2f_cancel_form').submit();
			}
	</script>
	<?php
	}else{
	?>
		<html>
		<head>  <meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?php
				echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
				echo '<script src="' . plugins_url('includes/js/bootstrap.min.js', __FILE__) . '" ></script>';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/bootstrap.min.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/front_end_login.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/style_settings.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/hide-login.css', __FILE__) . '" />';
			?>
		</head>
			<body>
			<div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
				<div class="mo2f-modal-backdrop"></div>
				<div class="mo2f_modal-dialog mo2f_modal-lg" style="width:999px !important;margin:0px auto !important;">
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h4><?php echo mo2f_lt('Reconfigure Google Authenticator');?></h4>
							<?php if(isset($login_message) && !empty($login_message)) {  ?>
								<div  id="otpMessage">
									<p class="mo2fa_display_message_frontend" style="text-align: left !important;"><?php echo $login_message; ?></p>
								</div>
							<?php } ?>
						</div>
						<div class="mo2f_modal-body center">
						<?php
								$mo2f_google_auth = isset($_SESSION['mo2f_google_auth']) ? $_SESSION['mo2f_google_auth'] : null;
								$data = isset($_SESSION['mo2f_google_auth']) ? $mo2f_google_auth['ga_qrCode'] : null;
								$ga_secret = isset($_SESSION['mo2f_google_auth']) ? $mo2f_google_auth['ga_secret'] : null;
								
						?>
							<table style="border:hidden;" id="mo2f_ga_tab">
								<tr>
								<td style="vertical-align:top;width:15%;padding-right:15px">
									<p style="font-size: 15px !important;">	
										<input type="radio" name="mo2f_reconfig_app_type_radio" value="android" <?php checked( $mo2f_google_auth['ga_phone'] == 'android' ); ?> /> <b>Android</b><br /><br />
										<input type="radio" name="mo2f_reconfig_app_type_radio" value="iphone" <?php checked( $mo2f_google_auth['ga_phone'] == 'iphone' ); ?> /> <b>iPhone</b><br /><br />
										<input type="radio" name="mo2f_reconfig_app_type_radio" value="blackberry" <?php checked( $mo2f_google_auth['ga_phone'] == 'blackberry' ); ?> /> <b>BlackBerry / Windows</b><br /><br />
									</form>
									
										<input type="button" name="back" id="back_btn" class="miniorange-button" style="width:45%;" onclick= "mologinback();" value="<?php echo mo2f_lt('Back');?>" />
									
									</td>
				
				
				<td style="vertical-align:top;width:28%;padding-right:15px">
					<h3><?php echo mo2f_lt('Step-2: Set up Google Authenticator');?></h3><hr>
					<div id="mo2f_android_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'android' ? 'display:block' : 'display:none'; ?>" >
					<h4><?php echo mo2f_lt('Install the Google Authenticator App for Android.');?></h4>
					<ol>
						<li><?php echo mo2f_lt('On your phone,Go to Google Play Store.');?></li>
						<li><?php echo mo2f_lt('Search for <b>Google Authenticator.</b>');?>
						<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank"><?php echo mo2f_lt('Download from the Google Play Store and install the application.');?></a>
						</li>
					
					</ol>
					<h4><?php echo mo2f_lt('Now open and configure Google Authenticator.');?></h4>
					<ol>
						<li><?php echo mo2f_lt('In Google Authenticator, touch Menu and select "Set up account."');?></li>
						<li><?php echo mo2f_lt('Select "Scan a barcode". Use your phone\'s camera to scan this barcode.');?></li>
					<center><br><div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div></center>
						
					</ol>
					<div><a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_a" aria-expanded="false" ><b><?php echo mo2f_lt('Can\'t scan the barcode? ');?></b></a></div>
					<div class="mo2f_collapse" id="mo2f_scanbarcode_a">
						<ol>
							<li><?php echo mo2f_lt('In Google Authenticator, touch Menu and select "Set up account."');?></li>
							<li><?php echo mo2f_lt('Select "Enter provided key"');?></li>
							<li><?php echo mo2f_lt('In "Enter account name" type your full email address.');?></li>
							<li><?php echo mo2f_lt('In "Enter your key" type your secret key:');?></li>
								<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
									<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
									<?php echo $ga_secret; ?>
									</div>
									<div style="font-size: 80%;color: #666666;">
									<?php echo mo2f_lt('Spaces don\'t matter.');?>
									</div>
								</div>
							<li><?php echo mo2f_lt('Key type: make sure "Time-based" is selected.');?></li>
							<li><?php echo mo2f_lt('Tap Add.');?></li>
						</ol>
					</div>
					</div>
					
					<div id="mo2f_iphone_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'iphone' ? 'display:block' : 'display:none'; ?>" >
					<h4><?php echo mo2f_lt('Install the Google Authenticator app for iPhone.');?></h4>
					<ol>
						<li><?php echo mo2f_lt('On your iPhone, tap the App Store icon.');?></li>
						<li><?php echo mo2f_lt('Search for <b>Google Authenticator.</b>');?>
						<a href="http://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><?php echo mo2f_lt('Download from the App Store and install it');?></a>
						</li>
					</ol>
					<h4><?php echo mo2f_lt('Now open and configure Google Authenticator.');?></h4>
					<ol>
						<li><?php echo mo2f_lt('In Google Authenticator, tap "+", and then "Scan Barcode."');?></li>
						<li><?php echo mo2f_lt('Use your phone\'s camera to scan this barcode.');?>
							<center><br><div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div></center>
						</li>
					</ol>
					<div><a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_i" aria-expanded="false" ><b><?php echo mo2f_lt('Can\'t scan the barcode?');?> </b></a></div>
					<div class="mo2f_collapse" id="mo2f_scanbarcode_i"  >
						<ol>
							<li><?php echo mo2f_lt('In Google Authenticator, tap +.');?></li>
							<li><?php echo mo2f_lt('Key type: make sure "Time-based" is selected.');?></li>
							<li><?php echo mo2f_lt('In "Account" type your full email address.');?></li>
							<li><?php echo mo2f_lt('In "Key" type your secret key:');?></li>
								<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
									<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
									<?php echo $ga_secret; ?>
									</div>
									<div style="font-size: 80%;color: #666666;">
									<?php echo mo2f_lt('Spaces don\'t matter.');?>
									</div>
								</div>
							<li><?php echo mo2f_lt('Tap Add.');?></li>
						</ol>
					</div>
					</div>
					
					<div id="mo2f_blackberry_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'blackberry' ? 'display:block' : 'display:none'; ?>" >
					<h4><?php echo mo2f_lt('Install the Google Authenticator app for BlackBerry');?></h4>
					<ol>
						<li><?php echo mo2f_lt('On your phone, open a web browser.Go to <b>m.google.com/authenticator.</b>');?></li>
						<li><?php echo mo2f_lt('Download and install the Google Authenticator application.');?></li>
					</ol>
					<h4><?php echo mo2f_lt('Now open and configure Google Authenticator.');?></h4>
					<ol>
						<li><?php echo mo2f_lt('In Google Authenticator, select Manual key entry.');?></li>
						<li><?php echo mo2f_lt('In "Enter account name" type your full email address.');?></li>
						<li><?php echo mo2f_lt('In "Enter key" type your secret key:');?></li>
							<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
								<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
								<?php echo $ga_secret; ?>
								</div>
								<div style="font-size: 80%;color: #666666;">
								<?php echo mo2f_lt('Spaces don\'t matter.');?>
								</div>
							</div>
						<li><?php echo mo2f_lt('Choose Time-based type of key.');?></li>
						<li><?php echo mo2f_lt('Tap Save.');?></li>
					</ol>
					</div>
					
				
				<td style="vertical-align:top;width:15%;padding-right:15px">
					<h3><?php echo mo2f_lt('Step-3: Verify and Save');?></h3><hr>
					<div style="<?php echo isset($_SESSION['mo2f_google_auth']) ? 'display:block' : 'display:none'; ?>">
					<div><?php echo mo2f_lt('Once you have scanned the barcode, enter the 6-digit verification code generated by the Authenticator app');?></div><br/>
					<form name="" method="post" id="mo2f_reconfig_verify_ga_code_form" >
						<span><b><?php echo mo2f_lt('Code:');?> </b>
						<input class="mo2f_table_textbox"  autofocus="true" required="true" pattern="[0-9]{4,8}" type="text" id="google_auth_code" name="google_auth_code" placeholder="<?php echo mo2f_lt('Enter OTP');?>" /></span><br /><br/>
									
						<input type="submit" name="validate" id="validate" class="miniorange-button" value="<?php echo mo2f_lt('Verify and Save');?>" />
						<input type="hidden" name="mo2f_reconfig_validate_ga_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-reconfig-google-auth-nonce'); ?>" />
					</form>
					</div>
				</td>
				</table>
							<?php mo2f_customize_logo() ?>
						</div>
					</div>
				</div>
			</div>
			<form name="f" method="post" id="mo2f_reconfig_app_type_ga_form" action="" style="display:none;">
				<input type="hidden" name="google_phone_type" />
				<input type="hidden" name="mo2f_reconfig_ga_phone_type_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-reconfig-ga-phone-type-nonce'); ?>" />
			</form>
			<form name="f" method="post" action="" id="mo2f_cancel_form">
				<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
			</body>
			<script>
			function mologinback(){
				jQuery('#mo2f_cancel_form').submit();
			}
			jQuery('input:radio[name=mo2f_reconfig_app_type_radio]').click(function() {
				var selectedPhone = jQuery(this).val();
				document.getElementById("mo2f_reconfig_app_type_ga_form").elements[0].value = selectedPhone;
				jQuery('#mo2f_reconfig_app_type_ga_form').submit();
			});
			 jQuery('html,body').animate({scrollTop: jQuery(document).height()}, 600);
			</script>		
	
	<?php
	}
}


function prompt_user_for_reconfigure_kba($current_user, $login_status, $login_message){
	global $current_user;
	$current_user = wp_get_current_user();
	if($login_status == 'MO2F_RECONFIGURE_SUCCESS_KBA'){
		?>
		<html>
		<head>  <meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?php
				echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
				echo '<script src="' . plugins_url('includes/js/bootstrap.min.js', __FILE__) . '" ></script>';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/bootstrap.min.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/front_end_login.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/style_settings.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/hide-login.css', __FILE__) . '" />';
			?>
		</head>
			<body>
			<div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
				<div class="mo2f-modal-backdrop"></div>
				<div class="mo2f_modal-dialog mo2f_modal-lg" style="width:999px !important;margin:0px auto !important;">
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h4><?php echo mo2f_lt('Reconfiguration successful');?></h4>
						</div>
						<div class="mo2f_modal-body center">
							<center>
								<h3><b style="color:#7EAFB7;"><?php echo mo2f_lt('Security Questions');?> </b><?php echo mo2f_lt('have been reconfigured successfully..<br>');?></h3>
							</center>
							</br>
							<input type="button" name="back" id="back_btn" class="miniorange_button" onclick= "mologinback();" value="<?php echo mo2f_lt('Back');?>" />
						</div>
					</div>
				</div>
			</div>
	<form name="f" method="post" action="" id="mo2f_cancel_form">
				<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
			</body>
	<script>
			function mologinback(){
				jQuery('#mo2f_cancel_form').submit();
			}
	</script>
	<?php
	}else{
	?>
	<html>
		<head>  <meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?php
				echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
				echo '<script src="' . plugins_url('includes/js/bootstrap.min.js', __FILE__) . '" ></script>';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/bootstrap.min.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/front_end_login.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/style_settings.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/hide-login.css', __FILE__) . '" />';
			?>
			<style>
				.mo2f_kba_ques, .mo2f_table_textbox{
					background: whitesmoke none repeat scroll 0% 0%;
				}
				.mo2f_custom_kba_table{
					table-layout: auto !important;
					border: 0px transparent;
				}
				.mo2fa_thtd{
				   border: 0px transparent;
				}
			</style>
		</head>
		<body>
			<div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
				<div class="mo2f-modal-backdrop"></div>
				<div class="mo2f_modal-dialog mo2f_modal-lg">
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h4><?php echo mo2f_lt('Reconfigure Security Questions');?></h4>
						</div>
						<div class="mo2f_modal-body">
						<form name="f" method="post" action="" >
								<?php mo2f_configure_kba_questions(); ?>
								<br />
								<div class ="row">
									<div class="col-md-12" style="margin: 0 auto;width: 100px;">
										<center><input type="submit" name="validate" class="miniorange_button" value="<?php echo mo2f_lt('Save');?>" /></center>
									</div>
								</div>
								
								<input type="hidden" name="mo2f_reconfig_kba_option" />
								<input type="hidden" name="mo2f_reconfig_save_kba_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-reconfig-save-kba-nonce'); ?>" />
						</form>
						<form name="f" method="post" action="" id="mo2f_cancel_form">
										<input type="hidden" name="option" value="mo2f_cancel_configuration" />
										<center><input type="submit" name="back" id="back_btn" class="miniorange_button" style="width:10%;" value="<?php echo mo2f_lt('Back');?>" /></center>
						</form>
							<br>
							<?php mo2f_customize_logo() ?>
						</div>
					</div>
				</div>
			</div>
			<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
				<input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
			</form>
		</body>
		<script>
			function mologinback(){
				jQuery('#mo2f_backto_mo_loginform').submit();
			}
		</script>
						
						
						
	</html>
	<?php
	}
}
	
public function mo2fa_reconfigform_ShortCode($atts= null){
	global $current_user;
	$currentuser = wp_get_current_user();
		global $dbQueries;
	$current_roles = miniorange_get_user_role($currentuser);
	$enabled = miniorange_check_if_2fa_enabled_for_roles($current_roles);
	if(is_user_logged_in() && mo2f_is_customer_registered() && $enabled){ 
		//global $current_user;
		//$current_user = wp_get_current_user();
		$html = '';
		$registered = mo2f_is_customer_registered();
		$disabled    = !$registered ? 'disabled' : '';
            $email = $dbQueries->get_user_detail( 'mo2f_user_email', $current_user->ID );
		if($email){
		
		$html .= '
		<form id="mo2f_2factor_reconfigure_form_google" action="" method="post">
					
						<input type="checkbox" id="mo2f_reconfig_google" name="mo2f_reconfig_google" value="1" '. checked( get_site_option('mo2f_reconfig_google') == 1 ).''. $disabled. ' />Reconfigure Google Authenticator<br/>
					
					<script>
						jQuery("#mo2f_reconfig_google").click(function(){
							jQuery("#mo2f_2factor_reconfigure_form_google").submit();
						});
					</script>
					<input type="hidden" name="option" value="mo2f_2factor_reconfigure_form_google_save" />
				</form>
				<form id="mo2f_2factor_reconfigure_form_kba" action="" method="post">
					
						<input type="checkbox" id="mo2f_reconfig_kba" name="mo2f_reconfig_kba" value="1" '. checked( get_site_option('mo2f_reconfig_kba') == 1 ).''. $disabled. ' />Reconfigure Security Questions<br/>	
					
					<script>
						jQuery("#mo2f_reconfig_kba").click(function(){
							jQuery("#mo2f_2factor_reconfigure_form_kba").submit();
						});
					</script>
					<input type="hidden" name="option" value="mo2f_2factor_reconfigure_form_kba_save" />
				</form>';
		return $html;
	}
	}
}
	
public function mo2fa_enable2faform_ShortCode($atts= null){
	global $current_user;
	$currentuser = wp_get_current_user();
		global $dbQueries;
	$current_roles = miniorange_get_user_role($current_user);
	$enabled = miniorange_check_if_2fa_enabled_for_roles($current_roles);
        $is_user_registered = $dbQueries->get_user_detail( 'mo2f_user_email', $current_user->ID );
	if(is_user_logged_in() && mo2f_is_customer_registered() && $enabled){ 
		global $current_user;
		$current_user = wp_get_current_user();
		$html = '';
            $enable_2fa_byusers = $dbQueries->get_user_detail( 'mo2f_2factor_enable_2fa_byusers', $current_user->ID );
			// get_user_meta($current_user->ID, 'mo2f_2factor_enable_2fa_byusers');
		
            $checked = isset($enable_2fa_byusers) ? ($dbQueries->get_user_detail( 'mo2f_2factor_enable_2fa_byusers', $current_user->ID )) ? 'checked' : ' ' : 'checked';
		$message = $is_user_registered ? "" : "<p style='font-size:12px'> Enable this checkbox to setup 2-Factor. You will be asked to set up your 2nd Factor during login after selecting above checkbox.</p>";
		
		$html = "
		<form id='mo2f_2factor_enable2fa_byusers_form' action='' method='post'>
			<input type='checkbox' id='mo2f_2factor_enable_2fa_byusers' name='mo2f_2factor_enable_2fa_byusers' " . $checked . "/>2 Factor On/Off</br>
			<input type='hidden' name='option' value='mo2f_2factor_enable_2fa'>" . $message . 
		"</form>
		<script>
			jQuery('#mo2f_2factor_enable_2fa_byusers').click(function(){
				jQuery('#mo2f_2factor_enable2fa_byusers_form').submit();
			});
		</script>";
		return $html;
		
	}
	
}

public function mo2fa_enablerba_ShortCode($atts= null){
	$form_id = get_site_option('mo2f_rba_loginform_id');
	$html = '';
				
	$html = '<script>
			jQuery(document).ready(function(){
				if(document.getElementById("loginform") != null){
					 jQuery("#loginform").on("submit", function(e){
						jQuery("#miniorange_rba_attribures").val(JSON.stringify(rbaAttributes.attributes));
					});
				}
			});
		</script>
	<p><input type="hidden" id="miniorange_rba_attribures" name="miniorange_rba_attribures" value="" /></p>';
				wp_enqueue_script( 'jquery_script', plugins_url('includes/js/rba/js/jquery-1.9.1.js', __FILE__ ));
				wp_enqueue_script( 'flash_script', plugins_url('includes/js/rba/js/jquery.flash.js', __FILE__ ));
				wp_enqueue_script( 'uaparser_script', plugins_url('includes/js/rba/js/ua-parser.js', __FILE__ ));
				wp_enqueue_script( 'client_script', plugins_url('includes/js/rba/js/client.js', __FILE__ ));
				wp_enqueue_script( 'device_script', plugins_url('includes/js/rba/js/device_attributes.js', __FILE__ ));
				wp_enqueue_script( 'swf_script', plugins_url('includes/js/rba/js/swfobject.js', __FILE__ ));
				wp_enqueue_script( 'font_script', plugins_url('includes/js/rba/js/fontdetect.js', __FILE__ ));
				wp_enqueue_script( 'murmur_script', plugins_url('includes/js/rba/js/murmurhash3.js', __FILE__ ));
				wp_enqueue_script( 'miniorange_script', plugins_url('includes/js/rba/js/miniorange-fp.js', __FILE__ )); 
return $html;
	
}


}
?>