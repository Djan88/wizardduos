<?php
include dirname( __FILE__ ) . '/views/admin_setup_select_2_factor_method';
include dirname( __FILE__ ) . '/views/configure_miniorange_authenticator';
include dirname( __FILE__ ) . '/views/configure_otp_over_sms';
include dirname( __FILE__ ) . '/views/test_otp_over_sms';
include dirname( __FILE__ ) . '/views/test_email_verification';
include dirname( __FILE__ ) . '/views/test_google_authy_authenticator';
include dirname( __FILE__ ) . '/views/test_miniorange_qr_code_authentication';
include dirname( __FILE__ ) . '/views/test_miniorange_push_notification';
include dirname( __FILE__ ) . '/views/test_miniorange_soft_token';


	function mo2f_check_if_registered_with_miniorange($current_user){
		global $dbQueries;
		$user_registration_status = $dbQueries->get_user_detail( 'mo_2factor_user_registration_status',$current_user->ID);
		if($user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'){ 
				?>
				<br />
				<div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure">click here</a> to setup Two-Factor.</div>
	<?php	
		}else if(!($user_registration_status == 'MO_2_FACTOR_INITIALIZE_MOBILE_REGISTRATION' || mo2f_is_customer_registered())) { ?>
			<br/><div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">Please <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=2factor_setup">Login with your miniOrange account</a> to configure miniOrange 2 Factor plugin.</div>
	<?php } 
	}
	
	function mo2f_get_activated_second_factor($current_user){
		global $dbQueries;
		$mobile_registration_status = $dbQueries->get_user_detail( 'mo_2factor_mobile_registration_status',$current_user->ID);
		$user_registration_status = $dbQueries->get_user_detail( 'mo_2factor_user_registration_status',$current_user->ID);
		if($mobile_registration_status == 'MO_2_FACTOR_SUCCESS'){ 
		
			//checking this option for existing users
			// update_user_meta($current_user->ID,'mo2f_mobile_registration_status',true);
			$dbQueries->update_user_details( $current_user->ID, array('mo2f_mobile_registration_status' =>true) );
			$mo2f_second_factor = 'MOBILE AUTHENTICATION';
			return $mo2f_second_factor;
		}else if($user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){
			return 'NONE';
		}else{
			//for new users
			global $dbQueries;
	// $selected_2factor_method = $dbQueries->get_user_detail( $current_user->ID,'mo2f_configured_2FA_method');
		$user_registration_with_miniorange = $dbQueries->get_user_detail( 'mo_2factor_user_registration_with_miniorange',$current_user->ID);
			if($user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS' && $user_registration_with_miniorange == 'SUCCESS'){
				$enduser = new Two_Factor_Setup();
				$email = $dbQueries->get_user_detail( 'mo2f_user_email',$current_user->ID);
				$userinfo = json_decode($enduser->mo2f_get_userinfo($email),true);
				if(json_last_error() == JSON_ERROR_NONE){
					if($userinfo['status'] == 'ERROR'){
						update_site_option( 'mo2f_message', Mo2fConstants::langTranslate($userinfo['message']));
						$mo2f_second_factor = 'NONE';
					}else if($userinfo['status'] == 'SUCCESS'){
						$mo2f_second_factor = isset( $userinfo['authType'] ) && ! empty( $userinfo['authType'] ) ? $userinfo['authType'] : 'NONE';
						// // $mo2f_second_factor = $userinfo['authType'];
					}else if($userinfo['status'] == 'FAILED'){
						$mo2f_second_factor = 'NONE';
						update_site_option( 'mo2f_message', Mo2fConstants::langTranslate("ACCOUNT_REMOVED"));
					}else{
						$mo2f_second_factor = 'NONE';
					}
				}else{
					update_site_option( 'mo2f_message', Mo2fConstants::langTranslate("INVALID_REQ"));
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
				<div><center><p style="font-size:17px;"><?php echo __('A new security system has been enabled to better protect your account. Please configure your Two-Factor Authentication method by setting up your account.','miniorange-2-factor-authentication');?></p></center></div>
				<div id="panel1">
					<table class="mo2f_settings_table">
						
						<tr>
							<td><center><div class="alert-box"><input type="email" autofocus="true" name="mo_useremail" style="width:48%;text-align: center;height: 40px;font-size:18px;border-radius:5px;" required placeholder="<?php echo __('person@example.com','miniorange-2-factor-authentication');?>" value="<?php echo $current_user->user_email;?>"/></div></center></td>
						</tr>
						<tr>
							<td><center><p><?php echo __('Please enter a valid email id that you have access to. You will be able to move forward after verifying an OTP that we will be sending to this email','miniorange-2-factor-authentication');?>.</p></center></td>
						</tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<?php if(get_site_option('mo2f_enable_gdpr_policy')){?>
						<tr><td><center><input type="checkbox" id="mo2f_gdpr" name="mo2f_gdpr" required /><?php echo mo2f_lt( 'I agree to' ); ?> <a href="<?php echo get_site_option('mo2f_privacy_policy_link'); ?>" target="_blank"><u><?php echo mo2f_lt( 'terms & conditions' ); ?></u></a>.<br/></center></td></tr>
						<?php } ?>
						<tr><td></td></tr>
						<tr><td></td></tr>
						<tr>
							<td><input type="hidden" name="miniorange_user_reg_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-user-reg-nonce'); ?>" />
							<center><input type="submit" name="miniorange_get_started" id="miniorange_get_started" class="button button-primary button-large" value="<?php echo __('Get Started','miniorange-2-factor-authentication');?>" /></center> </td>
						</tr>
					</table>
				</div>
			</div>
		</form>
	<?php
	}
	
	function show_2_factor_advanced_options($current_user){
		global $dbQueries;
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
			
				
				<span><h3><?php echo __('Customize Security Questions (KBA)','miniorange-2-factor-authentication');?>
				</h3><hr></span>
					<p style="margin-left: 2%;"><?php echo __('You can customize the questions list shown in the Security Questions. You can also choose how many custom questions your endusers can add while setting up Security Questions.','miniorange-2-factor-authentication');?></p> 
					<p style="font-size:15px;margin-left: 2%;"><b><a data-toggle="mo2f_collapse" aria-expanded="false" href="#customSecurityQuestions"><?php echo __('Click Here','miniorange-2-factor-authentication');?></a> <?php echo __('to customize Security Questions.','miniorange-2-factor-authentication');?></b></p>
					<div class="mo2f_collapse" id="customSecurityQuestions" style="margin-left: 2%;">
						<form name="f"  id="custom_security_questions" method="post" action="">
							<a data-toggle="mo2f_collapse" aria-expanded="false" href="#addAdminQuestions"><b><?php echo __('Hints for choosing questions:','miniorange-2-factor-authentication');?></b></a>
							<div class="mo2f_collapse" id="addAdminQuestions">
							<ol>
								<li><?php echo __('What is your first company name?','miniorange-2-factor-authentication');?></li>
								<li><?php echo __('What was your childhood nickname?','miniorange-2-factor-authentication');?></li>
								<li><?php echo __('In what city did you meet your spouse/significant other?','miniorange-2-factor-authentication');?></li>
								<li><?php echo __('What is the name of your favorite childhood friend?','miniorange-2-factor-authentication');?></li>
								<li><?php echo __('What school did you attend for sixth grade?','miniorange-2-factor-authentication');?></li>
								<li><?php echo __('In what city or town was your first job?','miniorange-2-factor-authentication');?></li>
								<li><?php echo __('What is your favourite sport?','miniorange-2-factor-authentication');?></li>
								<li><?php echo __('Who is your favourite sports player?','miniorange-2-factor-authentication');?></li>
								<li><?php echo __('What is your grandmother\'s maiden name?','miniorange-2-factor-authentication');?></li>
								<li><?php echo __('What was your first vehicle\'s registration number?','miniorange-2-factor-authentication');?></li>
							</ol>
							</div><br /><br />
							<b><?php echo __('Add Questions in the Security Questions (KBA) List: (Alteast 10)','miniorange-2-factor-authentication');?></b><br /><br />
							<table class="mo2f_kba_table">
								<?php for($qc = 0; $qc <= 15; $qc++){ ?>
								<tr class="mo2f_kba_body">
									<td>Q<?php echo $qc + 1; ?>:</td>
									<td>
										<input class="mo2f_kba_ques" type="text" name="mo2f_kbaquestion_custom_admin[]" id="mo2f_kbaquestion_custom_admin_<?php echo $qc + 1; ?>" pattern="(?=\S)[A-Za-z0-9\/_?@'.$#&+\-*\s]{1,100}" value="<?php echo Mo2fConstants::langTranslate($array_question[$qc]) ?>" placeholder="<?php echo __('Enter your custom question here','miniorange-2-factor-authentication');?>" autocomplete="off" />
									</td>
								</tr>
								<?php } ?>
							</table>
							<br /><br />
								<div style="margin-left: 2%;">
							<b><?php echo __('Security Questions for users: ','miniorange-2-factor-authentication');?></b><br /><br />
							<span><?php echo __('Default Questions to choose from above list: ','miniorange-2-factor-authentication');?><input style="border: 1px solid #ddd;border-radius: 4px;width:40px;" type="text" name="mo2f_default_kbaquestions_users" id="mo2f_default_kbaquestions_users" value="<?php echo get_site_option( 'mo2f_default_kbaquestions_users'); ?>" pattern="[0-9]{1}" autocomplete="off" /> <b><=5</b></span><br />
							
							<?php echo __('Custom Questions added by users: ','miniorange-2-factor-authentication');?><input style="border: 1px solid #ddd;border-radius: 4px;width:40px;" type="text" name="mo2f_custom_kbaquestions_users" id="mo2f_custom_kbaquestions_users" value="<?php echo get_site_option( 'mo2f_custom_kbaquestions_users'); ?>" pattern="[0-9]{1}" autocomplete="off" /> <b><=5</b>
							<br /><br />
					</div>
							<input type="hidden" name="option" value="mo_auth_save_custom_security_questions" />
							<input type="submit" name="submit" value="<?php echo __('Save Settings','miniorange-2-factor-authentication');?>" class="button button-primary button-large" <?php 
					if(mo2f_is_customer_registered()){ } else{ echo 'disabled' ; } ?> />
						</form>
						
					</div>
					
					<br>
					<span><h3><?php echo __('Customize Settings','miniorange-2-factor-authentication');?>
					</h3><hr></span>
					<br>
					<div style="border: 1px solid #DCDCDC;padding:20px;">
						<form name="f"  id="custom_settings" method="post" action="">
					<span><h3><?php echo __('Remove KBA setup during inline registration','miniorange-2-factor-authentication');?>
					</h3><hr></span>
					
					<input type="checkbox" id="mo2f_disable_kba" name="mo2f_disable_kba" style="margin-left: 2%;" value="1" <?php checked( get_site_option('mo2f_disable_kba') == 1 ); 
					if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /> 
					<?php echo __('Remove KBA setup for users during inline registration. ','miniorange-2-factor-authentication');?><br/>
					<br /><div id="mo2f_note"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('Checking this option will remove \'KBA\' setup for your users during inline registration.','miniorange-2-factor-authentication');?></div>
					<br>
					
					<span><h3><?php echo __('Enable','miniorange-2-factor-authentication');?> '<b><?php echo __('Remember Device','miniorange-2-factor-authentication');?></b>' 
						</h3><hr></span>
					<input type="checkbox" id="mo2f_remember_device" name="mo2f_remember_device" style="margin-left: 2%;" value="1" <?php checked( get_site_option('mo2f_remember_device') == 1 ); 
					
					if(mo2f_is_customer_registered()&& get_site_option('mo2f_login_policy')){}else{ echo 'disabled';} ?> /> 
					<?php echo __('Enable','miniorange-2-factor-authentication');?> '<b><?php echo __('Remember Device','miniorange-2-factor-authentication');?></b>' <?php echo __('option. ','miniorange-2-factor-authentication');?><br /><span style="color:red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<?php echo __('Applicable only with ','miniorange-2-factor-authentication');?><i><?php echo __('Login with password + 2nd Factor','miniorange-2-factor-authentication');?>)</i></span></br>
					<br />
					
					<div style="margin-left:6%; <?php echo get_site_option('mo2f_remember_device')==1 ? 'display:block' : 'display:none' ?>" id="mo2f_enable_remember_dev" >
						<input type="radio" name="mo2f_enable_rba_types" value="1" <?php checked( get_site_option('mo2f_enable_rba_types') == 1 ); 
						if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
						<?php echo __('Give users an option to enable','miniorange-2-factor-authentication');?> '<b><?php echo __('Remember Device','miniorange-2-factor-authentication');?></b>'.	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<br><br>
						<input type="radio" name="mo2f_enable_rba_types" value="0" <?php checked( get_site_option('mo2f_enable_rba_types') == 0 ); 
						if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
						<?php echo __('Silently enable','miniorange-2-factor-authentication');?> '<b><?php echo __('Remember Device.','miniorange-2-factor-authentication');?></b>'.
						<br><br>
					</div>
					
					
					<div class="mo2f_advanced_options_note" style="margin-left: 2%;"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('Checking this option will enable','miniorange-2-factor-authentication');?>'<b><?php echo __('Remember Device','miniorange-2-factor-authentication');?></b>'. <?php echo __('In the login from the same device, user will bypass 2nd factor i.e user will be logged in through username + password only.','miniorange-2-factor-authentication');?></div>
					<br>
					
					
					
					<input type="hidden" name="option" value="mo_auth_save_custom_settings" />
					<input type="submit" name="submit" value="<?php echo __('Save Settings','miniorange-2-factor-authentication');?>" class="button button-primary button-large" <?php 
					if(mo2f_is_customer_registered()){ } else{ echo 'disabled' ; } ?> />
					</div>
					<script>
						jQuery('#mo2f_remember_device').click(function() {
							if(jQuery(this).is(':checked'))
								jQuery('#mo2f_enable_remember_dev').show();
							else
								jQuery('#mo2f_enable_remember_dev').hide();
						});
					</script>
					</form>
					<br />
					<h3><?php echo __('Send Email to notify your Users','miniorange-2-factor-authentication');?></h3><hr><br />
				 <div>
					  <form name="f" method="post" id="mo2f_users_notify" action="">
					<div><span > <?php echo mo2f_lt('To:' ); ?></span>  <input type="text" name="mo2f_users_notify" value="All Your Wordpress Users " style="width:50%;float:right" disabled></div><br>
					<div><span><?php echo mo2f_lt('From:' ); ?></span><input type="text" name="mo2f_users_notify_email" style="width:50%;float:right" value="<?php echo get_site_option('mo2f_email')?>" disabled></div><br>
					 <span Style="margin-right=10px"><?php echo mo2f_lt('Subject:' ); ?></span><input type="text" name="mo2f_users_notify_subject" style="width:50%;float:right" value="<?php echo get_site_option('mo2f_users_notify_subject')?>"><br>
					
					 <br><br><b><?php echo mo2f_lt('Custom Message:' ); ?></b><br><br>
						<div><span><?php echo mo2f_lt('Message Part 1:' ); ?></span><input type="text" name="mo2f_users_notify_msg1" style="width:70%;float:right" value="<?php echo get_site_option('mo2f_users_notify_msg1')?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>></div><br>
						<div><span><?php echo mo2f_lt('Message Part 2:' ); ?></span><input type="text" name="mo2f_users_notify_msg2" style="width:70%;float:right" value="<?php echo get_site_option('mo2f_users_notify_msg2')?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>></div><br>
						<div><span><?php echo mo2f_lt('Message Part 3:' ); ?></span><input type="text" name="mo2f_users_notify_msg3" style="width:70%;float:right" value="<?php echo get_site_option('mo2f_users_notify_msg3')?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>></div><br>
						<div><span><?php echo mo2f_lt('Site Url:' ); ?></span><input type="text" name="mo2f_users_notify_site_url"  style="width:70%;float:right" value="<?php echo get_site_option('mo2f_users_notify_site_url')?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>></div><br>
						<div><span><?php echo mo2f_lt('Image Url:' ); ?></span><input type="text" name="mo2f_users_notify_image" style="width:70%;float:right" value="<?php echo get_site_option('mo2f_users_notify_image')?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>></div><br>
							<input type="hidden" name="option" value="mo2f_users_notify_save" /><br>
							<input type="submit" name="Save" id="save"  style="float:left" class="button button-primary button-large" value="<?php echo __('Save','miniorange-2-factor-authentication');?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>/>
					 </form>
					<form name="f" method="post" id="mo2f_users_notify_reset" action="">
						<input type="hidden" name="option" value="mo2f_users_notify_reset" />
						<input type="submit" name="Reset" id="Reset" style="display:inline;margin-left:10px" class="button button-primary button-large" value="<?php echo __('Reset','miniorange-2-factor-authentication');?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>/><br>
					</form>
					 <br>
					 <h3><?php echo mo2f_lt('Template:  ');?></h3><br>
					 
					 <?php 
					  $imgurl = get_site_option('mo2f_users_notify_image');
					  $line1  = get_site_option('mo2f_users_notify_msg1');
					  $line2  =  get_site_option('mo2f_users_notify_msg2');
					  $site_url = get_site_option('mo2f_users_notify_site_url');
					  $line3=get_site_option('mo2f_users_notify_msg3');
					  $content='<table cellpadding="25" style="margin:0px auto"><tbody><tr><td><table cellpadding="24" width="584px" style="margin:0 auto;max-width:584px;background-color:#f6f4f4;border:1px solid #a8adad">
						<tbody><tr><td><img src="'.$imgurl.'" style="color:#5fb336;text-decoration:none;display:block;width:auto;height:auto;max-height:35px" ></td>
						</tr></tbody></table><table cellpadding="24" style="background:#fff;border:1px solid #a8adad;width:584px;border-top:none;color:#4d4b48;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:18px">
						<tbody><tr><td>
						<p style="margin-top:0;margin-bottom:20px">Dear User,</p><p style="margin-top:0;margin-bottom:10px"><p style="margin-top:0;margin-bottom:10px">'.$line1.'</p></p>
						<p style="margin-top:0;margin-bottom:10px"><p style="margin-top:0;margin-bottom:10px">'. $line2.' <a href="'.$site_url.'" target="_blank">'.$site_url.'</a>
						<p style="margin-top:0;margin-bottom:15px">Thank you,<br>'. $line3.'</p><p style="margin-top:0;margin-bottom:0px;font-size:11px">Disclaimer: This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed.</p>
						</span></td></tr></tbody></table></td></tr></tbody></table>'; ?>
						<div style="border:2px solid">	
						<?php echo $content;?>
						</div>
						<br>
						<form name="f" method="post" id="mo2f_users_notify_send" action="">
						<input type="hidden" name="option" value="mo2f_users_notify_send" />
						<input type="hidden" name="content" value='<?php echo $content;?>' />
						<input type="submit" name="Send" id="Send" style="float:left" class="button button-primary button-large" value="<?php echo __('Send','miniorange-2-factor-authentication');?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>/>
						</form>
						<form name="f" method="post" id="mo2f_users_notify_send" action="">
						<input type="hidden" name="option" value="mo2f_users_notify_send" />
						<input type="hidden" name="content" value='<?php echo $content;?>' />
						<input type="submit" name="test" id="test" style="display:inline;margin-left:10px" class="button button-primary button-large" value="<?php echo __('Test With Admin','miniorange-2-factor-authentication');?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>/><br>
						</form>
					</div>
				<h3><?php echo __('Backup Codes','miniorange-2-factor-authentication');?></h3><hr>
				<p><?php echo __('You can create new Backup codes. These are one time use codes. Your old codes will not be valid.','miniorange-2-factor-authentication');?></p>
				<div>
				<form name="f" method="post" id="mo2f_users_backup" action="">
						<input type="hidden" name="option" value="mo2f_users_backup" />
					
						<input type="submit" name="Generate Codes" id="codes" style="display:inline;margin-left:10px" class="button button-primary button-large" value="<?php echo __('Generate Codes','miniorange-2-factor-authentication');?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>/><br>
				</form>
				</div><br />
				 
					
				
				
				<span><h3><?php echo __('Device Profile View','miniorange-2-factor-authentication');?>
				</h3><hr></span>
					<p style="margin-left: 2%;"><?php echo __('You can manage trusted devices which you have stored during login by remembering devices.','miniorange-2-factor-authentication');?></p> 
					<a class="button button-primary button-large" style="margin-left: 2%;" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('RBA'); ?>' )" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled style="pointer-events: none;cursor: default;"';} ?> ><?php echo __('View Profiles','miniorange-2-factor-authentication');?></a>
				<br>
				
				
				<h3><?php echo mo2f_lt('MultiSite Support');?></h3><hr>
					<p style="margin-left: 2%;"><?php echo mo2f_lt('Just One time Setup. User has to setup his 2nd factor only once, no matter, in how many sites he exists. Ease of use.');?>
					</p>
				<h3><?php echo mo2f_lt('Custom Email and SMS Templates');?></h3><hr>
					<p style="margin-left: 2%;"><?php echo mo2f_lt('You can change the templates for Email and SMS as per your requirement.');?></p>
					<?php if(mo2f_is_customer_registered()){ 
							if( get_site_option('mo2f_miniorange_admin') == $current_user->ID ){ ?>
								<a class="button button-primary button-large" style="margin-left: 2%;" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('EMAIL'); ?>' )" ><?php echo mo2f_lt('Customize Email Template');?></a><span style="margin-left:10px;"></span>
								<a class="button button-primary button-large" style="margin-left: 2%;" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('SMS'); ?>' )" ><?php echo mo2f_lt('Customize SMS Template');?></a>
						<?php	} 
						}else{ ?>
						<a class="button button-primary button-large" style="margin-left: 2%;" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('EMAIL'); ?>' )" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled style="pointer-events: none;cursor: default;"';} ?> >Customize Email Template</a><span style="margin-left:10px;"></span>
						<a class="button button-primary button-large" style="margin-left: 2%;" onclick="mo2fLoginMiniOrangeDashboard( '<?php echo MO2f_Utility::get_miniorange_login_url('SMS'); ?>' )" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled style="pointer-events: none;cursor: default;"';} ?> >Customize SMS Template</a>
					<?php } ?>
				<h3><?php echo mo2f_lt('Custom Redirection');?></h3><hr>
					<p style="margin-left: 2%;"><?php echo mo2f_lt('This option will allow the users during login to redirect on the specific page role wise. Set custom URLs under Login Settings tab.');?></p>
		<form name="f"  id="advance_options_form" method="post" action="">
			<?php if(current_user_can('manage_options')){ ?>
			<input type="hidden" name="option" value="mo_auth_advanced_options_save" />
				<h3><?php echo mo2f_lt('Customize \'powered by\' Logo:');?></h3><hr>
				 <div>
				 	<input type="checkbox" id="mo2f_disable_poweredby" name="mo2f_disable_poweredby" value="1" style="margin-left: 2%;" <?php checked( get_site_option('mo2f_disable_poweredby') == 1 ); 
				 	if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /> 
				 	<?php echo mo2f_lt('Remove \'Powered By\' option from the Login Screens. ');?><br />
				 	<br /><div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%"><?php echo mo2f_lt('<b>Note:</b> Checking this option will remove \'Powered By\' from the Login Screens.');?></div>
				 	<br>
				 <input type="checkbox" id="mo2f_enable_custom_poweredby" name="mo2f_enable_custom_poweredby" style="margin-left: 2%;" value="1" <?php checked( get_site_option('mo2f_enable_custom_poweredby') == 1 ); 
					 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
					 
					 <?php echo mo2f_lt('Enable Custom \'Powered By\' option for the Login Screens. ');?><br><br>
					 <div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%"><?php echo mo2f_lt('<b>Instructions:</b>
						Go to /wp-content/uploads/miniorange folder and upload a .png image with the name "custom" (Max Size: 100x28px).');?>
					 </div>
				</div>
				 	<br>

			

				
					<br>
					<input type="submit" name="submit" value="<?php echo mo2f_lt('Save Settings');?>" class="button button-primary button-large" <?php 
					if(mo2f_is_customer_registered()){ } else{ echo 'disabled' ; } ?> />
				<?php
				} 
				?>
				<br /><br/>
			</form>
				</div>
			<form style="display:none;" id="mo2fa_loginform" action="<?php echo get_site_option( 'mo2f_host_name').'/moas/login'; ?>" 
		target="_blank" method="post">
		
			<input type="email" name="username" value="<?php echo $dbQueries->get_user_detail( 'mo2f_user_email',$current_user->ID); ?>" />
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
	
	function mo2f_show_2FA_configuration_screen( $user, $selected2FAmethod ) {
	switch ( $selected2FAmethod ) {
		case "Google Authenticator":
			mo2f_configure_google_authenticator( $user );
			break;
		case "Authy Authenticator":
			mo2f_configure_authy_authenticator( $user );
			break;
		case "Security Questions":
			mo2f_configure_for_mobile_suppport_kba( $user );
			break;
		// case "Email Verification":
		// error_log("2");
			// mo2f_configure_for_Verification( $user,"Email Verification" );
			// break;
		// case "OTP Over Email":
			// mo2f_configure_for_Verification( $user,"OTP Over Email" );
			// break;
		case "OTP Over SMS":
			mo2f_configure_otp_over_sms( $user );
			break;
		case "OTP Over SMS And Email":
			mo2f_configure_otp_over_sms( $user );
			break;
		default:
			mo2f_configure_miniorange_authenticator( $user );
	}

}
	
	function mo2f_show_user_otp_validation_page(){
	?>
		<!-- Enter otp -->
		
		<div class="mo2f_table_layout">
			<h3><?php echo mo2f_lt('Validate One Time Passcode (OTP)');?></h3><hr>
			<div id="panel1">
				<table class="mo2f_settings_table">
					<form name="f" method="post" id="mo_2f_otp_form" action="">
						<input type="hidden" name="option" value="mo_2factor_validate_user_otp" />
							<tr>
								<td><b><font color="#FF0000">*</font><?php echo mo2f_lt('Enter OTP:');?></b></td>
								<td colspan="2"><input class="mo2f_table_textbox" autofocus="true" type="text" name="otp_token" required placeholder="<?php echo mo2f_lt('Enter OTP');?>" style="width:95%;"/></td>
								<td><a href="#resendotplink"><?php echo mo2f_lt('Resend OTP ?');?></a></td>
							</tr>
							
							<tr>
								<td>&nbsp;</td>
								<td style="width:17%">
								<input type="submit" name="submit" value="<?php echo mo2f_lt('Validate OTP');?>" class="button button-primary button-large" /></td>

						</form>
						<form name="f" method="post" action="">
						<td>
						<input type="hidden" name="option" value="mo_2factor_backto_user_registration"/>
							<input type="submit" name="mo2f_goback" id="mo2f_goback" value="<?php echo mo2f_lt('Back');?>" class="button button-primary button-large" /></td>
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
				<h1><?php echo mo2f_lt('Reset 2nd Factor');?></h1>

				<p><?php echo mo2f_lt('You have specified this user for reset:');?></p>

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
		global $dbQueries;
		
		if($mo2f_second_factor == 'OUT OF BAND EMAIL'){
			$mo2f_second_factor = 'Email Verification';
		}else if($mo2f_second_factor == 'SMS'){
			$mo2f_second_factor = 'OTP over SMS';
		}else if($mo2f_second_factor == 'SMS AND EMAIL'){
			$mo2f_second_factor = 'OTP over SMS And Email';
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
				
		$user_registration_status = $dbQueries->get_user_detail( 'mo_2factor_user_registration_status',$current_user->ID);
						if($user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'){ 
					?>
						<br />
						<div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);"><?php echo __('Please','miniorange-2-factor-authentication');?> <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure"><?php echo __('click here','miniorange-2-factor-authentication');?></a> <?php echo __('to setup Two-Factor.','miniorange-2-factor-authentication');?></div>
				<?php }
				?>
					<?php if(current_user_can('manage_options')){ ?> <h4><?php echo __('Thank you for upgrading to premium plugin.','miniorange-2-factor-authentication');?> <span style="float:right;"></h4>
					<?php }else{ ?>
					<h4><?php echo __('Thank you for registering with us.','miniorange-2-factor-authentication');?></h4>
					<?php } ?>
					<h3><?php echo __('Your Profile','miniorange-2-factor-authentication');?></h3>
					<table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:100%">
						<tr>
							<td style="width:45%; padding: 10px;"><b><?php echo mo2f_lt('2 Factor Registered Email');?></b></td>
							
							<td style="width:55%; padding: 10px;"><?php echo $dbQueries->get_user_detail( 'mo2f_user_email',$current_user->ID); echo '  (' . $current_user->user_login . ')';?> 
							</td>
						</tr>
						<tr>
							<td style="width:45%; padding: 10px;"><b><?php echo __('Activated 2nd Factor','miniorange-2-factor-authentication');?></b></td>
							<td style="width:55%; padding: 10px;"><?php echo $mo2f_second_factor;?> 
							</td>
						</tr>
						<?php if(current_user_can('manage_options')){ ?>
						<tr>
							<td style="width:45%; padding: 10px;"><b>miniOrange <?php echo __('Customer Email','miniorange-2-factor-authentication');?></b></td>
							<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_email');?></td>
						</tr>
						<tr>
							<td style="width:45%; padding: 10px;"><b><?php echo __('Customer ID','miniorange-2-factor-authentication');?></b></td>
							<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_customerKey');?></td>
						</tr>
						<tr>
							<td style="width:45%; padding: 10px;"><b><?php echo __('API Key','miniorange-2-factor-authentication');?></b></td>
							<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_api_key');?></td>
						</tr>
						<tr>
							<td style="width:45%; padding: 10px;"><b><?php echo __('Token Key','miniorange-2-factor-authentication');?></b></td>
							<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_customer_token');?></td>
						</tr>
						<?php if(get_site_option('mo2f_app_secret')){ ?>
							<tr>
								<td style="width:45%; padding: 10px;"><b><?php echo __('App Secret','miniorange-2-factor-authentication');?></b></td>
								<td style="width:55%; padding: 10px;"><?php echo get_site_option('mo2f_app_secret');?></td>
							</tr>
						<?php 
							} 
						?>
						<tr style="height:40px;">
							<td style="border-right-color:white;"><a target="_blank" href="https://auth.miniorange.com/moas/idp/resetpassword"><b>&nbsp; <?php echo __('Click Here','miniorange-2-factor-authentication');?></b></a> <?php echo __('to reset your miniOrange password.','miniorange-2-factor-authentication');?></td>
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
	global $dbQueries;
		$mobile_registration_status = $dbQueries->get_user_detail( 'mo_2factor_mobile_registration_status',$current_user->ID);
		$is_flow_driven_setup = ! ( get_user_meta( $current_user->ID, 'current_modal', true ) ) ? 0 : 1;
		if(!$mobile_registration_status) {
			download_instruction_for_mobile_app($is_flow_driven_setup,$mobile_registration_status);
		}
	?><div>
		<h3><?php echo __('Step-2 : Scan QR code','miniorange-2-factor-authentication');?></h3><hr>
			
			<form name="f" method="post" action="">
				<input type="hidden" name="option" value="mo_auth_refresh_mobile_qrcode" />
					<?php if($mobile_registration_status) {   ?>
					<div id="reconfigurePhone">
					<a  data-toggle="mo2f_collapse" href="#mo2f_show_download_app" aria-expanded="false" ><?php echo __('Click here to see Authenticator App download instructions.','miniorange-2-factor-authentication');?></a>
					<div id="mo2f_show_download_app" class="mo2f_collapse"> 
						<?php download_instruction_for_mobile_app($is_flow_driven_setup,$mobile_registration_status); ?>
					</div>
					<br>
					<h4><?php echo __('Please click on \'Reconfigure your phone\' button below to see QR Code.','miniorange-2-factor-authentication');?></h4>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" />
					<input type="submit" name="submit" class="button button-primary button-large" value="<?php echo __('Reconfigure your phone','miniorange-2-factor-authentication');?>" />	
					</div>
					
					<?php } else {?>
					<div id="configurePhone"><h4><?php echo __('Please click on \'Configure your phone\' button below to see QR Code.','miniorange-2-factor-authentication');?></h4>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" />
					<input type="submit" name="submit" class="button button-primary button-large" value="<?php echo __('Configure your phone','miniorange-2-factor-authentication');?>" />
					</div>
					<?php } ?>
			</form>
				
					 <?php 
						if(isset($_SESSION[ 'mo2f_show_qr_code' ]) && $_SESSION[ 'mo2f_show_qr_code' ] == 'MO_2_FACTOR_SHOW_QR_CODE' && isset($_POST['option']) && $_POST['option'] == 'mo_auth_refresh_mobile_qrcode'){
									initialize_mobile_registration($is_flow_driven_setup);
								 if($mobile_registration_status) {   ?>
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
	
	
	
	function mo2f_configure_kba_questions(){ 
	
	
	$kbaQuestionsArray = get_site_option( 'mo2f_auth_admin_custom_kbaquestions');

	$defaultQuestions = get_site_option( 'mo2f_default_kbaquestions_users');
	$customQuestions = get_site_option( 'mo2f_custom_kbaquestions_users');
	
	?>
			<table class="mo2f_custom_kba_table" style="border-spacing: 15px;">
              <thead>
				<tr style="padding: 15px;">
					<th class="mo2fa_thtd" scope="col"><?php echo __('Sl. No.','miniorange-2-factor-authentication');?></th>
					<th class="mo2fa_thtd" scope="col"><?php echo __('Question','miniorange-2-factor-authentication');?></th>
					<th class="mo2fa_thtd" scope="col"><?php echo __('Answer','miniorange-2-factor-authentication');?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php  for ($count = 0; $count < $defaultQuestions; $count++){ ?>
				<tr>
					<td class="mo2fa_thtd">
					<?php echo $count + 1; ?>.
					</td>
					<td data-label="<?php echo __('Question','miniorange-2-factor-authentication');?>" class="mo2fa_thtd">
						<select name="mo2f_kbaquestion[]" id="mo2f_kbaquestion_<?php echo $count + 1; ?>" class="mo2f_kba_ques" required="true"  onchange="mo_option_hide(<?php echo $count + 1; ?>)">
							<option value="" selected="selected"> ----------------<?php echo __('Select your question','miniorange-2-factor-authentication');?>----------------</option>
							<?php
								$index = 1;
								foreach($kbaQuestionsArray as $question){ 
							?>
									<option id="mq<?php echo $index; ?>_<?php echo $count + 1; ?>" value="<?php echo $question; ?>"><?php echo Mo2fConstants::langTranslate($question)?></option>
							<?php 	$index = $index + 1; 
								}
							?>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   					 
					</td>
					<td class="mo2fa_thtd">
						<input class="mo2f_table_textbox" type="text" name="mo2f_kba_ans[]" id="mo2f_kba_ans<?php echo $count + 1; ?>" title="<?php echo __('Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.','miniorange-2-factor-authentication');?>" pattern="(?=\S)[A-Za-z0-9\/_?@'.$#&+\-*\s]{1,100}" required="true" autofocus="true" placeholder="<?php echo __('Enter your answer','miniorange-2-factor-authentication');?>" autocomplete="off" />
					</td>
				</tr>
				<?php } 
				for ($count1 = 0; $count1 < $customQuestions; $count1++){ ?>
				<tr>
					<td class="mo2fa_thtd">
					<?php echo $count + $count1 + 1;?>.
					</td>

					<td data-label="<?php echo __('Question','miniorange-2-factor-authentication');?>" class="mo2fa_thtd">
						<input class="mo2f_kba_ques" type="text" name="mo2f_kbaquestion[]" id="mo2f_kbaquestion_<?php echo $count + $count1 + 1; ?>"  required="true" placeholder="<?php echo __('Enter your custom question here','miniorange-2-factor-authentication');?>" autocomplete="off" pattern="(?=\S)[A-Za-z0-9\/_?@'.$#&+\-*\s]{1,100}" />
					</td>
					<td class="mo2fa_thtd">
						<input class="mo2f_table_textbox" type="text" name="mo2f_kba_ans[]" id="mo2f_kba_ans<?php echo $count + $count1 + 1; ?>"  title="<?php echo __('Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.','miniorange-2-factor-authentication');?>" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+-\s]{1,100}" required="true" placeholder="<?php echo __('Enter your answer','miniorange-2-factor-authentication');?>" autocomplete="off" />
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
		<?php if ( ! get_user_meta( $current_user->ID, 'current_modal', true ) ) { ?>
			<h3><?php echo __('Configure Second Factor - KBA (Security Questions)','miniorange-2-factor-authentication');?></h3><hr />
   
	<?php } ?>
				<form name="f" method="post" action="" id="mo2f_kba_setup_form">
					<?php mo2f_configure_kba_questions(); ?>
					<br />
					<input type="hidden" name="option" value="mo2f_save_kba" />
			<table>
            <tr>
                <td>
					<input type="submit" id="mo2f_kba_submit_btn" name="submit" value="<?php echo __('Save','miniorange-2-factor-authentication');?>" class="button button-primary button-large" style="width:100px;line-height:30px;float:left !important;"/>
				</form>	
					<?php if ( get_user_meta( $current_user->ID, 'current_modal', true ) ) { ?>
					<br><br>
					<?php } ?>	
                </td>
				<td>		
				<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
					<?php if ( ! get_user_meta( $current_user->ID, 'current_modal', true ) ) { ?>
					<input type="submit" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" style="width:100px;line-height:30px;float:right !important;" />
					<?php } ?>
				</form>
		  </td>
		</tr>
		</table>	

		<script>
		
			jQuery('#mo2f_kba_submit_btn').click(function() {
				jQuery('#mo2f_kba_setup_form').submit();
			});
		</script>
	<?php
	}
	
	function mo2f_set_kba_backup( $user,$selected2FAmethod ){
	// var_dump($selected2FAmethod);exit;
	if($selected2FAmethod!="Security Questions")
	mo2f_configure_for_mobile_suppport_kba( $user );
	
		
	}
	
	function mo2f_show_2FA_test_screen( $user, $selected2FAmethod ) {
     // var_dump($selected2FAmethod);exit;
	switch ( $selected2FAmethod ) {
		case "miniOrange QR Code Authentication":
			mo2f_test_miniorange_qr_code_authentication( $user );
			break;
		case "miniOrange Push Notification":
			mo2f_test_miniorange_push_notification( $user );
			break;
		case "miniOrange Soft Token":
			mo2f_test_miniorange_soft_token( $user );
			break;
		case "Security Questions":
			test_kba_authentication( $user );
			break;
		case "OTP Over SMS":
			mo2f_test_otp_over_sms( $user );
			break;
		case "OTP Over SMS And Email":
			mo2f_test_otp_over_sms( $user );
			break;
		case "Email Verification":
			mo2f_test_email_verification( $user );
			break;
		case "OTP Over Email":
			mo2f_test_otp_over_sms( $user );
			break;
		default:
			mo2f_test_google_authy_authenticator1( $user, $selected2FAmethod );
	}

}
	function mo2f_select_2_factor_method($current_user,$mo2f_second_factor){ 
	global $dbQueries;
            $opt=fetch_methods($current_user);
			// $opt = (array) get_site_option('mo2f_auth_methods_for_users');
			
			if($mo2f_second_factor == 'OUT OF BAND EMAIL'){
						$selectedMethod = "Email Verification";
						$testMethod="OUT OF BAND EMAIL";
			}if($mo2f_second_factor == 'PHONE VERIFICATION'){
						$selectedMethod = "PHONE VERIFICATION";
						$testMethod="PHONE VERIFICATION";
			} else if($mo2f_second_factor == 'MOBILE AUTHENTICATION'){
						$selectedMethod = "QR Code Authentication";
						$testMethod="MOBILE AUTHENTICATION";
			}else if($mo2f_second_factor == 'SMS'){
						$selectedMethod = "OTP Over SMS";
						$testMethod="SMS";
			}else if($mo2f_second_factor == 'EMAIL'){
			$selectedMethod = 'OTP OVER EMAIL';
			$testMethod="OTP_OVER_EMAIL";	
			}else if($mo2f_second_factor == 'KBA'){
			$selectedMethod = 'Security Questions';
			$testMethod="KBA";	
			}else if($mo2f_second_factor == 'NONE'){
						$selectedMethod = 'NONE';
						$testMethod="NONE";	
			}else if($mo2f_second_factor == 'SOFT TOKEN'){
						$selectedMethod = 'miniOrange Soft Token';
						$testMethod="SOFT TOKEN";	
			}else if($mo2f_second_factor == 'PUSH NOTIFICATIONS'){
						$selectedMethod = 'miniOrange Push Notification';
						$testMethod="PUSH NOTIFICATIONS";	
			}else if($mo2f_second_factor == 'SMS AND EMAIL'){
						$selectedMethod = "OTP Over SMS And Email";
						$testMethod="SMS AND EMAIL";
			}else if($mo2f_second_factor == 'GOOGLE AUTHENTICATOR'||$mo2f_second_factor == 'Google Authenticator'){
				$app_type = get_user_meta($current_user->ID,'mo2f_external_app_type',true);
				// var_dump($app_type);exit;
				if($app_type == 'GOOGLE AUTHENTICATOR'){
					$selectedMethod = 'GOOGLE AUTHENTICATOR';
					$testMethod=$selectedMethod;
				}else if($app_type == 'AUTHY 2-FACTOR AUTHENTICATION'){
					$selectedMethod = 'AUTHY 2-FACTOR AUTHENTICATION';
					$testMethod=$selectedMethod;
				}else{
					$selectedMethod = 'GOOGLE AUTHENTICATOR';
					$testMethod=$selectedMethod;
					update_user_meta($current_user->ID,'mo2f_external_app_type','GOOGLE AUTHENTICATOR');
				}
			}
				// $selectedMethod = $mo2f_second_factor;
			?>
		<div class="mo2f_table_layout">	
		<?php
		
		if( get_user_meta($current_user->ID,'mo2f_configure_test_option',true) == 'MO2F_CONFIGURE'){
			
				$current_selected_method = $dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$current_user->ID);
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
			      // var_dump(get_user_meta($current_user->ID,'mo2f_configure_test_option',true));exit;
				$current_selected_method = $dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$current_user->ID);
				// var_dump($current_selected_method);exit;	
				// $current_selected_method = get_user_meta($current_user->ID,'mo2f_selected_2factor_method',true);
				if($current_selected_method == 'MOBILE AUTHENTICATION') {
					test_mobile_authentication();
				}else if($current_selected_method == 'PUSH NOTIFICATIONS'){
					test_push_notification();
				}else if($current_selected_method == 'SOFT TOKEN'){
					test_soft_token();
				}else if ($current_selected_method == 'SMS' || $current_selected_method == 'PHONE VERIFICATION' || $current_selected_method == 'SMS AND EMAIL'|| $current_selected_method == 'OTP_OVER_EMAIL'){
					test_otp_over_sms($current_user);
				}else if($current_selected_method == 'GOOGLE AUTHENTICATOR' || $current_selected_method == 'AUTHY 2-FACTOR AUTHENTICATION' ){
					test_google_authenticator($current_selected_method);
				}else if( $current_selected_method == 'KBA' ){
					test_kba_authentication($current_user);
				}else {
					test_out_of_band_email($current_user);
				}
			
		}else{
			// var_dump("here");exit;
			$is_customer_registered = $dbQueries->get_user_detail(  'mo_2factor_user_registration_with_miniorange',$current_user->ID ) == 'SUCCESS' ? true : false;
			// $is_customer_registered = mo2f_is_customer_registered();
			// var_dump($is_customer_registered);exit;
			// update_user_meta( $current_user	->ID, 'skipped_flow_driven_setup', 0 );
			// var_dump($is_customer_registered &&  ! get_user_meta( $current_user->ID, 'skipped_flow_driven_setup', true ));
			if ( $is_customer_registered && ( ! get_user_meta( $current_user->ID, 'skipped_flow_driven_setup', true ) ) ) {
			if ( ! get_user_meta( $current_user->ID, 'current_modal', true ) ) {
				update_user_meta( $current_user->ID, 'current_modal', 1 );
				update_option( 'mo2f_message', '' );
			}
			start_flow_driven_setup( $current_user );
		}
			
		$kba_registration_status = $dbQueries->get_user_detail( 'mo2f_SecurityQuestions_config_status',$current_user->ID);
		$user_registration_status = $dbQueries->get_user_detail( 'mo_2factor_user_registration_status',$current_user->ID);
		if(!$kba_registration_status && ($is_customer_registered || $user_registration_status  == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR')){
			
		?>
		
		<div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);" class="error notice is-dismissible"><a href="#mo2f_kba_config"><?php echo __('Click Here','miniorange-2-factor-authentication');?></a>   <?php echo __('to configure Security Questions (KBA) as alternate 2 factor method so that you are not locked out of your account in case you lost or forgot your phone.','miniorange-2-factor-authentication');?></div>
		
		<?php
			
		}else if($user_registration_status  == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'){ 
				?>
				<br />
				<div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);"><?php echo __('Please configure your 2nd factor here to complete the Two-Factor setup..','miniorange-2-factor-authentication');?></div>
	<?php	
		}
								// var_dump($is_customer_registered);exit;
		$user_registration_status = $dbQueries->get_user_detail( 'mo_2factor_user_registration_status',$current_user->ID);
		$otp_registration_status = $dbQueries->get_user_detail( 'mo2f_otp_registration_status',$current_user->ID);
		$mobile_registration_status = $dbQueries->get_user_detail( 'mo2f_mobile_registration_status',$current_user->ID);
		$email_otp_registration_status = $dbQueries->get_user_detail( 'mo2f_email_otp_registration_status',$current_user->ID);
		// $is_customer_registered = mo2f_is_customer_registered();
	
	  if(!$is_customer_registered){
	?>
	
	<br>
	<div class="mo2f_register_with_mo_message"><?php echo mo2f_lt( 'Please ' ); ?>
            <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=2factor_setup"><?php echo mo2f_lt( 'Login with your miniOrange account'  ); ?></a> <?php echo mo2f_lt( 'to configure the miniOrange 2 Factor plugin.' ); ?>
        </div>
	
	<?php }?>
		<div style="text-align: center;">

                <p style="font-size:20px;color:darkorange;padding:10px;"><?php echo __('Selected Method','miniorange-2-factor-authentication');?> - <?php echo $selectedMethod; ?></p>

		<a href="#test" class="button button-primary button-large" data-method="<?php echo $testMethod;?>" <?php echo $is_customer_registered && ( $selectedMethod != 'NONE' ) ? "" : " disabled "; ?>   ><?php echo __('Test Authentication Method','miniorange-2-factor-authentication');?></a><br><br>
		<form name="f" method="post" id="mo2f_users_backup" action="">
						<input type="hidden" name="option" value="mo2f_users_backup" />
					
						<input type="submit" name="Generate Codes" id="codes" style="display:inline;margin-left:10px;float:right;" class="button button-primary button-large" value="<?php echo __('Generate Backup Codes','miniorange-2-factor-authentication');?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?>/><br>
				</form>
		
		<br><br>
			<hr>
		</div>
		<br>
		<form name="f" method="post" action="" id="mo2f_2factor_form">
		
			
			<div>
                        <a class="mo2f_view_free_plan_auth_methods" >
                            <img src=" <?php echo plugins_url( 'includes\images\right-arrow.png', __FILE__ );?>"
                                 class="mo2f_2factor_heading_images"/>
                            <p class="mo2f_heading_style"><?php echo mo2f_lt('Authentication methods');?><span style="color:limegreen"><?php echo mo2f_lt('( Current Plan )');?></span>
								                            </p>
                        </a>

                    </div>

				<table style="margin-left:4%">
				<tr >

				<td style="padding-right:14px;" class="<?php if(!current_user_can('manage_options') && !(in_array("OUT OF BAND EMAIL", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
					
                         
					<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
						<div >
						  <div >
							<div style="width: 80px; float:left;" >
                          <img src="<?php echo plugins_url( 'includes/images/authmethods/EmailVerification.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
							</div>
							<div style="width:190px; padding:20px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>"><?php echo mo2f_lt('Email Verification');?></label></b><br>
								<p style="padding:5px; padding-left:0px;padding-bottom:23px;"> <?php echo mo2f_lt('Accept the verification link sent to your email to login.');?></p>
							</div>
						  </div>
                        </div>
								<?php 
								if($is_customer_registered){
									$email_verification_status = $dbQueries->get_user_detail( 'mo2f_email_verification_status',$current_user->ID);
										if(!$email_verification_status){
											$dbQueries->update_user_details( $current_user->ID, array('mo2f_email_verification_status' =>true) );
											// update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
										}
										 if($selectedMethod!='Email Verification')
												$flag=1;
									else
												$flag=0;
										// var_dump(!$flag);exit;
									?> 
									<div class="configuredLaptop"  id="OUT_OF_BAND_EMAIL"  <?php if(!$flag){?> style="background-color:#48b74b !important;height:19px"<?php }?> title="Supported in Desktops, Laptops, Smartphones">
										<?php  
										if($flag){
											?>
										<a name="mo2f_selected_2factor_method" style="color:white;"  data-method="OUT OF BAND EMAIL" <?php if($is_customer_registered || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> ><?php echo mo2f_lt('Set as 2-factor' );?></a>
										<?php } ?>
									</div>
								<?php } else { ?>
									
									<div class="notConfiguredLaptop" style="padding:20px;" id="OUT_OF_BAND_EMAIL" title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>"></div>
								<?php } ?>
					</div>
						
						
				</td>
					<td style="padding-right:14px;" class="<?php if(!current_user_can('manage_options') && !(in_array("SMS", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
						   <div >
						  <div >
							<div style="width: 80px; float:left;">
                          <img src="<?php echo plugins_url( 'includes/images/authmethods/OTPOverSMS.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
							</div>
							<div style="width:190px; padding:20px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>">OTP Over SMS</label></b><br>
								<p style="padding:5px; padding-left:0px;padding-bottom:23px;"> <?php echo mo2f_lt('Enter the One Time Passcode sent to your phone to login.');?></p>
							</div>
						  </div>
							</div>
							
							<?php 
							 if($selectedMethod!='OTP Over SMS')
												$flag=1;
									else
												$flag=0;
							
							if($otp_registration_status){ ?>
								<div class="configuredBasic" id="SMS" <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> title="<?php echo mo2f_lt('supported in smartphone,feature phone');?>">
									<a href="#reconfigure" data-method="SMS" style="color:white;"><?php echo mo2f_lt('Reconfigure');?></a>  
									<?php if($flag){?>|
										<a name="mo2f_selected_2factor_method" style="color:white;"  data-method="SMS" <?php if($is_customer_registered || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> ><?php echo mo2f_lt('Set as 2-factor' );?></a>
									<?php }?>
								</div>
							<?php } else { ?>
							
								<div class="notConfiguredBasic" title="<?php echo mo2f_lt('Supported in Smartphones, Feature Phones.');?>"><a name="mo2f_selected_2factor_method"  data-method="SMS" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>><?php echo mo2f_lt('Configure');?></a></div>
							<?php } ?>
						</div>
					</td >
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("PHONE VERIFICATION", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>">
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
						 <div >
						  <div >
							<div style="width: 80px; float:left;">
                          <img src="<?php echo plugins_url( 'includes/images/authmethods/EmailVerification.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
							</div>
							<div style="width:190px; font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>">Phone Call Verification </label></b><br>
								<p style="padding:5px; padding-left:0px;padding-bottom:25px;"><?php echo mo2f_lt('You will receive a phone call telling a one time passcode. You have to enter the one time passcode to login.');?>
							</p>
							</div>
						  </div>
							</div>
							<?php 
							 if($selectedMethod!='PHONE VERIFICATION')
												$flag=1;
									else
												$flag=0;
							
							if($otp_registration_status){ ?>
								<div class="configuredLandline" id="PHONE_VERIFICATION" <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> title="<?php echo mo2f_lt('Supported in Landline phones, Smartphones, Feature phones.');?>">
									<a href="#reconfigure" style="color:white;" data-method="PHONE VERIFICATION" > <?php echo mo2f_lt('Reconfigure');?> </a>  <?php if($flag){?>|<a name="mo2f_selected_2factor_method" style="color:white;" class="mo2f_configure_set_2_factor"  data-method="PHONE VERIFICATION" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> > <?php echo mo2f_lt('Set as 2-factor' );?> </a>
											<?php }?>
								</div>
							<?php } else { ?>
								<div class="notConfiguredLandline" title="<?php echo mo2f_lt('supported in Landline phone,smartphone,feature phone');?>"><a name="mo2f_selected_2factor_method"  data-method="PHONE VERIFICATION" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>><?php echo mo2f_lt('Configure');?></a></div>
							<?php } ?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("SOFT TOKEN", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
							<div >
								<div >
									<div style="width: 80px; float:left;">
										<img src="<?php echo plugins_url( 'includes/images/authmethods/miniOrangeSoftToken.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
									</div>
									<div style="width:190px; padding:20px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>">miniOrange Soft Token </label></b><br>
										<p style="padding:5px; padding-left:0px;"><?php echo mo2f_lt('Enter the soft token from the account in your miniOrange Authenticator App to login.');?></p>
									</div>
								</div>
							</div>
							
							<?php 
							 if($selectedMethod!='miniOrange Soft Token')
												$flag=1;
									else
												$flag=0;
							
							if($mobile_registration_status){ ?>
							<div class="configuredSmart"  id="SOFT_TOKEN" <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> title="<?php echo mo2f_lt('Supported in Smartphones only');?>">
								<a href="#reconfigure" style="color:white;" data-method="SOFT TOKEN" ><?php echo mo2f_lt('Reconfigure');?> </a>
								<?php if($flag){?>
								| <a name="mo2f_selected_2factor_method" class="mo2f_configure_set_2_factor"  data-method="SOFT TOKEN" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> > <?php echo mo2f_lt('Set as 2-factor' );?></a>
								<?php }?>
							</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="<?php echo mo2f_lt('supported in smartphone');?>"><a name="mo2f_selected_2factor_method"  data-method="SOFT TOKEN" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>><?php echo mo2f_lt('Configure');?></a></div>
							<?php } ?>
						</div>
					</td>
				
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("MOBILE AUTHENTICATION", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
							<div >
								<div >
									<div style="width: 80px; float:left;">
										<img src="<?php echo plugins_url( 'includes/images/authmethods/miniOrangeQRCodeAuthentication.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
									</div>
									<div style="width:190px; padding:10px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>">miniOrange QR Code Authentication</label></b><br>
										<p style="padding:5px; padding-left:0px;"><?php echo mo2f_lt('Scan the QR code from the account in your miniOrange Authenticator App to login.');?></p>
									</div>
								</div>
							</div>
							
							<?php   if($selectedMethod!='QR Code Authentication')
												$flag=1;
									else
												$flag=0;
							
							if($mobile_registration_status  ){ ?>
								<div class="configuredSmart" id="MOBILE_AUTHENTICATION" <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> title="<?php echo mo2f_lt('Supported in Smartphones only.');?>">
									<a href="#reconfigure" style="color:white;" data-method="MOBILE AUTHENTICATION"><?php echo mo2f_lt('Reconfigure');?> </a> 
									<?php if($flag){?>
									| <a name="mo2f_selected_2factor_method" class="mo2f_configure_set_2_factor"  data-method="MOBILE AUTHENTICATION" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> > <?php echo mo2f_lt('Set as 2-factor' );?></a>
								<?php }?>
								</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="<?php echo mo2f_lt('Supported in Smartphones only');?>"><a name="mo2f_selected_2factor_method"  data-method="MOBILE AUTHENTICATION" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>><?php echo mo2f_lt('Configure');?></a></div>
							<?php } ?>
						</div>
					</td>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("PUSH NOTIFICATIONS", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
							<div >
								<div >
									<div style="width: 80px; float:left;">
										<img src="<?php echo plugins_url( 'includes/images/authmethods/miniOrangePushNotification.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
									</div>
									<div style="width:190px; padding:10px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>">miniOrange Push Notification</label></b><br>
										<p style="padding:5px; padding-left:0px;"><?php echo mo2f_lt('Accept a push notification in your miniOrange Authenticator App to login.');?></p>
									</div>
								</div>
							</div>
							
							<?php 
							 if($selectedMethod!='miniOrange Push Notification')
												$flag=1;
									else
												$flag=0;
							if($mobile_registration_status){ ?>
							<div class="configuredSmart" id="PUSH_NOTIFICATIONS" <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> title="<?php echo mo2f_lt('supported in smartphone');?>">
								<a href="#reconfigure" style="color:white;" data-method="PUSH NOTIFICATIONS" ><?php echo mo2f_lt('Reconfigure');?></a>
								<?php if($flag){?>
								| <a name="mo2f_selected_2factor_method" class="mo2f_configure_set_2_factor"  data-method="PUSH NOTIFICATIONS" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> ><?php echo mo2f_lt('Set as 2-factor' );?></a>
								<?php }?>
							</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="<?php echo mo2f_lt('Supported in Smartphones only.');?>"><a name="mo2f_selected_2factor_method"  data-method="PUSH NOTIFICATIONS" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>><?php echo mo2f_lt('Configure');?></a></div>
							<?php } ?>
						</div>
					</td>
					</tr>
				<tr>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("GOOGLE AUTHENTICATOR", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
						
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
							<div >
								<div >
									<div style="width: 80px; float:left;">
										<img src="<?php echo plugins_url( 'includes/images/authmethods/GoogleAuthenticator.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
									</div>
									<div style="width:190px; padding:20px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>">Google Authenticator</label></b><br>
										<p style="padding:5px; padding-left:0px;"><?php echo mo2f_lt('Enter the soft token from the account in your Google Authenticator App to login.');?></p>
									</div>
								</div>
							</div>
							
							<?php  if($selectedMethod!='GOOGLE AUTHENTICATOR')
												$flag=1;
									else
												$flag=0;
							$google_authentication_status = $dbQueries->get_user_detail( 'mo2f_GoogleAuthenticator_config_status',$current_user->ID);
							// print_r($google_authentication_status);
							if($google_authentication_status){ ?>
							<div class="configuredSmart"  <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> id="GOOGLE_AUTHENTICATOR" title="<?php echo mo2f_lt('supported in smartphone');?>">
							
								<a href="#reconfigure" style="color:white;" data-method="GOOGLE AUTHENTICATOR" ><?php echo mo2f_lt('Reconfigure');?></a> <?php if($flag){?>| <a name="mo2f_selected_2factor_method" class="mo2f_configure_set_2_factor"  data-method="GOOGLE AUTHENTICATOR" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> ><?php echo mo2f_lt('Set as 2-factor' );?></a>
								<?php }?>
							</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="<?php echo mo2f_lt('Supported in Smartphones only.');?>"><a name="mo2f_selected_2factor_method"  data-method="GOOGLE AUTHENTICATOR" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>><?php echo mo2f_lt('Configure');?></a></div>
							<?php } ?>
						</div>
					</td>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("AUTHY 2-FACTOR AUTHENTICATION", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
						
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
							<div >
								<div >
									<div style="width: 80px; float:left;">
										<img src="<?php echo plugins_url( 'includes/images/authmethods/AuthyAuthenticator.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
									</div>
									<div style="width:190px; padding:20px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>">Authy Authenticator</label></b><br>
										<p style="padding:5px; padding-left:0px;"><?php echo mo2f_lt('Enter the soft token from the account in your Authy Authenticator App to login.');?></p>
									</div>
								</div>
							</div>
						
							<?php 
							 if($selectedMethod!='AUTHY 2-FACTOR AUTHENTICATION')
												$flag=1;
									else
												$flag=0;
							
								$authy_authentication_status = $dbQueries->get_user_detail( 'mo2f_AuthyAuthenticator_config_status',$current_user->ID);
							// print_r($authy_authentication_status);
							if($authy_authentication_status){ ?>
							<div class="configuredSmart"  id="GOOGLE_AUTHENTICATOR" <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> title="<?php echo mo2f_lt('supported in smartphone');?>">
								<a href="#reconfigure" style="color:white;" data-method="AUTHY 2-FACTOR AUTHENTICATION" ><?php echo mo2f_lt('Reconfigure');?></a> 
								<?php if($flag){?>
								| <a name="mo2f_selected_2factor_method" class="mo2f_configure_set_2_factor"  data-method="AUTHY 2-FACTOR AUTHENTICATION" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> ><?php echo mo2f_lt('Set as 2-factor' );?></a>
								<?php }?>
							</div>
							<?php } else { ?>
								<div class="notConfiguredSmart" title="<?php echo mo2f_lt('Supported in Smartphones only.');?>"><a name="mo2f_selected_2factor_method"  data-method="AUTHY 2-FACTOR AUTHENTICATION" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>><?php echo mo2f_lt('Configure');?></a></div>
							<?php } ?>
						</div>
					</td>
					<td class="<?php if( !current_user_can('manage_options') && !(in_array("KBA", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; }?>">
						
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
							<div >
								<div >
									<div style="width: 80px; float:left;">
										<img src="<?php echo plugins_url( 'includes/images/authmethods/SecurityQuestions.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
									</div>
									<div style="width:190px; padding:20px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>"><?php echo mo2f_lt('Security Questions');?></label></b><br>
										<p style="padding:5px; padding-left:0px; padding-bottom:23px;"><?php echo mo2f_lt('Answer the three security questions you had set, to login.');?></p>
									</div>
								</div>
							</div>
							
							<?php if($selectedMethod!='Security Questions')
												$flag=1;
									else
												$flag=0;
											
							$kba_registration_status = $dbQueries->get_user_detail( 'mo2f_SecurityQuestions_config_status',$current_user->ID);
							if($kba_registration_status) { ?>
									<div class="configuredLaptop" id="KBA" <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones');?>">
										<a href="#reconfigure" style="color:white;" data-method="KBA" ><?php echo mo2f_lt('Reconfigure');?></a>
										<?php if($flag){?>
										| <a name="mo2f_selected_2factor_method" class="mo2f_configure_set_2_factor"  data-method="KBA" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> ><?php echo mo2f_lt('Set as 2-factor' );?></a>
								<?php }?>
									</div>
							<?php } else { ?>
								<div class="notConfiguredLaptop" style="padding:10px !important;"title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>"><a name="mo2f_selected_2factor_method"  data-method="KBA" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>><?php echo mo2f_lt('Configure');?></a></div>
							<?php } ?>
							
						</div>
					</td>
				</tr>
				<tr>
					<td class="<?php if(!current_user_can('manage_options') && !(in_array("SMS AND EMAIL", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
						    <div >
								<div >
									<div style="width: 80px; float:left;">
										<img src="<?php echo plugins_url( 'includes/images/authmethods/OTPOverSMSandEmail.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
									</div>
									<div style="width:190px; padding:20px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>">OTP Over SMS and Email</label></b><br>
										<p style="padding:5px; padding-left:0px;"><?php echo mo2f_lt('You will receive a one time passcode via SMS on your phone and your email.');?></p>
									</div>
								</div>
							</div>
						
							
										<?php if($selectedMethod!='OTP Over SMS And Email')
												$flag=1;
											else
												$flag=0;
											
							 if($otp_registration_status){ ?>
							<div class="configuredBasic" id="SMS_AND_EMAIL" <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones');?>">
										<a href="#reconfigure" style="color:white;" data-method="SMS AND EMAIL" ><?php echo mo2f_lt('Reconfigure');?></a> 
										<?php if($flag){?>
										| <a name="mo2f_selected_2factor_method" class="mo2f_configure_set_2_factor"  data-method="SMS AND EMAIL" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> ><?php echo mo2f_lt('Set as 2-factor' );?></a>
								<?php }?>
									</div>
							<?php } else { ?>
								<div class="notConfiguredLaptop" style="padding:10px !important;"title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.','miniorange-2-factor-authentication');?>"><a name="mo2f_selected_2factor_method"  data-method="SMS AND EMAIL" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>><?php echo mo2f_lt('Configure');?></a></div>
							<?php } ?>
								
						</div>
					</td >
					 <td class="<?php if(!current_user_can('manage_options') && !(in_array("OTP_OVER_EMAIL", $opt))  ){ echo "mo2f_td_hide"; }else { echo "mo2f_td_show"; } ?>" >
						
						<div class="mo2f_thumbnail" style="height:190px;border-color:#ddd;">
						    <div >
								<div >
									<div style="width: 80px; float:left;">
										<img src="<?php echo plugins_url( 'includes/images/authmethods/OTPOverEmail.png', __FILE__ ); ?>" class="mo2f_auth_methods_thumbnail" />
									</div>
									<div style="width:190px; padding:20px;font-size:14px;overflow: hidden;"><b><label title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>">OTP Over Email</label></b><br>
										<p style="padding:5px; padding-left:0px;padding-bottom:23px;"><?php echo __('You will receive a one time passcode via Email.','miniorange-2-factor-authentication');?></p>
									</div>
								</div>
							</div>
						<?php 
						 if($selectedMethod!='OTP OVER EMAIL')
												$flag=1;
									else
												$flag=0;
						if($email_otp_registration_status && mo2f_is_customer_registered() ){ ?>
							<div class="configuredBasic" id="OTP_OVER_EMAIL" <?php if(!$flag){?> style="background-color:#48b74b"<?php }?> title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones');?>">
								<?php 
								if($flag){?>
									
									<a name="mo2f_selected_2factor_method" class="mo2f_configure_set_2_factor"  data-method="OTP_OVER_EMAIL" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?> ><?php echo mo2f_lt('Set as 2-factor' );?></a>
											<?php } ?>
									</div>
							<?php } else { ?>
								<div class="notConfiguredLaptop" style="padding:19px !important;"title="<?php echo mo2f_lt('Supported in Desktops, Laptops, Smartphones.');?>"><a name="mo2f_selected_2factor_method"  data-method="OTP_OVER_EMAIL" <?php if(mo2f_is_customer_registered() || $user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR' ){ 
											} else{ echo 'disabled'; } ?>></a></div>
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
			<form name="f" method="post" action="" id="mo2f_2factor_test_authentication_method_form">
                    <input type="hidden" name="option" value="mo_2factor_test_authentication_method"/>
                    <input type="hidden" name="mo2f_configured_2FA_method_test" id="mo2f_configured_2FA_method_test"/>
                </form>
	     <?php
		 $mobile_registration_status = $dbQueries->get_user_detail( 'mo2f_mobile_registration_status',$current_user->ID);
		 $email_verification_status = $dbQueries->get_user_detail( 'mo2f_email_verification_status',$current_user->ID);
		 $google_authentication_status = $dbQueries->get_user_detail('mo2f_GoogleAuthenticator_config_status',$current_user->ID);
		 $kba_registration_status = $dbQueries->get_user_detail( 'mo2f_SecurityQuestions_config_status',$current_user->ID);
		 $email_otp_registration_status=$dbQueries->get_user_detail( 'mo2f_email_otp_registration_status',$current_user->ID);
		 // $is_customer_registered=mo2f_is_customer_registered();
		  $is_customer_registered=$dbQueries->get_user_detail(  'mo_2factor_user_registration_with_miniorange',$current_user->ID ) == 'SUCCESS' ? true : false;
		 // var_dump($mobile_registration_status, $email_verification_status, $google_authentication_status, $kba_registration_status, $email_otp_registration_status);
		 ?>
		<script>
			  // function show_free_plan_auth_methods() {
                // jQuery("#mo2f_free_plan_auth_methods").slideToggle(1000);
                // jQuery("#mo2f_standard_plan_auth_methods").hide();
                // jQuery("#mo2f_premium_plan_auth_methods").hide();
            // }
			// alert("hrere");
			jQuery('a[href="#mo2f_kba_config"]').click(function() {
				jQuery('#mo2f_2factor_configure_kba_backup_form').submit();
			});
			<?php if($is_customer_registered){ ?>
			jQuery('a[name="mo2f_selected_2factor_method"]').click(function() {
				var selectedMethod = jQuery(this).data("method");
				// alert(selectedMethod);
				<?php if($mobile_registration_status) { ?>
				    if(selectedMethod == 'MOBILE AUTHENTICATION' || selectedMethod == 'SOFT TOKEN' || selectedMethod == 'PUSH NOTIFICATIONS' ){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					}
				<?php } else{ ?>
					if(selectedMethod == 'MOBILE AUTHENTICATION' || selectedMethod == 'SOFT TOKEN' || selectedMethod == 'PUSH NOTIFICATIONS'  ){
						jQuery('#mo2f_reconfigure_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_reconfigure_form').submit();
					}
				<?php } if( $email_verification_status) { ?>
					if(selectedMethod == 'OUT OF BAND EMAIL'  ){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					 }
				<?php } else{ ?>
					if(selectedMethod == 'OUT OF BAND EMAIL' ){
						jQuery('#mo2f_reconfigure_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_reconfigure_form').submit();
					 }
					 
				<?php } if($otp_registration_status) { ?>
					 if(selectedMethod == 'SMS' || selectedMethod == 'PHONE VERIFICATION' || selectedMethod == 'SMS AND EMAIL'){
						 // alert("1161");alert(selectedMethod);
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					 }
					
				<?php } else{ ?>
					if(selectedMethod == 'SMS' || selectedMethod == 'PHONE VERIFICATION' || selectedMethod == 'SMS AND EMAIL'){
						
						// alert("1168");alert(selectedMethod);
						jQuery('#mo2f_reconfigure_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_reconfigure_form').submit();
						// jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						// jQuery('#mo2f_2factor_form').submit();
					}
				<?php } if($email_otp_registration_status) { ?>
					 if(selectedMethod == 'OTP_OVER_EMAIL'){
					 // alert("1306");
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					 }					
				<?php } else{ ?>
					if(selectedMethod == 'OTP_OVER_EMAIL'){
						 // alert("1312");
						jQuery('#mo2f_reconfigure_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_reconfigure_form').submit();
					}
					
				<?php } if($google_authentication_status) { ?>
					  if(selectedMethod == 'GOOGLE AUTHENTICATOR' ){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					  }
				<?php } else{ ?>
						if(selectedMethod == 'GOOGLE AUTHENTICATOR' ){
							jQuery('#mo2f_reconfigure_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_reconfigure_form').submit();
						}
				<?php } if($authy_authentication_status) { ?>
					  if(selectedMethod == 'AUTHY 2-FACTOR AUTHENTICATION' ){
						  // alert("1222");
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					  }
				<?php } else{ ?>
						if(selectedMethod == 'AUTHY 2-FACTOR AUTHENTICATION' ){
							// alert("here");
							jQuery('#mo2f_reconfigure_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_reconfigure_form').submit();
						}
				<?php } if($kba_registration_status) { ?>
					  if(selectedMethod == 'KBA' ){
						jQuery('#mo2f_selected_2factor_method').val(selectedMethod);
						jQuery('#mo2f_2factor_save_form').submit();
					  }
				<?php } else{ ?>
						if(selectedMethod == 'KBA' ){
							// alert("wefnowen");
							jQuery('#mo2f_reconfigure_2factor_method').val(selectedMethod);
							jQuery('#mo2f_2factor_reconfigure_form').submit();
						}
				<?php }?>
				
					
			});
			<?php }?>
			
			function testAuthenticationMethod(authMethod) {
				// alert(authMethod);
				
                jQuery('#mo2f_configured_2FA_method_test').val(authMethod);
                jQuery('#loading_image').show();

                jQuery('#mo2f_2factor_test_authentication_method_form').submit();
            }
			
			jQuery('a[href="#reconfigure"]').click(function() {
				var reconfigureMethod = jQuery(this).data("method");
				// alert(reconfigureMethod);
				jQuery('#mo2f_reconfigure_2factor_method').val(reconfigureMethod);
				jQuery('#mo2f_2factor_reconfigure_form').submit();
			});
			jQuery('a[href="#test"]').click(function() {
				var currentMethod = jQuery(this).data("method");
				
			// alert("12114");alert(currentMethod);
				if(currentMethod == 'MOBILE AUTHENTICATION'){
					jQuery('#mo2f_2factor_test_mobile_form').submit();
				}else if(currentMethod == 'PUSH NOTIFICATIONS'){
					jQuery('#mo2f_2factor_test_push_form').submit();
				}else if(currentMethod == 'SOFT TOKEN'){
					jQuery('#mo2f_2factor_test_softtoken_form').submit();
				}else if(currentMethod == 'SMS' || currentMethod == 'PHONE VERIFICATION' || currentMethod == 'SMS AND EMAIL' || currentMethod == 'OTP_OVER_EMAIL'){
					//alert(currentMethod);
					jQuery('#mo2f_test_2factor_method').val(currentMethod);
					jQuery('#mo2f_2factor_test_smsotp_form').submit();
				}else if(currentMethod == 'Google Authenticator' ||currentMethod == 'GOOGLE AUTHENTICATOR'){
					jQuery('#mo2f_2factor_test_google_auth_form').submit();
				}else if(currentMethod == 'AUTHY 2-FACTOR AUTHENTICATION'){
					jQuery('#mo2f_2factor_test_authy_app_form').submit();
				}else if(currentMethod == 'OUT OF BAND EMAIL'){
					jQuery('#mo2f_2factor_test_out_of_band_email_form').submit();
				}else if(currentMethod == 'KBA' ){
					jQuery('#mo2f_2factor_test_kba_form').submit();
				}
			});
			<?php if($user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){ ?>
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
		
	$is_flow_driven_setup = ! ( get_user_meta( $current_user->ID, 'current_modal', true ) ) ? 0 : 1;
		?>
		<table>
			<tr>
				<td style="vertical-align:top;width:26%;padding-right:15px">
				<?php if ( ! $is_flow_driven_setup ) { ?>
					<h3><?php echo mo2f_lt('Step-1: Configure with Authy');?></h3><h3><?php echo mo2f_lt('2-Factor Authentication App.');?></h3><hr />
				<?php } ?>	
					<form name="f" method="post" id="mo2f_app_type_ga_form" action="" >
						<br /><input type="submit" name="mo2f_authy_configure" class="button button-primary button-large" style="width:45%;" value="<?php echo __('Next','miniorange-2-factor-authentication');?> >> &nbsp;&nbsp;" /><br /><br />
						<input type="hidden" name="option" value="mo2f_configure_authy_app" />
					</form>
				
					<form name="f" method="post" action="" id="mo2f_cancel_form">
						<input type="hidden" name="option" value="mo2f_cancel_configuration" />
						<?php if ( ! $is_flow_driven_setup ) { ?>
						<input type="submit" name="back" id="back_btn" class="button button-primary button-large" style="width:45%;" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" />
					   <?php } ?>
					</form>
				</td>
				<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
				<td style="width:46%;padding-right:15px;vertical-align:top;">
					<h3><?php echo __('Step-2: Set up Authy 2-Factor Authentication App','miniorange-2-factor-authentication');?></h3><h3>&nbsp;	</h3><hr>
					<div style="<?php echo isset($_SESSION['mo2f_authy_keys']) ? 'display:block' : 'display:none'; ?>">
					<h4><?php echo __('Install the Authy 2-Factor Authentication App.','miniorange-2-factor-authentication');?></h4>
				
					<h4><?php echo __('Now open and configure Authy 2-Factor Authentication App.','miniorange-2-factor-authentication');?></h4>
					<h4> <?php echo __('Tap on Add Account and then tap on SCAN QR CODE in your App and scan the qr code.','miniorange-2-factor-authentication');?></h4>
					<center><br><div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div></center>
					<div><a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_a" aria-expanded="false" ><b><?php echo __('Can\'t scan the QR Code?','miniorange-2-factor-authentication');?> </b></a></div>
					<div class="mo2f_collapse" id="mo2f_scanbarcode_a">
						<ol>
							<li><?php echo __('In Authy 2-Factor Authentication App, tap on ENTER KEY MANUALLY.','miniorange-2-factor-authentication');?>"</li>
							<li><?php echo __('In "Adding New Account" type your secret key:','miniorange-2-factor-authentication');?></li>
								<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
									<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
									<?php echo $authy_secret; ?>
									</div>
									<div style="font-size: 80%;color: #666666;">
									<?php echo __('Spaces don\'t matter.','miniorange-2-factor-authentication');?>
									</div>
								</div>
							<li><?php echo __('Tap OK.','miniorange-2-factor-authentication');?></li>
						</ol>
					</div>
					</div>
				</td>
				<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
				<td style="vertical-align:top;width:30%">
					<h3><?php echo __('Step-3: Verify and Save','miniorange-2-factor-authentication');?></h3><h3>&nbsp;</h3><hr>
					<div style="<?php echo isset($_SESSION['mo2f_authy_keys']) ? 'display:block' : 'display:none'; ?>">
					<h4><?php echo __('Once you have scanned the qr code, enter the verification code generated by the Authenticator app','miniorange-2-factor-authentication');?></h4><br/>
					<form name="f" method="post" action="" >
						<span><b><?php echo __('Code:','miniorange-2-factor-authentication');?> </b>
						<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" required="true" type="text" name="authy_token" placeholder="<?php echo __('Enter OTP','miniorange-2-factor-authentication');?>" style="width:95%;"/></span><br /><br/>

						<input type="hidden" name="option" value="mo2f_validate_authy_auth" />
						<input type="submit" name="validate" id="validate" class="button button-primary button-large" style="margin-left:12%;" value="<?php echo __('Verify and Save','miniorange-2-factor-authentication');?>" />
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
	$is_flow_driven_setup = ! ( get_user_meta( $current_user->ID, 'current_modal', true ) ) ? 0 : 1;
	// update_site_option()
	if(get_site_option('mo2f_enable_gauth_name')){
				$ga_account_name = get_user_meta($current_user->ID,'mo2f_GA_account_name',true) ? get_user_meta($current_user->ID,'mo2f_GA_account_name',true) : get_site_option('mo2f_GA_account_name');
				// update_site_option('mo2f_GA_account_name', $ga_account_name);
				// user_meta
				// update_user_meta($current_user->ID,'mo2f_GA_account_name', $google_account_name);
	}else{
		$ga_account_name=get_site_option('mo2f_GA_account_name');
	}
				// update_site_option('mo2f_GA_account_name', 'miniOrangeauth');
	// var_dump(get_site_option('mo2f_enable_gauth_name'));exit;
	// $ga_account_name = get_option('mo2f_GA_account_name') ? get_option('mo2f_GA_account_name') : '';
	?>
		<table>
			<tr>
				<td style="vertical-align:top;width:22%;padding-right:15px">
					<h4><?php echo __('Step-1: Select phone Type','miniorange-2-factor-authentication');?></h4><hr />
					<form name="f" method="post" id="mo2f_app_type_ga_form" action="" >
						<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" required="true"
							   type="text" name="google_account_name" placeholder="<?php echo mo2f_lt('Account Name');?>" style="width:95%;"
							   value="<?php echo $ga_account_name; ?>" <?php if(get_site_option('mo2f_enable_gauth_name')){}else{ echo 'disabled';}?>/>
							   <br><br>
						<input type="radio" name="mo2f_app_type_radio" value="android" <?php checked( $mo2f_google_auth['ga_phone'] == 'android' ); ?> /> <b><?php echo __('Android','miniorange-2-factor-authentication');?></b><br /><br />
						<input type="radio" name="mo2f_app_type_radio" value="iphone" <?php checked( $mo2f_google_auth['ga_phone'] == 'iphone' ); ?> /> <b><?php echo __('iPhone','miniorange-2-factor-authentication');?></b><br /><br />
						<input type="radio" name="mo2f_app_type_radio" value="blackberry" <?php checked( $mo2f_google_auth['ga_phone'] == 'blackberry' ); ?> /> <b><?php echo __('BlackBerry / Windows','miniorange-2-factor-authentication');?></b><br /><br />
						<input type="hidden" name="option" value="mo2f_configure_google_auth_phone_type" />
					</form>
					<form name="f" method="post" action="" id="mo2f_cancel_form">
						<input type="hidden" name="option" value="mo2f_cancel_configuration" />
						<?php if ( ! $is_flow_driven_setup ) { ?>
						<input type="submit" name="back" id="back_btn" class="button button-primary button-large" style="width:45%;" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" />
						<?php } ?>
					</form>
				</td>
				<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
				<td style="width:46%;padding-right:15px;vertical-align:top;">
					<h4><?php echo __('Step-2: Set up Google Authenticator','miniorange-2-factor-authentication');?></h4><hr>
					<div id="mo2f_android_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'android' ? 'display:block' : 'display:none'; ?>" >
					<h4><?php echo __('Install the Google Authenticator App for Android.','miniorange-2-factor-authentication');?></h4>
					<ol>
						<li><?php echo __('On your phone,Go to Google Play Store.','miniorange-2-factor-authentication');?></li>
						<li><?php echo __('Search for','miniorange-2-factor-authentication');?> <b><?php echo __('Google Authenticator.','miniorange-2-factor-authentication');?></b>
						<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank"><?php echo __('Download from the Google Play Store and install the application.','miniorange-2-factor-authentication');?></a>
						</li>
					
					</ol>
					<h4><?php echo __('Now open and configure Google Authenticator.','miniorange-2-factor-authentication');?></h4>
					<ol>
						<li><?php echo __('In Google Authenticator, touch Menu and select "Set up account".','miniorange-2-factor-authentication');?></li>
						<li><?php echo __('Select "Scan a barcode". Use your phone\'s camera to scan this barcode.','miniorange-2-factor-authentication');?></li>
					<center><br><div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div></center>
						
					</ol>
					<div><a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_a" aria-expanded="false" ><b><?php echo __('Can\'t scan the barcode?','miniorange-2-factor-authentication');?> </b></a></div>
					<div class="mo2f_collapse" id="mo2f_scanbarcode_a">
						<ol>
							<li><?php echo __('In Google Authenticator, touch Menu and select "Set up account".','miniorange-2-factor-authentication');?></li>
							<li><?php echo __('Select "Enter provided key"','miniorange-2-factor-authentication');?></li>
							<li><?php echo __('In "Enter account name" type your full email address.','miniorange-2-factor-authentication');?></li>
							<li><?php echo __('In "Enter your key" type your secret key:','miniorange-2-factor-authentication');?></li>
								<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
									<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
									<?php echo $ga_secret; ?>
									</div>
									<div style="font-size:80%;color:#666666;">
									<?php echo __('Spaces do not matter','miniorange-2-factor-authentication');?>. 
									</div>
								</div>
							<li><?php echo __('Key type: make sure "Time-based" is selected','miniorange-2-factor-authentication');?>.</li>
							<li><?php echo __('Tap Add.','miniorange-2-factor-authentication');?></li>
						</ol>
					</div>
					</div>
					
					<div id="mo2f_iphone_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'iphone' ? 'display:block' : 'display:none'; ?>" >
					<h4><?php echo __('Install the Google Authenticator app for iPhone.','miniorange-2-factor-authentication');?></h4>
					<ol>
						<li><?php echo __('On your iPhone, tap the App Store icon.','miniorange-2-factor-authentication');?></li>
						<li><?php echo __('Search for ','miniorange-2-factor-authentication');?><b><?php echo __('Google Authenticator.','miniorange-2-factor-authentication');?></b>
						<a href="http://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><?php echo __('Download from the App Store and install it','miniorange-2-factor-authentication');?></a>
						</li>
					</ol>
					<h4><?php echo __('Now open and configure Google Authenticator.','miniorange-2-factor-authentication');?></h4>
					<ol>
						<li><?php echo __('In Google Authenticator, tap "+", and then "Scan Barcode".','miniorange-2-factor-authentication');?></li>
						<li><?php echo __('Use your phone\'s camera to scan this barcode.','miniorange-2-factor-authentication');?>
							<center><br><div id="displayQrCode" ><?php echo '<img src="data:image/jpg;base64,' . $data . '" />'; ?></div></center>
						</li>
					</ol>
					<div><a  data-toggle="mo2f_collapse" href="#mo2f_scanbarcode_i" aria-expanded="false" ><b><?php echo __('Can\'t scan the barcode? ','miniorange-2-factor-authentication');?></b></a></div>
					<div class="mo2f_collapse" id="mo2f_scanbarcode_i"  >
						<ol>
							<li><?php echo __('In Google Authenticator, tap +.','miniorange-2-factor-authentication');?></li>
							<li><?php echo __('Key type: make sure "Time-based" is selected.','miniorange-2-factor-authentication');?></li>
							<li><?php echo __('In "Account" type your full email address.','miniorange-2-factor-authentication');?></li>
							<li><?php echo __('In "Key" type your secret key:','miniorange-2-factor-authentication');?></li>
								<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
									<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
									<?php echo $ga_secret; ?>
									</div>
									<div style="font-size: 80%;color: #666666;">
									<?php echo __('Spaces don\'t matter.','miniorange-2-factor-authentication');?>
									</div>
								</div>
							<li><?php echo __('Tap Add.','miniorange-2-factor-authentication');?></li>
						</ol>
					</div>
					</div>
					
					<div id="mo2f_blackberry_div" style="<?php echo $mo2f_google_auth['ga_phone'] == 'blackberry' ? 'display:block' : 'display:none'; ?>" >
					<h4><?php echo __('Install the Google Authenticator app for BlackBerry','miniorange-2-factor-authentication');?></h4>
					<ol>
						<li><?php echo __('On your phone, open a web browser.Go to ','miniorange-2-factor-authentication');?><b> m.google.com/authenticator</b>.</li>
						<li><?php echo __('Download and install the Google Authenticator application.','miniorange-2-factor-authentication');?></li>
					</ol>
					<h4><?php echo __('Now open and configure Google Authenticator.','miniorange-2-factor-authentication');?></h4>
					<ol>
						<li><?php echo __('In Google Authenticator, select Manual key entry.','miniorange-2-factor-authentication');?></li>
						<li><?php echo __('In "Enter account name" type your full email address.','miniorange-2-factor-authentication');?></li>
						<li><?php echo __('In "Enter key" type your secret key:
						','miniorange-2-factor-authentication');?></li>
							<div style="padding: 10px; background-color: #f9edbe;width: 20em;text-align: center;" >
								<div style="font-size: 14px; font-weight: bold;line-height: 1.5;" >
								<?php echo $ga_secret; ?>
								</div>
								<div style="font-size: 80%;color: #666666;">
								<?php echo __('Spaces don\'t matter.','miniorange-2-factor-authentication');?>
								</div>
							</div>
						<li><?php echo __('Choose Time-based type of key.','miniorange-2-factor-authentication');?></li>
						<li><?php echo __('Tap Save.','miniorange-2-factor-authentication');?></li>
					</ol>
					</div>
					
				</td>
				<td style="border-left: 1px solid #EBECEC; padding: 5px;"></td>
				<td style="vertical-align:top;width:30%">
					<h4><?php echo __('Step-3: Verify and Save','miniorange-2-factor-authentication');?></h4><hr>
					<div style="<?php echo isset($_SESSION['mo2f_google_auth']) ? 'display:block' : 'display:none'; ?>">
					<div><?php echo __('Once you have scanned the barcode, enter the 6-digit verification code generated by the Authenticator app','miniorange-2-factor-authentication');?></div><br/>
					<form name="f" method="post" action="" >
						<span><b><?php echo __('Code:','miniorange-2-factor-authentication');?> </b>
						<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" required="true" type="text" name="google_token" placeholder="<?php echo __('Enter OTP','miniorange-2-factor-authentication');?>" style="width:95%;"/></span><br /><br/>
						<input type="hidden" name="option" value="mo2f_validate_google_auth" />
						<input type="submit" name="validate" id="validate" class="button button-primary button-large" style="margin-left:12%;" value="<?php echo __('Verify and Save','miniorange-2-factor-authentication');?>" />
					</form>
					</div>
				</td>
			</tr><br>
			<a  data-toggle="mo2f_collapse" href="#mo2f_question" aria-expanded="false" ><b><?php echo __('How miniOrange Authenticator is better than Google Authenticator ?','miniorange-2-factor-authentication');?></b></a>
			<div id="mo2f_question" class="mo2f_collapse"><p>
					 <?php echo __('miniOrange Authenticator manages the Google Authenticator keys better and easier by providing these extra features:','miniorange-2-factor-authentication');?><br>
1. miniOrange <b><?php echo __('encrypts all data','miniorange-2-factor-authentication');?></b>, <?php echo __('whereas Google Authenticator stores data in plain text.','miniorange-2-factor-authentication');?><br>
2. <?php echo __('miniOrange Authenticator app has in-build','miniorange-2-factor-authentication');?> <b><?php echo __('Pin-Protection','miniorange-2-factor-authentication');?></b> <?php echo __('so you can protect your google authenticator keys or whole app using pin whereas Google Authenticator is not protected at all.','miniorange-2-factor-authentication');?><br>
3. <?php echo __('No need to type in the code at all. Contact us to get','miniorange-2-factor-authentication');?> <b><?php echo __('miniOrange Autofill Plugin','miniorange-2-factor-authentication');?></b>, <?php echo __('it can seamlessly connect your computer to your phone. Code will get auto filled and saved.','miniorange-2-factor-authentication');?></p>
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
	global $dbQueries;
	$selected_2factor_method = $dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$current_user->ID);
	$user_phone = $dbQueries->get_user_detail( 'mo2f_user_phone',$current_user->ID);
			if($selected_2factor_method == 'SMS AND EMAIL') {
			?>
			<h3><?php echo __('Verify Your Phone and Email','miniorange-2-factor-authentication');?></h3><hr>
			<?php }else if($selected_2factor_method == 'OTP_OVER_EMAIL') { ?>
			<h3><?php echo __('Verify Your Email','miniorange-2-factor-authentication');?></h3><hr>
			<?php }else {?>
			<h3><?php echo __('Verify Your Phone','miniorange-2-factor-authentication');?></h3><hr>
			<?php }?>
					<form name="f" method="post" action="" id="mo2f_verifyphone_form">
						<input type="hidden" name="option" value="mo2f_verify_phone" />
						
						<div style="display:inline;">
						<?php 
						//$selectedFactor=get_user_meta($current_user->ID, 'mo2f_selected_2factor_method',true);
						if($selected_2factor_method == 'SMS AND EMAIL' || $selected_2factor_method == 'SMS') {
						?>	
						<input class="mo2f_table_textbox" style="width:200px;" type="text" name="verify_phone" id="phone" 
						    value="<?php if( isset($_SESSION['mo2f_phone'])){ echo $_SESSION['mo2f_phone'];} else echo $user_phone; ?>"  pattern="[\+]?[0-9]{1,4}\s?[0-9]{7,12}" title="<?php echo mo2f_lt('Enter phone number without any space or dashes');?>" /><br>
						<?php } ?>
						<?php if($selected_2factor_method  == 'SMS AND EMAIL' ||$selected_2factor_method  == 'OTP_OVER_EMAIL') {
						?>	
							<input class="mo2f_table_textbox" style="width:200px;" type="text" name="verify_email" id="email" 
						    value="<?php if( isset($_SESSION['mo2f_email'])){ echo $_SESSION['mo2f_email'];} else echo $dbQueries->get_user_detail( 'mo2f_user_email',  $current_user->ID); ?>" disabled /><br><br>
						<?php } ?>
						<input type="submit" name="verify" id="verify" class="button button-primary button-large" value="<?php echo mo2f_lt('Verify');?>" />
						</div>
					</form>	
				<form name="f" method="post" action="" id="mo2f_validateotp_form">
					<input type="hidden" name="option" value="mo2f_validate_otp" />
						<p><?php echo __('Enter One Time Passcode','miniorange-2-factor-authentication');?></p>
								<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token" placeholder="<?php echo __('Enter OTP','miniorange-2-factor-authentication');?>" style="width:95%;"/>
								<?php if ($selected_2factor_method == 'PHONE VERIFICATION'){ ?>
									<a href="#resendsmslink"><?php echo __('Call Again ?','miniorange-2-factor-authentication');?></a>
								<?php } else {?>
									<a href="#resendsmslink"><?php echo __('Resend OTP ?','miniorange-2-factor-authentication');?></a>
								<?php } ?><br><br>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" />
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="<?php echo __('Validate OTP','miniorange-2-factor-authentication');?>" />
				</form><br>
				<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
				</form>
		<script>
			jQuery("#phone").intlTelInput({
				// initialCountry:
			});
			jQuery('#back_btn').click(function() {	
					jQuery('#mo2f_cancel_form').submit();
			});
			jQuery('a[href="#resendsmslink"]').click(function(e) {
				jQuery('#mo2f_verifyphone_form').submit();
			});

		</script>
	<?php 
	}
	
	
	
	function test_mobile_authentication() {
		?>
		
			<h3><?php echo __('Test QR Code Authentication','miniorange-2-factor-authentication');?></h3><hr>
			<p><?php echo __('Open your miniOrange','miniorange-2-factor-authentication');?> <b><?php echo __('Authenticator App','miniorange-2-factor-authentication');?></b> <?php echo __('and click on','miniorange-2-factor-authentication');?> <b><?php echo __('SCAN QR Code','miniorange-2-factor-authentication');?></b> <?php echo __('to scan the QR code. Your phone should have internet connectivity to scan QR code.','miniorange-2-factor-authentication');?></p>
			
			<div style="color:red;"><b><?php echo __('I am not able to scan the QR code,','miniorange-2-factor-authentication');?> <a  data-toggle="mo2f_collapse" href="#mo2f_testscanqrcode" aria-expanded="false" ><?php echo __('click here','miniorange-2-factor-authentication');?> </a></b></div>
			<div class="mo2f_collapse" id="mo2f_testscanqrcode">
				<br /><?php echo __('Follow these instructions below and try again.','miniorange-2-factor-authentication');?>
				<ol>
					<li><?php echo __('Make sure your desktop screen has enough brightness.','miniorange-2-factor-authentication');?></li>
					<li><?php echo __('Open your app and click on Green button (your registered email is displayed on the button) to scan QR Code.','miniorange-2-factor-authentication');?></li>
					<li><?php echo __('If you get cross mark on QR Code then click on \'Back\' button and again click on \'Test\' link.','miniorange-2-factor-authentication');?></li>
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
				<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" />
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
		<h3><?php echo __('Test Soft Token','miniorange-2-factor-authentication');?></h3><hr>
		<p><?php echo __('Open your','miniorange-2-factor-authentication');?> <b><?php echo __('miniOrange Authenticator App','miniorange-2-factor-authentication');?></b> <?php echo __('and click on','miniorange-2-factor-authentication');?> <b><?php echo __('Soft Token Tab','miniorange-2-factor-authentication');?></b>. <?php echo __('Enter the','miniorange-2-factor-authentication');?> <b><?php echo __('one time passcode','miniorange-2-factor-authentication');?></b> <?php echo __('shown in App in the textbox below.','miniorange-2-factor-authentication');?></p>
			<form name="f" method="post" action="" id="mo2f_test_token_form">
					<input type="hidden" name="option" value="mo2f_validate_soft_token" />
					
								<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP" style="width:95%;"/>
								<br><br>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" />
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="<?php echo __('Validate OTP','miniorange-2-factor-authentication');?>" />
					
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
			<h3><?php echo __('Test Google Authenticator','miniorange-2-factor-authentication');?></h3><hr>
			<p><b><?php echo __('Enter verification code','miniorange-2-factor-authentication');?></b></p>
			<p><?php echo __('Get a verification code from "Google Authenticator" app','miniorange-2-factor-authentication');?></p>
		<?php }else{ ?>
			<h3><?php echo __('Test Authy 2-Factor Authentication','miniorange-2-factor-authentication');?></h3><hr>
			<p><b><?php echo __('Enter verification code','miniorange-2-factor-authentication');?></b></p>
			<p><?php echo __('Get a verification code from "Authy 2-Factor Authentication" app','miniorange-2-factor-authentication');?></p>
		<?php } ?>
			<form name="f" method="post" action="" >
					<input type="hidden" name="option" value="mo2f_validate_google_auth_test" />
					
								<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token" required placeholder="<?php echo __('Enter OTP','miniorange-2-factor-authentication');?>" style="width:95%;"/>
								<br><br>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" />
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="<?php echo __('Validate OTP','miniorange-2-factor-authentication');?>" />
					
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
	global $dbQueries;
	// $selected_2factor_method = $dbQueries->get_user_detail( $current_user->ID,'mo2f_configured_2FA_method');
		$selected_2_factor_method = $dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$current_user->ID);
		if ($selected_2_factor_method == 'SMS'){ ?>
			<h3><?php echo __('Test OTP Over SMS','miniorange-2-factor-authentication');?></h3><hr>
				<p><?php echo __('Enter the one time passcode sent to your registered mobile number.','miniorange-2-factor-authentication');?></p>
		<?php } else if($selected_2_factor_method == 'SMS AND EMAIL') { ?>
			<h3><?php echo __('Test OTP Over SMS And EMAIL','miniorange-2-factor-authentication');?></h3><hr>
			<p><?php echo __('Enter the one time passcode sent to your registered mobile number and email id.','miniorange-2-factor-authentication');?></p>
			<?php } else if($selected_2_factor_method == 'OTP_OVER_EMAIL') { ?>
			<h3><?php echo __('Test OTP Over EMAIL','miniorange-2-factor-authentication');?></h3><hr>
			<p><?php echo __('Enter the one time passcode sent to your registered email id.','miniorange-2-factor-authentication');?></p>
		<?php }
		else { ?>
			<h3><?php echo __('Test Phone Call Verification','miniorange-2-factor-authentication');?></h3><hr>
			<p><?php echo __('You will receive a phone call now. Enter the one time passcode here.','miniorange-2-factor-authentication');?></p>
		<?php } ?>
	
			<form name="f" method="post" action="" id="mo2f_test_token_form">
					<input type="hidden" name="option" value="mo2f_validate_otp_over_sms" />
					
								<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token" required placeholder="<?php echo __('Enter OTP','miniorange-2-factor-authentication');?>" style="width:95%;"/>
								<?php if ($selected_2_factor_method == 'PHONE VERIFICATION'){ ?>
									<a href="#resendsmslink"><?php echo __('Call Again ?','miniorange-2-factor-authentication');?></a>
								<?php } else {?>
									<a href="#resendsmslink"><?php echo mo2f_lt('Resend OTP ?');?></a>
								<?php } ?>
								<br><br>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo mo2f_lt('Back');?>" />
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="<?php echo mo2f_lt('Validate OTP');?>" />
					
		    </form>
			<form name="f" method="post" action="" id="mo2f_cancel_form">
					<input type="hidden" name="option" value="mo2f_cancel_configuration" />
			</form>
			<form name="f" method="post" action="" id="mo2f_test_smsotp_form">
				<input type="hidden" name="option" value="mo_2factor_test_otp_over_sms" />
				<input type="hidden" name="mo2f_selected_2factor_method" value="<?php echo $selected_2_factor_method; ?>" 
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
	
			<h3><?php echo __('Test Push Notification','miniorange-2-factor-authentication');?></h3><hr>
	<div >
			<br><br>
			<center>
				<h3><?php echo __('A Push Notification has been sent to your phone.','miniorange-2-factor-authentication');?> <br><?php echo __('We are waiting for your approval...','miniorange-2-factor-authentication');?></h3>
				<img src="<?php echo plugins_url( 'includes/images/ajax-loader-login.gif' , __FILE__ );?>" />
			</center>
		<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" style="margin-top:100px;margin-left:10px;"/>
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
	
			<h3><?php echo __('Test Email Verification','miniorange-2-factor-authentication');?></h3><hr>
	<div>
			<br><br>
			<center>
				<h3><?php echo __('A verification email is sent to your registered email.','miniorange-2-factor-authentication');?> <br>
				<?php echo __('We are waiting for your approval...','miniorange-2-factor-authentication');?></h3>
				<img src="<?php echo plugins_url( 'includes/images/ajax-loader-login.gif' , __FILE__ );?>" />
			</center>
			
			<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" style="margin-top:100px;margin-left:10px;"/>
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

		function test_kba_authentication($current_user){ 
			if ( ! get_user_meta( $current_user->ID, 'current_modal', true ) ) { ?>
			<h3><?php echo mo2f_lt('Test Security Questions( KBA )');?></h3><hr>
			<p><?php echo mo2f_lt('Please answer the following question.');?></p>
			<?php } ?><br>
			
			<form name="f" method="post" action="" id="mo2f_test_kba_form">
				<input type="hidden" name="option" value="mo2f_validate_kba_details" />
					
					<div id="mo2f_kba_content">
						<?php if(isset($_SESSION['mo_2_factor_kba_questions'])){
							echo Mo2fConstants::langTranslate($_SESSION['mo_2_factor_kba_questions'][0]);
						?>
						<br />
						<input class="mo2f_table_textbox" style="width:227px;" type="text" name="mo2f_answer_1" id="mo2f_answer_1" required="true" autofocus="true" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+-\s]{1,100}" title="<?php echo __('Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.','miniorange-2-factor-authentication');?>"><br /><br />
						<?php
							echo Mo2fConstants::langTranslate($_SESSION['mo_2_factor_kba_questions'][1]);
						?>
						<br />
						<input class="mo2f_table_textbox" style="width:227px;" type="text" name="mo2f_answer_2" id="mo2f_answer_2" required="true" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+-\s]{1,100}" title="<?php echo __('Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.','miniorange-2-factor-authentication');?>"><br /><br />
						<?php 
							}
						?>
					</div>
					
			<?php if ( ! get_user_meta( $current_user->ID, 'current_modal', true ) ) { ?>
					<input type="button" name="back" id="back_btn" class="button button-primary button-large" value="<?php echo mo2f_lt('Back');?>" />
					<?php } ?>
					<input type="submit" name="validate" id="validate" class="button button-primary button-large" value="<?php echo mo2f_lt('Validate Answers');?>" />
		
			<?php if ( get_user_meta( $current_user->ID, 'current_modal', true ) ) { ?>
            <br><br>
				<?php } ?>		
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
		
		function show_2_factor_pricing_page( $user ) {
	global $dbQueries;

	// $is_NC = get_option( 'mo2f_is_NC' );

	$is_customer_registered = mo2f_is_customer_registered();

	$mo2f_feature_set = array(
		"Authentication Methods",
		"No. of Users",
		"Language Translation Support",
		"Login with Username + password + 2FA",
		"Login with Username + 2FA (skip password)",
		"Backup Methods",
		"Multi-Site Support",
		"User role based redirection after Login",
		"Add custom Security Questions (KBA)",
		"Customize account name in Google Authenticator app",
		"Enable 2FA for specific User Roles",
		"Enable 2FA for specific Users",
		"Choose specific authentication methods for Users",
		"Prompt for 2FA Registration for Users at login",
		"One Time Email Verification for Users during 2FA Registration",
		"Enable Security Questions as backup for Users during 2FA registration",
		"App Specific Password to login from mobile Apps",
		"Support"
	);


	$two_factor_methods = array(
		"miniOrange QR Code Authentication",
		"miniOrange Soft Token",
		"miniOrange Push Notification",
		"Google Authenticator",
		"Security Questions",
		"Authy Authenticator",
		"Email Verification",
		"OTP Over SMS",
		"OTP Over Email",
		"OTP Over SMS and Email",
		"Hardware Token"
	);

	$two_factor_methods_EC = array_slice( $two_factor_methods, 0, 7 );
	// $user_plan =  get_option( 'mo2f_is_NC' ) && !get_option( 'mo2f_is_NNC' ) ? "Unlimited" : "1";
	$mo2f_feature_set_with_plans_NC = array(
		"Authentication Methods"                                                => array(
			
			array_slice( $two_factor_methods, 0, 10 ),
			array_slice( $two_factor_methods, 0, 11 )
		),
		"No. of Users"                                                          => array(
			
			"User Based Pricing",
			"User Based Pricing"
		),
		"Language Translation Support"                                          => array(  true, true ),
		"Login with Username + password + 2FA"                                  => array(  true, true ),
		"Login with Username + 2FA (skip password)"                             => array(  true, true ),
		"Backup Methods"                                                        => array(
			
			"KBA",
			array( "KBA", "OTP Over Email", "Backup Codes" )
		),
		"Multi-Site Support"                                                    => array(  true, true ),
		"User role based redirection after Login"                               => array(  true, true ),
		"Add custom Security Questions (KBA)"                                   => array(  true, true ),
		"Customize account name in Google Authenticator app"                    => array(  true, true ),
		"Enable 2FA for specific User Roles"                                    => array(  false, true ),
		"Enable 2FA for specific Users"                                         => array(  false, true ),
		"Choose specific authentication methods for Users"                      => array(  false, true ),
		"Prompt for 2FA Registration for Users at login"                        => array(  false, true ),
		"One Time Email Verification for Users during 2FA Registration"         => array(  false, true ),
		"Enable Security Questions as backup for Users during 2FA registration" => array(  false, true ),
		"App Specific Password to login from mobile Apps"                       => array(  false, true ),
		"Support"                                                               => array(
			
			"Priority Support by Email",
			array( "Priority Support by Email", "Priority Support with GoTo meetings" )
		),

	);

	$mo2f_feature_set_with_plans_EC = array(
		"Authentication Methods"                                                => array(
			
			array_slice( $two_factor_methods, 0, 10 ),
			array_slice( $two_factor_methods, 0, 11 )
		),
		"No. of Users"                                                          => array(
			
			"User Based Pricing",
			"User Based Pricing"
		),
		"Language Translation Support"                                          => array(  true, true ),
		"Login with Username + password + 2FA"                                  => array(  true, true ),
		"Login with Username + 2FA (skip password)"                             => array(  true, true ),
		"Backup Methods"                                                        => array(
			
			"KBA",
			array( "KBA", "OTP Over Email", "Backup Codes" )
		),
		"Multi-Site Support"                                                    => array(  true, true ),
		"User role based redirection after Login"                               => array(  true, true ),
		"Add custom Security Questions (KBA)"                                   => array(  true, true ),
		"Customize account name in Google Authenticator app"                    => array(  true, true ),
		"Enable 2FA for specific User Roles"                                    => array(  false, true ),
		"Enable 2FA for specific Users"                                         => array(  false, true ),
		"Choose specific authentication methods for Users"                      => array(  false, true ),
		"Prompt for 2FA Registration for Users at login"                        => array(  false, true ),
		"One Time Email Verification for Users during 2FA Registration"         => array(  false, true ),
		"Enable Security Questions as backup for Users during 2FA registration" => array(  false, true ),
		"App Specific Password to login from mobile Apps"                       => array(  false, true ),
		"Support"                                                               => array(
			"Basic Support by Email",
			"Priority Support by Email",
			array( "Priority Support by Email", "Priority Support with GoTo meetings" )
		),

	);

	$mo2f_addons           = array(
		"RBA & Trusted Devices Management Add-on",
		"Personalization Add-on",
		"Short Codes Add-on"
	);
	$mo2f_addons_plan_name = array(
		"RBA & Trusted Devices Management Add-on" => "wp_2fa_addon_rba",
		"Personalization Add-on"                  => "wp_2fa_addon_personalization",
		"Short Codes Add-on"                      => "wp_2fa_addon_shortcode"
	);


	$mo2f_addons_with_features = array(
		"Personalization Add-on"                  => array(
			"Custom UI of 2FA popups",
			"Custom Email and SMS Templates",
			"Customize 'powered by' Logo",
			"Customize Plugin Icon",
			"Customize Plugin Name",
			"Add Recaptcha on Login Page"
		),
		"RBA & Trusted Devices Management Add-on" => array(
			"Remember Device",
			"Set Device Limit for the users to login",
			"IP Restriction: Limit users to login from specific IPs"
		),
		"Short Codes Add-on"                      => array(
			"Option to turn on/off 2-factor by user",
			"Option to configure the Google Authenticator and Security Questions by user",
			"Option to 'Enable Remember Device' from a custom login form",
			"On-Demand ShortCodes for specific fuctionalities ( like for enabling 2FA for specific pages)"
		)
	);
	?>
    <div class="mo2f_licensing_plans">

		<?php echo mo2f_check_if_registered_with_miniorange( $user ) . '<br>'; ?>

        <table class="table mo_table-bordered mo_table-striped">
            <thead>
            <tr class="mo2f_licensing_plans_tr">
                <th width="25%">
                    <h3><?php echo mo2f_lt('Features \ Plans');?></h3></th>
                <th class="text-center" width="25%"><h3><?php echo mo2f_lt('Standard');?></h3>

                    <p class="mo2f_licensing_plans_plan_desc">Intermediate 2FA for Medium Scale Web Businesses with
                        basic support</p><span>
						<?php echo mo2f_yearly_standard_pricing(); ?>

						<?php echo mo2f_sms_cost(); ?>

                        <h4 class="mo2f_pricing_sub_header" style="padding-bottom:8px !important;"><button
                                    class="button button-primary button-large"
                                    onclick="mo2f_upgradeform('wp_2fa_basic_plan')" <?php echo $is_customer_registered ? "" : " disabled " ?>>Upgrade</button></h4>
                <br>
				</span></h3>
                </th>

                <th class="text-center" width="25%"><h3><?php echo mo2f_lt('Premium');?></h3>

                    <p class="mo2f_licensing_plans_plan_desc" style="margin:16px 0 18px 0">Advanced and Intuitive
                        2FA for Large Scale Web businesses with enterprise-grade support</p><span>
                    <?php echo mo2f_yearly_premium_pricing(); ?>
						<?php echo mo2f_sms_cost(); ?>
                        <h4 class="mo2f_pricing_sub_header" style="padding-bottom:8px !important;"><button
                                    class="button button-primary button-large"
                                    onclick="mo2f_upgradeform('wp_2fa_premium_plan')" <?php echo $is_customer_registered ? "" : " disabled " ?>>Upgrade</button></h4>
                <br>
				</span></h3>
                </th>

            </tr>
            </thead>
            <tbody class="mo_align-center mo-fa-icon">
			<?php for ( $i = 0; $i < count( $mo2f_feature_set ); $i ++ ) { ?>
                <tr>
                    <td><?php
						$feature_set = $mo2f_feature_set[ $i ];

						echo $feature_set;
						?></td>


					<?php 
						$f_feature_set_with_plan = $mo2f_feature_set_with_plans_NC[ $feature_set ];
				
					
					?>
                    <td><?php
						if ( is_array( $f_feature_set_with_plan[0] ) ) {
							echo mo2f_create_li( $f_feature_set_with_plan[0] );
						} else {
							if ( gettype( $f_feature_set_with_plan[0] ) == "boolean" ) {
								echo mo2f_get_binary_equivalent( $f_feature_set_with_plan[0] );
							} else {
								echo $f_feature_set_with_plan[0];
							}
						} ?>
                    </td>
                    <td><?php
						if ( is_array( $f_feature_set_with_plan[1] ) ) {
							echo mo2f_create_li( $f_feature_set_with_plan[1] );
						} else {
							if ( gettype( $f_feature_set_with_plan[1] ) == "boolean" ) {
								echo mo2f_get_binary_equivalent( $f_feature_set_with_plan[1] );
							} else {
								echo $f_feature_set_with_plan[1];
							}
						} ?>
                    </td>
                  
                </tr>
			<?php } ?>

            <tr>
                <td><b>Add-Ons</b></td>
			    <td><b>Purchase Separately</b></td>
                <td><b>Included</b></td>
            </tr>
			<?php for ( $i = 0; $i < count( $mo2f_addons ); $i ++ ) { ?>
                <tr>
                    <td><?php echo $mo2f_addons[ $i ]; ?> <?php for ( $j = 0; $j < $i + 1; $j ++ ) { ?>*<?php } ?>
                    </td>
					
                        <td>
                            <button class="button button-primary button-small" style="cursor:pointer"
                                    onclick="mo2f_upgradeform('<?php echo $mo2f_addons_plan_name[ $mo2f_addons[ $i ] ]; ?>')" <?php echo $is_customer_registered ? "" : " disabled " ?> >
                                Purchase
                            
                        </td>
					
                        <td><i class='fa fa-check'></i></td>
                </tr>
			<?php } ?>

            </tbody>
        </table>
        <br>
        <div style="padding:10px;">
			<?php for ( $i = 0; $i < count( $mo2f_addons ); $i ++ ) {
				$f_feature_set_of_addons = $mo2f_addons_with_features[ $mo2f_addons[ $i ] ];
				for ( $j = 0; $j < $i + 1; $j ++ ) { ?>*<?php } ?>
                <b><?php echo $mo2f_addons[ $i ]; ?> Features</b>
                <br>
                <ol>
					<?php for ( $k = 0; $k < count( $f_feature_set_of_addons ); $k ++ ) { ?>
                        <li><?php echo $f_feature_set_of_addons[ $k ]; ?></li>
					<?php } ?>
                </ol>

                <hr><br>
			<?php } ?>
            <b>**** SMS Charges</b>
            <p><?php echo mo2f_lt( 'If you wish to choose OTP Over SMS / OTP Over SMS and Email as your authentication method,
                    SMS transaction prices & SMS delivery charges apply and they depend on country. SMS validity is for lifetime.' ); ?></p>
            <hr>
            <br>
            <div>
                <h2>Note</h2>
                <ol class="mo2f_licensing_plans_ol">
                    <li><?php echo mo2f_lt( 'The plugin works with many of the default custom login forms (like Woocommerce / Theme My Login), however if you face any issues with your custom login form, contact us and we will help you with it.' ); ?></li>
                </ol>
            </div>

            <br>
            <hr>
            <br>
            <div>
                <h2>Steps to upgrade to the Premium Plan</h2>
                <ol class="mo2f_licensing_plans_ol">
                    <li><?php echo mo2f_lt( 'Click on \'Upgrade\' button of your preferred plan above.' ); ?></li>
                    <li><?php echo mo2f_lt( ' You will be redirected to the miniOrange Console. Enter your miniOrange username and password, after which you will be redirected to the payment page.' ); ?></li>

                    <li><?php echo mo2f_lt( 'Select the number of users you wish to upgrade for, and any add-ons if you wish to purchase, and make the payment.' ); ?></li>
                    <li><?php echo mo2f_lt( 'After making the payment, you can find the Premium plugin to download from the \'License\' tab in the left navigation bar of the miniOrange Console.' ); ?></li>
                    <li><?php echo mo2f_lt( 'Download the premium plugin from the miniOrange Console.' ); ?></li>
                    <li><?php echo mo2f_lt( 'In the Wordpress dashboard, uninstall the free plugin and install the premium plugin downloaded.' ); ?></li>
                    <li><?php echo mo2f_lt( 'Login to the premium plugin with the miniOrange account you used to make the payment, after this your users will be able to set up 2FA.' ); ?></li>
                </ol>
            </div>
            <div>
                <h2>Note</h2>
                <ul class="mo2f_licensing_plans_ol">
                    <li><?php echo mo2f_lt( 'There is no license key required to activate the Premium Plugins. You will have to just login with the miniOrange Account you used to make the purchase.' ); ?></li>
                </ul>
            </div>

            <br>
            <hr>
            <br>
            <div>
                <h2>Refund Policy</h2>
                <p class="mo2f_licensing_plans_ol"><?php echo mo2f_lt( 'At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you purchased is not working as advertised and you\'ve attempted to resolve any issues with our support team, which couldn\'t get resolved then we will refund the whole amount within 10 days of the purchase.' ); ?>
                </p>
            </div>
            <br>
            <hr>
            <br>
            <div>
                <h2>Contact Us</h2>
                <p class="mo2f_licensing_plans_ol"><?php echo mo2f_lt( 'If you have any doubts regarding the licensing plans, you can mail us at' ); ?>
                    <a href="mailto:info@miniorange.com"><i>info@miniorange.com</i></a> <?php echo mo2f_lt( 'or submit a query using the support form.' ); ?>
                </p>
            </div>
            <br>
            <hr>
            <br>

            <form class="mo2f_display_none_forms" id="mo2fa_loginform"
                  action="<?php echo get_option( 'mo2f_host_name' ) . '/moas/login'; ?>"
                  target="_blank" method="post">
                <input type="email" name="username" value="<?php echo get_option( 'mo2f_email' ); ?>"/>
                <input type="text" name="redirectUrl"
                       value="<?php echo get_option( 'mo2f_host_name' ) . '/moas/initializepayment'; ?>"/>
                <input type="text" name="requestOrigin" id="requestOrigin"/>
            </form>
            <script>
                function mo2f_upgradeform(planType) {
                    jQuery('#requestOrigin').val(planType);
                    jQuery('#mo2fa_loginform').submit();
                }
            </script>

            <style>#mo2f_support_table {
                    display: none;
                }

            </style>
        </div>
    </div>

<?php }

function mo2f_create_li( $mo2f_array ) {
	$html_ol = '<ul>';
	foreach ( $mo2f_array as $element ) {
		$html_ol .= "<li>" . $element . "</li>";
	}
	$html_ol .= '</ul>';

	return $html_ol;
}

function mo2f_sms_cost() {
	?>
    <p class="mo2f_pricing_text" id="mo2f_sms_cost"
       title="<?php echo mo2f_lt( '(Only applicable if OTP over SMS is your preferred authentication method.)' ); ?>"><?php echo mo2f_lt( 'SMS Cost' ); ?>
        ****<br/>
        <select id="mo2f_sms" class="form-control" style="border-radius:5px;width:200px;">
            <option><?php echo mo2f_lt( '$5 per 100 OTP + SMS delivery charges' ); ?></option>
            <option><?php echo mo2f_lt( '$15 per 500 OTP + SMS delivery charges' ); ?></option>
            <option><?php echo mo2f_lt( '$22 per 1k OTP + SMS delivery charges' ); ?></option>
            <option><?php echo mo2f_lt( '$30 per 5k OTP + SMS delivery charges' ); ?></option>
            <option><?php echo mo2f_lt( '$40 per 10k OTP + SMS delivery charges' ); ?></option>
            <option><?php echo mo2f_lt( '$90 per 50k OTP + SMS delivery charges' ); ?></option>
        </select>
    </p>
	<?php
}

function mo2f_yearly_standard_pricing() {
	?>

    <p class="mo2f_pricing_text"
       id="mo2f_yearly_sub"><?php echo __( 'Yearly Subscription Fees', 'miniorange-2-factor-authentication' ); ?>

        <select id="mo2f_yearly" class="form-control" style="border-radius:5px;width:200px;">
            <option> <?php echo mo2f_lt( '1 - 5 users - $20 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '5 - 50 users - $30 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '50 - 100 users - $49 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '100 - 500 users - $99 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '500 - 1000 users - $199 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '1000 - 5000 users - $299 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '5000 -  10000 users - $499 per year' ); ?></option>
            <option> <?php echo mo2f_lt( '10000 - 20000 users - $799 per year' ); ?> </option>
        </select>
    </p>
	<?php
}

function mo2f_yearly_premium_pricing() {
	?>

    <p class="mo2f_pricing_text"
       id="mo2f_yearly_sub"><?php echo __( 'Yearly Subscription Fees', 'miniorange-2-factor-authentication' ); ?>

        <select id="mo2f_yearly" class="form-control" style="border-radius:5px;width:200px;">
            <option> <?php echo mo2f_lt( '1 - 5 users - $30 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '5 - 50 users - $99 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '50 - 100 users - $199 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '100 - 500 users - $349 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '500 - 1000 users - $499 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '1000 - 5000 users - $799 per year' ); ?> </option>
            <option> <?php echo mo2f_lt( '5000 -  10000 users - $999 per year ' ); ?></option>
            <option> <?php echo mo2f_lt( '10000 - 20000 users - $1449 per year' ); ?> </option>
        </select>
    </p>
	<?php
}

function mo2f_get_binary_equivalent( $mo2f_var ) {

	switch ( $mo2f_var ) {
		case 1:
			return "<i class='fa fa-check'></i>";
		case 0:
			return "";
		default:
			return $mo2f_var;
	}
}
		
	 ?>