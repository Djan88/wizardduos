<?php
	function mo_2_factor_register($current_user) {
		if(mo_2factor_is_curl_installed()==0){ ?>
			<p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL extension</a> is not installed or disabled)</p>
		<?php
		}
		
		
		$mo2f_active_tab = isset($_GET['mo2f_tab']) ? $_GET['mo2f_tab'] : '2factor_setup';
	
		
		?>
		
		<div id="tab">
			<h2 class="nav-tab-wrapper">
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=2factor_setup" class="nav-tab <?php echo $mo2f_active_tab == '2factor_setup' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab1">
				<?php if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_MOBILE_REGISTRATION' || get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS'){ ?>User Profile <?php }else{ ?> Account Setup <?php } ?></a> 
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure" class="nav-tab <?php echo $mo2f_active_tab == 'mobile_configure' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab3">Setup Two-Factor</a>
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_login" class="nav-tab <?php echo $mo2f_active_tab == 'mo2f_login' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab2">Login Settings</a>
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=advance_option" class="nav-tab <?php echo $mo2f_active_tab == 'advance_option' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab2">Premium Options</a>
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_pricing" class="nav-tab <?php echo $mo2f_active_tab == 'mo2f_pricing' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab6">Licensing Plans</a>
				<a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo" class="nav-tab <?php echo $mo2f_active_tab == 'mo2f_demo' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab4">How To Setup</a>
			    <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_help" class="nav-tab <?php echo $mo2f_active_tab == 'mo2f_help' ? 'nav-tab-active' : ''; ?>" id="mo2f_tab5">Help & Troubleshooting</a>
				
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
								update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
							}
						/* ----------------------------------------- */
						
							if($mo2f_active_tab == 'mobile_configure') {
								
									$mo2f_second_factor= mo2f_get_activated_second_factor($current_user);
									
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
								if(get_site_option( 'mo_2factor_admin_registration_status') == 'MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS' && get_site_option( 'mo2f_miniorange_admin') != $current_user->ID){
									if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_OTP_DELIVERED_SUCCESS' || get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_OTP_DELIVERED_FAILURE'){
										mo2f_show_user_otp_validation_page();  // OTP over email validation page
									} else if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_MOBILE_REGISTRATION'){  //displaying user profile
										$mo2f_second_factor = mo2f_get_activated_second_factor($current_user);
										mo2f_show_instruction_to_allusers($current_user,$mo2f_second_factor);
									} else if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS'){
										$mo2f_second_factor = mo2f_get_activated_second_factor($current_user);
										mo2f_show_instruction_to_allusers($current_user,$mo2f_second_factor);  //displaying user profile	
									}else{
										show_user_welcome_page($current_user);  //Landing page for additional admin for registration
									}
								}
								else{
								
									if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_OTP_DELIVERED_SUCCESS' || get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_OTP_DELIVERED_FAILURE'){
										mo2f_show_otp_validation_page($current_user);  // OTP over email validation page for admin
									} else if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_MOBILE_REGISTRATION'){  //displaying user profile
										$mo2f_second_factor = mo2f_get_activated_second_factor($current_user);
										mo2f_show_instruction_to_allusers($current_user,$mo2f_second_factor);
									}else if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_VERIFY_CUSTOMER') {
										mo2f_show_verify_password_page();  //verify password page
									}else if(!mo2f_is_customer_registered()){
										delete_site_option('password_mismatch');
										mo2f_show_new_registration_page($current_user); //new registration page
									}else if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS'){
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
		?>
			<!--Register with miniOrange-->
			<form name="f" method="post" action="">
				<input type="hidden" name="option" value="mo_auth_register_customer" />
				<div class="mo2f_table_layout">
					<h3>Register with miniOrange</h3><hr>
					<div id="panel1">
						<div><b>Please enter a valid email id that you have access to. You will be able to move forward after verifying an OTP that we will be sending to this email. <a href="#mo2f_account_exist">Already registered with miniOrange?</a></b></div>
						<p class="float-right"><font color="#FF0000">*</font> Indicates Required Fields</p>
						<table class="mo2f_settings_table">
							<tr>
							<td><b><font color="#FF0000">*</font>Email :</b></td>
							<td><input class="mo2f_table_textbox" type="email" name="email" required placeholder="person@example.com" value="<?php if(get_site_option('mo2f_email')){echo get_site_option('mo2f_email');}else{echo $current_user->user_email;}?>"/></td>
							</tr>

							<tr>
							<td><b>&nbsp;&nbsp;Phone number :</b></td>
							 <td><input class="mo2f_table_textbox" style="width:100% !important;" type="text" name="phone" pattern="[\+]?([0-9]{1,4})?\s?([0-9]{7,12})?" id="phone" autofocus="true"  value="<?php echo get_user_meta($current_user->ID,'mo2f_user_phone',true);?>" />
							 This is an optional field. We will contact you only if you need support.</td>
							</tr>
							
							<tr>
							<td><b><font color="#FF0000">*</font>Password :</b></td>
							 <td><input class="mo2f_table_textbox" type="password" required name="password" placeholder="Choose your password with minimun 6 characters" /></td>
							</tr>
							<tr>
							<td><b><font color="#FF0000">*</font>Confirm Password :</b></td>
							 <td><input class="mo2f_table_textbox" type="password" required name="confirmPassword" placeholder="Confirm your password with minimum 6 characters" /></td>
							</tr>
							<tr><td>&nbsp;</td></tr>
						  <tr>
							<td>&nbsp;</td>
							<td><input type="submit" name="submit" value="Submit" class="button button-primary button-large" /></td>
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
	?>
		<!-- Enter otp -->
		
		<div class="mo2f_table_layout">
			<h3>Validate OTP</h3><hr>
			<div id="panel1">
				<table class="mo2f_settings_table">
					<form name="f" method="post" id="mo_2f_otp_form" action="">
						<input type="hidden" name="option" value="mo_2factor_validate_otp" />
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
						<input type="hidden" name="option" value="mo_2factor_gobackto_registration_page"/>
							<input type="submit" name="mo2f_goback" id="mo2f_goback" value="Back" class="button button-primary button-large" /></td>
						</form>
						</td>
						</tr>
						<form name="f" method="post" action="" id="resend_otp_form">
							<input type="hidden" name="option" value="mo_2factor_resend_otp"/>
						</form>
						
				</table>
				<br>
				<hr>

				<h3>I did not recieve any email with OTP . What should I do ?</h3>
				<form id="phone_verification" method="post" action="">
					<input type="hidden" name="option" value="mo_2factor_phone_verification" />
					 If you can't see the email from miniOrange in your mails, please check your <b>SPAM Folder</b>. If you don't see an email even in SPAM folder, verify your identity with our alternate method.
					 <br><br>
						<b>Enter your valid phone number here and verify your identity using one time passcode sent to your phone.</b>
						<br><br>
						<table>
						<tr>
						<td>
						<input class="mo2f_table_textbox" required autofocus="true" type="text" name="phone_number" id="phone" placeholder="Enter Phone Number" value="<?php echo get_user_meta( $current_user->ID,'mo2f_user_phone',true); ?>" pattern="[\+]?[0-9]{1,4}\s?[0-9]{7,12}" title="Enter phone number without any space or dashes."/>
						</td>
						<td>
						<a href="#resendsmsotplink">Resend OTP ?</a>
						</td>
						</tr>
						</table>
						<br><input type="submit" value="Send OTP" class="button button-primary button-large" />
				
				</form>
				<br>
				<h3>What is an OTP ?</h3>
				<p>OTP is a one time passcode ( a series of numbers) that is sent to your email or phone number to verify that you have access to your email account or phone. </p>
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
			
			 
			}
		return $opt;
}
	
		function miniorange_2_factor_select_method_user_roles($current_user) 
	{
		$opt=fetch_methods($current_user); 

		?>
		<h3>Select the specific set of authentication methods for your users.<?php echo'<span style="float:right;font-size: 13px;"><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_pricing"></a></span>'; ?></h3><hr>
		
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
		
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		foreach($wp_roles->role_names as $id => $name)
		{
			$copt[$id]=get_site_option('mo2f_auth_methods_for_'.$id);
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
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		print '<div> ';
		foreach($wp_roles->role_names as $id => $name) {	
				$setting = get_option('mo2fa_'.$id);
				$newcopt=$copt[$id];
				
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
				
				</tr>
				</tbody>
				</div>
				</table>
			
				
				
		<?php			
		}
	
		
		print '</div>';

	?>
	
	<script>
	function displayTab(role){
		jQuery('.mo2f_display_tab').removeClass("blue");
		jQuery('.mo2f_display_tab').addClass("btn");
		jQuery('#mo2f_role_'+role).removeClass("btn");
		jQuery('#mo2f_role_'+role).addClass("blue");
		
		
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('#mo2f_for_all_'+role).show();
	}
	jQuery(".option_for_auth").click(function(){
		jQuery('.mo2f_display_tab').hide();
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('.mo2f_for_all_users').show();
		
	})
	
	
	jQuery(".option_for_auth2").click(function(){
		jQuery('.mo2f_display_tab').show();
		jQuery('.mo2f_for_all_users').hide();
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
		
		print '<div><span style="font-size:16px;">Roles<div style="float:right;">Custom Redirect Login Url</div></span><br /><br />';
		foreach($wp_roles->role_names as $id => $name) {	
			$setting = get_site_option('mo2fa_'.$id);
		?>
			<div><input type="checkbox" name="<?php echo 'mo2fa_'.$id; ?>" value="1" <?php checked($setting == 1); if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> /><?php echo $name; ?>
			<input type="text" class="mo2f_table_textbox" style="width:50% !important;float:right;" name="<?php echo 'mo2fa_'.$id; ?>_login_url" value="<?php echo get_option('mo2fa_' .$id . '_login_url'); ?>" <?php if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
			</div> 
			
			<br />
		<?php
		}
		print '</div>';
		$url=admin_url();
		?>
		 </div>
		 <div class="mo2f_show_user_redirect" <?php if(!get_site_option('mo2f_by_roles')){}else{echo 'hidden';}?>>
		 <a href="<?php echo admin_url();?>users.php" class="">Click Here to enable 2FA for specific users</a>
		 <br><br>
		 <div id="mo2f_note"><b><?php echo __('Note:','miniorange-2-factor-authentication');?></b> <?php echo __('For <b>Select Users</b> option you will redirected to Wordpress Users Page. You can select the users and apply Bulk Actions to apply or remove Two Factor for users','miniorange-2-factor-authentication');?></div>
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
	?>
	<div class="mo2f_table_layout">
			<?php echo mo2f_check_if_registered_with_miniorange($current_user); ?>
				
			   <form name="f"  id="login_settings_form" method="post" action="">
				<input type="hidden" name="option" value="mo_auth_login_settings_save" />
				<span>
				<h3>Select Roles to enable 2-Factor
				<input type="submit" name="submit" value="Save Settings" style="float:right;" class="button button-primary button-large" <?php 
				if(mo2f_is_customer_registered() ){ } else{ echo 'disabled' ; } ?> /></h3><span>
				<hr><br>
				
				<?php  echo miniorange_2_factor_user_roles($current_user); ?>
								
				
				<div id="mo2f_note"><b>Note:</b> You can select which Two Factor methods you want to enable for your users. By default all Two Factor methods are enabled for all users of the role you have selected above.</div>
				<br>
				<h3>Invoke Inline Registration to setup 2nd factor for users.</h3><hr><br />
				
				<div>
				
				 <input type="radio" name="mo2f_inline_registration" value="1" <?php checked( get_site_option('mo2f_inline_registration') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 Enforce 2 Factor registration for users at login time.&nbsp;&nbsp;
				 <input type="radio" name="mo2f_inline_registration" value="0" <?php checked( get_site_option('mo2f_inline_registration') == 0 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 Skip 2 Factor registration at login.
				 <br><br>
				 <div id="mo2f_note"><b>Note:</b> If this option is enabled then users have to setup their two-factor account forcefully during their login. By selecting second option, you will provide your users to skip their two-factor setup during login.
				 
				 </div>
				</div>
				 <br />
				 
				 <h3>Email verification of Users during Inline Registration </h3><hr><br />
				<div>
				
				 <input type="radio" name="mo2f_enable_emailchange" value="1" <?php checked( get_site_option('mo2f_enable_emailchange') == 1 ); if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				  Enable users to <b>edit their email address</b> for registration with miniOrange.<br><br>
				 <input type="radio" name="mo2f_enable_emailchange" value="0" <?php checked( get_site_option('mo2f_enable_emailchange') == 0 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 Skip e-mail verification by user.
				 <br><br>
				 <div id="mo2f_note"><b>Note:</b> If this option is enabled then users can edit their email during inline registration with miniOrange, and they will be prompted for e-mail verification. By selecting second option, the user will be silently registered with miniOrange without the need of e-mail verification.</div>
				</div>
				</br> 
				 <h3>Mobile Support</h3><hr></br>
				 <input type="checkbox" name="mo2f_enable_mobile_support" value="1" <?php checked( get_site_option('mo2f_enable_mobile_support') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 Enable Mobile Support for users.<br /><br />
				 <div id="mo2f_note"><b>Note:</b> If this option is enabled then Security Questions (KBA) will be invoked as 2nd factor during login through mobile browsers.</div>
				 <br />				 
				  
				<h3>Select Login Screen Options</h3><hr><br>
				<input type="radio"   name="mo2f_login_policy"  value="1"
						<?php checked( get_site_option('mo2f_login_policy')); 
						if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS'){}else{ echo 'disabled';} ?> />
						Login with password + 2nd Factor <span style="color:red">(Recommended)</span>&nbsp;&nbsp;
				<br><br>
				
				<div id="mo2f_note"><b>Note:</b> By default 2nd Factor is enabled after password authentication. If you do not want to remember passwords anymore and just login with 2nd Factor, please select 2nd option.</div>
				<br>
				<input type="radio"   name="mo2f_login_policy"  value="0"
						<?php checked( !get_site_option('mo2f_login_policy')); 
						if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS'){}else{ echo 'disabled';} ?> />
						Login with 2nd Factor only <span style="color:red">(No password required.)</span> &nbsp;<a class="btn btn-link" data-toggle="collapse" id="showpreview1" href="#preview1" aria-expanded="false">See preview</a>
				<br>
				<div class="mo2f_collapse" id="preview1" style="height:300px;">
					<center><br>
					<img style="height:300px;" src="https://auth.miniorange.com/moas/images/help/login-help-1.png" >
					</center>
					
				 </div> 
			    <br><br>
				<div id="mo2f_note"><b>Note:</b> Checking this option will add login with your phone button below default login form. Click above link to see the preview.</div>
				
				<div id="loginphonediv" hidden>	<br>
				<input style="margin-left:6%;" type="checkbox" id="mo2f_loginwith_phone" name="mo2f_loginwith_phone" value="1" <?php checked( get_site_option('mo2f_show_loginwith_phone') == 1 ); 
				if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS'){}else{ echo 'disabled';} ?> /> 
				I want to hide default login form. &nbsp;<a class="btn btn-link" data-toggle="collapse" id="showpreview2" href="#preview2" aria-expanded="false">See preview</a>
				<br>
				<div class="mo2f_collapse" id="preview2" style="height:300px;">
					<center><br>
					<img style="height:300px;" src="https://auth.miniorange.com/moas/images/help/login-help-3.png" >
					</center>
				 </div> 
				<br>
				<br><div id="mo2f_note"><b>Note:</b> Checking this option will hide default login form and just show login with your phone. Click above link to see the preview.</div>
			
				 </div>
				 <br>
				 
				 <h3>What happens if my phone is lost, discharged or not with me</h3><hr>
				 <br>
				 <input type="checkbox" id="mo2f_forgotphone" name="mo2f_forgotphone" value="1" <?php checked( get_site_option('mo2f_enable_forgotphone') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 Enable Forgot Phone.
				 <span style="color:red;float:right;">( If you disable this checkbox, then users will not get this option.)</span><br />
				 <div>	
				 <p style="margin-left:4%;">Select the alternate login method in case your phone is lost, discharged or not with you.</p>
				 <input type="checkbox" id="mo2f_forgotphone" style="margin-left:4%;" name="mo2f_forgotphone_kba" value="1" <?php checked( get_site_option('mo2f_enable_forgotphone_kba') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />KBA
				 <input type="checkbox" id="mo2f_forgotphone" style="margin-left:10%;" name="mo2f_forgotphone_email" value="1" <?php checked( get_site_option('mo2f_enable_forgotphone_email') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />OTP over EMAIL<br /><br />
				 </div>
				 <div id="mo2f_note"><b>Note:</b>This option will provide you alternate way of login in case your phone is lost, discharged or not with you.</div>
				<br><br />
				
				<h3>XML-RPC Settings</h3>
				<hr></br>
				Enabling this option will decrease your overall login security. Users will be able to login through external applications which support XML-RPC without authenticating from miniOrange. <b>Please keep it unchecked.</b><br /><br />
				<input type="checkbox" id="mo2f_enable_xmlrpc" name="mo2f_enable_xmlrpc" value="1" <?php checked( get_site_option('mo2f_enable_xmlrpc') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				Enable XML-RPC Login.
				
				<br /><br />
				
				<h3>Enable Two-Factor plugin</h3>
				<hr>
				 <br>
				 <input type="checkbox" id="mo2f_activate_plugin" name="mo2f_activate_plugin" value="1" <?php checked( get_site_option('mo2f_activate_plugin') == 1 ); 
				 if(mo2f_is_customer_registered()){}else{ echo 'disabled';} ?> />
				 Enable Two-Factor plugin. <span style="color:red;">( If you disable this checkbox, Two-Factor plugin will not invoke for any user during login.)</span><br />
				 <br /><div id="mo2f_note"><b>Note:</b> Disabling this option will allow all users to login with their username and password.Two-Factor will not invoke during login.</div>
				<br>
				
				<br>
				<input type="submit" name="submit" value="Save Settings" class="button button-primary button-large" <?php 
				if(mo2f_is_customer_registered()){ } else{ echo 'disabled' ; } ?> />
				<br /><br />
			</form>
			<script>	
				if(jQuery("input[name=mo2f_login_policy]:radio:checked").val() == 0){
					jQuery('#loginphonediv').show();
				}
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
				jQuery("#messages").append("<div class='updated notice is-dismissible mo2f_success_container'> <p class='mo2f_msgs'>If you are OK with default settings. <a href=<?php echo  $logouturl; ?>><b>Click Here</b></a> to logout and try login with 2-Factor.</p></div>");
				<?php } ?>
				
			</script>
		</div>

	<?php
	}

	function mo2f_show_verify_password_page() {
		?>
			<!--Verify password with miniOrange-->
			<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_auth_verify_customer" />
			<div class="mo2f_table_layout">
			<h3>Login with miniOrange</h3><hr>
			<div id="panel1">
			<p><b>It seems you already have an account with miniOrange. Please enter your miniOrange email and password. <a href="#forgot_password">Click here if you forgot your password ?</a></b></p>
			<br/>
			<table class="mo2f_settings_table">
				<tr>
				<td><b><font color="#FF0000">*</font>Email:</b></td>
				<td><input class="mo2f_table_textbox" type="email"  name="email" id="email" required placeholder="person@example.com" value="<?php echo get_site_option('mo2f_email');?>"/></td>
				</tr>
				<tr>
				<td><b><font color="#FF0000">*</font>Password:</b></td>
				 <td><input class="mo2f_table_textbox" type="password" name="password" required placeholder="Enter your miniOrange password" /></td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td>
				<td>
				<input type="button" name="mo2f_goback" id="mo2f_go_back" value="Back" class="button button-primary button-large" />
					
				<input type="submit" name="submit" value="Submit" class="button button-primary button-large" /></td>
					
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
	?>