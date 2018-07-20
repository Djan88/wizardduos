<?php

	function mo2f_check_if_registered_with_miniorange($current_user){
		if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'){ 
				?>
				<br />
				<div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure">click here</a> to setup Two-Factor.</div>
	<?php	
		}else if(!(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_MOBILE_REGISTRATION' || mo2f_is_customer_registered())) { ?>
			<br/><div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=2factor_setup">Register with miniOrange</a> to configure miniOrange 2 Factor plugin.</div>
	<?php } 
	}
	
	function mo2f_get_activated_second_factor($current_user){
		if(get_user_meta($current_user->ID,'mo_2factor_mobile_registration_status',true) == 'MO_2_FACTOR_SUCCESS'){ 
			//checking this option for existing users
			update_user_meta($current_user->ID,'mo2f_mobile_registration_status',true);
			$mo2f_second_factor = 'MOBILE AUTHENTICATION';
			return $mo2f_second_factor;
		}else if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){
			return 'NONE';
		}else{
			//for new users
			if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS' && get_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange',true) == 'SUCCESS'){
				$enduser = new Two_Factor_Setup();
				$userinfo = json_decode($enduser->mo2f_get_userinfo(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true)),true);
				if(json_last_error() == JSON_ERROR_NONE){
					if($userinfo['status'] == 'ERROR'){
						update_site_option( 'mo2f_message', $userinfo['message']);
						$mo2f_second_factor = 'NONE';
					}else if($userinfo['status'] == 'SUCCESS'){
						$mo2f_second_factor = $userinfo['authType'];
					}else if($userinfo['status'] == 'FAILED'){
						$mo2f_second_factor = 'NONE';
						update_site_option( 'mo2f_message','Your account has been removed.Please contact your administrator.');
					}else{
						$mo2f_second_factor = 'NONE';
					}
				}else{
					update_site_option( 'mo2f_message','Invalid Request. Please try again.');
					$mo2f_second_factor = 'NONE';
				}
			}else{
				$mo2f_second_factor = 'NONE';
			}
			return $mo2f_second_factor;
		} 
	}
	
	function mo_2factor_is_curl_installed() {
		if  (in_array  ('curl', get_loaded_extensions())) {
			return 1;
		} else
			return 0;
	}
	
	function show_user_welcome_page($current_user){
	?>
		<form name="f" method="post" action="">
			<div class="mo2f_table_layout">
				<div><center><p style="font-size:17px;">A new security system has been enabled to better protect your account. Please configure your Two-Factor Authentication method by setting up your account.</p></center></div>
				<div id="panel1">
					<table class="mo2f_settings_table">
						
						<tr>
							<td><center><div class="alert-box"><input type="email" autofocus="true" name="mo_useremail" style="width:48%;text-align: center;height: 40px;font-size:18px;border-radius:5px;" required placeholder="person@example.com" value="<?php echo $current_user->user_email;?>"/></div></center></td>
						</tr>
						<tr>
							<td><center><p>Please enter a valid email id that you have access to. You will be able to move forward after verifying an OTP that we will be sending to this email.</p></center></td>
						</tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr>
							<td><input type="hidden" name="miniorange_user_reg_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-user-reg-nonce'); ?>" />
							<center><input type="submit" name="miniorange_get_started" id="miniorange_get_started" class="button button-primary button-large extra-large" value="Get Started" /></center> </td>
						</tr>
					</table>
				</div>
			</div>
		</form>
	<?php
	}
	
	function show_2_factor_advanced_options($current_user){
		$admin_questions = get_site_option('mo2f_auth_admin_custom_kbaquestions');
		$array_question = array();
		if(is_array($admin_questions)){
			for($i = 0; $i <= 15; $i++){
				$que = array_key_exists($i, $admin_questions) ? $admin_questions[$i] : null; 
				array_push($array_question, $que);
			}
		}
		
	?>
		<div class="mo2f_table_layout">
			<?php echo mo2f_check_if_registered_with_miniorange($current_user); ?>
			
				
				<span><h3>Customize Security Questions (KBA)
				</h3><hr></span>
					<p>You can customize the questions list shown in the Security Questions. You can also choose how many custom questions your endusers can add while setting up Security Questions.</p> 
					<p style="font-size:15px;"><b><a data-toggle="mo2f_collapse" aria-expanded="false" href="#customSecurityQuestions">Click Here</a> to customize Security Questions.</b></p>
					<div class="mo2f_collapse" id="customSecurityQuestions">
						<form name="f"  id="custom_security_questions" method="post" action="">
							<a data-toggle="mo2f_collapse" aria-expanded="false" href="#addAdminQuestions"><b>Hints for choosing questions:</b></a>
							<div class="mo2f_collapse" id="addAdminQuestions">
							<ol>
								<li>What is your first company name?</li>
								<li>What was your childhood nickname?</li>
								<li>In what city did you meet your spouse/significant other?</li>
								<li>What is the name of your favorite childhood friend?</li>
								<li>What school did you attend for sixth grade?</li>
								<li>In what city or town was your first job?</li>
								<li>What is your favourite sport?</li>
								<li>Who is your favourite sports player?</li>
								<li>What is your grandmother's maiden name?</li>
								<li>What was your first vehicle's registration number?</li>
							</ol>
							</div><br /><br />
							<b>Add Questions in the Security Questions (KBA) List: (Alteast 10)</b><br /><br />
							<table class="mo2f_kba_table">
								<?php for($qc = 0; $qc <= 15; $qc++){ ?>
								<tr class="mo2f_kba_body">
									<td>Q<?php echo $qc + 1; ?>:</td>
									<td>
										<input class="mo2f_kba_ques" type="text" name="mo2f_kbaquestion_custom_admin[]" id="mo2f_kbaquestion_custom_admin_<?php echo $qc + 1; ?>" pattern="(?=\S)[A-Za-z0-9\/_?@'.$#&+\-*\s]{1,100}" value="<?php echo $array_question[$qc]; ?>" placeholder="Enter your custom question here" autocomplete="off" />
									</td>
								</tr>
								<?php } ?>
							</table>
							<br /><br />
							<b>Security Questions for users: </b><br /><br />
							<span>Default Questions to choose from above list: <input style="border: 1px solid #ddd;border-radius: 4px;width:40px;" type="text" name="mo2f_default_kbaquestions_users" id="mo2f_default_kbaquestions_users" value="<?php echo get_site_option( 'mo2f_default_kbaquestions_users'); ?>" pattern="[0-9]{1}" autocomplete="off" /> <b><=5</b></span><br />
							
							Custom Questions added by users: <input style="border: 1px solid #ddd;border-radius: 4px;width:40px;" type="text" name="mo2f_custom_kbaquestions_users" id="mo2f_custom_kbaquestions_users" value="<?php echo get_site_option( 'mo2f_custom_kbaquestions_users'); ?>" pattern="[0-9]{1}" autocomplete="off" /> <b><=5</b>
							<br /><br />
							<input type="hidden" name="option" value="mo_auth_save_custom_security_questions" />
							<input type="submit" name="submit" value="Save Settings" class="button button-primary button-large" <?php 
					if(mo2f_is_customer_registered()){ } else{ echo 'disabled' ; } ?> />
						</form>
						<br /><br /><br /><br />
					</div>
					
					<br>
					<span><h3>Customize Settings
					</h3><hr></span>
					<br>
					<div style="border: 1px solid #DCDCDC;padding:20px;">
						<form name="f"  id="custom_settings" method="post" action="">
					<span><h3>Remove KBA setup during inline registration
					</h3><hr></span>
					
					<input type="checkbox" id="mo2f_disable_kba" name="mo2f_disable_kba" value="1" <?php checked( get_site_option('mo2f_disable_kba') == 1 ); 
					if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /> 
					Remove KBA setup for users during inline registration. <br/>
					<br /><div id="mo2f_note"><b>Note:</b> Checking this option will remove 'KBA' setup for your users during inline registration.</div>
					<br>
					
					<span><h3>Enable '<b>Remember Device</b>' 
						</h3><hr></span>
					<input type="checkbox" id="mo2f_enable_rba" name="mo2f_enable_rba" value="1" <?php checked( get_site_option('mo2f_enable_rba') == 1 ); 
					
					if(mo2f_is_customer_registered()&& get_site_option('mo2f_login_policy')){}else{ echo 'disabled';} ?> /> 
					Enable '<b>Remember Device</b>' option. <br /><span style="color:red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Applicable only with <i>Login with password + 2nd Factor)</i></span></br>
					<br />
					
					<div style="margin-left:6%; <?php echo get_site_option('mo2f_enable_rba')==1 ? 'display:block' : 'display:none' ?>" id="mo2f_enable_remember_dev" >
						<input type="radio" name="mo2f_enable_rba_types" value="1" <?php checked( get_site_option('mo2f_enable_rba_types') == 1 ); 
						if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
						Give users an option to enable '<b>Remember Device</b>'.	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<br><br>
						<input type="radio" name="mo2f_enable_rba_types" value="0" <?php checked( get_site_option('mo2f_enable_rba_types') == 0 ); 
						if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
						Silently enable '<b>Remember Device</b>'.
						<br><br>
					</div>
					
					
					<div id="mo2f_note"><b>Note:</b> Checking this option will enable '<b>Remember Device</b>'. In the login from the same device, user will bypass 2nd factor i.e user will be logged in through username + password only.</div>
					<br>
					
					
					
					<input type="hidden" name="option" value="mo_auth_save_custom_settings" />
					<input type="submit" name="submit" value="Save Settings" class="button button-primary button-large" <?php 
					if(mo2f_is_customer_registered()){ } else{ echo 'disabled' ; } ?> />
					</div>
					<script>
						jQuery('#mo2f_enable_rba').click(function() {
							if(jQuery(this).is(':checked'))
								jQuery('#mo2f_enable_remember_dev').show();
							else
								jQuery('#mo2f_enable_remember_dev').hide();
						});
					</script>
					</form>
					<br />
					
					
				
				
				<span><h3>Device Profile View
				</h3><hr></span>
					<p>You can manage trusted devices which you have stored during login by remembering devices.</p> 
					<a class="button button-primary button-large" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('RBA'); ?>' )" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled style="pointer-events: none;cursor: default;"';} ?> >View Profiles</a>
				<br>
				
				<h3>Enable Two-Factor using Shortcode*</h3><hr>
				<p><b style="font-size:16px;color: #0085ba;">[miniorange_enable2fa]</b> : Add this shortcode to provide the option to turn on/off 2-factor by user.<br />
				<b style="font-size:16px;color: #0085ba;">[mo2f_enable_reconfigure]</b> : Add this shortcode to provide the option to configure the Google Authenticator and Security Questions by user.<br />
				<b style="font-size:16px;color: #0085ba;">[mo2f_enable_rba_shortcode]</b> : Add this shortcode to 'Enable Remember Devie' from your custom login form.
				</p>
				<a  data-toggle="mo2f_collapse" href="#custom_login_form_id" aria-expanded="false" >Click here to use "mo2f_enable_rba_shortcode".<b></b></a>
				
				<div class="mo2f_collapse" id="custom_login_form_id">
					
					<form name="f"  id="custom_login_form" method="post" action="">
						</br>
						Enter the id of your custom login form to use 'Enable Remember Device' on the login page:
						<input type="text" class="mo2f_table_textbox" id="mo2f_rba_loginform_id" name="mo2f_rba_loginform_id" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> value="<?php echo get_site_option('mo2f_rba_loginform_id')?>" />
						 <br><br>
					<input type="hidden" name="option" value="custom_login_form_save" />
					<input type="submit" name="submit" value="Save Settings" class="button button-primary button-large" <?php 
					if(mo2f_is_customer_registered()){ } else{ echo 'disabled' ; } ?> />
					</form>
				</div>
				</br>
				</br>
				*The shortcodes will require additional changes in the plugin. Contact us if you want to use these shortcodes.
				<h3>MultiSite Support</h3><hr>
					<p>Just One time Setup. User has to setup his 2nd factor only once, no matter, in how many sites he exists. Ease of use.
					</p>
				<h3>Custom Email and SMS Templates</h3><hr>
					<p>You can change the templates for Email and SMS as per your requirement.</p>
					<?php if(mo2f_is_customer_registered()){ 
							if( get_site_option('mo2f_miniorange_admin') == $current_user->ID ){ ?>
								<a class="button button-primary button-large" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('EMAIL'); ?>' )" >Customize Email Template</a><span style="margin-left:10px;"></span>
								<a class="button button-primary button-large" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('SMS'); ?>' )" >Customize SMS Template</a>
						<?php	} 
						}else{ ?>
						<a class="button button-primary button-large" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('EMAIL'); ?>' )" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled style="pointer-events: none;cursor: default;"';} ?> >Customize Email Template</a><span style="margin-left:10px;"></span>
						<a class="button button-primary button-large" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('SMS'); ?>' )" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled style="pointer-events: none;cursor: default;"';} ?> >Customize SMS Template</a>
					<?php } ?>
				<h3>Custom Redirection</h3><hr>
					<p>This option will allow the users during login to redirect on the specific page role wise. Set custom URLs under Login Settings tab.</p>
		<form name="f"  id="advance_options_form" method="post" action="">
			<?php if(current_user_can('manage_options')){ ?>
			<input type="hidden" name="option" value="mo_auth_advanced_options_save" />
				<h3>Customize 'powered by' Logo:</h3><hr>
				 <div>
				 	<input type="checkbox" id="mo2f_disable_poweredby" name="mo2f_disable_poweredby" value="1" <?php checked( get_site_option('mo2f_disable_poweredby') == 1 ); 
				 	if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /> 
				 	Remove 'Powered By' option from the Login Screens. <br />
				 	<br /><div id="mo2f_note"><b>Note:</b> Checking this option will remove 'Powered By' from the Login Screens.</div>
				 	<br>
				 <input type="checkbox" id="mo2f_enable_custom_poweredby" name="mo2f_enable_custom_poweredby" value="1" <?php checked( get_site_option('mo2f_enable_custom_poweredby') == 1 ); 
					 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
					 
					 Enable Custom 'Powered By' option for the Login Screens. <br><br>
					 <div id="mo2f_note"><b>Instructions:</b>
						Go to /wp-content/uploads/miniorange folder and upload a .png image with the name "custom" (Max Size: 100x28px).
					 </div>
				</div>
				 	<br>

				<h3>Customize Plugin Icon:</h3><hr>
				<div>
					<input type="checkbox" id="mo2f_enable_custom_icon" name="mo2f_enable_custom_icon" value="1" <?php checked( get_site_option('mo2f_enable_custom_icon') == 1 ); 
					 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
					 
					 Change Plugin Icon. <br><br>
					 <div id="mo2f_note"><b>Instructions:</b>
						Go to /wp-content/uploads/miniorange folder and upload a .png image with the name "plugin_icon" (Max Size: 20x34px).
					 </div>
				</div>
				 <br>

				<h3>Customize Plugin Name:</h3><hr>
				<div>
					 Change Plugin Name: <br><br>
				     <input type="text" class="mo2f_table_textbox" id="mo2f_custom_plugin_name" name="mo2f_custom_plugin_name" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> value="<?php echo get_site_option('mo2f_custom_plugin_name')?>" placeholder="Enter a custom Plugin Name." />
					 <br><br>
					 <div id="mo2f_note"><b>Note:</b>
						This will be the Plugin Name You and your Users see in  WordPress Dashboard.
					 </div>
				</div>	 	
					<br>
					<input type="submit" name="submit" value="Save Settings" class="button button-primary button-large" <?php 
					if(mo2f_is_customer_registered()){ } else{ echo 'disabled' ; } ?> />
				<?php
				} 
				?>
				<br /><br/>
			</form>
			<form style="display:none;" id="mo2fa_loginform" action="<?php echo get_site_option( 'mo2f_host_name').'/moas/login'; ?>" 
		target="_blank" method="post">
			<input type="email" name="username" value="<?php echo get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true); ?>" />
			<input type="text" name="redirectUrl" value="" />
		</form>
			<script>
			function mo2fLoginMiniOrangeDashboard(redirectUrl){
				document.getElementById('mo2fa_loginform').elements[1].value = redirectUrl;
				jQuery('#mo2fa_loginform').submit();
			}
		</script>
		</div>
	<?php
	}
	
	function mo2f_show_user_otp_validation_page(){
	?>
		<!-- Enter otp -->
		
		<div class="mo2f_table_layout">
			<h3>Validate One Time Passcode (OTP)</h3><hr>
			<div id="panel1">
				<table class="mo2f_settings_table">
					<form name="f" method="post" id="mo_2f_otp_form" action="">
						<input type="hidden" name="option" value="mo_2factor_validate_user_otp" />
							<tr>
								<td><b><font color="#FF0000">*</font>Enter OTP:</b></td>
								<td colspan="2"><input class="mo2f_table_textbox" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP" style="width:95%;"/></td>
								<td><a href="#resendotplink">Resend OTP ?</a></td>
							</tr>
							
							<tr>
								<td>&nbsp;</td>
								<td style="width:17%">
								<input type="submit" name="submit" value="Validate OTP" class="button button-primary button-large" /></td>

						</form>
						<form name="f" method="post" action="">
						<td>
						<input type="hidden" name="option" value="mo_2factor_backto_user_registration"/>
							<input type="submit" name="mo2f_goback" id="mo2f_goback" value="Back" class="button button-primary button-large" /></td>
						</form>
						</td>
						</tr>
						<form name="f" method="post" action="" id="resend_otp_form">
							<input type="hidden" name="option" value="mo_2factor_resend_user_otp"/>
						</form>
						
				</table>
				</div>
				<div>	
					<script>
						jQuery('a[href="#resendotplink"]').click(function(e) {
							jQuery('#resend_otp_form').submit();
						});
					</script>
		
			<br><br>
			</div>
			
			
						
		</div>
					
	<?php
	}
	
	function show_2_factor_login_demo($current_user){
			include_once('miniorange_2_factor_demo.php');
	}
	
	function mo_reset_2fa_for_users_by_admin(){
		if(isset($_GET['action']) && $_GET['action']== 'reset_edit'){
			$user_id = $_GET['user'];
			$user_info = get_userdata($user_id);	
		?> 
			<form method="post" name="reset2fa" id="reset2fa">
				
				<div class="wrap">
				<h1>Reset 2nd Factor</h1>

				<p>You have specified this user for reset:</p>

				<ul>
				<li>ID #<?php echo $user_info->ID; ?>: <?php echo $user_info->user_login; ?></li> 
				</ul>
					<input type="hidden" name="userid" value="<?php echo $user_id; ?>">
					<input type="hidden" name="miniorange_reset_2fa_option" value="mo_reset_2fa">
					
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Confirm Reset" ></p>
				</div>
			</form>
		<?php
			
		}	
	}
	
	function mo2f_show_instruction_to_allusers($current_user,$mo2f_second_factor){
		if($mo2f_second_factor == 'OUT OF BAND EMAIL'){
			$mo2f_second_factor = 'Email Verification';
		}else if($mo2f_second_factor == 'SMS'){
			$mo2f_second_factor = 'OTP over SMS';
		}else if($mo2f_second_factor == 'SMS AND EMAIL'){
			$mo2f_second_factor = 'OTP over SMS And Email';
		}else if($mo2f_second_factor == 'PHONE VERIFICATION'){
			$mo2f_second_factor = 'Phone Call Verification';
		}else if($mo2f_second_factor == 'SOFT TOKEN'){
			$mo2f_second_factor = 'Soft Token';
		}else if($mo2f_second_factor == 'MOBILE AUTHENTICATION'){
			$mo2f_second_factor = 'QR Code Authentication';
		}else if($mo2f_second_factor == 'PUSH NOTIFICATIONS'){
			$mo2f_second_factor = 'Push Notification';
		}else if($mo2f_second_factor == 'GOOGLE AUTHENTICATOR'){
				$app_type = get_user_meta($current_user->ID,'mo2f_external_app_type',true);
				if($app_type == 'GOOGLE AUTHENTICATOR'){
					$mo2f_second_factor = 'Google Authenticator';
				}else if($app_type == 'AUTHY 2-FACTOR AUTHENTICATION'){
					$mo2f_second_factor = 'Authy 2-Factor Authentication';
				}else{
					$mo2f_second_factor = 'Google Authenticator';
					update_user_meta($current_user->ID,'mo2f_external_app_type','GOOGLE AUTHENTICATOR');
				}
			}
		 ?>
	
			<div class="mo2f_table_layout">
				<?php
						if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'){ 
					?>
						<br />
						<div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure">click here</a> to setup Two-Factor.</div>
				<?php }
				?>
					<?php if(current_user_can('manage_options')){ ?> <h4>Thank you for upgrading to premium plugin. <span style="float:right;"><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_help&amp;mo2f_tabpan=question_adduser">Click Here</a> to see how to setup 2 factor for users? </span></h4>
					<?php }else{ ?>
					<h4>Thank you for registering with us.</h4>
					<?php } ?>
					<h3>Your Profile</h3>
					<table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:100%">
						<tr>
							<td style="width:45%; padding: 10px;"><b>2 Factor Registered Email</b></td>
							<td style="width:55%; padding: 10px;"><?php echo get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true); echo '  (' . $current_user->user_login . ')';?> 
							</td>
						</tr>
						<tr>
							<td style="width:45%; padding: 10px;"><b>Activated 2nd Factor</b></td>
							<td style="width:55%; padding: 10px;"><?php echo $mo2f_second_factor;?> 
							</td>
						</tr>
						<?php if(current_user_can('manage_options')){ ?>
						<tr>
							<td style="width:45%; padding: 10px;"><b>miniOrange Customer Email</b></td>
							<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_email');?></td>
						</tr>
						<tr>
							<td style="width:45%; padding: 10px;"><b>Customer ID</b></td>
							<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_customerKey');?></td>
						</tr>
						<tr>
							<td style="width:45%; padding: 10px;"><b>API Key</b></td>
							<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_api_key');?></td>
						</tr>
						<tr>
							<td style="width:45%; padding: 10px;"><b>Token Key</b></td>
							<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_customer_token');?></td>
						</tr>
						<?php if(get_site_option('mo2f_app_secret')){ ?>
							<tr>
								<td style="width:45%; padding: 10px;"><b>App Secret</b></td>
								<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_app_secret');?></td>
							</tr>
						<?php 
							} 
						?>
						<tr style="height:40px;">
							<td style="border-right-color:white;"><a href="#mo_registered_forgot_password"><b>&nbsp; Click Here</b></a> if you forgot your password ?</td>
							<td></td>
							
						</tr>
						<?php

						}
						?>
					</table><br>
					<form name="f" method="post" action="" id="forgotpasswordform">
						<input type="hidden" name="email" id="hidden_email" value="<?php echo get_option('mo2f_email'); ?>" />
						<input type="hidden" name="option" value="mo_2factor_forgot_password"/>
					</form>
					<script>
						jQuery('a[href="#mo_registered_forgot_password"]').click(function(){
							jQuery('#forgotpasswordform').submit();
						});
					</script>
				
			</div>	
		
		<br><br>
	
	<?php
	}
	
	function instruction_for_mobile_registration($current_user){ 
		if(!get_user_meta($current_user->ID,'mo2f_mobile_registration_status',true)) {
			download_instruction_for_mobile_app($current_user->ID);
		}
	?><div>
		<h3>Step-2 : Scan QR code</h3><hr>
			
			<form name="f" method="post" action="">
				<input type="hidden" name="option" value="mo_auth_refresh_mobile_qrcode" />
					<?php if(get_user_meta($current_user->ID,'mo2f_mobile_registration_status',true)) {   ?>
					<div id="reconfigurePhone">
					<a  data-toggle="mo2f_collapse" href="#mo2f_show_download_app" aria-expanded="false" >Click here to see Authenticator App download instructions.</a>
					<div id="mo2f_show_download_app" class="mo2f_collapse">
						<?php download_instruction_for_mobile_app($current_user->ID); ?>
					</div>
					<br>
					<h4>Please click on 'Reconfigure your phone' button below to see QR Code.</h4>
					<input type="button" name="back" id="back_btn" class="miniorange_button" value="Back" />
					<input type="submit" name="submit" class="miniorange_button" value="Reconfigure your phone" />	
					</div>
					
					<?php } else {?>
					<div id="configurePhone"><h4>Please click on 'Configure your phone' button below to see QR Code.</h4>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="Back" />
					<input type="submit" name="submit" class="miniorange_button" value="Configure your phone" />
					</div>
					<?php } ?>
			</form>
				
					 <?php 
						if(isset($_SESSION[ 'mo2f_show_qr_code' ]) && $_SESSION[ 'mo2f_show_qr_code' ] == 'MO_2_FACTOR_SHOW_QR_CODE' && isset($_POST['option']) && $_POST['option'] == 'mo_auth_refresh_mobile_qrcode'){
									initialize_mobile_registration();
								 if(get_user_meta($current_user->ID,'mo2f_mobile_registration_status',true)) {   ?>
									<script>jQuery("#mo2f_app_div").show();</script>
								<?php
								} else{ ?>
									<script>jQuery("#mo2f_app_div").hide();</script>
								<?php
								}
						} else{
					?><br><br>
					<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
					
					</form>
		
					<script>
					jQuery('#back_btn').click(function() {	
						jQuery('#mo2f_cancel_form').submit();
					});
					</script>
					<?php } ?>
					
					
	<?php }
	
	function download_instruction_for_mobile_app($current_user_id){	?>	
	<div id="mo2f_app_div" class="mo_margin_left">
		<?php if(!get_user_meta($current_user_id,'mo2f_mobile_registration_status',true)) { ?>
		<a  class="mo_app_link" data-toggle="mo2f_collapse"  href="#mo2f_sub_header_app" aria-expanded="false" ><h3 class="mo2f_authn_header">Step-1 : Download the miniOrange <span style="color: #F78701;"> Authenticator</span> App</h3></a><hr class="mo_hr">
		
		<div class="mo2f_collapse in" id="mo2f_sub_header_app">
		<?php } ?>
		<table cellpadding="15" cellspacing="8" width="100%;" id="mo2f_inline_table"  style="border: 2px solid;">
			<tr id="mo2f_inline_table">
		
				<td width="50%;" style="border: 1px solid black;border-color:#BDC3C7;border-radius:3px;border-padding:5px;">
				<h4 id="mo2f_phone_id"><b>iPhone Users</b></h4>
				<ol>
				<li>Go to App Store</li>
				<li>Search for <b>miniOrange</b> Authenticator.</li>
				<li>Download and install <span style="color: #F78701;">miniOrange<b> Authenticator</b></span> app (<b>NOT MOAuth</b>)</li>
				</ol>
					<center><span><a target="_blank" href="https://itunes.apple.com/us/app/miniorange-authenticator/id796303566?ls=1"><img src="<?php echo plugins_url( 'includes/images/appstore.png' , __FILE__ );?>" style="width:120px; height:45px; margin-left:6px;"></a></span></center>
				</td>
				<td  width="50%;" style="border: 1px solid black;border-color:#BDC3C7;border-radius:3px;border-padding:10px;">
				<h4 id="mo2f_phone_id"><b>Android Users</b></h4>
				<ol>
				<li> Go to Google Play Store.</li>
				<li> Search for <b>Authenticator</b> by miniOrange.</li>
				<li>Download and install <span style="color: #F78701;"><b>Authenticator</b></span> app (<b>NOT miniOrange Authenticator/MOAuth </b>)</li>
				</ol>
				<center><a target="_blank" href="https://play.google.com/store/apps/details?id=com.miniorange.android.authenticator&hl=en"><img src="<?php echo plugins_url( 'includes/images/playStore.png' , __FILE__ );?>" style="width:120px; height:=45px; margin-left:6px;"></a></center>
				</td>
		
			</tr>
		</table>
		<?php if(!get_user_meta($current_user_id,'mo2f_mobile_registration_status',true)) { ?> </div> <?php 
		}
		?>
	</div>
	<?php
	}
	function mo2f_configure_kba_questions(){ 
	
	$kbaQuestionsArray = get_site_option( 'mo2f_auth_admin_custom_kbaquestions');

	$defaultQuestions = get_site_option( 'mo2f_default_kbaquestions_users');
	$customQuestions = get_site_option( 'mo2f_custom_kbaquestions_users');
	
	?>
			<table class="mo2f_custom_kba_table" style="border-spacing: 15px;">
              <thead>
				<tr style="padding: 15px;">
					<th class="mo2fa_thtd" scope="col">Sl. No.</th>
					<th class="mo2fa_thtd" scope="col">Question</th>
					<th class="mo2fa_thtd" scope="col">Answer</th>
				</tr>
			  </thead>
			  <tbody>
				<?php  for ($count = 0; $count < $defaultQuestions; $count++){ ?>
				<tr>
					<td class="mo2fa_thtd">
					<?php echo $count + 1; ?>.
					</td>
					<td data-label="Question" class="mo2fa_thtd">
						<select name="mo2f_kbaquestion[]" id="mo2f_kbaquestion_<?php echo $count + 1; ?>" class="mo2f_kba_ques" required="true"  onchange="mo_option_hide(<?php echo $count + 1; ?>)">
							<option value="" selected="selected"> ----------------Select your question----------------</option>
							<?php
								$index = 1;
								foreach($kbaQuestionsArray as $question){ 
							?>
									<option id="mq<?php echo $index; ?>_<?php echo $count + 1; ?>" value="<?php echo $question; ?>"><?php echo $question; ?></option>
							<?php 	$index = $index + 1; 
								}
							?>
						</select>
   					 
					</td>
					<td class="mo2fa_thtd">
						<input class="mo2f_table_textbox" type="text" name="mo2f_kba_ans[]" id="mo2f_kba_ans<?php echo $count + 1; ?>" title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed." pattern="(?=\S)[A-Za-z0-9\/_?@'.$#&+\-*\s]{1,100}" required="true" autofocus="true" placeholder="Enter your answer" autocomplete="off" />
					</td>
				</tr>
				<?php } 
				for ($count1 = 0; $count1 < $customQuestions; $count1++){ ?>
				<tr>
					<td class="mo2fa_thtd">
					<?php echo $count + $count1 + 1;?>.
					</td>

					<td data-label="Question" class="mo2fa_thtd">
						<input class="mo2f_kba_ques" type="text" name="mo2f_kbaquestion[]" id="mo2f_kbaquestion_<?php echo $count + $count1 + 1; ?>"  required="true" placeholder="Enter your custom question here" autocomplete="off" pattern="(?=\S)[A-Za-z0-9\/_?@'.$#&+\-*\s]{1,100}" />
					</td>
					<td class="mo2fa_thtd">
						<input class="mo2f_table_textbox" type="text" name="mo2f_kba_ans[]" id="mo2f_kba_ans<?php echo $count + $count1 + 1; ?>"  title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed." pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+-\s]{1,100}" required="true" placeholder="Enter your answer" autocomplete="off" />
					</td>
				</tr>
				<?php } ?>
			</table>
			<script>
				//hidden element in dropdown list 1
				var mo_option_to_hide1;
				//hidden element in dropdown list 2
				var mo_option_to_hide2;

				function mo_option_hide(list) {
					//grab the team selected by the user in the dropdown list
					var list_selected = document.getElementById("mo2f_kbaquestion_" + list).selectedIndex;
					//if an element is currently hidden, unhide it
					if (typeof (mo_option_to_hide1) != "undefined" && mo_option_to_hide1 !== null && list == 2) {
						mo_option_to_hide1.style.display = 'block';
					} else if (typeof (mo_option_to_hide2) != "undefined" && mo_option_to_hide2 !== null && list == 1) {
						mo_option_to_hide2.style.display = 'block';
					}
					//select the element to hide and then hide it
					if (list == 1) {
						if(list_selected != 0){
							mo_option_to_hide2 = document.getElementById("mq" + list_selected + "_2");
							mo_option_to_hide2.style.display = 'none';
						}
					}
					if (list == 2) {
						if(list_selected != 0){
							mo_option_to_hide1 = document.getElementById("mq" + list_selected + "_1");
							mo_option_to_hide1.style.display = 'none';
						}
					}
				}
			</script>
			<?php if(isset($_SESSION['mo2f_mobile_support']) && $_SESSION['mo2f_mobile_support'] == 'MO2F_EMAIL_BACKUP_KBA'){
			?>
				<input type="hidden" name="mobile_kba_option" value="mo2f_request_for_kba_as_emailbackup" />
			<?php
			}
	}
	function mo2f_configure_for_mobile_suppport_kba($current_user){
	?>
		
			<h3>Configure Second Factor - KBA (Security Questions)</h3><hr />
				<form name="f" method="post" action="" id="mo2f_kba_setup_form">
					<?php mo2f_configure_kba_questions(); ?>
					<br />
					<input type="hidden" name="option" value="mo2f_save_kba" />
					<input type="submit" id="mo2f_kba_submit_btn" name="submit
					" value="Save" class="button button-primary button-large" style="width:100px;line-height:30px;float:left !important;"/>
				</form>	
				
				<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
					<input type="submit" name="back" id="back_btn" class="button button-primary button-large" value="Back" style="width:100px;line-height:30px;float:right !important;" />
				</form>
			

		<script>
		
			jQuery('#mo2f_kba_submit_btn').click(function() {
				jQuery('#mo2f_kba_setup_form').submit();
			});
		</script>
	<?php
	}
	
	function mo2f_select_2_factor_method($current_user,$mo2f_second_factor){ 
            $opt=fetch_methods($current_user);
		$selectedMethod = $mo2f_second_factor;
			if($mo2f_second_factor == 'OUT OF BAND EMAIL'){
						$selectedMethod = "Email Verification";
			} else if($mo2f_second_factor == 'MOBILE AUTHENTICATION'){
						$selectedMethod = "QR Code Authentication";
			}else if($mo2f_second_factor == 'SMS'){
						$selectedMethod = "OTP Over SMS";
			}else if($mo2f_second_factor == 'SMS AND EMAIL'){
						$selectedMethod = "OTP Over SMS And Email";
			}else if($mo2f_second_factor == 'GOOGLE AUTHENTICATOR'){
				
				$app_type = get_user_meta($current_user->ID,'mo2f_external_app_type',true);
				if($app_type == 'GOOGLE AUTHENTICATOR'){
					$selectedMethod = 'GOOGLE AUTHENTICATOR';
				}else if($app_type == 'AUTHY 2-FACTOR AUTHENTICATION'){
					$selectedMethod = 'AUTHY 2-FACTOR AUTHENTICATION';
				}else{
					$selectedMethod = 'GOOGLE AUTHENTICATOR';
					update_user_meta($current_user->ID,'mo2f_external_app_type','GOOGLE AUTHENTICATOR');
				}
			}?>
		<div class="mo2f_table_layout">	
		<?php
		
		if( get_user_meta($current_user->ID,'mo2f_configure_test_option',true) == 'MO2F_CONFIGURE'){
			
				$current_selected_method = get_user_meta($current_user->ID,'mo2f_selected_2factor_method',true);
				if($current_selected_method == 'MOBILE AUTHENTICATION' || $current_selected_method == 'SOFT TOKEN' || $current_selected_method == 'PUSH NOTIFICATIONS'){
					instruction_for_mobile_registration($current_user);
				}else if($current_selected_method == 'SMS' || $current_selected_method == 'PHONE VERIFICATION' || $current_selected_method == 'SMS AND EMAIL'){
					show_verify_phone_for_otp($current_user);
				}else if($current_selected_method == 'GOOGLE AUTHENTICATOR' ){
					mo2f_configure_google_authenticator($current_user);
				}else if($current_selected_method == 'AUTHY 2-FACTOR AUTHENTICATION' ){
					mo2f_configure_authy_authenticator($current_user);
				}else if($current_selected_method == 'KBA' ){
					mo2f_configure_for_mobile_suppport_kba($current_user);
				}else{
					test_out_of_band_email($current_user);
				}
		} else if( get_user_meta($current_user->ID,'mo2f_configure_test_option',true) == 'MO2F_TEST') {
			
				$current_selected_method = get_user_meta($current_user->ID,'mo2f_selected_2factor_method',true);
				if($current_selected_method == 'MOBILE AUTHENTICATION') {
					test_mobile_authentication();
				}else if($current_selected_method == 'PUSH NOTIFICATIONS'){
					test_push_notification();
				}else if($current_selected_method == 'SOFT TOKEN'){
					test_soft_token();
				}else if ($current_selected_method == 'SMS' || $current_selected_method == 'PHONE VERIFICATION' || $current_selected_method == 'SMS AND EMAIL'){
					test_otp_over_sms($current_user);
				}else if($current_selected_method == 'GOOGLE AUTHENTICATOR' || $current_selected_method == 'AUTHY 2-FACTOR AUTHENTICATION' ){
					test_google_authenticator($current_selected_method);
				}else if( $current_selected_method == 'KBA' ){
					test_kba_authentication($current_user);
				}else {
					test_out_of_band_email($current_user);
				}
			
		}else{
		
		if(!get_user_meta($current_user->ID,'mo2f_kba_registration_status',true) && (mo2f_is_customer_registered() || get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR')){
			
		?>
		<br>
		<div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);" class="error notice is-dismissible"><a href="#mo2f_kba_config">Click Here</a> to configure Security Questions (KBA) as alternate 2 factor method so that you are not locked out of your account in case you lost or forgot your phone. </div>
		
		<?php
			
		}else if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'){ 
				?>
				<br />
				<div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please configure your 2nd factor here to complete the Two-Factor setup..</div>
	<?php	
		}
	?>
			<h3>Setup Two-Factor<span style="font-size:15px;color:rgb(24, 203, 45);padding-left:250px;">Active Method - <?php echo $selectedMethod; ?></span><span style="float:right;"><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=2factor_setup" >Need Support?</a></span></h3><hr>
			<p><b>Select any Two-Factor of your choice below and complete its setup. <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo">Click here to see How To Setup ?</a></b>
		</p>
		<form name="f" method="post" action="" id="mo2f_2factor_form">
		
			<table style="width:100%;">
				<tr>
					<td>
						<span class="color-icon selectedMethod"></span> - Active Method
						<span class="color-icon activeMethod"></span> - Configured Method
						<span class="color-icon inactiveMethod"></span> - Unconfigured Method
					</td>
				</tr>
			</table><br>
				<table>
				<tr>
				<td class="<?php if(!current_user_can('manage_options') && !(in_array("OUT OF BAND EMAIL", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
					<div class="mo2f_thumbnail">
							<label title="Supported in Desktops, Laptops, Smartphones.">
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="OUT OF BAND EMAIL" <?php checked($mo2f_second_factor == 'OUT OF BAND EMAIL');
								if(mo2f_is_customer_registered() || get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>   />
								Email Verification
							</label><hr>
							<p>
								You will receive an email with link. You have to click the ACCEPT or DENY link to verify your email. Supported in Desktops, Laptops, Smartphones.
							</p>
								
								<?php if(mo2f_is_customer_registered()){
										if(!get_user_meta($current_user->ID,'mo2f_email_verification_status',true)){
											update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
										}
									?> 
									<div class="configuredLaptop" id="OUT_OF_BAND_EMAIL" title="Supported in Desktops, Laptops, Smartphones">
										<a href="#test" data-method="OUT OF BAND EMAIL"  <?php checked($mo2f_second_factor == 'OUT OF BAND EMAIL'); ?> >Test</a>
									</div>
								<?php } else { ?>
									
									<div class="notConfiguredLaptop" style="padding:20px;" id="OUT_OF_BAND_EMAIL" title="Supported in Desktops, Laptops, Smartphones."></div>
								<?php } ?>
								</div>
						
						
					</td>
					<td class="<?php if(!current_user_can('manage_options') && !(in_array("SMS", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
						<div class="mo2f_thumbnail">
							<label title="Supported in Smartphones, Feature Phones.">
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="SMS" <?php checked($mo2f_second_factor == 'SMS');
								if(mo2f_is_customer_registered() || get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> />
								OTP Over SMS
							</label><hr>
							<p>
								You will receive a one time passcode via SMS on your phone. You have to enter the otp on your screen to login. Supported in Smartphones, Feature Phones.
							</p>
							<?php if(get_user_meta($current_user->ID,'mo2f_otp_registration_status',true)){ ?>
								<div class="configuredBasic" id="SMS" title="supported in smartphone,feature phone">
									<a href="#reconfigure" data-method="SMS" >Reconfigure</a> | <a href="#test" data-method="SMS">Test</a>
								</div>
							<?php } else { ?>
								<div class="notConfiguredBasic" title="Supported in Smartphones, Feature Phones."><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo">How To Setup ?</a></div>
							<?php } ?>
						</div>
					</td >
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("PHONE VERIFICATION", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>">
						<div class="mo2f_thumbnail" >
							<label title="Supported in Landline phones, Smartphones, Feature phones.">
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="PHONE VERIFICATION" <?php checked($mo2f_second_factor == 'PHONE VERIFICATION');
								if(mo2f_is_customer_registered() || get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> />
								Phone Call Verification 
							</label><hr>
							<p>
								You will receive a phone call telling a one time passcode. You have to enter the one time passcode to login. Supported in Landlines, Smartphones, Feature phones.
							</p>
							<?php if(get_user_meta($current_user->ID,'mo2f_otp_registration_status',true)){ ?>
								<div class="configuredLandline" id="PHONE_VERIFICATION" title="Supported in Landline phones, Smartphones, Feature phones.">
									<a href="#reconfigure" data-method="PHONE VERIFICATION" >Reconfigure</a> | <a href="#test" data-method="PHONE VERIFICATION">Test</a>
								</div>
							<?php } else { ?>
								<div class="notConfiguredLandline" title="supported in Landline phone,smartphone,feature phone"><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#demo2">How To Setup ?</a></div>
							<?php } ?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("SOFT TOKEN", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
						<div class="mo2f_thumbnail">
							<label title="Supported in Smartphones only" >
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="SOFT TOKEN" <?php checked($mo2f_second_factor == 'SOFT TOKEN');
								if(mo2f_is_customer_registered() ||	get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
												} else{ echo 'disabled'; } ?> />
								Soft Token
							</label><hr>
							<p>
								You have to enter the 6 digits code generated by miniOrange Authenticator App like Google Authenticator code to login. Supported in Smartphones only.
							</p>
							<?php if(get_user_meta($current_user->ID,'mo2f_mobile_registration_status',true)){ ?>
							<div class="configuredSmart" id="SOFT_TOKEN" title="Supported in Smartphones only">
								<a href="#reconfigure" data-method="SOFT TOKEN" >Reconfigure</a> | <a href="#test" data-method="SOFT TOKEN">Test</a>
							</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="supported in smartphone"><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#demo1">How To Setup ?</a></div>
							<?php } ?>
						</div>
					</td>
				
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("MOBILE AUTHENTICATION", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
						<div class="mo2f_thumbnail">
							<label title="Supported in Smartphones only.">
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="MOBILE AUTHENTICATION" <?php checked($mo2f_second_factor == 'MOBILE AUTHENTICATION');
								if(mo2f_is_customer_registered() || get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> />
								QR Code Authentication
							</label><hr>
							<p>
								You have to scan the QR Code from your phone using miniOrange Authenticator App to login. Supported in Smartphones only.
							</p>
							<?php if(get_user_meta($current_user->ID,'mo2f_mobile_registration_status',true)  ){ ?>
								<div class="configuredSmart" id="MOBILE_AUTHENTICATION" title="Supported in Smartphones only.">
									<a href="#reconfigure" data-method="MOBILE AUTHENTICATION">Reconfigure</a> | <a href="#test" data-method="MOBILE AUTHENTICATION">Test</a>
								</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="Supported in Smartphones only"><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#demo3">How To Setup ?</a></div>
							<?php } ?>
						</div>
					</td>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("PUSH NOTIFICATIONS", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
						<div class="mo2f_thumbnail">
							<label title="Supported in Smartphones only">
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="PUSH NOTIFICATIONS" <?php checked($mo2f_second_factor == 'PUSH NOTIFICATIONS');
								if(mo2f_is_customer_registered() ||	get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
												} else{ echo 'disabled'; } ?> />
								Push Notification
							</label><hr>
							<p>
								You will receive a push notification on your phone. You have to ACCEPT or DENY it to login. Supported in Smartphones only.
							</p>
							<?php if(get_user_meta($current_user->ID,'mo2f_mobile_registration_status',true)){ ?>
							<div class="configuredSmart" id="PUSH_NOTIFICATIONS" title="supported in smartphone">
								<a href="#reconfigure" data-method="PUSH NOTIFICATIONS" >Reconfigure</a> | <a href="#test" data-method="PUSH NOTIFICATIONS">Test</a>
							</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="Supported in Smartphones only."><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#demo3">How To Setup ?</a></div>
							<?php } ?>
						</div>
					</td>
					</tr>
				<tr>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("GOOGLE AUTHENTICATOR", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
						
						<div class="mo2f_thumbnail">
							<label title="Supported in Smartphones only">
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="GOOGLE AUTHENTICATOR" <?php checked($selectedMethod == 'GOOGLE AUTHENTICATOR');
								if(mo2f_is_customer_registered() ||	get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
												} else{ echo 'disabled'; } ?> />
								Google Authenticator
							</label><hr>
							<p>
								You have to enter 6 digits code generated by Google Authenticator App to login. Supported in Smartphones only.
							</p>
							<?php if(get_user_meta($current_user->ID,'mo2f_google_authentication_status',true)){ ?>
							<div class="configuredSmart" id="GOOGLE_AUTHENTICATOR" title="supported in smartphone">
								<a href="#reconfigure" data-method="GOOGLE AUTHENTICATOR" >Reconfigure</a> | <a href="#test" data-method="GOOGLE AUTHENTICATOR">Test</a>
							</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="Supported in Smartphones only."><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#demo5">How To Setup ?</a></div>
							<?php } ?>
						</div>
					</td>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("AUTHY 2-FACTOR AUTHENTICATION", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
						
						<div class="mo2f_thumbnail">
							<label title="Supported in Smartphones only">
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="AUTHY 2-FACTOR AUTHENTICATION" <?php checked($selectedMethod == 'AUTHY 2-FACTOR AUTHENTICATION');
								if(mo2f_is_customer_registered() ||	get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
												} else{ echo 'disabled'; } ?> />
								Authy 2-Factor Authentication
							</label><hr>
							<p>
								You have to enter 6 digits code generated by Authy 2-Factor Authentication App to login. Supported in Smartphones only.
							</p>
							<?php if(get_user_meta($current_user->ID,'mo2f_authy_authentication_status',true)){ ?>
							<div class="configuredSmart" id="GOOGLE_AUTHENTICATOR" title="supported in smartphone">
								<a href="#reconfigure" data-method="AUTHY 2-FACTOR AUTHENTICATION" >Reconfigure</a> | <a href="#test" data-method="AUTHY 2-FACTOR AUTHENTICATION">Test</a>
							</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="Supported in Smartphones only."><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#demo5">How To Setup ?</a></div>
							<?php } ?>
						</div>
					</td>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("KBA", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
						
						<div class="mo2f_thumbnail">
							<label title="Supported in DeskTops,Laptops and Smartphones.">
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="KBA" <?php checked($mo2f_second_factor == 'KBA');
								if(mo2f_is_customer_registered() ||	get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
												} else{ echo 'disabled'; } ?> />
								Security Questions( KBA )
							</label><hr>
							<p>
								You have to answers some knowledge based security questions which are only known to you to authenticate yourself. Supported in Desktops,Laptops,Smartphones.
							</p>
							<?php if(get_user_meta($current_user->ID,'mo2f_kba_registration_status',true)) { ?>
									<div class="configuredLaptop" id="KBA" title="Supported in Desktops, Laptops, Smartphones">
										<a href="#reconfigure" data-method="KBA" >Reconfigure</a> | <a href="#test" data-method="KBA">Test</a>
									</div>
							<?php } else { ?>
								<div class="notConfiguredLaptop" style="padding:10px !important;"title="Supported in Desktops, Laptops, Smartphones."><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#demo6">How To Setup ?</a></div>
							<?php } ?>
							
						</div>
					</td>
				</tr>
				<tr>
					<td class="<?php if(!current_user_can('manage_options') && !(in_array("SMS AND EMAIL", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
						<div class="mo2f_thumbnail">
							<label title="Supported in Laptops, Smartphones, Feature phones.">
								<input type="radio"  name="mo2f_selected_2factor_method" style="margin:5px;" value="SMS AND EMAIL" <?php checked($mo2f_second_factor == 'SMS AND EMAIL');
								if(mo2f_is_customer_registered() || get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> />
								OTP Over SMS and Email
							</label><hr>
							<p>
								You will receive a one time passcode via SMS on your phone and your email. You have to enter the otp on your screen to login. Supported in Smartphones, Feature Phones.
							</p>
							<?php if(get_user_meta($current_user->ID,'mo2f_otp_registration_status',true)){ ?>
								<div class="configuredBasic" id="SMS_AND_EMAIL" title="supported in smartphone,feature phone">
									<a href="#reconfigure" data-method="SMS AND EMAIL" >Reconfigure</a> | <a href="#test" data-method="SMS AND EMAIL">Test</a>
								</div>
							<?php } else { ?>
								<div class="notConfiguredBasic" title="Supported in Smartphones, Feature Phones."><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo">How To Setup ?</a></div>
							<?php } ?>
						</div>
					</td >		
				</tr>
				</table>
				<input type="hidden" name="option" value="mo2f_save_2factor_method" />		
		</form>
			<form name="f" method="post" action="" id="mo2f_2factor_save_form">
					<input type="hidden" name="option" value="mo2f_update_2factor_method" />
					<input type="hidden" name="mo2f_selected_2factor_method" id="mo2f_selected_2factor_method" />
			</form>
			<form name="f" method="post" action="" id="mo2f_2factor_reconfigure_form">
				<input type="hidden" name="mo2f_selected_2factor_method" id="mo2f_reconfigure_2factor_method" />
				<input type="hidden" name="option" value="mo2f_save_2factor_method" />
			</form>
			<form name="f" method="post" action="" id="mo2f_2factor_test_mobile_form">
				<input type="hidden" name="option" value="mo_2factor_test_mobile_authentication" />
			</form>	
			<form name="f" method="post" action="" id="mo2f_2factor_test_softtoken_form">
				<input type="hidden" name="option" value="mo_2factor_test_soft_token" />
			</form>	
			<form name="f" method="post" action="" id="mo2f_2factor_test_smsotp_form">
				<input type="hidden" name="mo2f_selected_2factor_method" id="mo2f_test_2factor_method" />
				<input type="hidden" name="option" value="mo_2factor_test_otp_over_sms" />
			</form>	
			<form name="f" method="post" action="" id="mo2f_2factor_test_push_form">
				<input type="hidden" name="option" value="mo_2factor_test_push_notification" />
			</form>	
			<form name="f" method="post" action="" id="mo2f_2factor_test_out_of_band_email_form">
				<input type="hidden" name="option" value="mo_2factor_test_out_of_band_email" />
			</form>
			<form name="f" method="post" action="" id="mo2f_2factor_test_google_auth_form" >
				<input type="hidden" name="option" value="mo_2factor_test_google_auth" />
			</form>
			<form name="f" method="post" action="" id="mo2f_2factor_test_authy_app_form" >
				<input type="hidden" name="option" value="mo_2factor_test_authy_auth" />
			</form>
			<form name="f" method="post" action="" id="mo2f_2factor_test_kba_form" >
				<input type="hidden" name="option" value="mo2f_2factor_test_kba" />
			</form>
			<form name="f" method="post" action="" id="mo2f_2factor_configure_kba_backup_form" >
				<input type="hidden" name="option" value="mo2f_2factor_configure_kba_backup" />
			</form>
	
		<script>
			
			jQuery('a[href="#mo2f_kba_config"]').click(function() {
				jQuery('#mo2f_2factor_configure_kba_backup_form').submit();
			});
			
			jQuery('input:radio[name=mo2f_selected_2factor_method]').click(function() {
				var selectedMethod = jQuery(this).val();
				<?php if(get_user_meta($current_user->ID,'mo2f_mobile_registration_status',true)) { ?>
				    if(selectedMethod == 'MOBILE AUTHENTICATION' || selectedMethod == 'SOFT TOKEN' || selectedMethod == 'PUSH NOTIFICATIONS' ){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					}
				<?php } else{ ?>
					if(selectedMethod == 'MOBILE AUTHENTICATION' || selectedMethod == 'SOFT TOKEN' || selectedMethod == 'PUSH NOTIFICATIONS'  ){
						jQuery('#mo2f_2factor_form').submit();
					}
				<?php } if(get_user_meta($current_user->ID,'mo2f_email_verification_status',true)) { ?>
					if(selectedMethod == 'OUT OF BAND EMAIL'  ){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					 }
				<?php } else{ ?>
					if(selectedMethod == 'OUT OF BAND EMAIL' ){
						jQuery('#mo2f_2factor_form').submit();
					 }
				<?php } if(get_user_meta($current_user->ID,'mo2f_otp_registration_status',true)) { ?>
					 if(selectedMethod == 'SMS' || selectedMethod == 'PHONE VERIFICATION' || selectedMethod == 'SMS AND EMAIL'){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					 }
					
				<?php } else{ ?>
					if(selectedMethod == 'SMS' || selectedMethod == 'PHONE VERIFICATION' || selectedMethod == 'SMS AND EMAIL'){
						
						jQuery('#mo2f_2factor_form').submit();
					}
					
				<?php } if(get_user_meta($current_user->ID,'mo2f_google_authentication_status',true)) { ?>
					  if(selectedMethod == 'GOOGLE AUTHENTICATOR' ){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					  }
				<?php } else{ ?>
						if(selectedMethod == 'GOOGLE AUTHENTICATOR' ){
							jQuery('#mo2f_2factor_form').submit();
						}
				<?php } if(get_user_meta($current_user->ID,'mo2f_authy_authentication_status',true)) { ?>
					  if(selectedMethod == 'AUTHY 2-FACTOR AUTHENTICATION' ){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					  }
				<?php } else{ ?>
						if(selectedMethod == 'AUTHY 2-FACTOR AUTHENTICATION' ){
							jQuery('#mo2f_2factor_form').submit();
						}
				<?php } if(get_user_meta($current_user->ID,'mo2f_kba_registration_status',true)) { ?>
					  if(selectedMethod == 'KBA' ){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					  }
				<?php } else{ ?>
						if(selectedMethod == 'KBA' ){
							jQuery('#mo2f_2factor_form').submit();
						}
				<?php }?>
				
					
			});
			jQuery('a[href="#reconfigure"]').click(function() {
				var reconfigureMethod = jQuery(this).data("method");
				
				jQuery('#mo2f_reconfigure_2factor_method').val(reconfigureMethod);
				jQuery('#mo2f_2factor_reconfigure_form').submit();
			});
			jQuery('a[href="#test"]').click(function() {
				var currentMethod = jQuery(this).data("method");
			
				if(currentMethod == 'MOBILE AUTHENTICATION'){
					jQuery('#mo2f_2factor_test_mobile_form').submit();
				}else if(currentMethod == 'PUSH NOTIFICATIONS'){
					jQuery('#mo2f_2factor_test_push_form').submit();
				}else if(currentMethod == 'SOFT TOKEN'){
					jQuery('#mo2f_2factor_test_softtoken_form').submit();
				}else if(currentMethod == 'SMS' || currentMethod == 'PHONE VERIFICATION' || currentMethod == 'SMS AND EMAIL'){
					jQuery('#mo2f_test_2factor_method').val(currentMethod);
					jQuery('#mo2f_2factor_test_smsotp_form').submit();
				}else if(currentMethod == 'GOOGLE AUTHENTICATOR' ){
					jQuery('#mo2f_2factor_test_google_auth_form').submit();
				}else if(currentMethod == 'AUTHY 2-FACTOR AUTHENTICATION'){
					jQuery('#mo2f_2factor_test_authy_app_form').submit();
				}else if(currentMethod == 'OUT OF BAND EMAIL'){
					jQuery('#mo2f_2factor_test_out_of_band_email_form').submit();
				}else if(currentMethod == 'KBA' ){
					jQuery('#mo2f_2factor_test_kba_form').submit();
				}
			});
			<?php if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS'){ ?>
				var currentSecondFactor = jQuery('input[name=mo2f_selected_2factor_method][type=radio]:checked').val();
				var selectedMethod = currentSecondFactor.replace(/ /g, "_");
				jQuery("#" + selectedMethod).addClass('selectedMethod');
			<?php } ?>
		</script>
		<?php	} ?>
	
		<br><br>
		</div>
	<?php 
	}
	
	function mo2f_configure_authy_authenticator($current_user){
		$mo2f_authy_auth = isset($_SESSION['mo2f_authy_keys']) ? $_SESSION['mo2f_authy_keys'] : null;
		$data = isset($_SESSION['mo2f_authy_keys']) ? $mo2f_authy_auth['authy_qrCode'] : null;
		$authy_secret = isset($_SESSION['mo2f_authy_keys']) ? $mo2f_authy_auth['authy_secret'] : null;
		?>
		<table>
			<tr>
				<td style="vertical-align:top;width:26%;padding-right:15px">
					<h3>Step-1: Configure with Authy</h3><h3>2-Factor Authentication App.</h3><hr />
					<form name="f" method="post" id="mo2f_app_type_ga_form" action="" >
						<br /><input type="submit" name="mo2f_authy_configure" class="button button-primary button-large" style="width:45%;" value="Next >>" /><br /><br />
						<input type="hidden" name="option" value="mo2f_configure_authy_app" />
					</form>
					<form name="f" method="post" action="" id="mo2f_cancel_form">
						<input type="hidden" name="option" value="mo2f_cancel_configuration" />
						<input type="submit" name="back" id="back_btn" class="button button-primary button-large" style="width:45%;" value="Back" />
					</form>
				</td>
				<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
				<td style="width:46%;padding-right:15px;vertical-align:top;">
					<h3>Step-2: Set up Authy 2-Factor Authentication App</h3><h3>&nbsp;	</h3><hr>
					<div style="<?php echo isset($_SESSION['mo2f_authy_keys']) ? 'display:block' : 'display:none'; ?>">
					<h4>Install the Authy 2-Factor Authentication App.</h4>
					<h4>Now open and configure Authy 2-Factor Authentication App.</h4>
					<h4> Tap on Add Account and then tap on SCAN QR CODE in your App and scan the qr code.</h4>
					<center><br><div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div></center>
					<div><a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_a" aria-expanded="false" ><b>Can't scan the QR Code? </b></a></div>
					<div class="mo2f_collapse" id="mo2f_scanbarcode_a">
						<ol>
							<li>In Authy 2-Factor Authentication App, tap on ENTER KEY MANUALLY."</li>
							<li>In "Adding New Account" type your secret key:</li>
								<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
									<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
									<?php echo $authy_secret; ?>
									</div>
									<div style="font-size: 80%;color: #666666;">
									Spaces don't matter.
									</div>
								</div>
							<li>Tap OK.</li>
						</ol>
					</div>
					</div>
				</td>
				<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
				<td style="vertical-align:top;width:30%">
					<h3>Step-3: Verify and Save</h3><h3>&nbsp;</h3><hr>
					<div style="<?php echo isset($_SESSION['mo2f_authy_keys']) ? 'display:block' : 'display:none'; ?>">
					<h4>Once you have scanned the qr code, enter the verification code generated by the Authenticator app</h4><br/>
					<form name="f" method="post" action="" >
						<span><b>Code: </b>
						<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" required="true" type="text" name="authy_token" placeholder="Enter OTP" style="width:95%;"/></span><br /><br/>
						<input type="hidden" name="option" value="mo2f_validate_authy_auth" />
						<input type="submit" name="validate" id="validate" class="button button-primary button-large" style="margin-left:12%;"value="Verify and Save" />
					</form>
					</div>
				</td>
			</tr><br>
		</table>
		<script>
			jQuery('html,body').animate({scrollTop: jQuery(document).height()}, 600);
		</script>
	<?php
	}
	
	function mo2f_configure_google_authenticator($current_user){
	$mo2f_google_auth = isset($_SESSION['mo2f_google_auth']) ? $_SESSION['mo2f_google_auth'] : null;
	$data = isset($_SESSION['mo2f_google_auth']) ? $mo2f_google_auth['ga_qrCode'] : null;
	$ga_secret = isset($_SESSION['mo2f_google_auth']) ? $mo2f_google_auth['ga_secret'] : null;
	?>
		<table>
			<tr>
				<td style="vertical-align:top;width:22%;padding-right:15px">
					<h3>Step-1: Select phone Type</h3><hr />
					<form name="f" method="post" id="mo2f_app_type_ga_form" action="" >
						<input type="radio" name="mo2f_app_type_radio" value="android" <?php checked( $mo2f_google_auth['ga_phone'] == 'android' ); ?> /> <b>Android</b><br /><br />
						<input type="radio" name="mo2f_app_type_radio" value="iphone" <?php checked( $mo2f_google_auth['ga_phone'] == 'iphone' ); ?> /> <b>iPhone</b><br /><br />
						<input type="radio" name="mo2f_app_type_radio" value="blackberry" <?php checked( $mo2f_google_auth['ga_phone'] == 'blackberry' ); ?> /> <b>BlackBerry / Windows</b><br /><br />
						<input type="hidden" name="option" value="mo2f_configure_google_auth_phone_type" />
					</form>
					<form name="f" method="post" action="" id="mo2f_cancel_form">
						<input type="hidden" name="option" value="mo2f_cancel_configuration" />
						<input type="submit" name="back" id="back_btn" class="button button-primary button-large" style="width:45%;" value="Back" />
					</form>
				</td>
				<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
				<td style="width:46%;padding-right:15px;vertical-align:top;">
					<h3>Step-2: Set up Google Authenticator</h3><hr>
					<div id="mo2f_android_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'android' ? 'display:block' : 'display:none'; ?>" >
					<h4>Install the Google Authenticator App for Android.</h4>
					<ol>
						<li>On your phone,Go to Google Play Store.</li>
						<li>Search for <b>Google Authenticator.</b>
						<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">Download from the Google Play Store and install the application.</a>
						</li>
					
					</ol>
					<h4>Now open and configure Google Authenticator.</h4>
					<ol>
						<li>In Google Authenticator, touch Menu and select "Set up account."</li>
						<li>Select "Scan a barcode". Use your phone's camera to scan this barcode.</li>
					<center><br><div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div></center>
						
					</ol>
					<div><a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_a" aria-expanded="false" ><b>Can't scan the barcode? </b></a></div>
					<div class="mo2f_collapse" id="mo2f_scanbarcode_a">
						<ol>
							<li>In Google Authenticator, touch Menu and select "Set up account."</li>
							<li>Select "Enter provided key"</li>
							<li>In "Enter account name" type your full email address.</li>
							<li>In "Enter your key" type your secret key:</li>
								<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
									<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
									<?php echo $ga_secret; ?>
									</div>
									<div style="font-size: 80%;color: #666666;">
									Spaces don't matter.
									</div>
								</div>
							<li>Key type: make sure "Time-based" is selected.</li>
							<li>Tap Add.</li>
						</ol>
					</div>
					</div>
					
					<div id="mo2f_iphone_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'iphone' ? 'display:block' : 'display:none'; ?>" >
					<h4>Install the Google Authenticator app for iPhone.</h4>
					<ol>
						<li>On your iPhone, tap the App Store icon.</li>
						<li>Search for <b>Google Authenticator.</b>
						<a href="http://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">Download from the App Store and install it</a>
						</li>
					</ol>
					<h4>Now open and configure Google Authenticator.</h4>
					<ol>
						<li>In Google Authenticator, tap "+", and then "Scan Barcode."</li>
						<li>Use your phone's camera to scan this barcode.
							<center><br><div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div></center>
						</li>
					</ol>
					<div><a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_i" aria-expanded="false" ><b>Can't scan the barcode? </b></a></div>
					<div class="mo2f_collapse" id="mo2f_scanbarcode_i"  >
						<ol>
							<li>In Google Authenticator, tap +.</li>
							<li>Key type: make sure "Time-based" is selected.</li>
							<li>In "Account" type your full email address.</li>
							<li>In "Key" type your secret key:</li>
								<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
									<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
									<?php echo $ga_secret; ?>
									</div>
									<div style="font-size: 80%;color: #666666;">
									Spaces don't matter.
									</div>
								</div>
							<li>Tap Add.</li>
						</ol>
					</div>
					</div>
					
					<div id="mo2f_blackberry_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'blackberry' ? 'display:block' : 'display:none'; ?>" >
					<h4>Install the Google Authenticator app for BlackBerry</h4>
					<ol>
						<li>On your phone, open a web browser.Go to <b>m.google.com/authenticator.</b></li>
						<li>Download and install the Google Authenticator application.</li>
					</ol>
					<h4>Now open and configure Google Authenticator.</h4>
					<ol>
						<li>In Google Authenticator, select Manual key entry.</li>
						<li>In "Enter account name" type your full email address.</li>
						<li>In "Enter key" type your secret key:</li>
							<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
								<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
								<?php echo $ga_secret; ?>
								</div>
								<div style="font-size: 80%;color: #666666;">
								Spaces don't matter.
								</div>
							</div>
						<li>Choose Time-based type of key.</li>
						<li>Tap Save.</li>
					</ol>
					</div>
					
				</td>
				<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
				<td style="vertical-align:top;width:30%">
					<h3>Step-3: Verify and Save</h3><hr>
					<div style="<?php echo isset($_SESSION['mo2f_google_auth']) ? 'display:block' : 'display:none'; ?>">
					<div>Once you have scanned the barcode, enter the 6-digit verification code generated by the Authenticator app</div><br/>
					<form name="f" method="post" action="" >
						<span><b>Code: </b>
						<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" required="true" type="text" name="google_token" placeholder="Enter OTP" style="width:95%;"/></span><br /><br/>
						<input type="hidden" name="option" value="mo2f_validate_google_auth" />
						<input type="submit" name="validate" id="validate" class="button button-primary button-large" style="margin-left:12%;"value="Verify and Save" />
					</form>
					</div>
				</td>
			</tr><br>
			<a  data-toggle="mo2f_collapse" href="#mo2f_question" aria-expanded="false" ><b>How miniOrange Authenticator is better than Google Authenticator ?</b></a>
			<div id="mo2f_question" class="mo2f_collapse"><p>
					 miniOrange Authenticator manages the Google Authenticator keys better and easier by providing these extra features:<br>
1. miniOrange <b>encrypts all data</b>, whereas Google Authenticator stores data in plain text.<br>
2. miniOrange Authenticator app has in-build <b>Pin-Protection</b> so you can protect your google authenticator keys or whole app using pin whereas Google Authenticator is not protected at all.<br>
3. No need to type in the code at all. Contact us to get <b>miniOrange Autofill Plugin</b>, it can seamlessly connect your computer to your phone. Code will get auto filled and saved.</p>
</div><br><br>
		</table>
		<script>
			 jQuery('input[type=radio][name=mo2f_app_type_radio]').change(function() {
				
				jQuery('#mo2f_app_type_ga_form').submit();
			 });
			 jQuery('html,body').animate({scrollTop: jQuery(document).height()}, 600);
		</script>
	<?php 
	}
	
	function show_verify_phone_for_otp($current_user){ 
			if(get_user_meta($current_user->ID, 'mo2f_selected_2factor_method',true) == 'SMS AND EMAIL') {
			?>
			<h3>Verify Your Phone and Email</h3><hr>
			<?php }else { ?>
			<h3>Verify Your Phone</h3><hr>
			<?php } ?>
					<form name="f" method="post" action="" id="mo2f_verifyphone_form">
						<input type="hidden" name="option" value="mo2f_verify_phone" />
						
						<div style="display:inline;">
						<input class="mo2f_table_textbox" style="width:200px;" type="text" name="verify_phone" id="phone" 
						    value="<?php if( isset($_SESSION['mo2f_phone'])){ echo $_SESSION['mo2f_phone'];} else echo get_user_meta($current_user->ID,'mo2f_user_phone',true); ?>"  pattern="[\+]?[0-9]{1,4}\s?[0-9]{7,12}" title="Enter phone number without any space or dashes" /><br>
						<?php if(get_user_meta($current_user->ID, 'mo2f_selected_2factor_method',true) == 'SMS AND EMAIL') {
						?>	
							<input class="mo2f_table_textbox" style="width:200px;" type="text" name="verify_email" id="email" 
						    value="<?php if( isset($_SESSION['mo2f_email'])){ echo $_SESSION['mo2f_email'];} else echo get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true); ?>" disabled /><br><br>
						<?php } ?>
						<input type="submit" name="verify" id="verify" class="button button-primary button-large" value="Verify" />
						</div>
					</form>	
				<form name="f" method="post" action="" id="mo2f_validateotp_form">
					<input type="hidden" name="option" value="mo2f_validate_otp" />
						<p>Enter One Time Passcode</p>
								<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token" placeholder="Enter OTP" style="width:95%;"/>
								<?php if (get_user_meta($current_user->ID, 'mo2f_selected_2factor_method',true) == 'PHONE VERIFICATION'){ ?>
									<a href="#resendsmslink">Call Again ?</a>
								<?php } else {?>
									<a href="#resendsmslink">Resend OTP ?</a>
								<?php } ?><br><br>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="Back" />
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="Validate OTP" />
				</form><br>
				<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
				</form>
		<script>
			jQuery("#phone").intlTelInput();
			jQuery('#back_btn').click(function() {	
					jQuery('#mo2f_cancel_form').submit();
			});
			jQuery('a[href="#resendsmslink"]').click(function(e) {
				jQuery('#mo2f_verifyphone_form').submit();
			});

		</script>
	<?php 
	}
	
	function initialize_mobile_registration() {
		$data = $_SESSION[ 'mo2f_qrCode' ];
		$url = get_site_option('mo2f_host_name');
		?>
		
			<p>Open your miniOrange<b> Authenticator</b> app and click on <b>Add Account</b> to scan the QR Code. Your phone should have internet connectivity to scan QR code.</p>
			<div style="color:#E74C3C;">
			<p>I am not able to scan the QR code, <a  data-toggle="mo2f_collapse" href="#mo2f_scanqrcode" aria-expanded="false" style="color:#3498DB;" >click here </a></p></div>
			<div class="mo2f_collapse" id="mo2f_scanqrcode">
				Follow these instructions below and try again.
				<ol>
					<li>Make sure your desktop screen has enough brightness.</li>
					<li>Open your app and click on Configure button to scan QR Code again.</li>
					<li>If you get cross mark on QR Code then click on 'Refresh QR Code' link.</li>
				</ol>
			</div>
			
			<table class="mo2f_settings_table">
				<a href="#refreshQRCode" style="color:#3498DB;" >Click here to Refresh QR Code.</a>
				<div id="displayQrCode" style="margin-left:250px;"><br /> <?php echo '<img style="width:200px;" src="data:image/jpg;base64,' . $data . '" />'; ?>
				</div>
			</table>
			<br />
			<div id="mobile_registered" >
			<form name="f" method="post" id="mobile_register_form" action="" style="display:none;">
				<input type="hidden" name="option" value="mo_auth_mobile_registration_complete" />
			</form>
			</div>
			<form name="f" method="post" action="" id="mo2f_cancel_form" style="display:none;">
				<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form >
			<form name="f" method="post" id="mo2f_refresh_qr_form" action="" style="display:none;">
				<input type="hidden" name="option" value="mo_auth_refresh_mobile_qrcode" />
			</form >
			
			<input type="button" name="back" id="back_to_methods" class="button button-primary button-large" value="Back" />
			
			<br /><br />
		
			<script>
			jQuery('#back_to_methods').click(function(e) {	
					jQuery('#mo2f_cancel_form').submit();
			});
			jQuery('a[href="#refreshQRCode"]').click(function(e) {	
					jQuery('#mo2f_refresh_qr_form').submit();
			});
			jQuery("#configurePhone").hide();
			jQuery("#reconfigurePhone").hide();
			var timeout;
			pollMobileRegistration();
			function pollMobileRegistration()
			{
				var transId = "<?php echo $_SESSION[ 'mo2f_transactionId' ];  ?>";
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
							jQuery("#displayQrCode").empty();
							jQuery("#displayQrCode").append(content);
							setTimeout(function(){jQuery("#mobile_register_form").submit();}, 1000);
						} else if (status == 'ERROR' || status == 'FAILED') {
							var content = "<br/><div id='error'><img style='width:165px;margin-top:-1%;margin-left:2%;' src='" + "<?php echo plugins_url( 'includes/images/wrong.png' , __FILE__ );?>" + "' /></div>";
							jQuery("#displayQrCode").empty();
							jQuery("#displayQrCode").append(content);
							jQuery("#messages").empty();
							
							jQuery("#messages").append("<div class='error mo2f_error_container'> <p class='mo2f_msgs'>An Error occured processing your request. Please try again to configure your phone.</p></div>");
						} else {
							timeout = setTimeout(pollMobileRegistration, 3000);
						}
					}
				});
			}
			jQuery('html,body').animate({scrollTop: jQuery(document).height()}, 800);
</script>
		<?php
	}
	
	function test_mobile_authentication() {
		?>
		
			<h3>Test QR Code Authentication</h3><hr>
			<p>Open your miniOrange <b>Authenticator App</b> and click on <b>SCAN QR Code</b> to scan the QR code. Your phone should have internet connectivity to scan QR code.</p>
			
			<div style="color:red;"><b>I am not able to scan the QR code, <a  data-toggle="mo2f_collapse" href="#mo2f_testscanqrcode" aria-expanded="false" >click here </a></b></div>
			<div class="mo2f_collapse" id="mo2f_testscanqrcode">
				<br />Follow these instructions below and try again.
				<ol>
					<li>Make sure your desktop screen has enough brightness.</li>
					<li>Open your app and click on Green button (your registered email is displayed on the button) to scan QR Code.</li>
					<li>If you get cross mark on QR Code then click on 'Back' button and again click on 'Test' link.</li>
				</ol>
			</div>
			<br /><br />
			<table class="mo2f_settings_table">
				<div id="qr-success" ></div>
				<div id="displayQrCode" style="margin-left:250px;"><br/><?php echo '<img style="width:165px;" src="data:image/jpg;base64,' . $_SESSION[ 'mo2f_qrCode' ] . '" />'; ?>
				</div>
				
			</table>
			
			<div id="mobile_registered" >
			<form name="f" method="post" id="mo2f_mobile_authenticate_success_form" action="">
				<input type="hidden" name="option" value="mo2f_mobile_authenticate_success" />
			</form>
			<form name="f" method="post" id="mo2f_mobile_authenticate_error_form" action="">
				<input type="hidden" name="option" value="mo2f_mobile_authenticate_error" />
			</form>
			<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
				<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="Back" />
			</form>
			</div>
				
		
			<script>
			var timeout;
			pollMobileValidation();
			function pollMobileValidation()
			{	
				var transId = "<?php echo $_SESSION[ 'mo2f_transactionId' ];  ?>";
				var jsonString = "{\"txId\":\""+ transId + "\"}";
				var postUrl = "<?php echo get_site_option('mo2f_host_name');  ?>" + "/moas/api/auth/auth-status";
				
				jQuery.ajax({
					url: postUrl,
					type : "POST",
					dataType : "json",
					data : jsonString,
					contentType : "application/json; charset=utf-8",
					success : function(result) {
						var status = JSON.parse(JSON.stringify(result)).status;
						if (status == 'SUCCESS') {
							var content = "<br /><div id='success'><img style='width:165px;margin-top:-1%;margin-left:2%;' src='" + "<?php echo plugins_url( 'includes/images/right.png' , __FILE__ );?>" + "' /></div>";
							jQuery("#displayQrCode").empty();
							jQuery("#displayQrCode").append(content);
							setTimeout(function(){jQuery('#mo2f_mobile_authenticate_success_form').submit();}, 1000);
							
						} else if (status == 'ERROR' || status == 'FAILED') {
							var content = "<br /><div id='error'><img style='width:165px;margin-top:-1%;margin-left:2%;' src='" + "<?php echo plugins_url( 'includes/images/wrong.png' , __FILE__ );?>" + "' /></div>";
							jQuery("#displayQrCode").empty();
							jQuery("#displayQrCode").append(content);
							setTimeout(function(){jQuery('#mo2f_mobile_authenticate_error_form').submit();}, 1000);
						} else {
							timeout = setTimeout(pollMobileValidation, 3000);
						}
					}
				});
			}
			jQuery('html,body').animate({scrollTop: jQuery(document).height()}, 600);
			</script>
		<?php
	}
	function test_soft_token(){	?>
		<h3>Test Soft Token</h3><hr>
		<p>Open your <b>miniOrange Authenticator App</b> and click on <b>Soft Token Tab</b>. Enter the <b>one time passcode</b> shown in App in the textbox below.</p>
			<form name="f" method="post" action="" id="mo2f_test_token_form">
					<input type="hidden" name="option" value="mo2f_validate_soft_token" />
					
								<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP" style="width:95%;"/>
								<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#demo4">Click here to see How to Setup ?</a><br><br>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="Back" />
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="Validate OTP" />
					
		    </form>
			<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
		<script>
			jQuery('#back_btn').click(function() {	
					jQuery('#mo2f_cancel_form').submit();
			});
		</script>
	<?php } 
	
	function test_google_authenticator($method){
		if($method == 'GOOGLE AUTHENTICATOR'){ ?>
			<h3>Test Google Authenticator</h3><hr>
			<p><b>Enter verification code</b></p>
			<p>Get a verification code from "Google Authenticator" app</p>
		<?php }else{ ?>
			<h3>Test Authy 2-Factor Authentication</h3><hr>
			<p><b>Enter verification code</b></p>
			<p>Get a verification code from "Authy 2-Factor Authentication" app</p>
		<?php } ?>
			<form name="f" method="post" action="" >
					<input type="hidden" name="option" value="mo2f_validate_google_auth_test" />
					
								<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP" style="width:95%;"/>
								<br><br>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="Back" />
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="Validate OTP" />
					
		    </form>
			<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
		<script>
			jQuery('#back_btn').click(function() {	
					jQuery('#mo2f_cancel_form').submit();
			});
		</script>
			
	<?php
	}
	
	function test_otp_over_sms($current_user){	
		$selected_2_factor_method = get_user_meta($current_user->ID, 'mo2f_selected_2factor_method',true);
		if ($selected_2_factor_method == 'SMS'){ ?>
			<h3>Test OTP Over SMS</h3><hr>
				<p>Enter the one time passcode sent to your registered mobile number.</p>
		<?php } else if($selected_2_factor_method == 'SMS AND EMAIL') { ?>
			<h3>Test OTP Over SMS And EMAIL</h3><hr>
			<p>Enter the one time passcode sent to your registered mobile number and email id.</p>
		<?php }
		else { ?>
			<h3>Test Phone Call Verification</h3><hr>
			<p>You will receive a phone call now. Enter the one time passcode here.</p>
		<?php } ?>
	
			<form name="f" method="post" action="" id="mo2f_test_token_form">
					<input type="hidden" name="option" value="mo2f_validate_otp_over_sms" />
					
								<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP" style="width:95%;"/>
								<?php if ($selected_2_factor_method == 'PHONE VERIFICATION'){ ?>
									<a href="#resendsmslink">Call Again ?</a>
								<?php } else {?>
									<a href="#resendsmslink">Resend OTP ?</a>
								<?php } ?>
								<br><br>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="Back" />
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="Validate OTP" />
					
		    </form>
			<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
			<form name="f" method="post" action="" id="mo2f_test_smsotp_form">
				<input type="hidden" name="option" value="mo_2factor_test_otp_over_sms" />
				<input type="hidden" name="mo2f_selected_2factor_method" value="<?php echo get_user_meta($current_user->ID, 'mo2f_selected_2factor_method',true); ?>" 
					id="mo2f_test_2factor_method" />
			</form>	
		
		<script>
			jQuery('#back_btn').click(function() {	
					jQuery('#mo2f_cancel_form').submit();
			});
			jQuery('a[href="#resendsmslink"]').click(function(e) {
				jQuery('#mo2f_test_smsotp_form').submit();
			});
		</script>
	
	<?php } 
	function test_push_notification() {?>
	
			<h3>Test Push Notification</h3><hr>
	<div >
			<br><br>
			<center>
				<h3>A Push Notification has been sent to your phone. <br>We are waiting for your approval...</h3>
				<img src="<?php echo plugins_url( 'includes/images/ajax-loader-login.gif' , __FILE__ );?>" />
			</center>
		<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="Back" style="margin-top:100px;margin-left:10px;"/>
		<br><br>
	</div>
			
			<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
			<form name="f" method="post" id="mo2f_push_success_form" action="">
				<input type="hidden" name="option" value="mo2f_out_of_band_success" />
			</form>
			<form name="f" method="post" id="mo2f_push_error_form" action="">
				<input type="hidden" name="option" value="mo2f_out_of_band_error" />
			</form>
		
		<script>
			jQuery('#back_btn').click(function() {	
					jQuery('#mo2f_cancel_form').submit();
			});
			
			var timeout;
			pollMobileValidation();
			function pollMobileValidation()
			{	
				var transId = "<?php echo $_SESSION[ 'mo2f_transactionId' ];  ?>";
				var jsonString = "{\"txId\":\""+ transId + "\"}";
				var postUrl = "<?php echo get_site_option('mo2f_host_name');  ?>" + "/moas/api/auth/auth-status";
				
				jQuery.ajax({
					url: postUrl,
					type : "POST",
					dataType : "json",
					data : jsonString,
					contentType : "application/json; charset=utf-8",
					success : function(result) {
						var status = JSON.parse(JSON.stringify(result)).status;
						if (status == 'SUCCESS') {
							jQuery('#mo2f_push_success_form').submit();
						} else if (status == 'ERROR' || status == 'FAILED' || status == 'DENIED') {
							jQuery('#mo2f_push_error_form').submit();
						} else {
							timeout = setTimeout(pollMobileValidation, 3000);
						}
					}
				});
			}
						
		</script>
	
	<?php }  function test_out_of_band_email($current_user) {?>
	
			<h3>Test Email Verification</h3><hr>
	<div>
			<br><br>
			<center>
				<h3>A verification email is sent to your registered email. <br>
				We are waiting for your approval...</h3>
				<img src="<?php echo plugins_url( 'includes/images/ajax-loader-login.gif' , __FILE__ );?>" />
			</center>
			
			<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="Back" style="margin-top:100px;margin-left:10px;"/>
	</div>
			
			<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
			<form name="f" method="post" id="mo2f_out_of_band_success_form" action="">
				<input type="hidden" name="option" value="mo2f_out_of_band_success" />
			</form>
			<form name="f" method="post" id="mo2f_out_of_band_error_form" action="">
				<input type="hidden" name="option" value="mo2f_out_of_band_error" />
			</form>
		
		<script>
			jQuery('#back_btn').click(function() {	
					jQuery('#mo2f_cancel_form').submit();
			});
			
			var timeout;
			pollMobileValidation();
			function pollMobileValidation()
			{	
				var transId = "<?php echo $_SESSION[ 'mo2f_transactionId' ];  ?>";
				var jsonString = "{\"txId\":\""+ transId + "\"}";
				var postUrl = "<?php echo get_site_option('mo2f_host_name');  ?>" + "/moas/api/auth/auth-status";
				
				jQuery.ajax({
					url: postUrl,
					type : "POST",
					dataType : "json",
					data : jsonString,
					contentType : "application/json; charset=utf-8",
					success : function(result) {
						var status = JSON.parse(JSON.stringify(result)).status;
						if (status == 'SUCCESS') {
							jQuery('#mo2f_out_of_band_success_form').submit();
						} else if (status == 'ERROR' || status == 'FAILED' || status == 'DENIED') {
							jQuery('#mo2f_out_of_band_error_form').submit();
						} else {
							timeout = setTimeout(pollMobileValidation, 3000);
						}
					}
				});
			}
						
		</script>
	
	<?php }

		function test_kba_authentication($current_user){ ?>
			
			<h3>Test Security Questions( KBA )</h3><hr>
			<p>Please answer the following question.</p>
	
			<form name="f" method="post" action="" id="mo2f_test_kba_form">
				<input type="hidden" name="option" value="mo2f_validate_kba_details" />
					
					<div id="mo2f_kba_content">
						<?php if(isset($_SESSION['mo_2_factor_kba_questions'])){
							echo $_SESSION['mo_2_factor_kba_questions'][0];
						?>
						<br />
						<input class="mo2f_table_textbox" style="width:227px;" type="text" name="mo2f_answer_1" id="mo2f_answer_1" required="true" autofocus="true" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+-\s]{1,100}" title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed."><br /><br />
						<?php
							echo $_SESSION['mo_2_factor_kba_questions'][1];
						?>
						<br />
						<input class="mo2f_table_textbox" style="width:227px;" type="text" name="mo2f_answer_2" id="mo2f_answer_2" required="true" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+-\s]{1,100}" title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed."><br /><br />
						<?php 
							}
						?>
					</div>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="Back" />
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="Validate Answers" />
					
		    </form>
			<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
		<script>
			jQuery('#back_btn').click(function() {	
					jQuery('#mo2f_cancel_form').submit();
			});
		</script>
		<?php
		} 
		
	function show_2_factor_pricing_page($current_user) { ?>
		<div class="mo2f_table_layout">
		<?php echo mo2f_check_if_registered_with_miniorange($current_user); ?>
		<table class="mo2f_pricing_table">
		<h2>Licensing Plans - Thanks for upgrading to premium plugin
		<span style="float:right"><input type="button" name="ok_btn" id="ok_btn" class="button button-primary button-large" value="OK, Got It" onclick="window.location.href='admin.php?page=miniOrange_2_factor_settings&mo2f_tab=mobile_configure'" /></span>
		</h2><hr>
		<tr style="vertical-align:top;">
			
			<td><div class="mo2f_thumbnail mo2f_pricing_paid_tab">
				<h3 class="mo2f_pricing_header">Do it yourself</h3>
				<h4 class="mo2f_pricing_sub_header" style="padding-bottom:8px !important;"><a class="button button-primary button-large"
-				 onclick="mo2f_upgradeform('wp_2fa_add_user_plan')" >Click here to add users</a>*</h4>
				
				<hr>
				<p class="mo2f_pricing_text">For 1+ user</p><hr>
				<p class="mo2f_pricing_text">Yearly Subscription Fees**
				<select class="form-control" style="border-radius:5px;width:250px;">
						<option > 5 users - $15 per year </option> 
						<option > 10 users - $30 per year </option> 
						<option > 20 users - $45 per year </option> 
						<option > 30 users - $60 per year </option>
						<option > 40 users - $75 per year </option>
						<option > 50 users - $90 per year </option>
						<option > 60 users - $100 per year </option>
						<option > 70 users - $110 per year </option> 
						<option > 80 users - $120 per year </option>
						<option > 90 users - $130 per year </option>
						<option > 100 users - $140 per year </option>
						<option > 150 users - $177.5 per year </option> 
						<option > 200 users - $215 per year </option> 	
						<option > 250 users - $245 per year </option>
						<option > 300 users - $275 per year </option>
						<option > 350 users - $300 per year </option> 
						<option > 400 users - $325 per year </option>
						<option > 450 users - $347.5 per year </option>	
						<option > 500 users - $370 per year </option>			
						<option > 600 users - $395 per year </option>
						<option > 700 users - $420 per year </option>
						<option > 800 users - $445 per year </option>
						<option > 900 users - $470 per year </option>	
						<option > 1000 users - $495 per year </option>
						<option > 2000 users - $549 per year </option>	
						<option > 3000 users - $599 per year </option>
						<option > 4000 users - $649 per year </option>
						<option > 5000 users - $699 per year </option>	
						<option > 10000 users - $799 per year </option>
						<option > 20000 users - $999 per year </option>	
					</select>
				</p>
				<hr>
				<p class="mo2f_pricing_text">Features:</p>
				<p class="mo2f_pricing_text">All Authentication Methods***<br />
				Remember Device<br>
				Two-Factor for Woocommerce Front End Login<br>
				Enforce 2FA registration for users<br />
				Enable or Disable 2FA for individual users<br />
				Manage Registered Device Profiles<br />
				Multi-Site Support <br />
				Custom Redirection<br />
				Customize Email Templates<br />
				Customize SMS Templates<br/>
				Customize Powered By logo<br />
				Customize Security Questions (KBA)<br />
				Enable 2 Factor with various login forms****<br><br>
				</p><hr>
				<p class="mo2f_pricing_text">Backup Method:<br />
				Security Questions (KBA)<br />
				OTP over EMAIL</p>
				<hr>
				<p class="mo2f_pricing_text">Basic Support By Email</p>
			</div></td>
		</td>
		<td><div class="mo2f_thumbnail mo2f_pricing_free_tab">
				<h3 class="mo2f_pricing_header">Premium</h3>
				<h4 class="mo2f_pricing_sub_header" style="padding-bottom:8px !important;"><a class="button button-primary button-large"
-				 onclick="mo2f_upgradeform('wp_2fa_add_user_plan')" >Click here to add users</a>*</h4>
				
				<hr>
				<p class="mo2f_pricing_text">For 1+ user, Setup and Custom Work</p><hr>
				<p  class="mo2f_pricing_text">Yearly Subscription Fees**
				<select class="form-control" style="border-radius:5px;width:250px;">
						<option > 5 users - $15 per year </option> 
						<option > 10 users - $30 per year </option> 
						<option > 20 users - $45 per year </option> 
						<option > 30 users - $60 per year </option>
						<option > 40 users - $75 per year </option>
						<option > 50 users - $90 per year </option>
						<option > 60 users - $100 per year </option>
						<option > 70 users - $110 per year </option> 
						<option > 80 users - $120 per year </option>
						<option > 90 users - $130 per year </option>
						<option > 100 users - $140 per year </option>
						<option > 150 users - $177.5 per year </option> 
						<option > 200 users - $215 per year </option> 	
						<option > 250 users - $245 per year </option>
						<option > 300 users - $275 per year </option>
						<option > 350 users - $300 per year </option> 
						<option > 400 users - $325 per year </option>
						<option > 450 users - $347.5 per year </option>	
						<option > 500 users - $370 per year </option>			
						<option > 600 users - $395 per year </option>
						<option > 700 users - $420 per year </option>
						<option > 800 users - $445 per year </option>
						<option > 900 users - $470 per year </option>	
						<option > 1000 users - $495 per year </option>
						<option > 2000 users - $549 per year </option>	
						<option > 3000 users - $599 per year </option>
						<option > 4000 users - $649 per year </option>
						<option > 5000 users - $699 per year </option>	
						<option > 10000 users - $799 per year </option>
						<option > 20000 users - $999 per year </option>	
					</select>
				</p>
				<hr>
				<p class="mo2f_pricing_text">Features:</p>
				<p class="mo2f_pricing_text">All Authentication Methods***<br />
				Two-Factor for Woocommerce Front End Login<br>
				Enforce 2FA registration for users<br />
				Enable or Disable 2FA for individual users<br />
				Remember Device<br>
				Manage Registered Device Profiles<br />
				Multi-Site Support <br />
				Custom Redirection<br />
				Customize Email Templates<br />
				Customize SMS Templates<br/>
				Customize Powered By logo<br />
				End to End 2FA Integration***<br/>
				<br/>
				<br/>
				</p><hr>
				<p class="mo2f_pricing_text">Backup Method:<br />
				Security Questions (KBA)<br />
				OTP over EMAIL</p>
				<hr>
				<p class="mo2f_pricing_text">Premium Support Plans Available</p>
			</div></td>
		</td>
		</tr>
		
		</table>
		<br>
		<h3>* Steps to upgrade to premium plugin -</h3>
		<p>1. You will be redirected to miniOrange Login Console. Enter your password with which you created an account with us and verify your 2nd factor. After that you will be redirected to payment page.</p>
		<p>2. Enter you card details and complete the payment. On successful payment completion, you will see the link to download the premium plugin.</p>
		<p>3. Once you download the premium plugin, just unzip it and replace the folder with existing plugin. </p>
		<b>Note: Do not delete the plugin from the Wordpress Admin Panel and upload the plugin using zip. Your saved settings will get lost.</b>
		<p>4. From this point on, do not update the plugin from the Wordpress store. </p>
	
		<h3>** Volume discounts are available. Contact Us for more details.</h3>
		<p>You can mail us at <a href="mailto:info@miniorange.com"><b>info@miniorange.com</b></a> or submit the support form under User Profile tab to contact us.</p>
		<h3>*** End to End 2FA Integration - We will setup a Conference Call / Gotomeeting and do end to end setup for you. We provide services to do the setup on your behalf.
		<h3>*** All Authentication Methods:</h3><ol> 
		<li>We highly recommend to use phone based authentication methods like Soft Token, QR Code Authentication and Push Notification.</li>
		<li>Setting up knowledge based questions (KBA) as an alternate login method will protect you in case your phone is not working or out of reach. <br /><b><u>What to do in case you are locked out (Its common when you are setting up 2FA for the first time, so please read this).<br />
		<a data-toggle="mo2f_collapse" href="#mo2f_locked_out" aria-expanded="false" >Click Here to know how to login, in case you are locked out.</a></u></b/>
		<div class="mo2f_collapse" id="mo2f_locked_out">
			</br><b>Rename</b> the plugin by FTP access. Go to <b>wp-content/plugins folder</b> and rename miniorange-2-factor-authentication folder.<br /><br />
		</div>
		</li> 
		<li>OTP over SMS and Email delivery depends on the SMS and SMTP Gateway you choose. There are different levels of these gateway:</li>
			<ul>
				<li><b>Standard Gateway:</b> You may get a lag in the service of SMS and Email.</li>
				<li><b>Premium Gateway:</b> The delivery of SMS will be fast if you choose this gateway. However, we provide a global gateway and you may have a better local gateway. So our experience is that if you want OTP over SMS then the best thing is to go with your own local gateway which is proven and fast in your local area. </li>
				<li><b>Choose your own SMS and SMTP Gateway:</b> We recommend you choose your own SMS and SMTP gateway to send Email and SMS.</li>
			</ul>
		</ol>
		<br /><hr><br />
		<p><b>****</b>  The 2 Factor plugin works with various login forms like Woocommerce, Theme My Login and many more. We do not claim that 2 Factor works with all the customized login forms. In such cases, custom work is needed to integrate 2 factor with your customized login page.</p>
		
		<br/><hr><br>
		<h3>***** End to End 2FA Integration - We will setup a Conference Call / Gotomeeting and do end to end setup for you. We provide services to do the setup on your behalf.
		<h3>10 Days Return Policy -</h3>

		<div>At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved, we will refund the whole amount within 10 days of the purchase. Please email us at <a href="mailto:info@miniorange.com"><i>info@miniorange.com</i></a> for any queries regarding the return policy.<br /> 
		If you have any doubts regarding the licensing plans, you can mail us at <a href="mailto:info@miniorange.com"><i>info@miniorange.com</i></a> or submit a query using the support form.</div><br /><br />	
		</div>
		<form style="display:none;" id="mo2fa_loginform" action="<?php echo get_site_option( 'mo2f_host_name').'/moas/login'; ?>" 
		target="_blank" method="post">
			<input type="email" name="username" value="<?php echo get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true); ?>" />
			<input type="text" name="redirectUrl" value="<?php echo get_site_option( 'mo2f_host_name').'/moas/initializepayment'; ?>" />
			<input type="text" name="requestOrigin" id="requestOrigin"  />
		</form>
		<script>
			function mo2f_upgradeform(planType){
				jQuery('#requestOrigin').val(planType);
				jQuery('#mo2fa_loginform').submit();
			}
		</script>
		
	<?php } ?>