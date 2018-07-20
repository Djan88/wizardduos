<?php

include_once dirname( __FILE__ ) . '/miniorange_2_factor_mobile_configuration.php';

	
function prompt_user_to_register($current_user, $login_status, $login_message){
	$user_email = isset($current_user->user_email) ? $current_user->user_email : '';
	
	?>
	<html>
		<head>
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
				<div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h3 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
							<?php echo __('Setup Two Factor', 'miniorange-2-factor-authentication'); ?></h3>
						</div>
						<div class="mo2f_modal-body center">
							<?php if(isset($login_message) && !empty($login_message)) {  ?>
								<div  id="otpMessage">
									<p class="mo2fa_display_message_frontend" style="text-align: left !important;"><?php echo $login_message; ?></p>
								</div><br />
							<?php } echo __('A new security system has been enabled to better protect your account. Please configure your Two-Factor Authentication method by setting up your account.', 'miniorange-2-factor-authentication'); ?>
							<br><br>
							<form name="f" id="mo2f_inline_register_user_form" method="post" action=""> 
								<center>
									<input type="email" autofocus="true" name="mo_useremail" id="mo_user_email" class="mo2f_user_email" required placeholder="<?php echo __('person@example.com', 'miniorange-2-factor-authentication'); ?>" value="<?php echo $user_email; ?>" />
								
									<br><br>
								<?php if(get_site_option('mo2f_enable_gdpr_policy')){?>
								<input type="checkbox" id="mo2f_gdpr" name="mo2f_gdpr" required />I agree to <a href="<?php echo get_site_option('mo2f_privacy_policy_link'); ?>" target="_blank"><u>terms & conditions</u></a>.<br/>
								<br/>
								<?php } ?>
								<input type="submit" name="miniorange_get_started" class="miniorange_button" value="<?php echo __('Get Started', 'miniorange-2-factor-authentication'); ?>" />
								<input type="hidden" name="miniorange_inline_user_reg_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-user-reg-nonce'); ?>" />
								</center>
							</form>	
							<?php mo2f_customize_logo() ?>
						</div>
					
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
				<input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
			</form>
		</body>
		<script>
			function mologinback(){
				jQuery('#mo2f_backto_mo_loginform').submit();
			}
			function moskipregistersubmit(){
				jQuery('#mo2f_inline_register_skip_form').submit();
			}
		</script>
	</html>
	<?php 
}
	
function prompt_user_for_validate_otp($login_status, $login_message){ ?>
	<html>
		<head>
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
				<div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
							<?php echo __('Verify Email', 'miniorange-2-factor-authentication'); ?></h4>
						</div>
						<div class="mo2f_modal-body center">
							<?php if(isset($login_message) && !empty($login_message)) {  ?>
								<div  id="otpMessage" 
								<?php if(get_option('mo2f_is_error', true)) { ?>style="background-color:#FADBD8; color:#E74C3C;?>"<?php update_option('mo2f_is_error', false);} ?>
								>
									<p class="mo2fa_display_message_frontend" style="text-align: left !important;"><?php echo $login_message; ?></p>
								</div><br />
							<?php } ?>
							<div style="padding-left:40px;padding-right:40px;">
								<form name="f" id="mo2f_inline_user_validate_otp_form" method="post" action="" > 
									<center>
										<input  autofocus="true" type="text" name="otp_token" class="mo_otp_token" id="otp_token" pattern="[0-9]{4,8}" required placeholder="<?php echo mo2f_lt('Enter the code');?>" />
									</center>
									<br />
									
									<span style="color:#1F618D;"><?php echo mo2f_lt('Didn\'t get code?');?></span> &nbsp;<a href="#resendinlineotplink" style="color:#F4D03F ;font-weight:bold;"><?php echo mo2f_lt('RESEND IT');?></a><br /><br />
									
								<div class="row">
									<div class="col-xs-6" style="padding:5px;margin-left:36%;"><input type="submit" name="miniorange_validtae_otp" value="<?php echo __('Verify Code', 'miniorange-2-factor-authentication'); ?>" class="button button-primary button-large"/></div>
									<br /><br />
								</div><br />
                                    <div class="col-xs-6"><a href="#mo2f_inline_backto_regform" style="color:#1A5276;"><b><span style="font-size:12px;">&#60;&#60; </span><?php echo __('Back', 'miniorange-2-factor-authentication'); ?></b></span></div>

									<input type="hidden" name="miniorange_inline_validate_user_otp_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-validate-user-otp-nonce'); ?>" />
									
								</form>
							</div>
							<?php mo2f_customize_logo() ?>
						</div>
				
					</div>
				</div>
			</div>
			<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
				<input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
			</form>
			<form name="f" id="mo2f_goto_user_registration_form" method="post" action="" style="display:none;">
				<input type="hidden" name="miniorange_inline_goto_user_registration_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-goto-user-registration-nonce'); ?>" />
			</form>
			<form name="f" method="post" action="" id="mo2fa_inline_resend_otp_form" style="display:none;">
				<input type="hidden" name="miniorange_inline_resend_otp_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-resend-otp-nonce'); ?>" />
			</form>
		</body>	
		<script>
			function mologinback(){
				jQuery('#mo2f_backto_mo_loginform').submit();
			}
			jQuery('a[href="#resendinlineotplink"]').click(function(e) {
				jQuery('#mo2fa_inline_resend_otp_form').submit();
			});
			jQuery('a[href="#mo2f_inline_backto_regform"]').click(function() {	
				jQuery('#mo2f_goto_user_registration_form').submit();
			});
		</script>
	</html>
	<?php 
}
	function fetch_methods($current_user)
{		
$current_user_role= $current_user->roles[0];
	global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		foreach($wp_roles->role_names as $id => $name)
		{
			
			if(get_option('mo2f_all_users_method'))
				{
					$opt = (array) get_option('mo2f_auth_methods_for_users');
				}
			else if($id==$current_user_role){
			$opt = (array)get_site_option('mo2f_auth_methods_for_'.$id);
					
				}
			
			 
			// $copt[$id]=get_site_option('mo2f_auth_methods_for_users');
		}
		return $opt;
}
function prompt_user_to_select_2factor_mthod_inline($current_user_id, $login_status, $login_message){
	global $dbQueries;
	$current_user = get_userdata($current_user_id);
	
	$current_selected_method = $dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$current_user_id);
	if($current_selected_method == 'MOBILE AUTHENTICATION' || $current_selected_method == 'SOFT TOKEN' || $current_selected_method == 'PUSH NOTIFICATIONS'){
									
		prompt_user_for_miniorange_app_setup($current_user_id, $login_status, $login_message);
											
	}else if($current_selected_method == 'SMS' || $current_selected_method == 'PHONE VERIFICATION' || $current_selected_method == 'SMS AND EMAIL'){
									
		prompt_user_for_phone_setup($current_user_id, $login_status, $login_message);
										
	}else if($current_selected_method == 'GOOGLE AUTHENTICATOR' ){
									
		prompt_user_for_google_authenticator_setup($current_user_id, $login_status, $login_message);
											
	}else if($current_selected_method == 'AUTHY 2-FACTOR AUTHENTICATION'){
									
		prompt_user_for_authy_authenticator_setup($current_user_id, $login_status, $login_message);
									
	}else if($current_selected_method == 'KBA' ){
									
		prompt_user_for_kba_setup($current_user_id, $login_status, $login_message);
									
	}else if($current_selected_method == 'OUT OF BAND EMAIL' ){
									
		prompt_user_for_setup_success($current_user_id, $login_status, $login_message);
									
	}else{
		
		$current_user = get_userdata($current_user_id);
		$current_user_role=$current_user->roles[0];
		
		
		$opt=fetch_methods($current_user);
	
	?>	
		<html>
			<head>
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
					<div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
						<div class="login mo_customer_validation-modal-content">
							<div class="mo2f_modal-header">
								<h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
								<?php echo __('Select Two Factor Method', 'miniorange-2-factor-authentication'); ?></h4>
							</div>
							<div class="mo2f_modal-body">
								<?php if(isset($_SESSION['mo2f-login-message']) && !empty($_SESSION['mo2f-login-message'])) {  ?>
									<div  id="otpMessage">
										<p class="mo2fa_display_message_frontend" style="text-align: left !important;"><?php echo __($_SESSION['mo2f-login-message'], 'miniorange-2-factor-authentication'); ?></p>
									</div>
								<?php } ?>
								
								<b><?php echo __('Select any Two-Factor of your choice below and complete its setup.', 'miniorange-2-factor-authentication'); ?></b>
								<br><br>
								
								<span class="<?php if( !(in_array("OUT OF BAND EMAIL", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
									<label title="<?php echo __('You will receive an email with link. You have to click the ACCEPT or DENY link to verify your email. Supported in Desktops, Laptops, Smartphones.', 'miniorange-2-factor-authentication'); ?>">
												<input type="radio"  name="mo2f_selected_2factor_method"  value="OUT OF BAND EMAIL"  />
												<?php echo __('Email Verification', 'miniorange-2-factor-authentication'); ?>
									</label>
									<br>
								</span>	
						
								<span class="<?php if( !(in_array("SMS", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
									
										<label title="<?php echo __('You will receive a one time passcode via SMS on your phone. You have to enter the otp on your screen to login. Supported in Smartphones, Feature Phones.', 'miniorange-2-factor-authentication'); ?>">
											<input type="radio"  name="mo2f_selected_2factor_method"  value="SMS"  />
											<?php echo __('OTP Over SMS', 'miniorange-2-factor-authentication'); ?>
										</label>
									<br>
								</span>
						
								<span class="<?php if(  !(in_array("PHONE VERIFICATION", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>">
								
										<label title="<?php echo __('You will receive a phone call telling a one time passcode. You have to enter the one time passcode to login. Supported in Landlines, Smartphones, Feature phones.', 'miniorange-2-factor-authentication'); ?>">
											<input type="radio"  name="mo2f_selected_2factor_method"  value="PHONE VERIFICATION"  />
											<?php echo __('Phone Call Verification', 'miniorange-2-factor-authentication'); ?>
										</label>
									<br>
								</span>
						
								<span class="<?php if(  !(in_array("SOFT TOKEN", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
										<label title="<?php echo __('You have to enter 6 digits code generated by miniOrange Authenticator App like Google Authenticator code to login. Supported in Smartphones only.', 'miniorange-2-factor-authentication'); ?>" >
											<input type="radio"  name="mo2f_selected_2factor_method"  value="SOFT TOKEN"  />
											<?php echo __('Soft Token', 'miniorange-2-factor-authentication'); ?>
										</label>
										
									<br>
								</span>
						
								<span class="<?php if(  !(in_array("MOBILE AUTHENTICATION", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
								
										<label title="<?php echo __('You have to scan the QR Code from your phone using miniOrange Authenticator App to login. Supported in Smartphones only.', 'miniorange-2-factor-authentication'); ?>">
											<input type="radio"  name="mo2f_selected_2factor_method"  value="MOBILE AUTHENTICATION"  />
											<?php echo __('QR Code Authentication', 'miniorange-2-factor-authentication'); ?>
										</label>
									<br>
								</span>
						
								<span class="<?php if(  !(in_array("PUSH NOTIFICATIONS", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
									
										<label title="<?php echo __('You will receive a push notification on your phone. You have to ACCEPT or DENY it to login. Supported in Smartphones only.', 'miniorange-2-factor-authentication'); ?>">
											<input type="radio"  name="mo2f_selected_2factor_method"  value="PUSH NOTIFICATIONS"  />
											<?php echo __('Push Notification', 'miniorange-2-factor-authentication'); ?>
										</label>
										<br>	
								</span>
								
								<span class="<?php if( !(in_array("GOOGLE AUTHENTICATOR", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
										
											<label title="<?php echo __('You have to enter 6 digits code generated by Google Authenticator App to login. Supported in Smartphones only.', 'miniorange-2-factor-authentication'); ?>">
												<input type="radio"  name="mo2f_selected_2factor_method"  value="GOOGLE AUTHENTICATOR"  />
												<?php echo __('Google Authenticator', 'miniorange-2-factor-authentication'); ?>
											</label>
											<br>
								</span>
								
								<span class="<?php if( !(in_array("AUTHY 2-FACTOR AUTHENTICATION", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
										
											<label title="<?php echo __('You have to enter 6 digits code generated by Authy 2-Factor Authentication App to login. Supported in Smartphones only.', 'miniorange-2-factor-authentication'); ?>">
												<input type="radio"  name="mo2f_selected_2factor_method"  value="AUTHY 2-FACTOR AUTHENTICATION"  />
												<?php echo __('Authy 2-Factor Authentication', 'miniorange-2-factor-authentication'); ?>
											</label>
											<br>
								</span>
					
								<span class="<?php if( !(in_array("KBA", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
										
									<label title="<?php echo __('You have to answers some knowledge based security questions which are only known to you to authenticate yourself. Supported in Desktops,Laptops,Smartphones.', 'miniorange-2-factor-authentication'); ?>" >
									<input type="radio"  name="mo2f_selected_2factor_method"  value="KBA"  />
												<?php echo __('Security Questions ( KBA )', 'miniorange-2-factor-authentication'); ?>
											</label>
											<br>
								</span>
								
								<span class="<?php if( !(in_array("SMS AND EMAIL", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
										
									<label title="<?php echo __('You will receive a one time passcode via SMS on your phone and your email. You have to enter the otp on your screen to login. Supported in Smartphones, Feature Phones.', 'miniorange-2-factor-authentication'); ?>" >
									<input type="radio"  name="mo2f_selected_2factor_method"  value="SMS AND EMAIL"  />
												<?php echo __('OTP Over SMS and Email', 'miniorange-2-factor-authentication'); ?>
											</label>
											<br>
								</span>
								<span class="<?php if( !(in_array("OTP_OVER_EMAIL", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
										
									<label title="<?php echo __('You will receive a one time passcode on your email. You have to enter the otp on your screen to login. Supported in Smartphones, Feature Phones.', 'miniorange-2-factor-authentication'); ?>" >
									<input type="radio"  name="mo2f_selected_2factor_method"  value="OTP OVER EMAIL"  />
												<?php echo __('OTP Over Email', 'miniorange-2-factor-authentication'); ?>
											</label>
											
								</span>
								
								<br /><br />
								<?php mo2f_customize_logo() ?>
							</div>
						</div>
					</div>
				</div>
				<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
					<input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
				</form>
				<form name="f" method="post" action="" id="mo2f_select_2fa_methods_form" style="display:none;">
					<input type="hidden" name="mo2f_selected_2factor_method" />
					<input type="hidden" name="miniorange_inline_save_2factor_method_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-save-2factor-method-nonce'); ?>" />
				</form>
			</body>
			<script>
				function mologinback(){
					jQuery('#mo2f_backto_mo_loginform').submit();
				}
				jQuery('input:radio[name=mo2f_selected_2factor_method]').click(function() {
					var selectedMethod = jQuery(this).val();

					document.getElementById("mo2f_select_2fa_methods_form").elements[0].value = selectedMethod;
					jQuery('#mo2f_select_2fa_methods_form').submit();
				});
		
			</script>
		</html>
<?php 
	} 
}
		
function prompt_user_for_authy_authenticator_setup($current_user_id, $login_status, $login_message){
	$mo2f_authy_auth = isset($_SESSION['mo2f_authy_keys']) ? $_SESSION['mo2f_authy_keys'] : null;
	$data = isset($_SESSION['mo2f_authy_keys']) ? $mo2f_authy_auth['authy_qrCode'] : null;
	$authy_secret = isset($_SESSION['mo2f_authy_keys']) ? $mo2f_authy_auth['authy_secret'] : null;
	
	global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		 $current_user = get_userdata($current_user_id);
		$current_user_role=$current_user->roles[0];
		foreach($wp_roles->role_names as $id => $name)
		{
			
            if(get_option('mo2f_all_users_method'))
				{
	$opt = (array) get_site_option('mo2f_auth_methods_for_users'); 
				}
			else if($id==$current_user_role){
			$opt = (array)get_site_option('mo2f_auth_methods_for_'.$id);
					
				}
              			
		}
	
?>
	<html>
		<head>
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
							<h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login', 'miniorange-2-factor-authentication'); ?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
							<?php echo __('Set up Authy 2-Factor Authentication', 'miniorange-2-factor-authentication'); ?></h4>
						</div>
						<div class="mo2f_modal-body center">
							<?php if(isset($login_message) && !empty($login_message)) {  ?>
								<div  id="otpMessage">
									<p class="mo2fa_display_message_frontend" style="text-align: left !important;"><?php echo __($login_message, 'miniorange-2-factor-authentication'); ?> </p>
								</div>
								<?php if(isset($login_message)) {?> <br/> <?php } ?>
							<?php } ?>
							<table>
								<tr>
									<td style="vertical-align:top;width:30%;padding-right:15px">
										<h4><?php echo __('Step-1: Configure with Authy 2-Factor Authentication App.', 'miniorange-2-factor-authentication'); ?></h4><hr />
											<br />
											<form name="f" method="post" id="mo2f_inline_authy_configure_form" action="">
												<input type="submit" name="mo2f_authy_configure" id="mo2f_authy_configure" class="miniorange-button" style="width:45%;" value="<?php echo __('Configure', 'miniorange-2-factor-authentication'); ?>" />
												<input type="hidden" name="mo2f_inline_authy_configure_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-authy-configuration-nonce'); ?>" />
											</form>
											<br /><br />
											<?php if (sizeof($opt) > 1) { ?>
												<form name="f" method="post" action="" id="mo2f_goto_two_factor_form">
													<input type="submit" name="back" id="mo2f_inline_back_btn" class="miniorange-button" value="<?php echo __('Back', 'miniorange-2-factor-authentication'); ?>" />
													<input type="hidden" name="miniorange_inline_two_factor_setup" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-setup-nonce'); ?>" />
												</form>
											<?php } ?>
										
									</td>
									<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
									<td style="width:46%;padding-right:15px;vertical-align:top;">
										<h4><?php echo __('Step-2: Set up Authy 2-Factor Authentication App', 'miniorange-2-factor-authentication'); ?></h4><hr>
										<div style="<?php echo isset($_SESSION['mo2f_authy_keys']) ? 'display:block' : 'display:none'; ?> ;	font-size:15px;">
										<p><?php echo __('Install the Authy 2-Factor Authentication App.', 'miniorange-2-factor-authentication'); ?></p>
										<p><?php echo __('Now open and configure Authy 2-Factor Authentication App.', 'miniorange-2-factor-authentication'); ?></p>
										<p><?php echo __('Tap on Add Account and then tap on SCAN QR CODE in your App and scan the qr code.', 'miniorange-2-factor-authentication'); ?> </p>
										<center><br><div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div>
										<br />
										<div><a  data-toggle="mo2f_collapse" href="#mo2f_authy_scan" aria-expanded="false"  style="color:#21618C;" ><b><?php echo __('Cant scan the QR Code?', 'miniorange-2-factor-authentication'); ?></a></div></center>
										<div class="mo2f_collapse mo_margin_left" id="mo2f_authy_scan" >
											<ol>
												<li><?php echo __('In Authy 2-Factor Authentication App, tap on ENTER KEY MANUALLY.', 'miniorange-2-factor-authentication'); ?></li>
												<li><?php echo __('In "Adding New Account" type your secret key:', 'miniorange-2-factor-authentication'); ?></li>
													<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
														<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
														<?php echo $authy_secret; ?>
														</div>
														<div style="font-size: 80%;color: #666666;">
														<?php echo __('Spaces do not matter.', 'miniorange-2-factor-authentication'); ?>
														</div>
													</div>
												<li><?php echo __('Tap OK.', 'miniorange-2-factor-authentication'); ?></li>
											</ol>
										</div>
										</div>
									</td>
									<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
									<td style="vertical-align:top;width:30%">
										<h4><?php echo __('Step-3: Verify and Save', 'miniorange-2-factor-authentication'); ?></h4><hr>
										<div style="<?php echo isset($_SESSION['mo2f_authy_keys']) ? 'display:block' : 'display:none'; ?>;font-size:15px;">
											<p><?php echo __('Once you have scanned the qr code, enter the verification code generated by the Authenticator app', 'miniorange-2-factor-authentication'); ?></p><br/>
											<form name="" method="post" id="mo2f_inline_validate_authy_authentication_form" >
												<span><b><?php echo __('Code', 'miniorange-2-factor-authentication'); ?>: </b>
													<input class="mo2f_IR_GA_token" style="width:200px;" autofocus="true" required="true" type="text" id="authy_auth_code" name="authy_auth_code" pattern="[0-9]{4,8}" placeholder="<?php echo __('Enter OTP', 'miniorange-2-factor-authentication'); ?>" style="width:95%;"/>
												</span><br /><br />
												<input type="submit" name="validate" id="mo2f_authy_validate" class="miniorange-button" value="<?php echo __('Verify and Save', 'miniorange-2-factor-authentication'); ?>" />
												<input type="hidden" name="mo2f_inline_validate_authy_authentication_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-authy-authentication-nonce'); ?>" />
											</form>
										</div>
									</td>
								</tr>
							</table>
							<br/>
							<?php if (sizeof($opt) > 1) { ?>
								<form name="f" method="post" action="" id="mo2f_goto_two_factor_form">
									<input type="submit" name="back" id="mo2f_inline_back_btn" class="miniorange_button" value="<?php echo mo2f_lt('Back');?>" />
									<input type="hidden" name="miniorange_inline_two_factor_setup" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-setup-nonce'); ?>" />
								</form>
							<?php } ?>

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
		
function prompt_user_for_google_authenticator_setup($current_user_id, $login_status, $login_message){
	
?>
	<html>
		<head>
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
							<h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
							<?php echo __('Setup Google Authenticator', 'miniorange-2-factor-authentication'); ?></h4>
						</div>
						<div class="mo2f_modal-body center">
							<?php
								$mo2f_google_auth = isset($_SESSION['mo2f_google_auth']) ? $_SESSION['mo2f_google_auth'] : null;
								$data = isset($_SESSION['mo2f_google_auth']) ? $mo2f_google_auth['ga_qrCode'] : null;
								$ga_secret = isset($_SESSION['mo2f_google_auth']) ? $mo2f_google_auth['ga_secret'] : null;
							
							$current_user = get_userdata($current_user_id);	
							$opt=fetch_methods($current_user);	
								
							?>
			
							<?php if(isset($login_message) && !empty($login_message)) {  ?>
								<div  id="otpMessage"
								<?php if(get_user_meta($current_user_id, 'mo2f_is_error', true)) { ?>style="background-color:#FADBD8; color:#E74C3C;?>"<?php update_user_meta($current_user_id, 'mo2f_is_error', false);} ?>
								>
									<p class="mo2fa_display_message_frontend" style="text-align: left !important;"><?php echo __($login_message, 'miniorange-2-factor-authentication'); ?></p>
								</div>
								<?php if(isset($login_message)) {?> <br/> <?php } ?>
							<?php } ?>
							
							<table style="border:hidden;" id="mo2f_ga_tab">
								<tr>
									<td style="vertical-align:top;width:200px !important;border: none !important;">
										<div style="font-size: 18px !important;"><b><?php echo __('Select Phone Type', 'miniorange-2-factor-authentication'); ?> </b> </div>
										<br>
										<p style="font-size: 15px !important;">	
										<input type="radio" name="mo2f_inline_app_type_radio" value="android" <?php checked( $mo2f_google_auth['ga_phone'] == 'android' ); ?> /> <b><?php echo __('Android', 'miniorange-2-factor-authentication'); ?> </b> <br /><br />
										<input type="radio" name="mo2f_inline_app_type_radio" value="iphone" <?php checked( $mo2f_google_auth['ga_phone'] == 'iphone' ); ?> /> <b><?php echo __('iPhone', 'miniorange-2-factor-authentication'); ?> </b> <br /><br />
										<input type="radio" name="mo2f_inline_app_type_radio" value="blackberry" <?php checked( $mo2f_google_auth['ga_phone'] == 'blackberry' ); ?> /> <b><?php echo __('BlackBerry', 'miniorange-2-factor-authentication'); ?><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Windows', 'miniorange-2-factor-authentication'); ?><br /> </b> <br /><br /></p>
									</td>
									<td class="mo2f_separator mo2f_ga_table"></td>
									<td style="width:400px;border: none !important;">
										<div id="mo2f_android_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'android' ? 'display:block' : 'display:none'; ?>">
											<div style="font-size: 18px !important;"><b><?php echo __('Install the Google Authenticator App for Android.', 'miniorange-2-factor-authentication'); ?> </b> </div>
											<ol>
												<li class="mo2f_list"><?php echo __('On your phone,Go to Google Play Store.', 'miniorange-2-factor-authentication'); ?></li>
												<li class="mo2f_list"><?php echo __('Search for', 'miniorange-2-factor-authentication'); ?> <b><?php echo __('Google Authenticator.', 'miniorange-2-factor-authentication'); ?></b>
												<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank"  style="color:#2E86C1;"><?php echo __('Download from the Google Play Store and install the application.', 'miniorange-2-factor-authentication'); ?></a>
												</li>
											
											</ol>
											<div style="font-size: 18px !important;"><?php echo __('Now open and configure Google Authenticator.', 'miniorange-2-factor-authentication'); ?></div>
											<ol >
												<li class="mo2f_list"><?php echo __('In Google Authenticator, touch Menu and select "Set up account."', 'miniorange-2-factor-authentication'); ?></li>
												<li class="mo2f_list"><?php echo __('Select "Scan a barcode". Use your phone\'s camera to scan this barcode.', 'miniorange-2-factor-authentication'); ?></li>
												<center><br>
													<div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div>
													<br>
													<div><a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_a" aria-expanded="false" style="color:#21618C;"><b><?php echo __('Can\'t scan the barcode?', 'miniorange-2-factor-authentication'); ?></b></a></div>
												</center>				
											</ol>
									
											<div class="mo2f_collapse" id="mo2f_scanbarcode_a">
												<ol >
													<li class="mo2f_list"><?php echo __('In Google Authenticator, touch Menu and select "Set up account."', 'miniorange-2-factor-authentication'); ?></li>
													<li class="mo2f_list"><?php echo __('Select "Enter provided key"', 'miniorange-2-factor-authentication'); ?></li>
													<li class="mo2f_list"><?php echo __('In "Enter account name" type your full email address.', 'miniorange-2-factor-authentication'); ?></li>
													<li class="mo2f_list"><?php echo __('In "Enter your key" type your secret key:', 'miniorange-2-factor-authentication'); ?></li>
														<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
															<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
															<?php echo $ga_secret; ?>
															</div>
															<div style="font-size: 80%;color: #666666;">
															<?php echo __('Spaces don\'t matter.', 'miniorange-2-factor-authentication'); ?>
															</div>
														</div>
													<li class="mo2f_list"><?php echo __('Key type: make sure "Time-based" is selected.', 'miniorange-2-factor-authentication'); ?></li>
													<li class="mo2f_list"><?php echo __('Tap Add.', 'miniorange-2-factor-authentication'); ?></li>
												</ol>
											</div>
							
										</div>
					
										<div id="mo2f_iphone_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'iphone' ? 'display:block' : 'display:none'; ?>">
											<div style="font-size: 18px !important;"><b><?php echo __('Install the Google Authenticator app for iPhone.', 'miniorange-2-factor-authentication'); ?> </b> </div>
											<ol>
												<li class="mo2f_list"><?php echo __('On your iPhone, tap the App Store icon.', 'miniorange-2-factor-authentication'); ?></li>
												<li class="mo2f_list"><?php echo __('Search for', 'miniorange-2-factor-authentication'); ?> <b><?php echo __('Google Authenticator.', 'miniorange-2-factor-authentication'); ?></b>
												<a href="http://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><?php echo __('Download from the App Store and install it.', 'miniorange-2-factor-authentication'); ?></a>
												</li>
											</ol>
											<div style="font-size: 18px !important;"><?php echo __('Now open and configure Google Authenticator.', 'miniorange-2-factor-authentication'); ?></div>
											<ol >
												<li class="mo2f_list"><?php echo __('In Google Authenticator, tap "+", and then "Scan Barcode."', 'miniorange-2-factor-authentication'); ?></li>
												<li class="mo2f_list"><?php echo __('Use your phone\'s camera to scan this barcode.', 'miniorange-2-factor-authentication'); ?>
													<br><br /><div id="displayQrCode" >
													<center>
														<?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?>
														<br />
														<br />
														<a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_i" aria-expanded="false"style="color:#21618C;"><b><?php echo __('Can\'t scan the barcode?', 'miniorange-2-factor-authentication'); ?></b></a>
													</center>
													</div>
													
											<div class="mo2f_collapse" id="mo2f_scanbarcode_i"  >
												<ol >
													<li class="mo2f_list"><?php echo __('In Google Authenticator, tap +.', 'miniorange-2-factor-authentication'); ?></li>
													<li class="mo2f_list"><?php echo __('Key type: make sure "Time-based" is selected.', 'miniorange-2-factor-authentication'); ?></li>
													<li class="mo2f_list"><?php echo __('In "Account" type your full email address.', 'miniorange-2-factor-authentication'); ?></li>
													<li class="mo2f_list"><?php echo __('In "Key" type your secret key:', 'miniorange-2-factor-authentication'); ?></li>
														<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
															<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
															<?php echo $ga_secret; ?>
															</div>
															<div style="font-size: 80%;color: #666666;">
															<?php echo __('Spaces don\'t matter.', 'miniorange-2-factor-authentication'); ?>
															</div>
														</div>
													<li class="mo2f_list"><?php echo __('Tap Add.', 'miniorange-2-factor-authentication'); ?></li>
												</ol>
											</div>
												</li>
											</ol>
											<br>
											
										</div>
										<div id="mo2f_blackberry_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'blackberry' ? 'display:block' : 'display:none'; ?>">
											<div style="font-size: 18px !important;"><b><?php echo __('Install the Google Authenticator app for BlackBerry', 'miniorange-2-factor-authentication'); ?> </b> </div>
											
											<ol >
												<li class="mo2f_list"><?php echo __('On your phone, open a web browser.Go to', 'miniorange-2-factor-authentication'); ?> <b>m.google.com/authenticator</b>.</li>
												<li class="mo2f_list"><?php echo __('Download and install the Google Authenticator application.', 'miniorange-2-factor-authentication'); ?></li>
											</ol>
											<div style="font-size: 18px !important;"><?php echo __('Now open and configure Google Authenticator.', 'miniorange-2-factor-authentication'); ?></div>
											<ol>
												<li class="mo2f_list"><?php echo __('In Google Authenticator, select Manual key entry.', 'miniorange-2-factor-authentication'); ?></li>
												<li class="mo2f_list"><?php echo __('In "Enter account name" type your full email address.', 'miniorange-2-factor-authentication'); ?></li>
												<li class="mo2f_list"><?php echo __('In "Enter key" type your secret key:', 'miniorange-2-factor-authentication'); ?></li>
													<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
														<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
														<?php echo $ga_secret; ?>
														</div>
														<div style="font-size: 80%;color: #666666;">
														<?php echo __('Spaces don\'t matter.', 'miniorange-2-factor-authentication'); ?>
														</div>
													</div>
												<li class="mo2f_list"><?php echo __('Choose Time-based type of key.', 'miniorange-2-factor-authentication'); ?></li>
												<li class="mo2f_list"><?php echo __('Tap Save.', 'miniorange-2-factor-authentication'); ?></li>
											</ol>
										</div>
										<br />
									</td>
									<td class="mo2f_separator mo2f_ga_table"></td>
									<td style="vertical-align:top;border: none !important;">
										<div style="<?php echo isset($_SESSION['mo2f_google_auth']) ? 'display:block' : 'display:none'; ?>">
											<div style="font-size: 18px !important;"><b><?php echo __('Verify and Save', 'miniorange-2-factor-authentication'); ?> </b> </div><br />
											<div style="font-size: 15px !important;"><?php echo __('Once you have scanned the barcode, enter the 6-digit verification code generated by the Authenticator app', 'miniorange-2-factor-authentication'); ?></div><br />
											<form name="" method="post" id="mo2f_inline_verify_ga_code_form" >
												<span><b><?php echo __('Code:', 'miniorange-2-factor-authentication'); ?> </b>
												<br />
												<input class="mo2f_IR_GA_token"  autofocus="true" required="true" pattern="[0-9]{4,8}" type="text" id="google_auth_code" name="google_auth_code" placeholder="<?php echo __('Enter OTP', 'miniorange-2-factor-authentication'); ?>" /></span><br />
									
												<input type="submit" name="validate" id="validate" class="miniorange_button" value="<?php echo __('Verify and Save', 'miniorange-2-factor-authentication'); ?>" />
												<input type="hidden" name="mo2f_inline_validate_ga_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-google-auth-nonce'); ?>" />
											</form>
										</div>
									</td>
								</tr>
							</table>
							<br>
							<?php if (sizeof($opt) > 1) { ?>
								<form name="f" method="post" action="" id="mo2f_goto_two_factor_form">
									<input type="submit" name="back" id="mo2f_inline_back_btn" class="miniorange_button" value="<?php echo mo2f_lt('Back');?>" />
									<input type="hidden" name="miniorange_inline_two_factor_setup" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-setup-nonce'); ?>" />
								</form>
							<?php } ?>
							<br>
							<?php mo2f_customize_logo() ?>
						</div>
					</div>
				</div>
			</div>
			<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
				<input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
			</form>
			<form name="f" method="post" id="mo2f_inline_app_type_ga_form" action="" style="display:none;">
				<input type="hidden" name="google_phone_type" />
				<input type="hidden" name="mo2f_inline_ga_phone_type_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-ga-phone-type-nonce'); ?>" />
			</form>
			
		</body>
		<script>
			function mologinback(){
				jQuery('#mo2f_backto_mo_loginform').submit();
			}
			jQuery('input:radio[name=mo2f_inline_app_type_radio]').click(function() {
				var selectedPhone = jQuery(this).val();
				document.getElementById("mo2f_inline_app_type_ga_form").elements[0].value = selectedPhone;
				jQuery('#mo2f_inline_app_type_ga_form').submit();
			});
			</script>
	
	<?php
	 }
	 
function prompt_user_for_phone_setup($current_user_id, $login_status, $login_message){ 
$current_user = get_userdata($current_user_id);	
							$opt=fetch_methods($current_user);	
	global $dbQueries;
	
	$current_selected_method = $dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$current_user_id);
?>
	<html>
		<head>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?php
				echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
				echo '<script src="' . plugins_url('includes/js/bootstrap.min.js', __FILE__) . '" ></script>';
				echo '<script src="' . plugins_url('includes/js/phone.js', __FILE__) . '" ></script>';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/bootstrap.min.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/front_end_login.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/style_settings.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/hide-login.css', __FILE__) . '" />';
				echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('includes/css/phone.css', __FILE__) . '" />';
			?>
		</head>
		<body>
			<div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
				<div class="mo2f-modal-backdrop"></div>
				<div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md" >
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
							<?php
							if($current_selected_method == 'SMS AND EMAIL'){?>
							<?php 	echo __('Verify Your Phone and Email', 'miniorange-2-factor-authentication'); ?></h4>
							<?php }
							else if($current_selected_method == 'OTP OVER EMAIL'){
							?>
							<?php echo __('Verify Your EMAIL', 'miniorange-2-factor-authentication'); ?></h4>
							<?php }
							else{
							?>
							<?php 	echo __('Verify Your Phone', 'miniorange-2-factor-authentication'); ?></h3>
							<?php } ?>
						</div>
						<div class="mo2f_modal-body">
							<?php if(isset($login_message) && !empty($login_message)) {  ?>
								<div  id="otpMessage" 
								<?php if(get_user_meta($current_user_id, 'mo2f_is_error', true)) { ?>style="background-color:#FADBD8; color:#E74C3C;?>"<?php update_user_meta($current_user_id, 'mo2f_is_error', false);} ?>
								>
									<p class="mo2fa_display_message_frontend" style="text-align: left !important; "> <?php echo $login_message; ?></p>
								</div>
								<?php if(isset($login_message)) {?> <br/> <?php } ?>
							<?php } ?>
							<div class="mo2f_row">
								<form name="f" method="post" action="" id="mo2f_inline_verifyphone_form">
									
									<p>
									<?php
									if($current_selected_method == 'SMS AND EMAIL'){?>
									<?php echo __('Enter your phone number. An One Time Passcode(OTP) wll be sent to this number and your email address.', 'miniorange-2-factor-authentication'); ?></p>
									<?php 
									}else if($current_selected_method == 'OTP OVER EMAIL'){
										//no message
									}else{
									?>
									<?php echo __('Enter your phone number', 'miniorange-2-factor-authentication'); ?></h4>
									<?php } 
									if(!($current_selected_method == 'OTP OVER EMAIL')){
									?>	
									<input class="mo2f_table_textbox"  type="text" name="verify_phone" id="phone"
										value="<?php if( isset($_SESSION['mo2f_phone'])){ echo $_SESSION['mo2f_phone'];} else echo get_user_meta($current_user_id,'mo2f_user_phone',true); ?>" pattern="[\+]?[0-9]{1,4}\s?[0-9]{7,12}" required="true" title="<?php echo __('Enter phone number without any space or dashes', 'miniorange-2-factor-authentication'); ?>" /><br />
									<?php } ?>
							
									<?php
									$email = $dbQueries->get_user_detail( 'mo2f_user_email',$current_user_id);
									
									if($current_selected_method == 'SMS AND EMAIL' ||$current_selected_method == 'OTP OVER EMAIL' ){?>
										<input class="mo2f_IR_phone"  type="text" name="verify_email" id="email"
										value="<?php if( isset($_SESSION['mo2f_email'])){ echo $_SESSION['mo2f_email'];} else echo $email ; ?>"  title="<?php echo __('Enter your email', 'miniorange-2-factor-authentication'); ?>" style="width: 250px;" disabled /><br />
									<?php } ?>	
									<input type="submit" name="verify" class="miniorange_button" value="<?php echo __('Send OTP', 'miniorange-2-factor-authentication'); ?>" />
									<input type="hidden" name="miniorange_inline_verify_phone_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-verify-phone-nonce'); ?>" />
								</form>
							</div>	
							<form name="f" method="post" action="" id="mo2f_inline_validateotp_form" >
								
								<p>
								<?php
									if($current_selected_method == 'SMS AND EMAIL'){?>
								<h4><?php echo __('Enter One Time Passcode', 'miniorange-2-factor-authentication'); ?></h4>
									<?php }
									else{
									?>
									<?php echo mo2f_lt('Please enter the One Time Passcode sent to your phone.');?></p>
								<?php } ?>
								
								<input class="mo2f_IR_phone_OTP"  required="true" pattern="[0-9]{4,8}" autofocus="true" type="text" name="otp_token" placeholder="<?php echo __('Enter the code', 'miniorange-2-factor-authentication'); ?>" id="otp_token"/><br>
								
								<span style="color:#1F618D;"><?php echo mo2f_lt('Didn\'t get code?');?></span> &nbsp;
								<?php if ($current_selected_method == 'PHONE VERIFICATION'){ ?>
									<a href="#resendsmslink" style="color:#F4D03F ;font-weight:bold;"><?php echo __('CALL AGAIN', 'miniorange-2-factor-authentication'); ?></a>
								<?php } else {?>
									<a href="#resendsmslink" style="color:#F4D03F ;font-weight:bold;"><?php echo __('RESEND IT', 'miniorange-2-factor-authentication'); ?></a>
								<?php } ?>
								<br /><br />
								<input type="submit" name="validate" class="miniorange_button" value="<?php echo __('Verify Code', 'miniorange-2-factor-authentication'); ?>" />
								<?php if (sizeof($opt) > 1) { ?>
									<input type="button" name="back" id="mo2f_inline_back_btn" class="miniorange_button" value="<?php echo __('Back', 'miniorange-2-factor-authentication'); ?>" />
								<?php } ?>
								<input type="hidden" name="miniorange_inline_validate_otp_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-validate-otp-nonce'); ?>" />
							</form>
							<?php mo2f_customize_logo() ?>
						</div>
					</div>
				</div>
			</div>
			<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
				<input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
			</form>
			<form name="f" method="post" action="" id="mo2fa_inline_resend_otp_form" style="display:none;">
				<input type="hidden" name="miniorange_inline_resend_otp_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-resend-otp-nonce'); ?>" />
			</form>
			<?php if (sizeof($opt) > 1) { ?>
			<form name="f" method="post" action="" id="mo2f_goto_two_factor_form" >
					<input type="hidden" name="miniorange_inline_two_factor_setup" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-setup-nonce'); ?>" />
			</form>
			<?php } ?>
		</body>
		<script>
			jQuery("#phone").intlTelInput();
			function mologinback(){
				jQuery('#mo2f_backto_mo_loginform').submit();
			}
			
			jQuery('#mo2f_inline_back_btn').click(function() {	
					jQuery('#mo2f_goto_two_factor_form').submit();
			});
			
			jQuery('a[href="#resendsmslink"]').click(function(e) {
				jQuery('#mo2fa_inline_resend_otp_form').submit();
			});
		</script>
	</html>
<?php 
}


function prompt_user_for_miniorange_app_setup($current_user_id, $login_status, $login_message){ 
	$current_user = get_userdata($current_user_id);	
	$opt=fetch_methods($current_user);	
	global $dbQueries;
	$mobile_registration_status = $dbQueries->get_user_detail( 'mo_2factor_mobile_registration_status',$current_user_id);
	?>
	<html>
		<head>
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
				<div class="mo2f_modal-dialog mo2f_modal-lg" >
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login', 'miniorange-2-factor-authentication'); ?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
							<?php echo __('Setup miniOrange', 'miniorange-2-factor-authentication'); ?> <b><?php echo __('Authenticator', 'miniorange-2-factor-authentication'); ?></b> <?php echo __('App', 'miniorange-2-factor-authentication'); ?></h4>
						</div>
						<div class="mo2f_modal-body">
							<?php if(isset($login_message) && !empty($login_message)) {  ?>
								<div  id="otpMessage">
									<p class="mo2fa_display_message_frontend" style="text-align: left !important;"><?php echo __($login_message, 'miniorange-2-factor-authentication'); ?></p>
								</div>
							<?php } ?>
							<div style="margin-right:7px;"><?php download_instruction_for_mobile_app($current_user_id,$mobile_registration_status); ?></div>
							<div class="mo_margin_left">
								<h3><?php echo __('Step-2 : Scan QR code', 'miniorange-2-factor-authentication'); ?></h3><hr class="mo_hr">
								<div id="mo2f_configurePhone"><h4><?php echo __('Please click on \'Configure your phone\' button below to see QR Code.', 'miniorange-2-factor-authentication'); ?></h4>
									<center>
									<?php if (sizeof($opt) > 1) { ?>
										<input type="button" name="back" id="mo2f_inline_back_btn" class="miniorange_button" value="<?php echo __('Back', 'miniorange-2-factor-authentication'); ?>" />
									<?php } ?>
										<input type="button" name="submit" onclick="moconfigureapp();" class="miniorange_button" value="<?php echo __('Configure your phone', 'miniorange-2-factor-authentication'); ?>" />
									</center>
								</div>
								<?php 
									if(isset($_SESSION[ 'mo2f_show_qr_code' ]) && $_SESSION[ 'mo2f_show_qr_code' ] == 'MO_2_FACTOR_SHOW_QR_CODE' && isset($_POST['miniorange_inline_show_qrcode_nonce']) && wp_verify_nonce( $_POST['miniorange_inline_show_qrcode_nonce'], 'miniorange-2-factor-inline-show-qrcode-nonce' )){
										initialize_inline_mobile_registration($current_user); ?>
								<?php } ?>
								<br>
							</div>
							<br>
							<?php mo2f_customize_logo() ?>
						</div>
					</div>
				</div>
			</div>
			<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
				<input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
			</form>
			<form name="f" method="post" action="" id="mo2f_inline_configureapp_form" style="display:none;">
				<input type="hidden" name="miniorange_inline_show_qrcode_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-show-qrcode-nonce'); ?>" />
			</form>
			<form name="f" method="post" id="mo2f_inline_mobile_register_form" action="" style="display:none;">
				<input type="hidden" name="mo_auth_inline_mobile_registration_complete_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-mobile-registration-complete-nonce'); ?>" />
			</form>
			<?php if (sizeof($opt) > 1) { ?>
				<form name="f" method="post" action="" id="mo2f_goto_two_factor_form">
					<input type="hidden" name="miniorange_inline_two_factor_setup" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-setup-nonce'); ?>" />
				</form>
			<?php } ?>
		<script>
			function mologinback(){
				jQuery('#mo2f_backto_mo_loginform').submit();
			}
			
			function moconfigureapp(){
				jQuery('#mo2f_inline_configureapp_form').submit();
			}
			jQuery('#mo2f_inline_back_btn').click(function() {	
					jQuery('#mo2f_goto_two_factor_form').submit();
			});
			<?php 
				if(isset($_SESSION[ 'mo2f_show_qr_code' ]) && $_SESSION[ 'mo2f_show_qr_code' ] == 'MO_2_FACTOR_SHOW_QR_CODE' && isset($_POST['miniorange_inline_show_qrcode_nonce']) && wp_verify_nonce( $_POST['miniorange_inline_show_qrcode_nonce'], 'miniorange-2-factor-inline-show-qrcode-nonce' )){ 
			?>
									
			<?php } ?>
		</script>
		</body>
	</html>
<?php 
}
	
function initialize_inline_mobile_registration($current_user){
		$data = $_SESSION[ 'mo2f-login-qrCode' ];
		$url = get_site_option('mo2f_host_name');
		
		$opt=fetch_methods($current_user);	
		?>
		
			<p><?php echo __('Open your miniOrange', 'miniorange-2-factor-authentication'); ?><b> <?php echo __('Authenticator', 'miniorange-2-factor-authentication'); ?></b> <?php echo __('app and click on', 'miniorange-2-factor-authentication'); ?> <b><?php echo __('Configure button', 'miniorange-2-factor-authentication'); ?> </b> <?php echo __('to scan the QR Code. Your phone should have internet connectivity to scan QR code.', 'miniorange-2-factor-authentication'); ?> </p>
			<div class="red" style="color:E74C3C;">
			<p><?php echo __('I am not able to scan the QR code,', 'miniorange-2-factor-authentication'); ?> <a  data-toggle="mo2f_collapse" href="#mo2f_scanqrcode" aria-expanded="false"  style="color:#3498DB;"><?php echo __('click here ', 'miniorange-2-factor-authentication'); ?></a></p></div>
			<div class="mo2f_collapse" id="mo2f_scanqrcode" style="margin-left:5px;">
				<?php echo __('Follow these instructions below and try again.', 'miniorange-2-factor-authentication'); ?>
				<ol>
					<li><?php echo __('Make sure your desktop screen has enough brightness.', 'miniorange-2-factor-authentication'); ?></li>
					<li><?php echo __('Open your app and click on Configure button to scan QR Code again.', 'miniorange-2-factor-authentication'); ?></li>
					<li><?php echo __('If you get cross mark on QR Code then click on \'Refresh QR Code\' link.', 'miniorange-2-factor-authentication'); ?></li>
				</ol>
			</div>
			<table class="mo2f_settings_table">
				<a href="#mo2f_refreshQRCode" style="color:#3498DB;"><?php echo __('Click here to Refresh QR Code.', 'miniorange-2-factor-authentication'); ?></a>
				<div id="displayInlineQrCode" style="margin-left:300px;"><?php echo '<img style="width:200px;" src="data:image/jpg;base64,' . $data . '" />'; ?>
				</div>
			</table>
			<center>
				<?php 
				if (sizeof($opt) > 1) { ?>
					<input type="button" name="back" id="mo2f_inline_back_btn" class="miniorange_button" value="<?php echo __('Back', 'miniorange-2-factor-authentication'); ?>" />
				<?php }
				?>
			</center>
			<script>
				jQuery('a[href="#mo2f_refreshQRCode"]').click(function(e) {	
					jQuery('#mo2f_inline_configureapp_form').submit();
				});
					
					jQuery("#mo2f_configurePhone").empty();
					jQuery("#mo2f_app_div").hide();
					
					var timeout;
					pollInlineMobileRegistration();
					function pollInlineMobileRegistration()
					{
						var transId = "<?php echo $_SESSION[ 'mo2f-login-transactionId' ];  ?>";
						var jsonString = "{\"txId\":\""+ transId + "\"}";
						var postUrl = "<?php echo $url;  ?>" + "/moas/api/auth/registration-status";
						jQuery.ajax({
							url: postUrl,
							type : "POST",
							dataType : "json",
							data : jsonString,
							contentType : "application/json; charset=utf-8",
							success : function(result) {
								var status = JSON.parse(JSON.stringify(result)).status;
								if (status == 'SUCCESS') {
									var content = "<br/><div id='success'><img style='width:165px;margin-top:-1%;margin-left:2%;' src='" + "<?php echo plugins_url( 'includes/images/right.png' , __FILE__ );?>" + "' /></div>";
									jQuery("#displayInlineQrCode").empty();
									jQuery("#displayInlineQrCode").append(content);
									setTimeout(function(){jQuery("#mo2f_inline_mobile_register_form").submit();}, 1000);
								} else if (status == 'ERROR' || status == 'FAILED') {
									var content = "<br/><div id='error'><img style='width:165px;margin-top:-1%;margin-left:2%;' src='" + "<?php echo plugins_url( 'includes/images/wrong.png' , __FILE__ );?>" + "' /></div>";
									jQuery("#displayInlineQrCode").empty();
									jQuery("#displayInlineQrCode").append(content);
									jQuery("#messages").empty();
									
									jQuery("#messages").append("<div class='error mo2f_error_container'> <p class='mo2f_msgs'>An Error occured processing your request. Please try again to configure your phone.</p></div>");
								} else {
									timeout = setTimeout(pollInlineMobileRegistration, 3000);
								}
							}
						});
					}	
			</script>
	<?php
	}
	
function prompt_user_for_kba_setup($current_user_id, $login_status, $login_message){

    $current_user = get_userdata($current_user_id);
    $opt=fetch_methods($current_user);
?>
	<html>
		<head>
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
			</style>
		</head>
		<body>
			<div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
				<div class="mo2f-modal-backdrop"></div>
				<div class="mo2f_modal-dialog mo2f_modal-lg">
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
							<?php echo __('Setup Security Question (KBA)', 'miniorange-2-factor-authentication'); ?></h4>
						</div>
						<div class="mo2f_modal-body">
						
							<?php if(isset($login_message) && !empty($login_message)) {   ?>
								<div  id="otpMessage">
									<p class="mo2fa_display_message_frontend" style="text-align: left !important;"><?php echo __($login_message, 'miniorange-2-factor-authentication'); ?></p>
								</div>
							<?php } ?>
							<form name="f" method="post" action="" >
								<?php mo2f_configure_kba_questions(); ?>
								<br />
								<div class ="row">
									<div class="col-md-4" style="margin: 0 auto;width: 100px;">
										<input type="submit" name="validate" class="miniorange_button" value="<?php echo __('Save', 'miniorange-2-factor-authentication'); ?>" />
									</div>
								</div>
								
								<input type="hidden" name="mo2f_inline_kba_option" />
								<input type="hidden" name="mo2f_inline_save_kba_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-save-kba-nonce'); ?>" />
							</form>
							<?php if (sizeof($opt) > 1) { ?>
									<form name="f" method="post" action="" id="mo2f_goto_two_factor_form">
										<div class ="row">
											<div class="col-md-4" style="margin: 0 auto;width: 100px;">
											<input type="submit" name="back" id="mo2f_inline_back_btn" class="miniorange_button" value="<?php echo __('Back', 'miniorange-2-factor-authentication'); ?>" />
											</div>
										</div>
										<input type="hidden" name="miniorange_inline_two_factor_setup" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-setup-nonce'); ?>" />
									</form>
							<?php } ?>
							
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

	
function prompt_user_for_setup_success($id, $login_status, $login_message){
	global $dbQueries;
?>
	<html>
		<head>
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
			</style>
		</head>
		<body>
			<div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
				<div class="mo2f-modal-backdrop"></div>
				<div class="mo2f_modal-dialog mo2f_modal-lg">
					<div class="login mo_customer_validation-modal-content">
						<div class="mo2f_modal-header">
							<h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login', 'miniorange-2-factor-authentication'); ?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
							<?php echo __('Two Factor Setup Complete', 'miniorange-2-factor-authentication'); ?></h4>
						</div>
						<div class="mo2f_modal-body center">
							<?php
							global $dbQueries;
	
							// $current_selected_method = $dbQueries->get_user_detail( $current_user_id,'mo2f_selected_2factor_method');
			
								// $mo2f_second_factor = get_user_meta($id,'mo2f_selected_2factor_method',true);
								$mo2f_second_factor = $dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$id);
								
								if($mo2f_second_factor == 'OUT OF BAND EMAIL'){
									$mo2f_second_factor = 'Email Verification';
								}else if($mo2f_second_factor == 'SMS'){
									$mo2f_second_factor = 'OTP over SMS';
								}else if($mo2f_second_factor == 'OTP_OVER_EMAIL'){
									$mo2f_second_factor = 'OTP_OVER_EMAIL';
								}else if($mo2f_second_factor == 'PHONE VERIFICATION'){
									$mo2f_second_factor = 'Phone Call Verification';
								}else if($mo2f_second_factor == 'SOFT TOKEN'){
									$mo2f_second_factor = 'Soft Token';
								}else if($mo2f_second_factor == 'MOBILE AUTHENTICATION'){
									$mo2f_second_factor = 'QR Code Authentication';
								}else if($mo2f_second_factor == 'PUSH NOTIFICATIONS'){
									$mo2f_second_factor = 'Push Notification';
								}else if($mo2f_second_factor == 'GOOGLE AUTHENTICATOR'){
									if(get_user_meta($id,'mo2f_external_app_type',true) == 'GOOGLE AUTHENTICATOR'){
										$mo2f_second_factor = 'Google Authenticator';
									}else{
										$mo2f_second_factor = 'Authy 2-Factor Authentication';
									}	
								}else if($mo2f_second_factor == 'KBA'){
									$mo2f_second_factor = 'Security Questions (KBA)';
								}
								$dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$id);
								$status = $dbQueries->get_user_detail( 'mo_2factor_user_registration_status',$id);
								// $status = get_user_meta($id,'mo_2factor_user_registration_status',true);
							?>
<?php

						if(get_site_option( 'mo2f_disable_kba' )!=1){
							if($status != 'MO_2_FACTOR_PLUGIN_SETTINGS'){
							?><div id="validation_msg" style="color:red;text-align:left !important;"></div>
								<div id="mo2f_show_kba_reg" class="mo2f_inline_padding" style="text-align:left !important;" >
								<?php if(isset($login_message) && !empty($login_message)){ ?>
									<div  id="otpMessage">
										<p class="mo2fa_display_message_frontend" style="text-align: left !important;"  ><?php echo $login_message; ?></p>
									</div> 
								<?php } ?>
								<h4> <?php echo __('Please set your security questions as an alternate login or backup method.', 'miniorange-2-factor-authentication'); ?></h4>
								<form name="f" method="post" action="" >
									<?php mo2f_configure_kba_questions(); ?>
									<br>
									<center>
										<input type="submit" name="validate" class="miniorange_button" value="<?php echo __('Save', 'miniorange-2-factor-authentication'); ?>" /> 
									</center>
									<input type="hidden" name="mo2f_inline_kba_option" />
									<input type="hidden" name="mo2f_inline_save_kba_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-inline-save-kba-nonce'); ?>" />
									<input type="hidden" name="mo2f_inline_kba_status" value="<?php echo $login_status; ?>" />
								</form>
								</div>
							<?php }
						}else{
							$mo2fa_login_status = 'MO_2_FACTOR_SETUP_SUCCESS';
							$dbQueries->update_user_details( $id, array('mo_2factor_user_registration_status' =>'MO_2_FACTOR_PLUGIN_SETTINGS') );
							// update_user_meta($id,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
							$status = 'MO_2_FACTOR_PLUGIN_SETTINGS';
						
						}
						
						
					if($status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){
						if(get_site_option('mo2f_remember_device')!=1)
						{
							?>
								<center>
								<p style="font-size:17px;"><?php echo __('You have successfully set up ', 'miniorange-2-factor-authentication'); ?><b style="color:#28B463;"><?php echo $mo2f_second_factor; ?> </b><?php echo __('as your Two Factor method.', 'miniorange-2-factor-authentication'); ?><br><br>
								
								<?php echo __('From now, when you login, you will be prompted for', 'miniorange-2-factor-authentication'); ?>  <span style="color:#28B463;"><?php echo __($mo2f_second_factor, 'miniorange-2-factor-authentication'); ?></span>  <?php echo __('as your 2nd factor method of authentication.', 'miniorange-2-factor-authentication'); ?>
								</p>
								</center>
								
								<br>
								<center>
								<p style="font-size:16px;"><a href="#" onclick="mologinback();"style="color:#CB4335;"><b><?php echo __('Click Here', 'miniorange-2-factor-authentication'); ?></b></a> <?php echo __('to sign-in to your account.', 'miniorange-2-factor-authentication'); ?>
								<br>
								</center>
							<?php 
						}else{
								$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
								$mo_enable_rem = new Miniorange_Password_2Factor_Login();
								mo2f_collect_device_attributes_handler($redirect_to);
						}
						
					}
							mo2f_customize_logo() ?>
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
	?>