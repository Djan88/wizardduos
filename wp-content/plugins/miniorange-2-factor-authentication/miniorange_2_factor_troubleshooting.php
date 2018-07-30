<?php 
	function mo2f_show_help_and_troubleshooting($current_user,$mo_expand_value=null) {
	?>
	<div class="mo2f_table_layout">
		<?php echo mo2f_check_if_registered_with_miniorange($current_user); ?>
		<br>
		<ul class="mo2f_faqs">
			<?php if(current_user_can( 'manage_options' )) { ?>
			
			<h3><a  data-toggle="mo2f_collapse" href="#question1" aria-expanded="false" ><li><?php echo __( 'How to enable PHP cURL extension? (Pre-requisite)', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question1">
					<?php 
			echo __( 'cURL is enabled by default but in case you have disabled it, follow the steps to enable', 'miniorange-2-factor-authentication')?>
				
				<ol>
					<li>.<?php 
			echo __( 'Open php.ini(its usually in /etc/ or in php folder on the server)', 'miniorange-2-factor-authentication')?></li>
					<li><?php 
			echo __( 'Search for extension=php_curl.dll. Uncomment it by removing the semi-colon( ; ) in front of it.', 'miniorange-2-factor-authentication')?></li>
					<li><?php 
			echo __( 'Restart the Apache Server.', 'miniorange-2-factor-authentication') ?></li>
					</ol>
					<?php 
			echo __( 'For any further queries, please submit a query on right hand side in our.', 'miniorange-2-factor-authentication') ?> <b> <?php 
			echo __( 'Support Section', 'miniorange-2-factor-authentication')?></b>.
				
				</div>
				<hr>
				<h3><a  data-toggle="mo2f_collapse" href="#question2" aria-expanded="false" ><li><?php echo mo2f_lt('I am getting error - curl_setopt(): CURLOPT_FOLLOWLOCATION cannot be activated when an open_basedir is set.');?>
				</li></a></h3>
				<div class="mo2f_collapse" id="question2">
					<?php 
			echo __( 'Just setsafe_mode = Off in your php.ini file (its usually in /etc/ on the server). If thats already off, then look around for the open_basedir in the php.ini file, and change it to open_basedir = .', 'miniorange-2-factor-authentication')?>

				</div>
				<hr>
				<?php if($mo_expand_value){ ?>
					<h3><a  data-toggle="mo2f_collapse" href="#question_adduser" aria-expanded="true" class><li><?php echo __( 'How to setup two factor for my users?', 'miniorange-2-factor-authentication')?>
					</li></a></h3>
					<div class="mo2f_collapse in" id="question_adduser" aria-expanded="true">
				<?php }else{ ?>
					<h3><a  data-toggle="mo2f_collapse" href="#question_adduser" aria-expanded="false" ><li><?php echo mo2f_lt('How to setup two factor for my users?');?>
					</li></a></h3>
					<div class="mo2f_collapse" id="question_adduser">
				<?php } ?>
					<ol>
						<li>
						<?php 
			echo __( 'Go to Login Settings tab in the plugin.', 'miniorange-2-factor-authentication')?></li>
						<li><?php 
			echo __( 'Select the roles under ', 'miniorange-2-factor-authentication')?><b>
							<li><?php 
			echo __( 'Select the authentication methods under ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Select the specific set of authentication methods for your users', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'header.', 'miniorange-2-factor-authentication')?></li>
			<?php echo __( 'Click on ', 'miniorange-2-factor-authentication')?> <b> <?php echo __( 'Save Settings', 'miniorange-2-factor-authentication')?> </b> <?php echo __( 'button.', 'miniorange-2-factor-authentication')?></li>
						<li><?php 
			echo __( 'Next time when the user will try to login then inline registration will be invoked that will ask the user to setup the 2nd factor.', 'miniorange-2-factor-authentication')?></li>
						<li><?php 
			echo __( 'After your all users are registered successfully, make sure to turn off the inline registration by selecting the radio button of ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Skip 2 Factor registration at login', 'miniorange-2-factor-authentication')?></b> </li>
					</ol>
				</div>
				<hr>
				<h3><a  data-toggle="mo2f_collapse" href="#question3" aria-expanded="false" ><li><?php echo __( 'I did not receive OTP while trying to register with miniOrange. What should I do?', 'miniorange-2-factor-authentication')?>
				</li></a></h3>
				<div class="mo2f_collapse" id="question3">
					<?php 
			echo __( 'The OTP is sent to your email address with which you have registered with miniOrange. If you can\'t see the email from miniOrange in your mails, please make sure to check your ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'SPAM folder', 'miniorange-2-factor-authentication')?></b>. <?php echo __( 'If you don\'t see an email even in SPAM folder, please submit a query on right hand side in our ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Support Section', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'or you can contact us at info@miniorange.com.', 'miniorange-2-factor-authentication')?>

				</div>
				<hr>
				<h3><a  data-toggle="mo2f_collapse" href="#question4" aria-expanded="false" ><li>
					<?php 
			echo __( 'I forgot the password of my miniOrange account. How can I reset it?', 'miniorange-2-factor-authentication')?>
				</li></a></h3>
				<div class="mo2f_collapse" id="question4">
					<?php 
			echo __( 'There are two cases according to the page you see -', 'miniorange-2-factor-authentication')?>
				
				<ul>
				<li>1. <b>
				<?php 
			echo __( 'Login with miniOrange screen:', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'You should click on forgot password link. You will get a new password on your email address with which you have registered with miniOrange . Now you can login with the new password', 'miniorange-2-factor-authentication')?>.</li><br>
				<li>2. <b><?php 
			echo __( 'Register with miniOrange screen:', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'Enter your email ID and any random password in password and confirm password input box. This will redirect you to Login with miniOrange screen. Now follow first step.', 'miniorange-2-factor-authentication')?></li>
				</ul>

				</div>
				<hr>
				<h3><a  data-toggle="mo2f_collapse" href="#question5" aria-expanded="false" ><li>
				<?php 
			echo __( 'I have a custom / front-end login page on my site and I want the look and feel to remain the same when I add 2 factor ?', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question5">
					<?php 
			echo __( 'If you have a custom login form other than wp-login.php then you can copy the shortcode from ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Advanced Options Tab', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'and embed in your login form. If you need any help setting up 2-Factor for your custom login form, please submit a query in our ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Support Section', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'on right hand side.', 'miniorange-2-factor-authentication')?>

				</div>
				<hr>
				<h3><a  data-toggle="mo2f_collapse" href="#question6" aria-expanded="false" ><li><?php echo mo2f_lt('I have Woocommerce theme login page on my site. How can I enable Two Factor ?');?></li></a></h3>
				<div class="mo2f_collapse" id="question6">
					<?php 
			echo __( 'If you have Woocommerce theme login then go to Advanced Options Tab and check ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Enable Two-Factor for Woocommerce Front End Login', 'miniorange-2-factor-authentication')?></b>. <?php echo __( 'If you need any help setting up 2-Factor for your Woocommerce theme login form, please submit a query in our ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Support Section', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'on right hand side.', 'miniorange-2-factor-authentication')?>
				</div>
				<hr>
				<h3><a  data-toggle="mo2f_collapse" href="#question7" aria-expanded="false" ><li>
				<?php 
			echo __( 'I am trying to login with Two-Factor but my screen got blank after entering username and password. I am locked out of my account. What to do now ?', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question7">
					<?php 
			echo __( 'If you have an additional administrator account whose Two Factor is not enabled yet. Login with it. Otherwise,
					Go to WordPress Database. Select wp_options, search for mo2f_activate_plugin key and update its value to 0. Two Factor will get disabled.', 'miniorange-2-factor-authentication')?>
				</div>
				<hr>
				<h3><a  data-toggle="mo2f_collapse" href="#question8" aria-expanded="false" ><li>
				<?php 
			echo __( 'If you are using any Security Plugin in WordPress like Simple Security Firewall, All in One WP Security Plugin and you are not able to login with Two-Factor.', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question8">
					<?php 
			echo __( 'Our Two-Factor plugin is compatible with most of the security plugins, but if it is not working for you.
				   Please submit a query in our ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Support Section', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'on right hand side or you can contact us at ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'info@miniorange.com', 'miniorange-2-factor-authentication')?></b>.
				</div>
				<hr>
				<h3><a  data-toggle="mo2f_collapse" href="#question9" aria-expanded="false" ><li>
				<?php 
			echo __( 'If you are using any render blocking javascript and css plugin like Async JS and CSS Plugin and you are not able to login with Two-Factor or your screen got blank.', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question9">
					<?php echo __( 'If you are using ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Async JS and CSS Plugin', 'miniorange-2-factor-authentication')?></b>. <?php echo __( 'Please go to its settings and add jquery in the list of exceptions and save settings. It will work. If you are still not able to get it right,
				   Please submit a query in our', 'miniorange-2-factor-authentication')?> <b><?php echo __( 'Support Section', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'on right hand side or you can contact us at ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'info@miniorange.com', 'miniorange-2-factor-authentication')?></b>.
				</div>
				<hr>
				<h3><a  data-toggle="mo2f_collapse" href="#question10" aria-expanded="false" ><li>
				<?php 
			echo __( 'I want to enable 2-factor only for administrators ', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question10">
					<?php echo __( '2-Factor is enabled by default for administrators on plugin activation. You just need to complete your account setup and configure your mobile from ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Configure Mobile Tab', 'miniorange-2-factor-authentication')?></b>. <?php echo __( 'Once this is done administrators can login using 2-Factor and other users can still login with their password.', 'miniorange-2-factor-authentication')?>
				</div>
				<hr>
			<h3><a  data-toggle="mo2f_collapse" href="#question11" aria-expanded="false" ><li>
			<?php 
			echo __( 'I want to enable 2 factor for administrators and end users ', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question11">
					<?php 
			echo __( 'Go to ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Login Settings Tab', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'and check ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Enable 2-Factor for all other users', 'miniorange-2-factor-authentication')?></b>. <?php echo __( 'Enable 2-Factor for admins is checked by default.', 'miniorange-2-factor-authentication')?>

				</div>
				<hr>

				<h3><a  data-toggle="mo2f_collapse" href="#question12" aria-expanded="false" ><li>
				<?php 
			echo __( 'My phone has no internet connectivity, how can I login?', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question12">
				   <?php 
			echo __( 'You can login using our alternate login method. Please follow below steps to login or', 'miniorange-2-factor-authentication')?>
				    <a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#myCarousel2">
				    <?php 
			echo __( 'click here', 'miniorange-2-factor-authentication')?></a>       <?php echo mo2f_lt('to see how it works.');?><br>

					<br><ol>
					 <li><?php 
			echo __( 'Enter your username and click on login with your phone.', 'miniorange-2-factor-authentication')?></li>
					  <li><?php echo __( 'Click on ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Phone is Offline?', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'button below QR Code.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'You will see a textbox to enter one time passcode.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Open miniOrange Authenticator app and Go to Soft Token Tab.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Enter the one time passcode shown in miniOrange Authenticator app in textbox.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Click on submit button to validate the otp.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Once you are authenticated, you will be logged in.', 'miniorange-2-factor-authentication')?></li>
					  </ol>
				</div>
				<hr>
			<h3><a  data-toggle="mo2f_collapse" href="#question13" aria-expanded="false" ><li><?php 
			echo __( 'My users have different types of phones. What phones are supported?', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question13">
					<?php echo __( 'We support all types of phone. Smart Phones, Basic Phones, Landlines, etc. Go to Setup Two-Factor Tab and select Two-Factor method of your choice from a range of 6 different options.', 'miniorange-2-factor-authentication')?>
				</div>
				<hr>
			<h3><a  data-toggle="mo2f_collapse" href="#question14" aria-expanded="false" ><li><?php echo __( 'What if a user does not have a smart phone?', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question14">
					<?php echo __( 'You can select OTP over SMS, Phone Call Verification or Email Verification as your Two-Factor method. All these methods are supported on basic phones.', 'miniorange-2-factor-authentication')?>
				</div>
				<hr>
			<?php }?>	
			<h3><a data-toggle="mo2f_collapse" href="#question15" aria-expanded="false" ><li><?php echo __( 'What if I am trying to login from my phone ?', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question15">
					<?php echo __( 'If you are logging in from your phone, just enter the one time passcode from miniOrange Authenticator App.
					Go to Soft Token Tab to see one time passcode.', 'miniorange-2-factor-authentication')?>
				</div>
				<hr>
			<?php if(current_user_can( 'manage_options' )) { ?>
				
			
			<h3><a  data-toggle="mo2f_collapse" href="#question16" aria-expanded="false" ><li><?php echo __( 'I want to hide default login form and just want to show login with phone?', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question16">
					<?php echo __( 'You should go to ', 'miniorange-2-factor-authentication')?><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_login"><?php echo __( 'Login Settings Tab', 'miniorange-2-factor-authentication')?></a> <?php echo __( 'and check ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'I want to hide default login form.', 'miniorange-2-factor-authentication')?> </b> <?php echo __( 'checkbox to hide the default login form.', 'miniorange-2-factor-authentication')?> 
					
					
				</div>
				<hr>
			<?php }?>
			<h3><a  data-toggle="mo2f_collapse" href="#question17" aria-expanded="false" ><li><?php echo __( 'My phone has no internet connectivity, how can I login?', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question17">
				   <?php echo __( 'You can login using our alternate login method. Please follow below steps to login or ', 'miniorange-2-factor-authentication')?><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#myCarousel2"><?php echo __( 'click here', 'miniorange-2-factor-authentication')?></a> <?php echo __( 'to see how it works.', 'miniorange-2-factor-authentication')?><br>
					<br><ol>
					 <li><?php echo __( 'Enter your username and click on login with your phone.', 'miniorange-2-factor-authentication')?></li>
					  <li><?php echo __( 'Click on ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Phone is Offline?', 'miniorange-2-factor-authentication')?> </b> <?php echo __( 'button below QR Code.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'You will see a textbox to enter one time passcode.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Open miniOrange Authenticator app and Go to Soft Token Tab.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Enter the one time passcode shown in miniOrange Authenticator app in textbox.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Click on submit button to validate the otp.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Once you are authenticated, you will be logged in.', 'miniorange-2-factor-authentication')?></li>
					  </ol>
				</div>
				<hr>
			<h3><a  data-toggle="mo2f_collapse" href="#question18" aria-expanded="false" ><li><?php echo __( 'My phone is lost, stolen or discharged. How can I login?', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question18">
				    <?php echo __( 'You can login using our alternate login method. Please follow below steps to login or ', 'miniorange-2-factor-authentication')?><a href="admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_demo#myCarousel3"><?php echo __( 'click here', 'miniorange-2-factor-authentication')?></a><?php echo __( 'to see how it works.', 'miniorange-2-factor-authentication')?>
					<br><br>
					<ol>
					<li><?php echo __( 'Enter your username and click on login with your phone.', 'miniorange-2-factor-authentication')?></li>
					  <li><?php echo __( 'Click on ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'Forgot Phone?', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'button below QR Code.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'You will see a textbox to enter one time passcode.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Check your registered email and copy the one time passcode in this textbox.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Click on submit button to validate the otp.', 'miniorange-2-factor-authentication')?></li>
					   <li><?php echo __( 'Once you are authenticated, you will be logged in.', 'miniorange-2-factor-authentication')?></li>
					   </ol>
				</div>
				<hr>
			<h3><a  data-toggle="mo2f_collapse" href="#question19" aria-expanded="false" ><li><?php echo __( 'My phone has no internet connectivity and i am entering the one time passcode from miniOrange Authenticator App, it says Invalid OTP.', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question19">
					<?php echo __( 'Click on the', 'miniorange-2-factor-authentication')?> <b><?php echo __( 'Settings Icon', 'miniorange-2-factor-authentication')?> </b> <?php echo __( 'on top right corner in ', 'miniorange-2-factor-authentication')?><b><?php echo __( 'miniOrange Authenticator App', 'miniorange-2-factor-authentication')?> </b> <?php echo __( 'and then press', 'miniorange-2-factor-authentication')?> <b><?php echo __( 'Sync button', 'miniorange-2-factor-authentication')?></b> <?php echo __( 'under \'Time correction for codes\' to sync your time with miniOrange Servers. If you still can\'t get it right, submit a query here in our', 'miniorange-2-factor-authentication')?> <b><?php echo __( 'support section', 'miniorange-2-factor-authentication')?></b>.<br><br>
				</div>
				<hr>
				<?php if(current_user_can( 'manage_options' )) { ?>
			
		
			<h3><a  data-toggle="mo2f_collapse" href="#question20" aria-expanded="false" ><li><?php echo __( 'I want to go back to default login with password.', 'miniorange-2-factor-authentication')?></li></a></h3>
				<div class="mo2f_collapse" id="question20">
					<?php echo __( 'You can disable Two Factor from Login settings Tab by unchecking Enable Two Factor Plugin checkbox.', 'miniorange-2-factor-authentication')?>
				</div>
				<hr>
		
	
		
	
		
			<h3><a><?php echo __( 'For any other query/problem/request, please feel free to submit a query in our support section on right hand side. We are happy to help you and will get back to you as soon as possible.', 'miniorange-2-factor-authentication')?></a></h3>
		   	<?php }?>
		</ul>
					
	</div>
	<?php } ?>