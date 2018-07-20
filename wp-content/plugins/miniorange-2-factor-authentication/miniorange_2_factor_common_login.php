<?php 
   function mo2f_collect_device_attributes_handler($redirect_to){
   	
   	?>
<html>
   <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <?php
         echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
         ?>
   </head>
   <body>
      <div style="text-align:center;">
         <form id="morba_loginform" method="post" >
            <h1><?php echo __('Please wait','miniorange-2-factor-authentication'); ?>...</h1>
            <img src="<?php echo plugins_url( 'includes/images/ajax-loader-login.gif' , __FILE__ );?>" />
            <?php 
               //if(get_site_option('mo2f_deviceid_enabled') || get_site_option('mo2f_enable_inline_rba')){
               if(get_site_option('mo2f_remember_device') && get_site_option('mo2f_login_policy')){	
               ?>
            <p><input type="hidden" id="miniorange_rba_attribures" name="miniorange_rba_attribures" value="" /></p>
            <?php
               echo '<script src="' . plugins_url('includes/js/rba/js/jquery-1.9.1.js', __FILE__ ) . '" ></script>';
               echo '<script src="' . plugins_url('includes/js/rba/js/jquery.flash.js', __FILE__ ) . '" ></script>';
               echo '<script src="' . plugins_url('includes/js/rba/js/ua-parser.js', __FILE__ ) . '" ></script>';
               echo '<script src="' . plugins_url('includes/js/rba/js/client.js', __FILE__ ) . '" ></script>';
               echo '<script src="' . plugins_url('includes/js/rba/js/device_attributes.js', __FILE__ ) . '" ></script>';
               echo '<script src="' . plugins_url('includes/js/rba/js/swfobject.js', __FILE__ ) . '" ></script>';
               echo '<script src="' . plugins_url('includes/js/rba/js/fontdetect.js', __FILE__ ) . '" ></script>';
               echo '<script src="' . plugins_url('includes/js/rba/js/murmurhash3.js', __FILE__ ) . '" ></script>';
               echo '<script src="' . plugins_url('includes/js/rba/js/miniorange-fp.js', __FILE__ ) . '" ></script>';
               }
               
               
               ?>	
            <input type="hidden" name="miniorange_attribute_collection_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-login-attribute-collection-nonce'); ?>" />
            <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
         </form>
      </div>
   </body>
</html>
<?php
   }
   
   function miniorange_get_user_role($current_user){
   	$current_roles = array();
   	if(is_multisite()){
   		$user_sites = get_blogs_of_user($current_user->ID);
   		foreach ($user_sites as $user_site) {
   			switch_to_blog($user_site->userblog_id);
   			$theuser = new WP_User($current_user->ID, $user_site->userblog_id);
   			$roles = $theuser->roles;
   			if(!empty($roles)){
   				$current_roles = array_merge($current_roles, $roles);
   			}
   			restore_current_blog();
   		}
   		
   		return $current_roles;
   	}else{
   		$current_roles = $current_user->roles;
   		return $current_roles;
   	}
   }
   
   
   function miniorange_check_if_2fa_enabled_for_roles($current_roles){
   	// var_dump($current_roles);exit;
   	if(empty($current_roles)){
   	    return 0;	
   	}
   	foreach( $current_roles as $value )
   	{	
   		if(get_site_option('mo2fa_'.$value))
   		{
   			return 1;
   		}
   	}
   	return 0;
   }
   
   
   
   function redirect_user_to($user, $redirect_to){	
   	$temp_url = '';
   	$current_role = '';
   	
   	if(is_multisite()){
   		$current_roles = miniorange_get_user_role($user);
   		$current_role = array_shift($current_roles);
   		
   		$blog_id = get_current_blog_id();
   		if(is_user_member_of_blog($user->ID,$blog_id)){
   			$temp_url = get_blog_option($blog_id,'mo2fa_' . $current_role . '_login_url');
   		}else{
   			$user_info = get_userdata($user->ID);
   			$temp_url = get_blog_option($user_info->primary_blog,'mo2fa_' . $current_role . '_login_url');
   		}
   	}else{
   		$roles = $user->roles;
   		$current_role = array_shift($roles);
   		$temp_url = get_option('mo2fa_' . $current_role . '_login_url');
   	}
	$current_roles = miniorange_get_user_role($user);
	$enabled = miniorange_check_if_2fa_enabled_for_roles($current_roles);
		
	if($enabled){
		$redirect_url = $temp_url ? $temp_url : $redirect_to;
	}else{
		$redirect_url = $redirect_to ? $redirect_to : $temp_url;
	}
	
   	$mo2f_redirect_url = empty($current_role) ? $redirect_to : $redirect_url;
   	$mo2f_redirect_url = isset($mo2f_redirect_url) ? $mo2f_redirect_url : site_url();
   
   	wp_redirect( $mo2f_redirect_url );	
   	
   }
   
   function mo2f_register_profile($email,$deviceKey,$mo2f_rba_status){
   	if(isset($deviceKey) && $deviceKey == 'true'){
   		
   		if($mo2f_rba_status['status'] == 'WAIT_FOR_INPUT' && $mo2f_rba_status['decision_flag']){
   			
   			$rba_profile = new Miniorange_Rba_Attributes();
   			$rba_response = json_decode($rba_profile->mo2f_register_rba_profile($email,$mo2f_rba_status['sessionUuid']),true); //register profile
   						
   			return true;
   		}else
   		{
   			
   			return false;
   		}
   	}
   	return false;
   }
   
   function mo2f_collect_attributes($email,$attributes){
   	if(get_site_option('mo2f_remember_device')&& get_site_option('mo2f_login_policy')){	
   		$rba_attributes = new Miniorange_Rba_Attributes();
   		$rba_response = json_decode($rba_attributes->mo2f_collect_attributes($email,$attributes),true); //collect rba attributes
   		// var_dump($rba_response);exit;
   		if(json_last_error() == JSON_ERROR_NONE){
   			if($rba_response['status'] == 'SUCCESS'){ //attribute are collected successfully
   				$sessionUuid = $rba_response['sessionUuid'];
   				
   				$rba_risk_response = json_decode($rba_attributes->mo2f_evaluate_risk($email,$sessionUuid),true); // evaluate the rba risk
   				
   				if(json_last_error() == JSON_ERROR_NONE){
   					if($rba_risk_response['status'] == 'SUCCESS' || $rba_risk_response['status'] == 'WAIT_FOR_INPUT'){ 
   						$mo2f_rba_status = array();
   						$mo2f_rba_status['status'] = $rba_risk_response['status'];
   						$mo2f_rba_status['sessionUuid'] = $sessionUuid;
   						$mo2f_rba_status['decision_flag'] = true;
   						return $mo2f_rba_status;
   					}else{
   						$mo2f_rba_status = array();
   						$mo2f_rba_status['status'] = $rba_risk_response['status'];
   						$mo2f_rba_status['sessionUuid'] = $sessionUuid;
   						$mo2f_rba_status['decision_flag'] = false;
   						return $mo2f_rba_status;
   					}
   				}else{
   					$mo2f_rba_status = array();
   					$mo2f_rba_status['status'] = 'JSON_EVALUATE_ERROR';
   					$mo2f_rba_status['sessionUuid'] = $sessionUuid;
   					$mo2f_rba_status['decision_flag'] = false;
   					return $mo2f_rba_status;
   				}
   			}else{
   				$mo2f_rba_status = array();
   				$mo2f_rba_status['status'] = 'ATTR_NOT_COLLECTED';
   				$mo2f_rba_status['sessionUuid'] = '';
   				$mo2f_rba_status['decision_flag'] = false;
   				return $mo2f_rba_status;
   			}
   		}else{
   			$mo2f_rba_status = array();
   			$mo2f_rba_status['status'] = 'JSON_ATTR_NOT_COLLECTED';
   			$mo2f_rba_status['sessionUuid'] = '';
   			$mo2f_rba_status['decision_flag'] = false;
   			return $mo2f_rba_status;
   		}
   	}else{
   		$mo2f_rba_status = array();
   		$mo2f_rba_status['status'] = 'RBA_NOT_ENABLED';
   		$mo2f_rba_status['sessionUuid'] = '';
   		$mo2f_rba_status['decision_flag'] = false;
   		return $mo2f_rba_status;
   	}
   }
   
   // function send_email_alert($email,$content){
		
		// // $hostname = Utilities::getHostname();
		// $hostname 	= get_site_option('mo2f_host_name') ;
		// $url = $hostname . '/moas/api/notify/send';
		// $ch = curl_init($url);
		
		// // $customer_details = Utilities::getCustomerDetails();
		// $customerKey = get_site_option('mo2f_customerKey');
		// $apiKey =  get_site_option('mo2f_api_key');
		// // $email = "mittal@miniorange.com";

		// $currentTimeInMillis= round(microtime(true) * 1000);
		// $stringToHash 		= $customerKey .  number_format($currentTimeInMillis, 0, '', '') . $apiKey;
		// $hashValue 			= hash("sha512", $stringToHash);
		// $customerKeyHeader 	= "Customer-Key: " . $customerKey;
		// $timestampHeader 	= "Timestamp: " .  number_format($currentTimeInMillis, 0, '', '');
		// $authorizationHeader= "Authorization: " . $hashValue;
		// $toEmail 			= $email;
		// $subject            = get_site_option('mo2f_users_notify_subject');
		// $site_url=site_url();
			
			// $content='<table cellpadding="25" style="margin:0px auto"><tbody><tr><td><table cellpadding="24" width="584px" style="margin:0 auto;max-width:584px;background-color:#f6f4f4;border:1px solid #a8adad">
						// <tbody><tr><td><img src="'.get_site_option('mo2f_users_notify_image').'" style="color:#5fb336;text-decoration:none;display:block;width:auto;height:auto;max-height:35px" ></td>
						// </tr></tbody></table><table cellpadding="24" style="background:#fff;border:1px solid #a8adad;width:584px;border-top:none;color:#4d4b48;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:18px">
						// <tbody><tr><td>
						// <p style="margin-top:0;margin-bottom:20px">Dear User,</p><p style="margin-top:0;margin-bottom:10px"><p style="margin-top:0;margin-bottom:10px">'.get_site_option('mo2f_users_notify_msg1').'</p></p>
						// <p style="margin-top:0;margin-bottom:10px"><p style="margin-top:0;margin-bottom:10px">'. get_site_option('mo2f_users_notify_msg2').' <a href="'.get_site_option('mo2f_users_notify_site_url').'" target="_blank">'.get_site_option('mo2f_users_notify_site_url').'</a>
						// <p style="margin-top:0;margin-bottom:15px">Thank you,<br>'. get_site_option('mo2f_users_notify_msg3').'</p><p style="margin-top:0;margin-bottom:0px;font-size:11px">Disclaimer: This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed.</p>
						// </span></td></tr></tbody></table></td></tr></tbody></table>';
			
			// $fromEmail  = get_site_option('mo2f_email');
			
		// $fields = array(
			// 'customerKey'	=> $customerKey,
			// 'sendEmail' 	=> true,
			// 'email' 		=> array(
				// 'customerKey' 	=> $customerKey,
				// 'fromEmail' 	=> $fromEmail,
				// 'bccEmail' 		=> $fromEmail,
				// 'fromName' 		=> 'miniOrange',
				// 'toEmail' 		=> $toEmail,
				// 'toName' 		=> $toEmail,
				// 'subject' 		=> $subject,
				// 'content' 		=> $content
			// ),
		// );
		// $field_string = json_encode($fields);
		
		// curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		// curl_setopt( $ch, CURLOPT_ENCODING, "" );
		// curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		// curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		// curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls

		// curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
			// $timestampHeader, $authorizationHeader));
		// curl_setopt( $ch, CURLOPT_POST, true);
		// curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
		// $content = curl_exec($ch);
        // // var_dump($field_string);
        // // var_dump($content);exit;
		// if(curl_errno($ch)){
			// return json_encode(array("status"=>'ERROR','statusMessage'=>curl_error($ch)));
		// }
		// curl_close($ch);
		// return $content;

	// }
   
   function mo2f_get_user_2ndfactor($current_user){
	   
	   global $dbQueries;
		$mobile_registration_status = $dbQueries->get_user_detail( 'mo_2factor_mobile_registration_status',$current_user->ID);
	   
   	if($mobile_registration_status == 'MO_2_FACTOR_SUCCESS'){
   		$mo2f_second_factor = 'MOBILE AUTHENTICATION';
   	}else{
   		$enduser = new Two_Factor_Setup();
		$email = $dbQueries->get_user_detail( 'mo2f_user_email',$current_user->ID);
   		$userinfo = json_decode($enduser->mo2f_get_userinfo($email),true);
		
   		if(json_last_error() == JSON_ERROR_NONE){
   			if($userinfo['status'] == 'ERROR'){
   				$mo2f_second_factor = 'NONE';
   			}else if($userinfo['status'] == 'SUCCESS'){
   				$mo2f_second_factor = $userinfo['authType'];
   			}else if($userinfo['status'] == 'FAILED'){
   				$mo2f_second_factor = 'USER_NOT_FOUND';
   			}else{
   				$mo2f_second_factor = 'NONE';
   			}
   		}else{
   			$mo2f_second_factor = 'NONE';
   		}
   	}
   	return $mo2f_second_factor;
   }
   
   function mo2f_customize_logo(){
   	
   	if(get_option('mo2f_disable_poweredby') != 1 ){
   		
   		if(get_option('mo2f_enable_custom_poweredby')==1) { ?>
<div style="float:right;" ><img alt="logo" src="<?php echo plugins_url('../../uploads/miniorange/custom.png',__FILE__); ?>" /></div>
<?php }else { ?>
<div style="float:right;" ><a target="_blank" href="http://miniorange.com/2-factor-authentication"><img alt="logo" src="<?php echo plugins_url('/includes/images/miniOrange2.png',__FILE__); ?>" /></a></div>
<?php } 
   }
   
   }
   
   function mo2f_get_forgotphone_form($login_status, $login_message, $redirect_to){
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
		 if ( get_option( 'mo2f_personalization_ui' ) ) {
		echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'includes/css/mo2f_login_popup_ui.css', __FILE__ ) . '" />';
	}
         ?>
   </head>
   <body>
      <div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
         <div class="mo2f-modal-backdrop"></div>
         <div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
            <div class="login mo_customer_validation-modal-content">
               <div class="mo2f_modal-header">
                  <h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
                    <?php echo __('How would you like to authenticate yourself?','miniorange-2-factor-authentication'); ?>
                  </h4>
               </div>
               <div class="mo2f_modal-body">
                  <?php if(get_site_option( 'mo2f_enable_forgotphone' )) {
                     if(isset($login_message) && !empty($login_message)){ ?>
                  <div  id="otpMessage">
                     <p class="mo2fa_display_message_frontend" ><?php echo $login_message; ?></p>
                  </div>
                  <?php } ?>
                  <p style="padding-left:10px;padding-right:10px;"><?php echo __('Please choose the options from below:','miniorange-2-factor-authentication'); ?></p>
                  <div style="padding-left:10px;padding-right:40px;">
                     <?php if(get_site_option( 'mo2f_enable_forgotphone_email' )) {?>
                     <input type="radio"  name="mo2f_selected_forgotphone_option"  value="OTP OVER EMAIL"  checked="checked" /><?php echo __('Send a one time passcode to my registered email', 'miniorange-2-factor-authentication');?><br /><br />
                     <?php } 
                        if(get_site_option( 'mo2f_enable_forgotphone_kba' )) {
                        ?>
                     <input type="radio"  name="mo2f_selected_forgotphone_option"  value="KBA"  /><?php echo __('Answer your Security Questions (KBA)', 'miniorange-2-factor-authentication'); ?>
                     <?php } ?>
                     <br /><br />
                     <input type="button" name="miniorange_validtae_otp" value="<?php echo __('Continue', 'miniorange-2-factor-authentication'); ?>" class="miniorange_validate_otp" onclick="mo2fselectforgotphoneoption();" />
                  </div>
                  <?php mo2f_customize_logo(); 
                     }
                     ?>
               </div>
            </div>
         </div>
      </div>
      <form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
      </form>
      <form name="f" id="mo2f_challenge_forgotphone_form" method="post" action="" style="display:none;">
         <input type="hidden" name="mo2f_selected_2factor_method" />
         <input type="hidden" name="miniorange_challenge_forgotphone_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-challenge-forgotphone-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
   </body>
   <script>
      function mologinback(){
      	jQuery('#mo2f_backto_mo_loginform').submit();
      }
      function mo2fselectforgotphoneoption(){
      	var option = jQuery('input[name=mo2f_selected_forgotphone_option]:checked').val();
      	document.getElementById("mo2f_challenge_forgotphone_form").elements[0].value = option;
      	jQuery('#mo2f_challenge_forgotphone_form').submit();
       }
   </script>
</html>
<?php }
   function mo2f_getkba_form($login_status, $login_message, $redirect_to){
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
		 if ( get_option( 'mo2f_personalization_ui' ) ) {
		echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'includes/css/mo2f_login_popup_ui.css', __FILE__ ) . '" />';
	}
         ?>
   </head>
   <body>
      <div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
         <div class="mo2f-modal-backdrop"></div>
         <div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
            <div class="login mo_customer_validation-modal-content">
               <div class="mo2f_modal-header">
                  <h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
                     <?php echo __('Validate Security Questions', 'miniorange-2-factor-authentication'); ?>
                  </h4>
               </div>
               <div class="mo2f_modal-body">
                  <div id="kbaSection" style="padding-left:10px;padding-right:10px;">
                     <div  id="otpMessage" 
						<?php if(get_option('mo2f_is_error', true)) { ?>style="background-color:#FADBD8; color:#E74C3C;?>"<?php update_option('mo2f_is_error', false);} ?>>
                        <p style="font-size:15px;"><?php echo (isset($login_message) && !empty($login_message)) ? $login_message :  __('Please answer the following questions:', 'miniorange-2-factor-authentication'); ?></p>
                     </div>
                     <form name="f" id="mo2f_submitkba_loginform" method="post" action="">
                        <div id="mo2f_kba_content">
                           <p style="font-size:15px;">
                              <?php if(isset($_SESSION['mo_2_factor_kba_questions'])){
                                 echo Mo2fConstants::langTranslate($_SESSION['mo_2_factor_kba_questions'][0]);
                                 ?><br />
                              <input class="mo2f-textbox" type="text" name="mo2f_answer_1" id="mo2f_answer_1" required="true" autofocus="true" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+-\s]{1,100}" title="<?php echo __('Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.','miniorange-2-factor-authentication'); ?>" autocomplete="off" ><br />
                              <?php
                                 echo Mo2fConstants::langTranslate($_SESSION['mo_2_factor_kba_questions'][1]);
                                 ?><br />
                              <input class="mo2f-textbox" type="text" name="mo2f_answer_2" id="mo2f_answer_2" required="true" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+-\s]{1,100}" title="<?php echo __('Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.','miniorange-2-factor-authentication'); ?>" autocomplete="off">
                              <?php 
                                 }
                                 ?>
                           </p>
                        </div>
                        <?php 
						
						
                           if(get_site_option('mo2f_remember_device') && get_site_option('mo2f_login_policy')&& get_site_option('mo2f_enable_rba_types')==1){ 	
                           ?>
                        <span style="float:left; font-size:15px;"><input style="vertical-align:text-top;" type="checkbox" name="mo2f_trust_device" id="mo2f_trust_device" /><?php echo __('Remember this device.', 'miniorange-2-factor-authentication'); ?></span>
						<br><br>
						<?php } ?>
						<a href="#mo2f_backup_option">
                               <p style="font-size:14px; font-weight:bold; color:#2980B9; "><?php echo __('Use Backup Codes', 'miniorange-2-factor-authentication');?></p>
                           </a>
                        <input type="submit" name="miniorange_kba_validate" id="miniorange_kba_validate" class="miniorange_kba_validate"  style="float:left;" value="<?php echo mo2f_lt('Validate' ); ?>" />
                        <input type="hidden" name="miniorange_kba_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-kba-nonce'); ?>" />
                        <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
                     </form>
					 </br>
                  </div>
                  <br /><br /><br />
                  <?php mo2f_customize_logo() ?>
               </div>
            </div>
         </div>
      </div>
      <form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
      </form>
	   <form name="f" id="mo2f_backup" method="post" action="" style="display:none;">
         <input type="hidden" name="miniorange_backup_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-backup-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
   </body>
   <script>
      function mologinback(){
      	jQuery('#mo2f_backto_mo_loginform').submit();
      }
	   jQuery('a[href="#mo2f_backup_option"]').click(function() {
      	jQuery('#mo2f_backup').submit();
      });
   </script>
</html>
<?php
   }
   
   function mo2f_backup_form($login_status, $login_message, $redirect_to){
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
		 if ( get_option( 'mo2f_personalization_ui' ) ) {
		echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'includes/css/mo2f_login_popup_ui.css', __FILE__ ) . '" />';
	}
         ?>
   </head>
   <body>
      <div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
         <div class="mo2f-modal-backdrop"></div>
         <div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
            <div class="login mo_customer_validation-modal-content">
               <div class="mo2f_modal-header">
                  <h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
                     <?php echo __('Validate Backup Code', 'miniorange-2-factor-authentication'); ?>
                  </h4>
               </div>
               <div class="mo2f_modal-body">
                  <div id="kbaSection" style="padding-left:10px;padding-right:10px;">
                     <div  id="otpMessage" 
						<?php if(get_option('mo2f_is_error', true)) { ?>style="background-color:#FADBD8; color:#E74C3C;?>"<?php update_option('mo2f_is_error', false);} ?>>
                        <p style="font-size:15px;"><?php echo (isset($login_message) && !empty($login_message)) ? $login_message :  __('Please answer the following questions:', 'miniorange-2-factor-authentication'); ?></p>
                     </div>
                     <form name="f" id="mo2f_submitbackup_loginform" method="post" action="">
                        <div id="mo2f_kba_content">
                           <p style="font-size:15px;">
                            
                              <input class="mo2f-textbox" type="text" name="mo2f_backup_code" id="mo2f_backup_code" required="true" autofocus="true"  title="<?php echo __('Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.','miniorange-2-factor-authentication'); ?>" autocomplete="off" ><br />
                              
                           </p>
                        </div>
                        
                        <input type="submit" name="miniorange_backup_validate" id="miniorange_backup_validate" class="miniorange_kba_validate"  style="float:left;" value="<?php echo mo2f_lt('Validate' ); ?>" />
                        <input type="hidden" name="miniorange_validate_backup_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-validate-backup-nonce'); ?>" />
                        <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
                     </form>
					 </br>
                  </div>
                  <br /><br /><br />
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
   
   function mo2f_getpush_oobemail_response($id, $login_status, $login_message, $redirect_to){
   global $dbQueries;
   $kba_registration_status = $dbQueries->get_user_detail( 'mo2f_SecurityQuestions_config_status',$id);
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
		 if ( get_option( 'mo2f_personalization_ui' ) ) {
		echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'includes/css/mo2f_login_popup_ui.css', __FILE__ ) . '" />';
	}
         ?>
   </head>
   <body>
      <div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
         <div class="mo2f-modal-backdrop"></div>
         <div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
            <div class="login mo_customer_validation-modal-content">
               <div class="mo2f_modal-header">
                  <h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
                     <?php echo __('Accept Your Transaction', 'miniorange-2-factor-authentication'); ?>
                  </h4>
               </div>
               <div class="mo2f_modal-body">
                  <?php if(isset($login_message) && !empty($login_message)){ ?>
                  <div  id="otpMessage">
                     <p class="mo2fa_display_message_frontend" ><?php echo $login_message; ?></p>
                  </div>
                  <?php } ?>
                  <div id="pushSection">
                     <div>
                        <center>
                           <p style="font-size:16px; font-weight:bold; color:#34495E; "><?php echo __('Waiting for your approval...', 'miniorange-2-factor-authentication'); ?></p>
                        </center>
                     </div>
                     <div id="showPushImage">
                        <center> 
                           <img src="<?php echo plugins_url( 'includes/images/ajax-loader-login.gif' , __FILE__ );?>" />
                        </center>
                     </div>
					 <center>
                        <a href="#showPushHelp" id="pushHelpLink">
                           <p style="font-size:16px; font-weight:bold; color:#5DADE2; "><?php echo mo2f_lt( 'See How It Works ?' ); ?></p>
                        </a>
                     </center>
                     <span style="padding-right:2%;">
                        <?php if(isset($login_status) && $login_status == 'MO_2_FACTOR_CHALLENGE_PUSH_NOTIFICATIONS'){ ?>
                        <center>
                           <?php if(get_site_option('mo2f_enable_forgotphone')){ ?>
                           <input type="button" name="miniorange_login_forgotphone" onclick="mologinforgotphone();" id="miniorange_login_forgotphone" class="miniorange_login_forgotphone" value="<?php echo __('Forgot Phone?','miniorange-2-factor-authentication'); ?>" />
                           <?php } ?>
						   &emsp;&emsp;
                           <input type="button" name="miniorange_login_offline" onclick="mologinoffline();" id="miniorange_login_offline" class="miniorange_login_offline" value="<?php echo __('Phone is Offline?','miniorange-2-factor-authentication'); ?>" />
                        </center>
                        <?php }else if(isset($login_status) && $login_status == 'MO_2_FACTOR_CHALLENGE_OOB_EMAIL' && get_site_option('mo2f_enable_forgotphone') && get_site_option('mo2f_enable_forgotphone_kba') && $kba_registration_status){ ?>
                        <center>
                           <a href="#mo2f_alternate_login_kba"  style="text-align:center;">
                               <p style="font-size:14px; font-weight:bold; color:#2980B9; "><?php echo __('Didn\'t receive mail?', 'miniorange-2-factor-authentication');?></p>
                           </a>
                        </center>
                        <?php }?>

						    <a style="text-align:center;" href="#mo2f_backup_option" >
                               <p style="font-size:14px; font-weight:bold; color:#2980B9; "><?php echo __('Use Backup Codes', 'miniorange-2-factor-authentication');?></p>
                           </a>

                     </span>
                  </div>
                  <div id="showPushHelp" class="showPushHelp" hidden>
                        <center>
                           <div id="myCarousel" class="mo2f_carousel slide" data-ride="carousel"  data-interval="15000" >
                              <ol class="mo2f_carousel-indicators">
                                 <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                 <li data-target="#myCarousel" data-slide-to="1"></li>
                                 <li data-target="#myCarousel" data-slide-to="2"></li>
                              </ol>
                              <div class="mo2f_carousel-inner" role="listbox">
                                 <?php  if($login_status == 'MO_2_FACTOR_CHALLENGE_OOB_EMAIL') { ?>
                                 <div class="item active">
                                 <p><b><?php echo __('A verification email has been sent to your registered email id.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                    <br>
									<img class="first-slide" src="https://auth.miniorange.com/moas/images/help/email-with-link-login-flow-1.png" alt="First slide" style="width:80%">
                                 </div>
                                 <div class="item">
                                 <p><b><?php echo __('Click on','miniorange-2-factor-authentication'); ?> <b style="color:red"><?php echo __('Accept Transaction', 'miniorange-2-factor-authentication');?></b> <?php echo __('link to verify your email.', 'miniorange-2-factor-authentication');?> </b> </p>
                                 <br>
                                    <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/email-with-link-login-flow-2.png" alt="First slide"style=" width:100%">
                              </div>
                              <div class="item">
                                 <p><b><?php echo __('You have been validated. You will be logged in to your website now.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                    <br>
									<img class="first-slide" src="https://auth.miniorange.com/moas/images/help/email-with-link-login-flow-3.png" alt="First slide" style=" width:100%">
                              </div>
                              <?php } else {	?>
                              <!-- Indicators -->
                              <div class="item active">
                                 <p><b><?php echo __('You will receive a notification on your phone.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                 <br>
                                    <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/push-login-flow.png" alt="First slide" style="width:80%">
                              </div>
                              <div class="item">
                                 <p><b><?php echo __('Click on', 'miniorange-2-factor-authentication'); ?> <b style="color:red"><?php echo __('Approve', 'miniorange-2-factor-authentication')?></b> <?php echo __('button.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                 <br>
                                    <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/push-login-flow-1.jpg" alt="First slide" style="width:100%">
                              </div>
                              <div class="item">
                                 <p><b><?php echo __('You are successfully authenticated.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                    <br>
									<img class="first-slide" src="https://auth.miniorange.com/moas/images/help/mo2f_softtoken_5.jpg" alt="First slide" style="width:100%">
                              </div>
                              <?php } ?>
                           </div>
                        </div>
                     </center>
                  </div>
                  <?php mo2f_customize_logo() ?>
               </div>
            </div>
         </div>
      </div>
      <form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
      </form>
      <form name="f" id="mo2f_mobile_validation_form" method="post" action="" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <form name="f" id="mo2f_show_softtoken_loginform" method="post" action="" style="display:none;">
         <input type="hidden" name="miniorange_softtoken" value="<?php echo wp_create_nonce('miniorange-2-factor-softtoken'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <form name="f" id="mo2f_show_forgotphone_loginform" method="post" action="" style="display:none;">
         <input type="hidden" name="request_origin_method" value="<?php echo $login_status; ?>" />
         <input type="hidden" name="miniorange_forgotphone" value="<?php echo wp_create_nonce('miniorange-2-factor-forgotphone'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <form name="f" id="mo2f_alternate_login_kbaform" method="post" action="" style="display:none;">
         <input type="hidden" name="miniorange_alternate_login_kba_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-alternate-login-kba-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form> 
	  <form name="f" id="mo2f_backup" method="post" action="" style="display:none;">
         <input type="hidden" name="miniorange_backup_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-backup-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
   </body>
   <script>
      var timeout;
      pollPushValidation();
      function pollPushValidation()
      {	
      	var transId = "<?php echo $_SESSION[ 'mo2f-login-transactionId' ];  ?>";
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
      				jQuery('#mo2f_mobile_validation_form').submit();
      			} else if (status == 'ERROR' || status == 'FAILED' || status == 'DENIED') {
      				jQuery('#mo2f_backto_mo_loginform').submit();
      			} else {
      				timeout = setTimeout(pollPushValidation, 3000);
      			}
      		}
      	});
      }
      jQuery('#myCarousel').carousel('pause');
      jQuery('#pushHelpLink').click(function() {
      	jQuery('#showPushHelp').show();
      	jQuery('#pushSection').hide();
      	jQuery('#otpMessage').hide();
      	jQuery('#myCarousel').carousel(0); 
      });
      jQuery('#pushLink').click(function() {
      	jQuery('#showPushHelp').hide();
      	jQuery('#pushSection').show();
      	jQuery('#otpMessage').show();
      	jQuery('#myCarousel').carousel('pause');
      });
      function mologinback(){
      	
      	jQuery('#mo2f_backto_mo_loginform').submit();
      }
      function mologinoffline(){
      	jQuery('#mo2f_show_softtoken_loginform').submit();
      }
      function mologinforgotphone(){
      	jQuery('#mo2f_show_forgotphone_loginform').submit();
      }
      jQuery('a[href="#mo2f_alternate_login_kba"]').click(function() {
      	jQuery('#mo2f_alternate_login_kbaform').submit();
      });
	  jQuery('a[href="#mo2f_backup_option"]').click(function() {
      	jQuery('#mo2f_backup').submit();
      });
      
   </script>
</html>
<?php 
   }

function mo2f_lt( $string ) {
    return __($string ,'miniorange-2-factor-authentication' );

}
   	
   function mo2f_getqrcode($login_status, $login_message, $redirect_to){
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
		 if ( get_option( 'mo2f_personalization_ui' ) ) {
		echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'includes/css/mo2f_login_popup_ui.css', __FILE__ ) . '" />';
	}
         ?>
   </head>
   <body>
      <div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
         <div class="mo2f-modal-backdrop"></div>
         <div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
            <div class="login mo_customer_validation-modal-content">
               <div class="mo2f_modal-header">
                  <h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
                     <?php echo __('Scan QR Code', 'miniorange-2-factor-authentication'); ?>
                  </h4>
               </div>
               <div class="mo2f_modal-body center">
                  <?php if(isset($login_message) && !empty($login_message)){ ?>
                  <div id="otpMessage">
                     <p class="mo2fa_display_message_frontend" style="text-align: left !important;"  ><?php echo $login_message; ?></p>
                  </div>
                  <br />
                  <?php } ?>
                  <div id="scanQRSection">
                        <center>
                           <a href="#showQRHelp" id="helpLink">
                              <p style="font-size:16px; font-weight:bold; color:#2980B9; "><?php echo __('See How It Works ?', 'miniorange-2-factor-authentication'); ?></p>
                           </a>
                        </center>
                        <div style="margin-bottom:10%;">
                           <center>
                              <p style="font-size:16px; font-weight:bold; font-color:#2980B9"><?php echo __('Identify yourself by scanning the QR code with miniOrange Authenticator app.', 'miniorange-2-factor-authentication'); ?></p>
                           </center>
                        </div>
                        <div id="showQrCode" style="margin-bottom:10%;">
                           <center><?php echo '<img src="data:image/jpg;base64,' . $_SESSION[ 'mo2f-login-qrCode' ] . '" />'; ?></center>
                        </div>
                        <span style="padding-right:2%;">
                           <center>
                              <?php if(get_option('mo2f_enable_forgotphone')){ ?>
                              <input type="button" name="miniorange_login_forgotphone" onclick="mologinforgotphone();" id="miniorange_login_forgotphone" class="miniorange_login_forgotphone" style="margin-right:5%;" value="<?php echo mo2f_lt('Forgot Phone?' ); ?>" />
                              <?php } ?>
							  &emsp;&emsp;
                              <input type="button" name="miniorange_login_offline" onclick="mologinoffline();" id="miniorange_login_offline" class="miniorange_login_offline" value="<?php echo mo2f_lt('Phone is Offline?' ); ?>" />
                           </center>
                        </span>
                     </div>
                   <a href="#mo2f_backup_option" style="text-align:center;">
                       <p style="font-size:14px; font-weight:bold; color:#2980B9; "><?php echo __('Use Backup Codes', 'miniorange-2-factor-authentication');?></p>
                   </a>
                     <div id="showQRHelp" class="showQRHelp" hidden>
                        <center>
                           <div id="myCarousel" class="mo2f_carousel slide" data-ride="carousel"  data-interval="15000" >
                              <!-- Indicators -->
                              <ol class="mo2f_carousel-indicators">
                                 <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                 <li data-target="#myCarousel" data-slide-to="1"></li>
                                 <li data-target="#myCarousel" data-slide-to="2"></li>
                              </ol>
                              <div class="mo2f_carousel-inner" role="listbox">
                                 <div class="item active">
                                    <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/qr-how-to-setup-1.png" alt="First slide"style="width:80%; height:100%;">
                                 </div>
                                 <div class="item">
                                 <p><b><?php echo __('Open miniOrange', 'miniorange-2-factor-authentication'); ?> <b style="color:red"><?php echo __('Authenticator', 'miniorange-2-factor-authentication'); ?></b> <?php echo __('app and click on SCAN QR CODE.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                    <br>
                                    <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/mo2f_softtoken_5.jpg" alt="First slide"style="width:100%">
                                 </div>
                                 <div class="item">
                                 <p><b><?php echo __('Scan the QR code from the app.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                    <br>
                                    <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/qr-help-3.jpg" alt="First slide"
									style="width:100%">
                                 </div>
                                 
                              </div>
                           </div>
						   <a href="#showQRHelp" id="qrLink">
                              <p style="font-size:14px; font-weight:bold; color:#2980B9; "><?php echo mo2f_lt('Back to Scan QR Code.' ); ?></p>
                           </a>
                        </center>
                     </div>

                  <?php mo2f_customize_logo() ?>
               </div>
            </div>
         </div>
      </div>
      <form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
      </form>
      <form name="f" id="mo2f_backup" method="post" action="" style="display:none;">
          <input type="hidden" name="miniorange_backup_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-backup-nonce'); ?>" />
          <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <form name="f" id="mo2f_mobile_validation_form" method="post" action="" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <form name="f" id="mo2f_show_softtoken_loginform" method="post" action="" style="display:none;">
         <input type="hidden" name="miniorange_softtoken" value="<?php echo wp_create_nonce('miniorange-2-factor-softtoken'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <form name="f" id="mo2f_show_forgotphone_loginform" method="post" action="" style="display:none;">
         <input type="hidden" name="request_origin_method" value="<?php echo $login_status; ?>" />
         <input type="hidden" name="miniorange_forgotphone" value="<?php echo wp_create_nonce('miniorange-2-factor-forgotphone'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
   </body>
   <script>
      var timeout;
      pollMobileValidation();
      function pollMobileValidation()
      {
      	var transId = "<?php echo $_SESSION[ 'mo2f-login-transactionId' ];  ?>";
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
      				var content = "<div id='success'><center><img src='" + "<?php echo plugins_url( 'includes/images/right.png' , __FILE__ );?>" + "' /></center></div>";
      				jQuery("#showQrCode").empty();
      				jQuery("#showQrCode").append(content);
      				setTimeout(function(){jQuery("#mo2f_mobile_validation_form").submit();}, 100);
      			} else if (status == 'ERROR' || status == 'FAILED') {
      				var content = "<div id='error'><center><img src='" + "<?php echo plugins_url( 'includes/images/wrong.png' , __FILE__ );?>" + "' /></center></div>";
      				jQuery("#showQrCode").empty();
      				jQuery("#showQrCode").append(content);
      				setTimeout(function(){jQuery('#mo2f_backto_mo_loginform').submit();}, 1000);
      			} else {
      				timeout = setTimeout(pollMobileValidation, 3000);
      			}
      		}
      	});
      }
      jQuery('#myCarousel').carousel('pause');
      jQuery('#helpLink').click(function() {
      	jQuery('#showQRHelp').show();
      	jQuery('#scanQRSection').hide();
      	
      	jQuery('#myCarousel').carousel(0); 
      });
      jQuery('#qrLink').click(function() {
      	jQuery('#showQRHelp').hide();
      	jQuery('#scanQRSection').show();
      	jQuery('#myCarousel').carousel('pause');
      });
      jQuery('a[href="#mo2f_backup_option"]').click(function() {
          // alert("here");
          jQuery('#mo2f_backup').submit();
      });
      function mologinback(){
      	jQuery('#mo2f_backto_mo_loginform').submit();
       }
       function mologinoffline(){
      	jQuery('#mo2f_show_softtoken_loginform').submit();
       }
       function mologinforgotphone(){
      	jQuery('#mo2f_show_forgotphone_loginform').submit();
       }
   </script>
</html>
<?php 
   }
   
   function mo2f_getotp_form($id,$login_status, $login_message, $redirect_to){
	 global $dbQueries;
	     $kba_registration_status = $dbQueries->get_user_detail( 'mo2f_SecurityQuestions_config_status',$id);
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
		 if ( get_option( 'mo2f_personalization_ui' ) ) {
		echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'includes/css/mo2f_login_popup_ui.css', __FILE__ ) . '" />';
	}
         ?>
   </head>
   <body>
      <div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
         <div class="mo2f-modal-backdrop"></div>
         <div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
            <div class="login mo_customer_validation-modal-content">
               <div class="mo2f_modal-header">
                  <h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
                     <?php echo __('Validate OTP', 'miniorange-2-factor-authentication'); ?>
                  </h4>
               </div>
               <div class="mo2f_modal-body center">
                  <?php if(isset($login_message) && !empty($login_message)){ ?>
                  <div  id="otpMessage" 
					<?php if(get_option('mo2f_is_error', true)) { ?>style="background-color:#FADBD8; color:#E74C3C;?>"<?php update_option('mo2f_is_error', false);} ?>
								>
                     <p class="mo2fa_display_message_frontend" style="text-align: left !important;"  ><?php echo $login_message; ?></p>
                  </div>
				  <?php if(isset($login_message)) {?> <br/> <?php } 
				  } ?>
                  <br />
                  <div id="showOTP">
                     <div class="mo2f-login-container">
                        <form name="f" id="mo2f_submitotp_loginform" method="post" action=""> 
							  <center> 
								 <input type="text" name="mo2fa_softtoken" style="height:28px !important;" placeholder="<?php echo __('Enter one time passcode','miniorange-2-factor-authentication'); ?>" id="mo2fa_softtoken" required="true" class="mo_otp_token" autofocus="true" pattern="[0-9]{4,8}" title="Only digits within range 4-8 are allowed."/>
						   	  </center>
							  
                              <br />
                              <input type="submit" name="miniorange_otp_token_submit" id="miniorange_otp_token_submit" class="miniorange_otp_token_submit"  value="<?php echo __('Validate','miniorange-2-factor-authentication'); ?>" />
                              <input type="hidden" name="request_origin_method" value="<?php echo $login_status; ?>" />
                           <input type="hidden" name="miniorange_soft_token_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-soft-token-nonce'); ?>" />
                           <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
                           </form><br/>
						<br/>
                        <?php if(get_option('mo2f_enable_forgotphone') && isset($login_status ) && $login_status != 'MO_2_FACTOR_CHALLENGE_OTP_OVER_EMAIL'){ ?>
                        <a name="miniorange_login_forgotphone"  onclick="mologinforgotphone();" id="miniorange_login_forgotphone" class="mo2f-link"   ><?php echo __('Forgot Phone ?', 'miniorange-2-factor-authentication'); ?></a>
                        <?php }?>

						<a href="#mo2f_backup_option" style="text-align:center;">
                               <p style="font-size:14px; font-weight:bold; color:#2980B9; "><?php echo __('Use Backup Codes', 'miniorange-2-factor-authentication');?></p>
                           </a>	<?php		
						if(isset($login_status) && $login_status == 'MO_2_FACTOR_CHALLENGE_OTP_OVER_EMAIL' && get_site_option('mo2f_enable_forgotphone') && get_site_option('mo2f_enable_forgotphone_kba') && $kba_registration_status){ ?>
                        <center>
                           <a href="#mo2f_alternate_login_kba" >
                               <p style="font-size:14px; font-weight:bold; color:#2980B9; "><?php echo mo2f_lt('Didn\'t receive mail?');?></p>
                            </a>
						<?php } ?>
						&emsp;&emsp;
						  
						<?php if($login_status != 'MO_2_FACTOR_CHALLENGE_GOOGLE_AUTHENTICATION'){  ?>
                            <a href="#showOTPHelp" id="otpHelpLink" class="mo2f-link"><?php echo mo2f_lt('See how it works ?');?></a>
                        <?php } ?>
						<br><br>
                     </div>
                  </div>
                  <div id="showOTPHelp" class="showOTPHelp" hidden>
                        <center>
                        <div id="myCarousel" class="mo2f_carousel slide" data-ride="carousel"  data-interval="15000" >
                           <!-- Indicators -->
                           <?php if($login_status == 'MO_2_FACTOR_CHALLENGE_SOFT_TOKEN'){ ?>
                           <ol class="mo2f_carousel-indicators">
                              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                              <li data-target="#myCarousel" data-slide-to="1"></li>
                              <li data-target="#myCarousel" data-slide-to="2"></li>
                              <li data-target="#myCarousel" data-slide-to="3"></li>
                           </ol>
                           <div class="mo2f_carousel-inner" role="listbox">
                              <div class="item active">
                              <p><b><?php echo __('Open miniOrange', 'miniorange-2-factor-authentication'); ?> <b style="color:red"><?php echo __('Authenticator', 'miniorange-2-factor-authentication'); ?></b> <?php echo __('app and click on Sync time from the top left menu option.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                 <br>
                                 <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/qr-help-2.jpg" alt="First slide" style="width:100%">
                              </div>
                              <div class="item">
                              <p><b><?php echo __('Click on','miniorange-2-factor-authentication'); ?> <b style="color:red"><?php echo __('Sync Time now','miniorange-2-factor-authentication');?></b> <?php echo __('to sync your time with miniOrange Servers. This is a one time sync to avoid otp validation failure.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                 <br>
                                 <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/token-help-3.jpg" alt="First slide"
								 style="width:100%">
                              </div>
                              <div class="item">
                              <p><?php echo __('Go to Home', 'miniorange-2-factor-authentication'); ?></p>
                                 <br>
                                 <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/token-help-2.jpg" alt="First slide"
								 style="width:100%">
                              </div>
                              <div class="item">
                              <p><b><?php echo __('Enter the one time passcode shown in miniOrange', 'miniorange-2-factor-authentication'); ?> <b style="color:red"><?php echo __('Authenticator', 'miniorange-2-factor-authentication'); ?></b> <?php echo __('app here.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                 <br>
                                 <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/soft-token-test-5.png" alt="First slide"style="width:100%">
                              </div>
                           </div>
                           <?php } else  if($login_status == 'MO_2_FACTOR_CHALLENGE_OTP_OVER_EMAIL') { ?>
                           <ol class="mo2f_carousel-indicators">
                              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                              <li data-target="#myCarousel" data-slide-to="1"></li>
                              <li data-target="#myCarousel" data-slide-to="2"></li>
                           </ol>
                           <div class="mo2f_carousel-inner" role="listbox">
                              <div class="item active">
							     <p><?php echo mo2f_lt('An One Time Passcode has been sent to your registered email address.');?></p>
                                 <br>
                                 <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/otp-help-1.png" alt="First slide"
								 style="width:100%">
                              </div>
                              <div class="item">
                              <p><?php echo __('Check your email with which you registered and copy the one time passcode.', 'miniorange-2-factor-authentication'); ?></p>
                                 <br>
                                 <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/otp-help-2.png" alt="First slide"
								 style="width:100%">
                              </div>
                              <div class="item">
							     <p><?php echo mo2f_lt('Enter the One Time Passcode to validate yourself.');?></p>
                                 <br>
                                 <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/otp-help-3.png" alt="First slide"
								 style="width:100%">
                              </div>
                           </div>
                           <?php } else if($login_status == 'MO_2_FACTOR_CHALLENGE_OTP_OVER_SMS') { ?>
                           <ol class="mo2f_carousel-indicators">
                              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                              <li data-target="#myCarousel" data-slide-to="1"></li>
                           </ol>
                           <div class="mo2f_carousel-inner" role="listbox">
                              <div class="item active">
			
                              <p><b><?php echo __('An OTP has been sent to your registered mobile number.', 'miniorange-2-factor-authentication'); ?> </b> </p>
                                 <br>
                                 <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/otp-over-sms-login-flow-1.png" alt="First slide"style="width:100%">
                              </div>
                              <div class="item">
							     <p><?php echo mo2f_lt('Enter the OTP received on your mobile phone to validate yourself.');?></p>
								 <br>
                                 <img class="first-slide" src="https://auth.miniorange.com/moas/images/help/otp-over-sms-login-flow-2.jpg" alt="First slide" style="width:100%">
                              </div>
                           </div>
                           <?php } ?>
                        </div>
						<br>
						<a href="#showOTP" id="otpLink" class="mo2f-link"><?php echo mo2f_lt('Go Back.');?></a>
						<br>
                     </div>
					 </center>
                     
                  <?php mo2f_customize_logo() ?>
               </div>
            </div>
         </div>
      </div>
      <form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
      </form>
	  <form name="f" id="mo2f_alternate_login_kbaform" method="post" action="" style="display:none;">
         <input type="hidden" name="miniorange_alternate_login_kba_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-alternate-login-kba-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <?php if(get_site_option('mo2f_enable_forgotphone') && isset($login_status ) && $login_status != 'MO_2_FACTOR_CHALLENGE_OTP_OVER_EMAIL'){ ?>
      <form name="f" id="mo2f_show_forgotphone_loginform" method="post" action="" style="display:none;">
         <input type="hidden" name="request_origin_method" value="<?php echo $login_status; ?>" />
         <input type="hidden" name="miniorange_forgotphone" value="<?php echo wp_create_nonce('miniorange-2-factor-forgotphone'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <?php } ?>
      <form name="f" id="mo2f_backup" method="post" action="" style="display:none;">
          <input type="hidden" name="miniorange_backup_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-backup-nonce'); ?>" />
          <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
   </body>
   <script>
      jQuery('#otpHelpLink').click(function() {
      	jQuery('#showOTPHelp').show();
      	jQuery('#showOTP').hide();
      	jQuery('#otpMessage').hide();
      });
      jQuery('#otpLink').click(function() {
      	jQuery('#showOTPHelp').hide();
      	jQuery('#showOTP').show();
      	jQuery('#otpMessage').show();
      });
	  jQuery('a[href="#mo2f_alternate_login_kba"]').click(function() {
			 //alert('here');
      	jQuery('#mo2f_alternate_login_kbaform').submit();
      });
	  jQuery('a[href="#mo2f_backup_option"]').click(function() {
	      // alert("here");
      	jQuery('#mo2f_backup').submit();
      });
      
      function mologinback(){
      	jQuery('#mo2f_backto_mo_loginform').submit();
       }
       function mologinforgotphone(){
      	jQuery('#mo2f_show_forgotphone_loginform').submit();
       }
   </script>
</html>
<?php
   }
   function mo2f_device_exceeded_error()
   {?>	
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
               <h4 class="mo2f_modal-title" ><button type="button"  class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Cancel','miniorange-2-factor-authentication');?>" onclick="mologinback();" ><span aria-hidden="true">&times;</span></button>
                  <?php echo __('Devices Exceeded', 'miniorange-2-factor-authentication'); ?>
               </h4>
            </div>
            <div class="mo2f_modal-body center">
               <center>
                  <h2 style="margin-bottom:0px !important;"> <?php echo __('Sorry, you are not allowed to log in from this device. You have exceeded the number of device registration allowed.', 'miniorange-2-factor-authentication');?></h2>
                  <br>
               </center>
               <?php mo2f_customize_logo() ?>
            </div>
         </div>
      </div>
      <form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
      </form>
      <script>
         function mologinback()
         {
         	jQuery('#mo2f_backto_mo_loginform').submit();
         }
      </script>
   </body>
</html>
<?php
   }
   
   function prompt_user_for_relogin($current_user_id, $login_status, $login_message)
   {?>	
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
		 if ( get_option( 'mo2f_personalization_ui' ) ) {
		echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'includes/css/mo2f_login_popup_ui.css', __FILE__ ) . '" />';
	}
         ?>
   </head>
   <body>
      <div class="mo2f_modal" tabindex="-1" role="dialog" id="myModal5">
      <div class="mo2f-modal-backdrop"></div>
      <div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
         <div class="login mo_customer_validation-modal-content">
            <div class="mo2f_modal-header">
               <h4 class="mo2f_modal-title" ><button type="button"  class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Cancel','miniorange-2-factor-authentication');?>" onclick="mologinback();" ><span aria-hidden="true">&times;</span></button>
                  <?php echo __('Email already registered','miniorange-2-factor-authentication'); ?>
               </h4>
            </div>
            <div class="mo2f_modal-body center">
               <center>
                  <h2 style="margin-bottom:0px !important;"><?php echo $login_message; ?></h2>
                  <br>
               </center>
               <?php mo2f_customize_logo() ?>
            </div>
         </div>
      </div>
      <form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
      </form>
      <script>
         function mologinback()
         {
         	jQuery('#mo2f_backto_mo_loginform').submit();
         }
      </script>
   </body>
</html>
<?php
   }
   
   function mo2f_get_device_form($id, $login_status, $login_message, $redirect_to)
   {
   	 global $dbQueries;
   // $selected_2factor_method = $dbQueries->get_user_detail( $id,'mo2f_selected_2factor_method');
   if(get_site_option('mo2f_enable_rba_types')==0)
   { 
   	$mo2f_second_factor = $dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$id);
   	
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
   		if(get_user_meta($id,'mo2f_external_app_type',true) == 'GOOGLE AUTHENTICATOR'){
   			$mo2f_second_factor = 'Google Authenticator';
   		}else{
   			$mo2f_second_factor = 'Authy 2-Factor Authentication';
   		}	
   	}else if($mo2f_second_factor == 'KBA'){
   		$mo2f_second_factor = 'Security Questions (KBA)';
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
      <div class="mo2f_modal-dialog mo2f_modal-lg">
      <div class="login mo_customer_validation-modal-content">
      <div class="mo2f_modal-header">
         <h4 class="mo2f_modal-title"><button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close" title="<?php echo __('Back to login','miniorange-2-factor-authentication');?>" onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
            <?php echo __('Two Factor Setup Complete', 'miniorange-2-factor-authentication'); ?>
         </h4>
      </div>
      <div class="mo2f_modal-body center">
      <center>
         <h3><b style="color:#7EAFB7;"><?php echo $mo2f_second_factor; ?> </b><?php echo __('has been set your Two Factor method for login.', 'miniorange-2-factor-authentication'); ?><br>
            <?php echo __('Next time when you will login, you will be prompted for', 'miniorange-2-factor-authentication'); ?> <?php echo $mo2f_second_factor; ?> <?php echo __('as your 2nd factor.', 'miniorange-2-factor-authentication');?>
         </h3>
      </center>
      <center>
         <h2 style="margin-bottom:0px !important;"><a href="#" onclick="mologinback();"><?php echo __('Click Here', 'miniorange-2-factor-authentication'); ?></a> </h2>
         <?php echo __('to login to your account.', 'miniorange-2-factor-authentication'); ?>
         <br>
      </center>
      <form name="f" id="mo2f_trust_device_confirm_form" method="post" action="" style="display:none;">
         <input type="hidden" name="mo2f_trust_device_confirm_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-trust-device-confirm-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <script>
         function mologinback(){
         	jQuery('#mo2f_trust_device_confirm_form').submit();
         }
      </script>
   </body>
</html>
<?php 
   }
   else{
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
                     <?php echo __('Remember Device', 'miniorange-2-factor-authentication'); ?>
                  </h4>
               </div>
               <div class="mo2f_modal-body center">
                  <div id="mo2f_device_content">
                     <h3><?php echo __('Do you want to remember this device?', 'miniorange-2-factor-authentication'); ?></h3>
                     <input type="button" name="miniorange_trust_device_yes" onclick="mo_check_device_confirm();" id="miniorange_trust_device_yes" class="mo_green" style="margin-right:5%;" value="<?php echo __('Yes','miniorange-2-factor-authentication'); ?>" />
                     <input type="button" name="miniorange_trust_device_no" onclick="mo_check_device_cancel();" id="miniorange_trust_device_no" class="mo_red" value="<?php echo __('No','miniorange-2-factor-authentication'); ?>" />
                  </div>
                  <div id="showLoadingBar"  hidden>
                     <p style="font-size:16px; font-weight:bold; color:#2980B9; "><?php echo __('Please wait...We are taking you into your account.', 'miniorange-2-factor-authentication'); ?></p>
                     <img src="<?php echo plugins_url( 'includes/images/ajax-loader-login.gif' , __FILE__ );?>" />
                  </div>
                  <br /><br />
                  <span>
                  <?php  echo __('Click on ', 'miniorange-2-factor-authentication'); ?> <i><b><?php echo __('Yes', 'miniorange-2-factor-authentication'); ?> </b> </i><?php echo __('if this is your personal device.', 'miniorange-2-factor-authentication'); ?><br />
                  <?php echo __('Click on ', 'miniorange-2-factor-authentication'); ?> <i><b><?php echo __('No ', 'miniorange-2-factor-authentication'); ?> </b> </i> <?php echo __('if this is a public device.', 'miniorange-2-factor-authentication'); ?>
                  </span><br /><br />
                  <?php mo2f_customize_logo() ?>
               </div>
            </div>
         </div>
      </div>
      <form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" style="display:none;">
         <input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
      </form>
      <form name="f" id="mo2f_trust_device_confirm_form" method="post" action="" style="display:none;">
         <input type="hidden" name="mo2f_trust_device_confirm_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-trust-device-confirm-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <form name="f" id="mo2f_trust_device_cancel_form" method="post" action="" style="display:none;">
         <input type="hidden" name="mo2f_trust_device_cancel_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-trust-device-cancel-nonce'); ?>" />
         <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
      </form>
      <script>
         function mologinback(){
         	jQuery('#mo2f_backto_mo_loginform').submit();
          }
         function mo_check_device_confirm(){
         	jQuery('#mo2f_device_content').hide();
         	jQuery('#showLoadingBar').show();
         	jQuery('#mo2f_trust_device_confirm_form').submit();
         }
         function mo_check_device_cancel(){
         	jQuery('#mo2f_device_content').hide();
         	jQuery('#showLoadingBar').show();
         	jQuery('#mo2f_trust_device_cancel_form').submit();
         }
      </script>
   </body>
</html>
<?php
   }
   }	
   
   ?>
