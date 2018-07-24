<?php
	function mo_2_factor_register($current_user) {
		if(mo_2factor_is_curl_installed()==0){ ?>
			<p style="color:red;">(<?php echo __('Warning:','miniorange-2-factor-authentication');?><a href="http://php.net/manual/en/curl.installation.php" target="_blank"><?php echo __('PHP CURL extension','miniorange-2-factor-authentication');?></a> <?php echo __('is not installed or disabled','miniorange-2-factor-authentication');?>)</p>
		<?php
		}
		
		
		$mo2f_active_tab = isset($_GET['mo2f_tab']) ? $_GET['mo2f_tab'] : '2factor_setup';
		global $dbQueries;
		$user_registration_status = $dbQueries->get_user_detail( 'mo_2factor_user_registration_status',$current_user->ID);
		// var_dump($user_registration_status);
		
		?>
		  <div class="wrap">
        <div><img style="float:left;" src="<?php echo plugins_url( 'includes/images/logo.png"', __FILE__ ); ?>"></div>
        <div style="display:block;font-size:23px;padding:9px 0 10px;line-height:29px; margin-left:3%">
            <a class="add-new-h2" href="https://faq.miniorange.com/kb/two-factor-authentication"
               target="_blank"><?php echo 'FAQ'; ?></a>
			
                <a class="twofa-license add-new-h2" id="mo2f_tab6"
                   href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_pricing"
                ><?php echo 'Premium Plan' ; ?></a>
			
        </div>
    </div>
		<div id="tab">
			<h2 class="nav-tab-wrapper">
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=2factor_setup" class="nav-tab <?php echo $mo2f_active_tab == '2factor_setup' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab1">
				<?php if($user_registration_status == 'MO_2_FACTOR_INITIALIZE_MOBILE_REGISTRATION' || $user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){ ?><?php echo __('User Profile','miniorange-2-factor-authentication');?> <?php }else{ ?> <?php echo __('Account Setup','miniorange-2-factor-authentication');?> <?php } ?></a> 
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure" class="nav-tab <?php echo $mo2f_active_tab == 'mobile_configure' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab3"><?php echo __('Setup Two-Factor','miniorange-2-factor-authentication');?></a>
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_login" class="nav-tab <?php echo $mo2f_active_tab == 'mo2f_login' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab2"><?php echo __('Login Settings','miniorange-2-factor-authentication');?></a>
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=advance_option" class="nav-tab <?php echo $mo2f_active_tab == 'advance_option' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab2"><?php echo __('Premium Options','miniorange-2-factor-authentication');?></a>
				  <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=proxy_setup"
                   class="nav-tab <?php echo $mo2f_active_tab == 'proxy_setup' ? 'nav-tab-active' : ''; ?>"
                   id="mo2f_tab5"><?php echo mo2f_lt( 'Proxy Setup' ); ?></a>
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_privacy_policy"
                    class="nav-tab <?php echo $mo2f_active_tab == 'mo2f_privacy_policy' ? 'nav-tab-active' : ''; ?>"
                    id="mo2f_tab5"><?php echo mo2f_lt( 'Privacy Policy' ); ?></a>
			
			  
				
			</h2>
		</div>

		
		<div class="mo2f_container">
		<div id="messages"></div>
			<table style="width:100%;padding:10px;">
				<tr>
					<td style="width:60%;vertical-align:top;">
						<?php
						/* to update the status of existing customers for adding their user registration status */
							if(get_site_option( 'mo_2factor_admin_registration_status') == 'MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS' && get_site_option( 'mo2f_miniorange_admin') == $current_user->ID ){
								// update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
								$dbQueries->update_user_details( $current_user->ID, array(
									'mo_2factor_user_registration_with_miniorange' =>'SUCCESS'
									) );
								
							}
						/* ----------------------------------------- */
						
							if($mo2f_active_tab == 'mobile_configure') {
								
									$mo2f_second_factor= mo2f_get_activated_second_factor($current_user);
									// var_dump($mo2f_second_factor);
									mo2f_select_2_factor_method($current_user,$mo2f_second_factor); //Configure 2-Factor tab
								
								?>
									<script>
										jQuery(document).ready(function(){
											jQuery("#mo2f_support_table").hide();
										});
									</script>
								<?php
							}else if($mo2f_active_tab == 'mo2f_help'){
								unset($_SESSION[ 'mo2f_google_auth' ]);
								unset($_SESSION[ 'mo2f_authy_keys' ]);
								unset($_SESSION[ 'mo2f_mobile_support' ]);
								$mo_expand_value = isset($_GET['mo2f_tabpan']) ? true : false;
								mo2f_show_help_and_troubleshooting($current_user,$mo_expand_value);  //Help & Troubleshooting tab
							}else if($mo2f_active_tab == 'mo2f_demo'){
								unset($_SESSION[ 'mo2f_google_auth' ]);
								unset($_SESSION[ 'mo2f_authy_keys' ]);
								unset($_SESSION[ 'mo2f_mobile_support' ]);
								show_2_factor_login_demo($current_user);
							}else if(current_user_can( 'manage_options' ) && $mo2f_active_tab == 'mo2f_login'){
								unset($_SESSION[ 'mo2f_google_auth' ]);
								unset($_SESSION[ 'mo2f_authy_keys' ]);
								unset($_SESSION[ 'mo2f_mobile_support' ]);
								show_2_factor_login_settings($current_user); //Login Settings tab
							}else if(current_user_can( 'manage_options' ) && $mo2f_active_tab == 'proxy_setup'){
								unset($_SESSION[ 'mo2f_google_auth' ]);
								unset($_SESSION[ 'mo2f_authy_keys' ]);
								unset($_SESSION[ 'mo2f_mobile_support' ]);
								show_2_factor_proxy_setup($current_user);
							}else if(current_user_can( 'manage_options' ) && $mo2f_active_tab == 'mo2f_privacy_policy'){
								unset($_SESSION[ 'mo2f_google_auth' ]);
								unset($_SESSION[ 'mo2f_authy_keys' ]);
								unset($_SESSION[ 'mo2f_mobile_support' ]);
								show_2_factor_privacy_policy($current_user);
							}else if(current_user_can( 'manage_options' ) && $mo2f_active_tab == 'advance_option'){
								unset($_SESSION[ 'mo2f_google_auth' ]);
								unset($_SESSION[ 'mo2f_authy_keys' ]);
								unset($_SESSION[ 'mo2f_mobile_support' ]);
								show_2_factor_advanced_options($current_user); //Login Settings tab
							}else if(current_user_can( 'manage_options' ) && $mo2f_active_tab == 'mo2f_pricing'){
								unset($_SESSION[ 'mo2f_google_auth' ]);
								unset($_SESSION[ 'mo2f_authy_keys' ]);
								unset($_SESSION[ 'mo2f_mobile_support' ]);
								show_2_factor_pricing_page($current_user); //Login Settings tab
							}else{
								unset($_SESSION[ 'mo2f_google_auth' ]);
								unset($_SESSION[ 'mo2f_mobile_support' ]);
								unset($_SESSION[ 'mo2f_authy_keys' ]);
									// var_dump(get_site_option( 'mo_2factor_admin_registration_status'),get_site_option( 'mo2f_miniorange_admin'),$user_registration_status);
								if(get_site_option( 'mo_2factor_admin_registration_status') == 'MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS' && get_site_option( 'mo2f_miniorange_admin') != $current_user->ID){
									if($user_registration_status == 'MO_2_FACTOR_OTP_DELIVERED_SUCCESS' || $user_registration_status == 'MO_2_FACTOR_OTP_DELIVERED_FAILURE'){
										mo2f_show_user_otp_validation_page();  // OTP over email validation page
									} else if($user_registration_status == 'MO_2_FACTOR_INITIALIZE_MOBILE_REGISTRATION'){  //displaying user profile
										$mo2f_second_factor = mo2f_get_activated_second_factor($current_user);
										mo2f_show_instruction_to_allusers($current_user,$mo2f_second_factor);
									} else if($user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){
										$mo2f_second_factor = mo2f_get_activated_second_factor($current_user);
										mo2f_show_instruction_to_allusers($current_user,$mo2f_second_factor);  //displaying user profile	
									}else{
										show_user_welcome_page($current_user);  //Landing page for additional admin for registration
									}
		// var_dump("here");exit;
								}
								else{
								// var_dump("here",$user_registration_status);
									if($user_registration_status == 'MO_2_FACTOR_OTP_DELIVERED_SUCCESS' || $user_registration_status == 'MO_2_FACTOR_OTP_DELIVERED_FAILURE'){
										mo2f_show_otp_validation_page($current_user);  // OTP over email validation page for admin
									} else if($user_registration_status == 'MO_2_FACTOR_INITIALIZE_MOBILE_REGISTRATION'){  //displaying user profile
										$mo2f_second_factor = mo2f_get_activated_second_factor($current_user);
										mo2f_show_instruction_to_allusers($current_user,$mo2f_second_factor);
									}else if($user_registration_status == 'MO_2_FACTOR_VERIFY_CUSTOMER') {
										// var_dump("here");exit;
										mo2f_show_verify_password_page();  //verify password page
									}else if(!mo2f_is_customer_registered()){
										// var_dump("here");exit;
										if(!get_site_option('mo2f_no_license_needed')){
											mo2f_no_license_key_required();
										}
										else{
										global $dbQueries;
										delete_site_option('password_mismatch');
										$dbQueries->insert_user($current_user->ID, array( 'user_id' => $current_user->ID ) );
										// update_site_option( 'mo2f_message', 'Please enter your registered email and password.');
										// update_user_meta( $current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_VERIFY_CUSTOMER');
										$dbQueries->update_user_details( $current_user->ID, array( 'mo_2factor_user_registration_status' => 'MO_2_FACTOR_VERIFY_CUSTOMER' ) );
										mo2f_show_verify_password_page(); 
										}
										// $this->mo_auth_show_success_message();
										
										// mo2f_show_new_registration_page($current_user); //new registration page
									}else if($user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){
										$mo2f_second_factor = mo2f_get_activated_second_factor($current_user);
										mo2f_show_instruction_to_allusers($current_user,$mo2f_second_factor);  //displaying user profile		
									} 
								}
							
							}
						?>
					</td>
					<td style="vertical-align:top;padding-left:1%;" id="mo2f_support_table">
						<?php if(!($mo2f_active_tab == 'mobile_configure')) {echo mo2f_support(); }?>	
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
	
	function mo2f_show_new_registration_page($current_user) {
		global $dbQueries;
		?>
			<!--Register with miniOrange-->
			<form name="f" method="post" action="">
				<input type="hidden" name="option" value="mo_auth_register_customer" />
				<div class="mo2f_table_layout">
					<h3><?php echo __('Register with miniOrange','miniorange-2-factor-authentication');?></h3><hr>
					<div id="panel1">
						<div><b><?php echo __('Please enter a valid email id that you have access to. You will be able to move forward after verifying an OTP that we will be sending to this email.','miniorange-2-factor-authentication');?> <a href="#mo2f_account_exist"><?php echo __('Already registered with miniOrange?','miniorange-2-factor-authentication');?></a> </b> </div>
						<p class="float-right"><font color="#FF0000">*</font> <?php echo __('Indicates Required Fields','miniorange-2-factor-authentication');?></p>
						<table class="mo2f_settings_table">
							<tr>
							<td><b><font color="#FF0000">*</font><?php echo __('Email :','miniorange-2-factor-authentication');?> </b> </td>
							<td><input class="mo2f_table_textbox" type="email" name="email" required placeholder="person@example.com" value="<?php if(get_site_option('mo2f_email')){echo get_site_option('mo2f_email');}else{echo $current_user->user_email;}?>"/></td>
							</tr>

							<tr>
							<td><b>&nbsp;&nbsp;<?php echo __('Phone number :','miniorange-2-factor-authentication');?> </b> </td>
							 <td><input class="mo2f_table_textbox" style="width:100% !important;" type="text" name="phone" pattern="[\+]?([0-9]{1,4})?\s?([0-9]{7,12})?" id="phone" autofocus="true"  value="<?php echo $dbQueries->get_user_detail( 'mo2f_user_phone',$current_user->ID);?>" />
							 <?php echo __('This is an optional field. We will contact you only if you need support.','miniorange-2-factor-authentication');?></td>
							</tr>
							
							<tr>
							<td><b><font color="#FF0000">*</font><?php echo __('Password :','miniorange-2-factor-authentication');?> </b> </td>
							 <td><input class="mo2f_table_textbox" type="password" required name="password" placeholder="<?php echo __('Choose your password with minimum 6 characters','miniorange-2-factor-authentication');?>" /></td>
							</tr>
							<tr>
							<td><b><font color="#FF0000">*</font><?php echo __('Confirm Password :','miniorange-2-factor-authentication');?> </b> </td>
							 <td><input class="mo2f_table_textbox" type="password" required name="confirmPassword" placeholder="<?php echo __('Confirm your password with minimum 6 characters','miniorange-2-factor-authentication');?>" /></td>
							</tr>
							<tr><td>&nbsp;</td></tr>
						  <tr>
							<td>&nbsp;</td>
							<td><input type="submit" name="submit" value="<?php echo __('Submit','miniorange-2-factor-authentication');?>" class="button button-primary button-large" /></td>
						  </tr>
						</table>
						<br>
						
					</div>
				</div>
			</form>
			<form name="f" method="post" action="" id="mo2f_verify_customerform" >
				<input type="hidden" name="option" value="mo2f_goto_verifycustomer">
			</form>
						
			<script>
				jQuery("#phone").intlTelInput();
				jQuery('a[href="#mo2f_account_exist"]').click(function(e) {	
					jQuery('#mo2f_verify_customerform').submit();
				});
			</script>
		<?php
	}
	
	function mo2f_show_otp_validation_page($current_user){
		global $dbQueries;
	?>
		<!-- Enter otp -->
		
		<div class="mo2f_table_layout">
			<h3><?php echo __('Validate OTP','miniorange-2-factor-authentication');?></h3><hr>
			<div id="panel1">
				<table class="mo2f_settings_table">
					<form name="f" method="post" id="mo_2f_otp_form" action="">
						<input type="hidden" name="option" value="mo_2factor_validate_otp" />
							<tr>
								<td><b><font color="#FF0000">*</font><?php echo __('Enter OTP:','miniorange-2-factor-authentication');?> </b> </td>
								<td colspan="2"><input class="mo2f_table_textbox" autofocus="true" type="text" name="otp_token" placeholder="<?php echo __('Enter OTP','miniorange-2-factor-authentication');?>" required style="width:95%;"  /></td>
								<td><a href="#resendotplink"><?php echo __('Resend OTP ?','miniorange-2-factor-authentication');?></a></td>
							</tr>
							
							<tr>
								<td>&nbsp;</td>
								<td style="width:17%">
								<input type="submit" name="submit" value="<?php echo __('Validate OTP','miniorange-2-factor-authentication');?>" class="button button-primary button-large" /></td>

						</form>
						<form name="f" method="post" action="">
						<td>
						<input type="hidden" name="option" value="mo_2factor_gobackto_registration_page"/>
							<input type="submit" name="mo2f_goback" id="mo2f_goback" value="<?php echo __('Back','miniorange-2-factor-authentication');?>" class="button button-primary button-large" /></td>
						</form>
						</td>
						</tr>
						<form name="f" method="post" action="" id="resend_otp_form">
							<input type="hidden" name="option" value="mo_2factor_resend_otp"/>
						</form>
						
				</table>
				<br>
				<hr>

				<h3><?php echo __('I did not receive any email with OTP . What should I do ?','miniorange-2-factor-authentication');?></h3>
				<form id="phone_verification" method="post" action="">
					<input type="hidden" name="option" value="mo_2factor_phone_verification" />
					 <?php echo __('If you can\'t see the email from miniOrange in your mails, please check your ','miniorange-2-factor-authentication');?><b><?php echo __('SPAM Folder','miniorange-2-factor-authentication');?></b>. <?php echo __('If you don\'t see an email even in SPAM folder, verify your identity with our alternate method.','miniorange-2-factor-authentication');?>
					 <br><br>
						<b><?php echo __('Enter your valid phone number here and verify your identity using one time passcode sent to your phone.','miniorange-2-factor-authentication');?></b>
						<br><br>
						<table>
						<tr>
						<td>
						<input class="mo2f_table_textbox" required autofocus="true" type="text" name="phone_number" id="phone" placeholder="<?php echo __('Enter Phone Number','miniorange-2-factor-authentication');?>" value="<?php echo $dbQueries->get_user_detail( 'mo2f_user_phone',$current_user->ID); ?>" pattern="[\+]?[0-9]{1,4}\s?[0-9]{7,12}" title="<?php echo __('Enter phone number without any space or dashes.','miniorange-2-factor-authentication');?>"/>
						</td>
						<td>
						<a href="#resendsmsotplink"><?php echo __('Resend OTP ?','miniorange-2-factor-authentication');?></a>
						</td>
						</tr>
						</table>
						<br><input type="submit" value="<?php echo __('Send OTP','miniorange-2-factor-authentication');?>" class="button button-primary button-large" />
				
				</form>
				<br>
				<h3><?php echo __('What is an OTP ?','miniorange-2-factor-authentication');?></h3>
				<p><?php echo __('OTP is a one time passcode ( a series of numbers) that is sent to your email or phone number to verify that you have access to your email account or phone. ','miniorange-2-factor-authentication');?></p>
				</div>
				<div>	
					<script>
						jQuery("#phone").intlTelInput();
						jQuery('a[href="#resendotplink"]').click(function(e) {
							jQuery('#resend_otp_form').submit();
						});
						jQuery('a[href="#resendsmsotplink"]').click(function(e) {
							jQuery('#phone_verification').submit();
						});
					</script>
		
			<br><br>
			</div>
			
			
						
		</div>
					
	<?php
	}
	
	function miniorange_2_factor_select_method_user_roles($current_user) 
	{
		$opt=fetch_methods($current_user); 

		?>
		<h3><?php echo mo2f_lt('Select the specific set of authentication methods for your users.');?><?php echo'<span style="float:right;font-size: 13px;"><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_pricing"></a></span>'; ?></h3><hr>
		
		<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="1" <?php checked( get_site_option('mo2f_all_users_method') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 <?php echo __('For all Users','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				 <input type="radio" class="option_for_auth2" name="mo2f_all_users_method" value="0" <?php checked( get_site_option('mo2f_all_users_method') == 0 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 <?php echo __('Specific Roles','miniorange-2-factor-authentication'); ?>
		<br><br>
				<table class="mo2f_for_all_users" <?php if(!get_site_option('mo2f_all_users_method')){echo 'hidden';} ?> ><tbody>
				
				<tr>
				<td>
				<input type='checkbox'  name='mo2f_authmethods[]'  value='OUT OF BAND EMAIL' <?php echo (in_array("OUT OF BAND EMAIL", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />Email Verification&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='SMS' <?php echo (in_array("SMS", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />OTP Over SMS&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='PHONE VERIFICATION' <?php echo (in_array("PHONE VERIFICATION", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />Phone Call Verification&nbsp;&nbsp;
				</td>
				</tr>
				
				<tr>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='SOFT TOKEN' <?php echo (in_array("SOFT TOKEN", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />Soft Token&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='MOBILE AUTHENTICATION' <?php echo (in_array("MOBILE AUTHENTICATION", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />QR Code Authentication&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='PUSH NOTIFICATIONS' <?php echo (in_array("PUSH NOTIFICATIONS", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />Push Notifications&nbsp;&nbsp;
				</td>
				</tr>
				
				<tr>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='GOOGLE AUTHENTICATOR' <?php echo (in_array("GOOGLE AUTHENTICATOR", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />Google Authenticator&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='AUTHY 2-FACTOR AUTHENTICATION' <?php echo (in_array("AUTHY 2-FACTOR AUTHENTICATION", $opt)) ? 'checked="checked"' : ''; if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />AUTHY 2-FACTOR AUTHENTICATION&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='KBA' <?php echo (in_array("KBA", $opt)) ? 'checked="checked"' : ''; if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />Security Questions (KBA)&nbsp;&nbsp;
				</td>
				</tr>
				</tbody>
				</div>
				</table>
				
				
		<?php
		
		$opt = (array) get_site_option('mo2f_auth_methods_for_users');
		$copt=array();
		$newcopt=array();
		// var_dump(get_site_option('mo2f_all_users_method'));
		 
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		foreach($wp_roles->role_names as $id => $name)
		{
			$copt[$id]=get_site_option('mo2f_auth_methods_for_'.$id);
			// var_dump($id);
			// var_dump(empty($copt[$id]));
			if(empty($copt[$id])){
				$copt[$id]=array("No Two Factor Selected");
		}?>
			 <span class="mo2f_display_tab btn" style="border-radius:6px;padding: 5px 25px; width:2px !important;"	 ID="mo2f_role_<?php echo $id ?>" onclick="displayTab('<?php echo $id ?>');" value="<?php echo $id ?>" <?php if(get_option('mo2f_all_users_method')){echo 'hidden';}?>> <?php echo $name ?></span>
			 
			 <style>
			 .blue{
				 cursor: pointer;
				  margin: 2px;
				  color:#0085ba;
				  
				  border: 2px solid #0085ba;
				  text-decoration: none;
				  padding: 5px;
				  font-size: 15px;
				}
			 .btn {
				  background-color: #0085ba;
				  color: #fff;
				  cursor: pointer;
				  margin: 2px;
				  border-radius: 3px solid;
				  border: 2px solid #0085ba;
				  text-decoration: none;
				  padding: 5px;
				  font-size: 12px;
				  transition:.5s;
				 	 
				  
				}
			 </style>
			 
			 <?php
		}
		?> <br> <br><?php 
		// var_dump($copt);		
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		// var_dump(count($wp_roles->roles));
		// var_dump($copt[$id]);
		print '<div> ';
		foreach($wp_roles->role_names as $id => $name) {	
				$setting = get_option('mo2fa_'.$id);
				// var_dump($name);
				// var_dump(in_array("OUT OF BAND EMAIL", $copt[$id]));
				$newcopt=$copt[$id];
				// var_dump($newcopt);
				// if(get_option('mo2f_all_users_method'))
				// {
					// $newcopt=$opt;
				// }
				// else{
					// $newcopt=$copt[$id];
				// }
				?>
				
				<table class="mo2f_for_all_roles" id="mo2f_for_all_<?php echo $id ?>" hidden><tbody>
				<tr>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='OUT OF BAND EMAIL' <?php echo (in_array("OUT OF BAND EMAIL", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Email Verification','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='SMS' <?php echo (in_array("SMS", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('OTP Over SMS','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='PHONE VERIFICATION' <?php echo (in_array("PHONE VERIFICATION", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Phone Call Verification','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				
				<tr>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='SOFT TOKEN' <?php echo (in_array("SOFT TOKEN", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Soft Token','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='MOBILE AUTHENTICATION' <?php echo (in_array("MOBILE AUTHENTICATION", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('QR Code Authentication','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='PUSH NOTIFICATIONS' <?php echo (in_array("PUSH NOTIFICATIONS", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Push Notifications','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				
				<tr>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='GOOGLE AUTHENTICATOR' <?php echo (in_array("GOOGLE AUTHENTICATOR", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Google Authenticator','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='AUTHY 2-FACTOR AUTHENTICATION' <?php echo (in_array("AUTHY 2-FACTOR AUTHENTICATION", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('AUTHY 2-FACTOR AUTHENTICATION','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='KBA' <?php echo (in_array("KBA", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Security Questions (KBA)','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				
				<tr>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='SMS AND EMAIL' <?php echo (in_array("SMS AND EMAIL", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('OTP Over SMS And Email','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo $id ?>[]"  value='OTP_OVER_EMAIL' <?php echo (in_array("OTP_OVER_EMAIL", $newcopt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('OTP Over Email','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				</tbody>
				</div>
				</table>
				<?php
				// var_dump(role_names);
				// var_dump($name);
				// exit;
				
				?>
				
				
		<?php			
		}
		// exit;
		
		print '</div>';
	// exit;
	?>
	
	<script>
	function displayTab(role){
		// alert(role);
		
		jQuery('.mo2f_display_tab').removeClass("blue");
		jQuery('.mo2f_display_tab').addClass("btn");
		jQuery('#mo2f_role_'+role).removeClass("btn");
		jQuery('#mo2f_role_'+role).addClass("blue");
		
		
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('#mo2f_for_all_'+role).show();
	}
	jQuery(".option_for_auth").click(function(){
		// alert("here");
		
		jQuery('.mo2f_display_tab').hide();
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('.mo2f_for_all_users').show();
		
	})
	// jQuery(".mo2f_display_tab").click(function(){
		// alert("Chal gaya");
	// })
	
	jQuery(".option_for_auth2").click(function(){
		// alert("here");
		// jQuery('.mo2f_display_tab').removeClass("blue");
		// jQuery('.mo2f_display_tab').addClass("btn");
		// jQuery('#mo2f_role_administrator').removeClass("btn");
		// jQuery('#mo2f_role_administrator').addClass("blue");
		jQuery('.mo2f_display_tab').show();
		jQuery('.mo2f_for_all_users').hide();
		// jQuery('#mo2f_for_all_administrator').show();
		
		// jQuery('.mo2f_for_al').hide();
		// jQuery('.mo2f_for_all_roles').show();
	}
	)
	
	</script>
	<?php
	}
	
	function miniorange_2_factor_user_roles($current_user) {
			 ?>
		
		<input type="radio" class="mo2f_for_roles" name="mo2f_by_roles" value="1" <?php checked( get_site_option('mo2f_by_roles') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 <?php echo __('For Roles','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
		 <input type="radio" class="mo2f_for_Select_users" name="mo2f_by_roles" value="0" <?php checked( get_site_option('mo2f_by_roles') == 0 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 <?php echo __('Select Users','miniorange-2-factor-authentication'); ?></br></br> 
		<div class="mo2f_show_roles" <?php if(get_site_option('mo2f_by_roles')){}else{echo 'hidden';}?>>
		<?php
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		
		print '<div><span style="font-size:16px;">Roles<div style="float:right;">' . __('Custom Redirect Login Url','miniorange-2-factor-authentication') . '</div></span><br /><br />';
		foreach($wp_roles->role_names as $id => $name) {	
			$setting = get_site_option('mo2fa_'.$id);
		?>
			<div><input type="checkbox" name="<?php echo 'mo2fa_'.$id; ?>" style="margin-left: 2%;"  value="1" <?php checked($setting == 1); if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo $name; ?>
			<input type="text" class="mo2f_table_textbox" style="width:50% !important;float:right;" name="<?php echo 'mo2fa_'.$id; ?>_login_url" value="<?php echo get_option('mo2fa_' .$id . '_login_url'); ?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
			</div> 
			
			<br />
		<?php
		}?>
		<div id="mo2f_note"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('Selecting the above roles will enable 2-Factor for all users associated with that role.Users of the selected role who have not setup their 2-Factor will be able to setup 2 factor during inline registration.','miniorange-2-factor-authentication');?></div>
				<br>
		<?php
		print '</div>';
		$url=admin_url();
		// echo $url;
		?>
		 </div>
		 <div class="mo2f_show_user_redirect" <?php if(!get_site_option('mo2f_by_roles')){}else{echo 'hidden';}?>>
		 <a href="<?php echo admin_url();?>users.php" class=""><?php echo mo2f_lt('Click Here to enable Authentication for users');?></a>
		 <br><br>
		 <div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('For <b>Select Users</b> option you will redirected to Wordpress Users Page. You can select the users and apply Bulk Actions to apply or remove Two Factor for users','miniorange-2-factor-authentication');?></div>
		</div>
		 
		 <?php miniorange_2_factor_select_method_user_roles($current_user);
		 ?>
		 
		 <script>
		 jQuery('.mo2f_for_roles').click(function(){
			 jQuery('.mo2f_show_roles').show();
			 jQuery('.mo2f_show_user_redirect').hide();
		 })
		 jQuery('.mo2f_for_Select_users').click(function(){
			 jQuery('.mo2f_show_roles').hide();
			 jQuery('.mo2f_show_user_redirect').show();
			 
		 })
		 </script>
		 <?php
	}
	
	function show_2_factor_login_settings($current_user) {
		$opt=fetch_methods($current_user);
		global $dbQueries;
		$user_registration_status=$dbQueries->get_user_detail( 'mo_2factor_user_registration_status',$current_user->ID);
	?>
	<div class="mo2f_table_layout">
			<?php echo mo2f_check_if_registered_with_miniorange($current_user); ?>
				
			   <form name="f"  id="login_settings_form" method="post" action="">
				<input type="hidden" name="option" value="mo_auth_login_settings_save" />
				<span>
				<h3><?php echo mo2f_lt(' Select Roles to enable 2-Factor');?>
				<input type="submit" name="submit" value="<?php echo __('Save Settings','miniorange-2-factor-authentication');?>" style="float:right;" class="button button-primary button-large" <?php 
				if(mo2f_is_customer_registered() ){ } else{ echo 'disabled' ; } ?> /></h3><span>
				<hr><br>
				
				<?php  echo miniorange_2_factor_user_roles($current_user); ?>
				
				
				<!--
				<h3><?php echo __('Select the specific set of authentication methods for your users.');?>','miniorange-2-factor-authentication');?></h3><hr><br />
				
				<table style="margin-left: 2%;"><tbody >
				<tr >
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='OUT OF BAND EMAIL' <?php echo (in_array("OUT OF BAND EMAIL", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Email Verification','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='SMS' <?php echo (in_array("SMS", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('OTP Over SMS','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='PHONE VERIFICATION' <?php echo (in_array("PHONE VERIFICATION", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Phone Call Verification','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				
				<tr>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='SOFT TOKEN' <?php echo (in_array("SOFT TOKEN", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Soft Token','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='MOBILE AUTHENTICATION' <?php echo (in_array("MOBILE AUTHENTICATION", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('QR Code Authentication','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='PUSH NOTIFICATIONS' <?php echo (in_array("PUSH NOTIFICATIONS", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Push Notifications','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				
				<tr>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='GOOGLE AUTHENTICATOR' <?php echo (in_array("GOOGLE AUTHENTICATOR", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Google Authenticator','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='AUTHY 2-FACTOR AUTHENTICATION' <?php echo (in_array("AUTHY 2-FACTOR AUTHENTICATION", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('AUTHY 2-FACTOR AUTHENTICATION','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='KBA' <?php echo (in_array("KBA", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('Security Questions (KBA)','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				
				<tr>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='SMS AND EMAIL' <?php echo (in_array("SMS AND EMAIL", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('OTP Over SMS And Email','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='OTP_OVER_EMAIL' <?php echo (in_array("OTP_OVER_EMAIL", $opt)) ? 'checked="checked"' : '';  if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('OTP Over Email','miniorange-2-factor-authentication');?>&nbsp;&nbsp;
				</td>
				</tr>
				</tbody>
				</table>
				-->
				 <br><div class="mo2f_advanced_options_note" style="margin-left: 2%;"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('You can select which Two Factor methods you want to enable for your users. By default all Two Factor methods are enabled for all users of the role you have selected above.','miniorange-2-factor-authentication');?></div>
				<br>
				<h3><?php echo __('Invoke Inline Registration to setup 2nd factor for users.','miniorange-2-factor-authentication');?></h3><hr><br />
				
				<div style="margin-left: 2%;">
				
				 <input type="radio" name="mo2f_inline_registration" value="1" <?php checked( get_site_option('mo2f_inline_registration') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 <?php echo __('Enforce 2 Factor registration for users at login time','miniorange-2-factor-authentication');?>.&nbsp;&nbsp;
				 <input type="radio" name="mo2f_inline_registration" value="0" <?php checked( get_site_option('mo2f_inline_registration') == 0 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 <?php echo __('Skip 2 Factor registration at login.','miniorange-2-factor-authentication');?>
				 <br><br>
				 <div class="mo2f_advanced_options_note"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('If this option is enabled then users have to setup their two-factor account forcefully during their login. By selecting second option, you will provide your users to skip their two-factor setup during login.','miniorange-2-factor-authentication');?>
				 
				 </div>
				</div>
				 <br />
				
				 <h3> <?php echo __('Email verification of Users during Inline Registration ','miniorange-2-factor-authentication');?></h3><hr><br />
				<div>
				
				 <input type="radio" name="mo2f_enable_emailchange" value="1" <?php checked( get_site_option('mo2f_enable_emailchange') == 1 ); if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				  <?php echo __('Enable users to ','miniorange-2-factor-authentication');?><b><?php echo __('edit their email address','miniorange-2-factor-authentication');?></b> <?php echo __('for registration with miniOrange.','miniorange-2-factor-authentication');?><br><br>
				 <input type="radio" name="mo2f_enable_emailchange" value="0" <?php checked( get_site_option('mo2f_enable_emailchange') == 0 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 <?php echo __('Skip e-mail verification by user.','miniorange-2-factor-authentication');?>
				 <br><br>
				 <div class="mo2f_advanced_options_note"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('If this option is enabled then users can edit their email during inline registration with miniOrange, and they will be prompted for e-mail verification. By selecting second option, the user will be silently registered with miniOrange without the need of e-mail verification.','miniorange-2-factor-authentication');?></div>
				</div>
				</br> 
				 <h3><?php echo __('Mobile Support','miniorange-2-factor-authentication');?></h3><hr>
				 <input type="checkbox" name="mo2f_enable_mobile_support" style="margin-left: 2%;" value="1" <?php checked( get_site_option('mo2f_enable_mobile_support') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 <?php echo __('Enable Mobile Support for users.','miniorange-2-factor-authentication');?><br /><br />
				 <div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('If this option is enabled then Security Questions (KBA) will be invoked as 2nd factor during login through mobile browsers.','miniorange-2-factor-authentication');?></div>
				 <br />				 
				  
				<h3><?php echo __('Select Login Screen Options','miniorange-2-factor-authentication');?></h3><hr><br>
				<input type="radio"   name="mo2f_login_policy"  value="1"
						<?php checked( get_site_option('mo2f_login_policy')); 
						if($user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){}else{ echo 'disabled';} ?> style="margin-left: 2%;"/>
						<?php echo __('Login with password + 2nd Factor ','miniorange-2-factor-authentication');?><span style="color:red">(<?php echo __('Recommended','miniorange-2-factor-authentication');?>)</span>&nbsp;&nbsp;
				<br><br>
				
				<div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('By default 2nd Factor is enabled after password authentication. If you do not want to remember passwords anymore and just login with 2nd Factor, please select 2nd option.','miniorange-2-factor-authentication');?></div>
				<br>
				<input type="radio"   name="mo2f_login_policy"  value="0"
						<?php checked( !get_site_option('mo2f_login_policy')); 
						if($user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){}else{ echo 'disabled';} ?> style="margin-left: 2%;" />
						<?php echo __('Login with 2nd Factor only ','miniorange-2-factor-authentication');?><span style="color:red">(<?php echo __('No password required.','miniorange-2-factor-authentication');?>)</span> &nbsp;<a class=" btn-link" data-toggle="collapse" id="showpreview1" href="#preview1" aria-expanded="false"><?php echo __('See preview','miniorange-2-factor-authentication');?></a>
				<br>
				<div class="mo2f_collapse" id="preview1" style="height:300px;">
					<center><br>
					<img style="height:300px;" src="https://auth.miniorange.com/moas/images/help/login-help-1.png" >
					</center>
					
				 </div> 
			    <br><br>
				<div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('Checking this option will add login with your phone button below default login form. Click above link to see the preview.','miniorange-2-factor-authentication');?></div>
				
				<div id="loginphonediv" hidden>	<br>
				<input style="margin-left:6%;" type="checkbox" id="mo2f_loginwith_phone" name="mo2f_loginwith_phone" value="1" <?php checked( get_site_option('mo2f_show_loginwith_phone') == 1 ); 
				if($user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){}else{ echo 'disabled';} ?> /> 
			<?php echo __('	I want to hide default login form.','miniorange-2-factor-authentication');?> &nbsp;<a class="btn-link" data-toggle="collapse" id="showpreview2" href="#preview2" aria-expanded="false"><?php echo __('See preview','miniorange-2-factor-authentication');?></a>
				<br>
				<div class="mo2f_collapse" id="preview2" style="height:300px;">
					<center><br>
					<img style="height:300px;" src="https://auth.miniorange.com/moas/images/help/login-help-3.png" >
					</center>
				 </div> 
				<br>
				<br><div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('Checking this option will hide default login form and just show login with your phone. Click above link to see the preview.','miniorange-2-factor-authentication');?></div>
			
				 </div>
				 <br>
				 
				 <h3><?php echo __('What happens if my phone is lost, discharged or not with me ','miniorange-2-factor-authentication');?></h3><hr>
				 <br>
				 <input type="checkbox" id="mo2f_forgotphone" name="mo2f_forgotphone" style="margin-left: 2%;" value="1" <?php checked( get_site_option('mo2f_enable_forgotphone') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 <?php echo __('Enable Forgot Phone.','miniorange-2-factor-authentication');?>
				 <span style="color:red;float:right;">( <?php echo __('If you disable this checkbox, then users will not get this option','miniorange-2-factor-authentication');?>.)</span><br />
				 <div>	
				 <p style="margin-left:4%;"><?php echo __('Select the alternate login method in case your phone is lost, discharged or not with you.','miniorange-2-factor-authentication');?></p>
				 <input type="checkbox" id="mo2f_forgotphone" style="margin-left:4%;" name="mo2f_forgotphone_kba" value="1" <?php checked( get_site_option('mo2f_enable_forgotphone_kba') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('KBA','miniorange-2-factor-authentication');?>
				 <input type="checkbox" id="mo2f_forgotphone" style="margin-left:10%;" name="mo2f_forgotphone_email" value="1" <?php checked( get_site_option('mo2f_enable_forgotphone_email') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo __('OTP over EMAIL','miniorange-2-factor-authentication');?><br /><br />
				 </div>
				 <div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%"><b><?php echo __('Note:','miniorange-2-factor-authentication');?> </b> <?php echo __('This option will provide you alternate way of login in case your phone is lost, discharged or not with you.','miniorange-2-factor-authentication');?></div>
				<br><br />
				
				<h3><?php echo mo2f_lt(' Change name in Google Authenticator App');?></h3>
                 <hr>
				 <p style="margin-left:2%;"><?php echo mo2f_lt('<b>Enable</b> option will allow user to change name in Google Authenticator App for themselves. <b>Disable</b> option will not allow users to change name for Google Authenticator App.');?></p>

				 <input type="radio"   name="mo2f_enable_gauth_name"  value="1"
						<?php checked( get_site_option('mo2f_enable_gauth_name')); 
						if($user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){}else{ echo 'disabled';} ?> style="margin-left: 2%;"/>
						<?php echo __('Enable','miniorange-2-factor-authentication');?> &nbsp;
				
				 <input type="radio"   name="mo2f_enable_gauth_name"  value="0"
						<?php checked( !get_site_option('mo2f_enable_gauth_name')); 
						if($user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){}else{ echo 'disabled';} ?> style="margin-left: 2%;" />
						<?php echo __('Disable','miniorange-2-factor-authentication');?>&nbsp;
						
				<br><br>
				<div id="mo2f_gauth_name" hidden>
				<b style="margin-left:2margin-top:4%;font-size:15px;	"><?php echo mo2f_lt('Custom name in  Google authenticator App:');?> &nbsp;&nbsp;</b>
			              <input type="text" class="mo2f_table_textbox" style="width:30% !important;float:right;margin-right:20%;" name="mo2f_GA_account_name" placeholder="<?php echo mo2f_lt('Enter the name');?>" id="mo2f_GA_account_name" required="true" value="<?php echo get_site_option('mo2f_GA_account_name'); ?>"  />
						  
				
				 <br>
				</div>
				<br>
						<h3><?php echo mo2f_lt(' Add Privacy Policy to your site');?></h3>
                 <hr>
                        <p style="margin-left:2%;"><?php echo mo2f_lt('Take a look at our');?> <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_privacy_policy"><?php echo mo2f_lt('Privacy policy');?></a > <?php echo mo2f_lt('so that you can add this into your Company Policy making it gdpr compliant.');?></p>

                         <input type="checkbox" id="mo2f_enable_gdpr_policy" style="margin-left:4%;" name="mo2f_enable_gdpr_policy" value="1" <?php checked( get_site_option('mo2f_enable_gdpr_policy') == 1 );
                                if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo mo2f_lt('Enable the option to ask for permission from users.');?><br /><br>

                        <div style="margin-left: 2%;" ><b><?php echo mo2f_lt('Enter the Link To Your Privacy Policy:');?></b>
			              <input type="text" class="mo2f_table_textbox" style="width:50% !important;" name="mo2f_privacy_policy_link" value="<?php echo get_site_option('mo2f_privacy_policy_link'); ?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
			            </div>

                <br>
				
				<h3>XML-RPC <?php echo __('Settings','miniorange-2-factor-authentication');?></h3>
				<hr></br><div style="margin-left: 2%;">
				<?php echo __('Enabling this option will decrease your overall login security. Users will be able to login through external applications which support XML-RPC without authenticating from miniOrange. ','miniorange-2-factor-authentication');?><b><?php echo __('Please keep it unchecked.','miniorange-2-factor-authentication');?> </b> <br /><br />
				
				<input type="checkbox" id="mo2f_enable_xmlrpc" name="mo2f_enable_xmlrpc" style="margin-left: 2%;" value="1" <?php checked( get_site_option('mo2f_enable_xmlrpc') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				<?php echo __('Enable XML-RPC Login.','miniorange-2-factor-authentication');?>
				</div>
				<br /><br />
				
				<h3><?php echo __('Enable Two-Factor plugin','miniorange-2-factor-authentication');?></h3>
				<hr>
				 <br>
				 <input type="checkbox" id="mo2f_activate_plugin" name="mo2f_activate_plugin" style="margin-left: 2%;font-style:Italic;padding:2%" value="1" <?php checked( get_site_option('mo2f_activate_plugin') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				  <?php echo __('Enable Two-Factor plugin. ','miniorange-2-factor-authentication');?><span style="color:red;">( If you disable this checkbox, Two-Factor plugin will not invoke for any user  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; during login.)</span><br />
				 <br /><div class="mo2f_advanced_options_note" style="margin-left: 2%;font-style:Italic;padding:2%"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('Disabling this option will allow all users to login with their username and password.Two-Factor will not invoke during login.','miniorange-2-factor-authentication');?></div>
				<br /><br />
				<br>
				
				<br>
				<input type="submit" name="submit" value="<?php echo __('Save Settings','miniorange-2-factor-authentication');?>" class="button button-primary button-large" <?php 
				if(mo2f_is_customer_registered()){ } else{ echo 'disabled' ; } ?> />
				<br /><br />
			</form>
			<script>	
				if(jQuery("input[name=mo2f_login_policy]:radio:checked").val() == 0){
					jQuery('#loginphonediv').show();
				}
				if(jQuery("input[name=mo2f_enable_gauth_name]:radio:checked").val() == 0){
                jQuery('#mo2f_gauth_name').show();
                jQuery("input[name=mo2f_GA_account_name]").prop('required',true);
				}else{
				jQuery("input[name=mo2f_GA_account_name]").prop('required',false);
				}
				
				jQuery("input[name=mo2f_enable_gauth_name]:radio").change(function () {
				
				 if (this.value == 1) {
                    jQuery("input[name=mo2f_GA_account_name]").prop('required',false);
                    jQuery('#mo2f_gauth_name').hide();
                } else {
                    jQuery('#mo2f_gauth_name').show();
                    jQuery("input[name=mo2f_GA_account_name]").prop('required',true);

                }
				
				});
				jQuery("input[name=mo2f_login_policy]:radio").change(function () {
					if (this.value == 1) {
						jQuery('#loginphonediv').hide();
					}else{
						jQuery('#loginphonediv').show();
					}
				});
				
				jQuery('#showpreview1').on('click', function() {
				  if ( jQuery("#preview1").is(":visible") ) { 
                      jQuery('#preview1').hide();
				  } else if ( jQuery("#preview1").is(":hidden") ) { 
					  jQuery('#preview1').show();
				  }
				});
				
				jQuery('#showpreview2').on('click', function() {
				  if ( jQuery("#preview2").is(":visible") ) { 
                      jQuery('#preview2').hide();
				  } else if ( jQuery("#preview2").is(":hidden") ) { 
					  jQuery('#preview2').show();
				  }
				});
				
				<?php  
				if( isset( $_REQUEST['true'] ) && get_option( 'mo2f_msg_counter') == 1 ){ 
				$logouturl= wp_login_url() . '?action=logout';
				
				?>
				jQuery("#messages").append("<div class='updated notice is-dismissible mo2f_success_container'> <p class='mo2f_msgs'><?php echo __('If you are OK with default settings. ','miniorange-2-factor-authentication');?><a href=<?php echo  $logouturl; ?>><b><?php echo __('Click Here','miniorange-2-factor-authentication');?> </b> </a> <?php echo __('to logout and try login with 2-Factor.','miniorange-2-factor-authentication');?></p></div>");
				<?php } ?>
				
			</script>
		</div>

	<?php
	}

	function show_2_factor_privacy_policy($current_user){
		?>
		<div class="mo2f_table_layout">
		
		<h2>miniOrange Privacy Policy</h2><hr>
		
		<p>miniOrange("Us," "We," "Our," "miniOrange," or the "Company")  is committed to protecting the privacy of your information while you use our WordPress miniOrange Two Factor Plugin. We’ve crafted the policy 
		below to help you understand how our plugin collects and uses personally identifiable information.</p>
		
		<h3><?php echo mo2f_lt('Definitions:');?></h3> 
		<ol>
			
			<li><b>miniOrange Two Factor Plugin</b>: This refers to Google Authenticator – Two Factor Authentication (2FA) WordPress plugin.</li>
			<li><b>Customer Support Services</b>: It involves screen sharing sessions, meetings and support by mail.</li>
			<li><b>miniOrange Servers</b>: This refers to miniOrange service which is stored on secure cloud service AWS. miniOrange Users data is also stored with AWS.</li> 
			<li><b>Third Party</b>: This refers to customer using miniOrange services i.e. plugin to provide Two Factor verification to its users.</li>
			<li><b>Personal data</b>: This refers to information provided by you such as  name, company name, address, phone number, email address, and any other information necessary.</li>
		</ol>
		
		<h3><?php echo mo2f_lt('Introduction:');?></h3> 

		<p>We protect your personal information using industry ­standard safeguards. We may share your information only with your consent or as required by law as detailed in this policy, and we will always let you know when we make significant changes to this Privacy Policy. Maintaining your trust is our top priority, so we adhere to the following principles to protect your privacy:</p>

		We protect your personal information and will only provide it to third parties: (1) with your consent; (2) where it is necessary to carry out your instructions; (3) as reasonably necessary in order to provide our features and functionality to you; (4) when we reasonably believe it is required by law, subpoena or other legal process; or (5) as necessary to enforce our User Agreement or protect the rights, property, or safety of miniOrange, its Customers and Users, and the public.

		<h3><?php echo mo2f_lt('What Personal Data do we collect?');?></h3>

		<p>miniOrange collects data provided by you while registering with miniOrange or through any other service while contacting miniOrange. We also collect data needed to provide miniOrange services.  This data may contain different information as listed below:</p>

		<ol>
		<li>
		When you register in the plugin, you provide us with information (including your name(optional), email address, phone number(optional), company name/website and password) that we use to offer you a personalized, relevant experience on miniOrange.</li>

		<li>When User contacts our Customer Support, User’s personal data is shared which is necessary for us to provide support where we can assist you with the plugin configurations, setup or any other issues while using miniorange Two Factor Plugin. The Personal Data you provided is used for purposes like answering questions, improving the content of the website, customizing the content, and communicating with the customers about miniOrange’s Services, including specials and new features.</li>

		<li>While using miniOrange Risk Based Authentication (RBA) services, device information is collected. The information collected for RBA is mentioned below under data used by the plugin.</li>

		<li>When you contact us using our support form, we collect information that helps us categorize your question, respond to it, and, if applicable, investigate any breach of our User Agreement or this Privacy Policy. We also use this information to track potential problems and trends and customize our support responses to better serve you.</li>

		<li>We do not collect email address from miniOrange production service for marketing use.</li> 
		</ol>
		<br>
		<div>
		<p>In the miniOrange Two Factor plugin, we collect the following information from users : </p>
			<div style="margin-left:2%">
			<b >Customer Details</b>: First name, last name, username, email, Questions and Answers of Security Questions, Phone Number, Company Name.
			 <br>
			<b >Device Information</b>: Location, Browser details, IP Address, Device Type, Time, Language, Useragent details, Device fingerprint
				<br><br> 
			</div>
		</div>
		
		<p>miniOrange only uses your data in order to provide you with the service and keeps this data available 
		only to the user that has provided the information or the third parties that the user has agreed to grant access to.The data is also provided to respective Authenticator Application owner which is required for verification during login.
        </p>
		<h3><?php echo mo2f_lt('How we use personal information?');?></h3>

		<p>miniOrange Two factor Plugin has various authentication methods and different methods require different information. Your Phone number and Email are used to send One Time Passcode. Your email is also used as a primary medium of contact only in case you need any help from us. Risk based Authentication uses information like device type, location, Ip address, time and other to identify the user and grant access based on the risk.
		</p>	
		<p>All data provided are stored with miniOrange which can be accessed through our site https://auth.miniorange.com where the user's account is created while using the miniOrange Two Factor Plugin.   
		</p>
		<h3><?php echo mo2f_lt('Personal Data Processing Duration:');?></h3>

		Personal data will only be processed until we have a legitimate business. When we have no legitimate business, your data is stored securely until deletion. During this period, if you request for data stored with miniOrange, you would have to give sufficient evidence of identity before we can provide you with this information. You can request this by contacting us at <a href="mailto:info@miniorange.com">info@miniorange.com</a>. Same would apply if you request for deletion of the personal data.
		<ol>
		<li><b>User consent</b>: End Users will be asked for consent if they agree to the terms and conditions of your website. If they deny consent, they will not be logged in and no data will be fetched. </li>

		<li><b>Encryption</b>: All data that is in transit because of miniOrange is encrypted in the miniOrange Two Factor plugin. </li>
		</ol>
		
		<h3><?php echo mo2f_lt('What are your rights:');?></h3>
		
		<ol>
		<li><b>Right to be forgotten</b>: Information collected in stored in two places -  Wordpress Database and miniOrange Servers. Customer can delete end-user’s information if end-user requests. </li>

		
		<li><b>Right to object</b>: In certain situations, end user has the right to object to the data being processed in so far as such data have been collected for direct marketing purposes.</li>

		<li><b>Right to rectification</b>: You have a right for clarification of inaccurate personal data. And change the data by providing complete information.</li>

		<li><b>Right of access</b>: You have the right to obtain from us information concerning i.e. you have the right to request and get access to that personal data.</li>
		</ol>
		
		<br>
		<b>Note</b>: Adding the policy will only make plugin features GDPR Compliant and not your complete site. 
		<br>
		<p>If you have any query regarding the policy, Please drop us a mail at <a href="mailto:info@miniorange.com">info@miniorange.com</a>.</p>
		
		</div>
		<?php
	}
	
	function show_2_factor_proxy_setup($user){
	global $dbQueries;
	?>

	<div class="mo2f_table_layout">
	<br>
        <div class="mo2f_proxy_setup">
            <h3><?php echo mo2f_lt('Proxy Settings');?></h3>


            <hr><br>
            <div style="float:right;">
                <form name="f" method="post" action="" id="mo2f_disable_proxy_setup_form">
                    <input type="hidden" name="option" value="mo2f_disable_proxy_setup_option"/>

                    <input type="submit" name="submit" style="float:right"
                           value="<?php echo mo2f_lt( 'Reset Proxy Settings' ); ?>"
                           class="button button-primary button-large"

                    <?php  if ( $dbQueries->get_user_detail( 'mo_2factor_user_registration_status', $user->ID ) != 'MO_2_FACTOR_PLUGIN_SETTINGS' || ! get_option('mo2f_proxy_host')) {
                    echo 'disabled';
                    } ?>
                    />

                </form>
            </div>
            <br><br>
            <form name="f" method="post" action="">
                <input type="hidden" name="option" value="mo2f_save_proxy_settings"/>
        <table class="mo2f_settings_table">
            <tr>

                <td style="width:30%"><b><span class="impt">*</span><?php echo mo2f_lt( 'Proxy Host Name: ' ); ?></b></td>
                <td style="width:70%"><input class="mo2f_table_textbox" type="text" name="proxyHost" required
                                             value="<?php echo get_option( 'mo2f_proxy_host' ); ?>"/></td>
            </tr>
            <tr>

                <td style="width:30%"><b><span class="impt">*</span><?php echo mo2f_lt( 'Port Number: ' ); ?></b></td>
                <td style="width:70%"><input class="mo2f_table_textbox" type="number" name="portNumber" required
                                             value="<?php echo get_option( 'mo2f_port_number' ); ?>"/></td>
            </tr>
            <tr>

                <td style="width:30%"><b><?php echo mo2f_lt( 'Username: ' ); ?></b></td>
                <td style="width:70%"><input class="mo2f_table_textbox" type="text" name="proxyUsername"
                                             value="<?php echo get_option( 'mo2f_proxy_username' ); ?>"/></td>
            </tr>
            <tr>

                <td style="width:30%"><b><?php echo mo2f_lt( 'Password: ' ); ?></b></td>
                <td style="width:70%"><input class="mo2f_table_textbox" type="password" name="proxyPass"
                                             value="<?php echo get_option( 'mo2f_proxy_password' ); ?>"/></td>
            </tr>

            <tr>

                <td>&nbsp;</td>
                <td><input type="submit" name="submit" style="float:right"
                           value="<?php echo mo2f_lt( 'Save Settings' ); ?>"
                           class="button button-primary button-large"
                    <?php if ( $dbQueries->get_user_detail( 'mo_2factor_user_registration_status', $user->ID ) != 'MO_2_FACTOR_PLUGIN_SETTINGS' ) {
	                    echo 'disabled';
                    } ?> /></td>
            </tr>

        </table>
    </form>
    </div>
	<br>
    </div>
<?php }
	
	function mo2f_no_license_key_required(){
		// var_dump("here");
		?>
		<form name="f" method="post" action="" id="mo2f_license_needed">
			<input type="hidden" name="option" value="mo_license" />
       <div class="mo2f_table_layout" style="min-height: 250px !important">
				<h3><?php echo mo2f_lt('Thank You for Upgrading to our Premium ');?></h3><hr>
				
                  
				<h3><?php echo mo2f_lt('Note:');?></h3><h4><?php echo mo2f_lt('No License Key Required');?></h4>
				<div>
					<p><?php echo mo2f_lt('You do not need a license key to use the 2FA Premium plugin since the premium license you have
						purchased is linked to the account you used to make the purchase. You will just need to log in to
						the premium plugin with the miniOrange account you used for the upgrade. ');?></p>
								
                    </div>
				<div>
						                               
					<button type="button" id="mo_license" class="button button-primary button-large"><?php echo mo2f_lt('I Understand');?></button><br><br>
								                    </div>
                </div>

            </div>
        </div>
		 
			</div><br><br>
			</div>
			</form>
			 <script>

        jQuery(function () {
            jQuery('#myModal').modal('toggle');
        });
		 jQuery('#mo_license').click(function () {
            jQuery('#mo2f_license_needed').submit();
        });
		 </script>
		<?php
	}
	function mo2f_show_verify_password_page() {
		
		if(!get_site_option('mo2f_no_license_needed')){
											mo2f_no_license_key_required();
										}
										else{
		?>
			<!--Verify password with miniOrange-->
			<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_auth_verify_customer" />
			<div class="mo2f_table_layout">
			<h3><?php echo __('Login with miniOrange','miniorange-2-factor-authentication');?></h3><hr>
			<div id="panel1">
			<p><b><?php echo __('It seems you already have an account with miniOrange. Please enter your miniOrange email and password.','miniorange-2-factor-authentication');?> <a target="_blank"
                            href="https://auth.miniorange.com/moas/idp/resetpassword"><b>&nbsp; <?php echo __('Click Here','miniorange-2-factor-authentication');?></b></a> <?php echo __('to reset your miniOrange password.','miniorange-2-factor-authentication');?></b></p>
			<br/>
			<table class="mo2f_settings_table">
				<tr>
				<td><b><font color="#FF0000">*</font><?php echo __('Email:','miniorange-2-factor-authentication');?> </b> </td>
				<td><input class="mo2f_table_textbox" type="email"  name="email" id="email" required placeholder="person@example.com" value="<?php echo get_site_option('mo2f_email');?>"/></td>
				</tr>
				<tr>
				<td><b><font color="#FF0000">*</font><?php echo __('Password:','miniorange-2-factor-authentication');?> </b> </td>
				 <td><input class="mo2f_table_textbox" type="password" name="password" required placeholder="<?php echo __('Enter your miniOrange password','miniorange-2-factor-authentication');?>" /></td>
				</tr>
				
				<?php if(get_site_option('mo2f_enable_gdpr_policy')){?>
				<tr>
				<td>&nbsp;</td>
				<td>
				
				<input type="checkbox" id="mo2f_gdpr" name="mo2f_gdpr"  required /><?php echo mo2f_lt('I agree to');?> <a href="https://www.miniorange.com/2-factor-authentication-for-wordpress-gdpr" target="_blank"><u><?php echo mo2f_lt('terms & conditions');?></u></a>.<br/></tr>
				<?php } ?>
				<tr><td colspan="2">&nbsp;</td></tr>

				<tr><td>&nbsp;</td><td>
				<input type="submit" name="submit" value="<?php echo __('Submit','miniorange-2-factor-authentication');?>" class="button button-primary button-large" /></td>
					
			  </tr>
			
			</table>
		
			</div><br><br>
			</div>
			
			</form>
			<form name="f" method="post" action="" id="gobackform">
					<input type="hidden" name="option" value="mo_2factor_gobackto_registration_page"/>
			</form>
			<form name="f" method="post" action="" id="forgotpasswordform">
					<input type="hidden" name="email" id="hidden_email" />
					<input type="hidden" name="option" value="mo_2factor_forgot_password"/>
			</form>
			<script>
				jQuery('#mo2f_go_back').click(function(){
					jQuery('#gobackform').submit();
				});
				jQuery('a[href="#forgot_password"]').click(function(){
					var email = jQuery('#email').val();
					jQuery('#hidden_email').val(email);
					jQuery('#forgotpasswordform').submit();
				});
			</script>
<?php	}
}
?>