<?php
/**
* Plugin Name: miniOrange 2 Factor Authentication
* Plugin URI: http://miniorange.com
* Description: [PREMIUM PLUGIN] This plugin provides various two-factor authentication methods as an additional layer of security for wordpress login. We Support Phone Call, SMS, Email Verification, QR Code, Push, Soft Token, Google Authenticator, Authy, Security Questions(KBA), Woocommerce front-end login, Shortcodes for custom login pages.
* Version: 12.4.2-premium
* Author: miniOrange
* Author URI: http://miniorange.com
* License: GPL2
*/

define('MO2FA_DIR_PATH', plugin_dir_path(__FILE__));
define('MOAUTH_PATH', plugins_url(__FILE__));

include_once dirname( __FILE__ ) . '/miniorange_2_factor_configuration.php';
include_once dirname( __FILE__ ) . '/miniorange_2_factor_mobile_configuration.php';
include_once dirname( __FILE__ ) . '/miniorange_2_factor_troubleshooting.php';
include_once dirname( __FILE__ ) . '/class-rba-attributes.php';
include_once dirname( __FILE__ ) . '/class-two-factor-setup.php';
include_once dirname( __FILE__ ) . '/class-customer-setup.php';
require('class-miniorange-2-factor-shortcode.php');
require('class-utility.php');
require('miniorange_2_factor_support.php');
require('class-miniorange-2-factor-user-registration.php');
require('class-miniorange-2-factor-pass2fa-login.php');

class Miniorange_Authentication {
	
	private $defaultCustomerKey = "16555";
	private $defaultApiKey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
	
	function __construct() {
		
		$mo2f_auth_types = array('OUT OF BAND EMAIL','SMS', 'SMS AND EMAIL','PHONE VERIFICATION','SOFT TOKEN','MOBILE AUTHENTICATION','PUSH NOTIFICATIONS','GOOGLE AUTHENTICATOR','AUTHY 2-FACTOR AUTHENTICATION','KBA');
		add_site_option( 'mo2f_auth_methods_for_users' ,$mo2f_auth_types);
		$mo2f_kba_qlist = array('What is your first company name?', 'What was your childhood nickname?', 'In what city did you meet your spouse/significant other?', 'What is the name of your favorite childhood friend?', 'What school did you attend for sixth grade?', 'In what city or town was your first job?', 'What is your favourite sport?', 'Who is your favourite sports player?', 'What is your grandmother\'s maiden name?', 'What was your first vehicle\'s registration number?');
		$mo2f_select_user_for_2fa=array();
		add_site_option( 'mo2f_select_user_for_2fa', $mo2f_select_user_for_2fa);
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		foreach($wp_roles->role_names as $id => $name)
		{
			add_option( 'mo2f_auth_methods_for_'.$id ,$mo2f_auth_types);
			
		}
		add_site_option( 'mo2f_all_users_method',1);
		add_site_option( 'mo2f_auth_admin_custom_kbaquestions', $mo2f_kba_qlist);
		add_site_option( 'mo2f_default_kbaquestions_users', 2);
		add_site_option( 'mo2f_custom_kbaquestions_users', 1);
		add_site_option( 'mo2f_inline_registration',1);
		add_site_option( 'mo2f_enable_emailchange', 1);
		add_site_option( 'mo2f_enable_mobile_support', 1);
		add_site_option( 'mo2f_activate_plugin', 1 );
		add_site_option( 'mo2f_msg_counter', 1 );
		add_site_option( 'mo2f_enable_forgotphone', 1);
		add_site_option( 'mo2f_enable_forgotphone_kba', 1);
		add_site_option( 'mo2f_enable_forgotphone_email', 1);
		add_site_option( 'mo2f_enable_xmlrpc', 0);
		add_site_option( 'mo2f_disable_poweredby',0);
		add_site_option( 'mo2f_disable_kba', 0 );
		add_site_option( 'mo2f_enable_rba', 0);
		add_site_option( 'mo2f_enable_rba_types', 1);
		add_site_option( 'mo2f_rba_loginform_id', 'loginform');
		add_site_option( 'mo2f_custom_plugin_name', 'miniOrange 2-Factor');
		add_action( 'admin_menu', array( $this, 'miniorange_auth_menu' ) );
		add_action( 'admin_init',  array( $this, 'miniorange_auth_save_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_script' ) );
		remove_action( 'admin_notices', array( $this, 'mo_auth_success_message') );
		remove_action( 'admin_notices', array( $this, 'mo_auth_error_message') );
		add_action('wp_logout', array( $this, 'mo_2_factor_endsession'));
		add_action( 'deleted_user', array( $this, 'delete_mo2fa_users_data') );
		add_option( 'mo2f_is_error', 0);
		
		$mo2f_shortcode = new MO2F_ShortCode();
		//add shortcode
		add_action( 'init' , array($mo2f_shortcode, 'miniorange_auth_user_settings') );
		add_shortcode( 'mo2f_enable_reconfigure', array( $mo2f_shortcode, 'mo2fa_reconfigform_ShortCode') );
		add_shortcode( 'miniorange_enable2fa', array($mo2f_shortcode, 'mo2fa_enable2faform_ShortCode') );
		add_shortcode( 'mo2f_enable_rba_shortcode', array($mo2f_shortcode, 'mo2fa_enablerba_ShortCode') );
		
		
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		if(get_site_option('mo2f_admin_disabled_status') == 1 || get_site_option('mo2f_admin_disabled_status') == 0){
			if(get_site_option('mo2f_admin_disabled_status') == 1){
				add_site_option('mo2fa_administrator',1);
				add_option('mo2fa_'.$id.'_login_url',admin_url());
			}else{
				foreach($wp_roles->role_names as $id => $name) {
					add_site_option('mo2fa_'.$id, 1);
					if($id == 'administrator'){
						add_option('mo2fa_'.$id.'_login_url',admin_url());
					}else{
						add_option('mo2fa_'.$id.'_login_url',home_url());
					}
				}
			}
			delete_site_option('mo2f_admin_disabled_status');
		}else{
			foreach($wp_roles->role_names as $id => $name) {
				add_site_option('mo2fa_'.$id, 1);
				if($id == 'administrator'){
					add_option('mo2fa_'.$id.'_login_url',admin_url());
				}else{
					add_option('mo2fa_'.$id.'_login_url',home_url());
				}
			}
		}
		
		if( get_site_option('mo2f_activate_plugin') == 1){
			
			$pass2fa_login = new Miniorange_Password_2Factor_Login();
			add_action( 'init', array( $pass2fa_login, 'miniorange_pass2login_redirect'));
			
			if(get_site_option( 'mo_2factor_admin_registration_status') == 'MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS' ){
				
				remove_filter('authenticate', 'wp_authenticate_username_password',20);
			if(get_site_option('mo2f_by_roles')==0){
				add_filter( 'bulk_actions-users', array($this,'add_2fa_my_bulk_actions'));
				add_filter( 'bulk_actions-users', array($this,'remove_2fa_my_bulk_actions'));
				add_filter( 'handle_bulk_actions-users', array($this,'my_bulk_action_handler'), 10, 3 );
                }
				add_filter('authenticate', array($pass2fa_login, 'mo2f_check_username_password'),99999,4);	
				$actions = add_filter('user_row_actions', array( $this, 'miniorange_reset_users'),10,2);
				add_filter('manage_users_columns', array($this, 'mo2f_mapped_email_column'));
				add_action('manage_users_custom_column', array($this ,'mo2f_mapped_email_column_content'), 10, 3);
				add_action('manage_users_custom_column', array($this ,'mo2f_selected_user_column_content'), 10, 3);
				
				if(get_site_option('mo2f_login_policy')){					
					
					add_action( 'login_form', array( $pass2fa_login, 'mo_2_factor_pass2login_show_wp_login_form' ),10 );
					add_action( 'login_footer', array( $pass2fa_login, 'miniorange_pass2login_footer_form' ));
					add_action( 'login_enqueue_scripts', array( $pass2fa_login,'mo_2_factor_enable_jquery_default_login') );
					
					//for woocommerce
					add_action( 'woocommerce_before_customer_login_form', array( $pass2fa_login, 'miniorange_pass2login_footer_form' ) );
					add_action( 'woocommerce_login_form_end', array( $pass2fa_login, 'mo_2_factor_pass2login_show_wp_login_form' ) );
					add_action( 'wp_enqueue_scripts', array( $pass2fa_login,'mo_2_factor_enable_jquery_default_login') );	
					
					//Actions for other plugins to use miniOrange 2FA plugin
					add_action('miniorange_pre_authenticate_user_login', array($pass2fa_login, 'mo2f_check_username_password'),1,4);
					add_action('miniorange_post_authenticate_user_login', array($pass2fa_login, 'miniorange_initiate_2nd_factor'),1,4);
					add_action('miniorange_collect_attributes_for_authenticated_user', array($pass2fa_login, 'mo2f_collect_device_attributes_for_authenticated_user'),1,2);							
							
				}
				else{
					
					$mobile_login = new Miniorange_Password_2Factor_Login();
					add_action( 'login_form', array( $mobile_login, 'miniorange_pass2login_form_fields' ),10 );
					add_action( 'login_footer', array( $mobile_login, 'miniorange_pass2login_footer_form' ));
					add_action( 'login_enqueue_scripts', array( $mobile_login,'custom_login_enqueue_scripts') );					
				}

			}
		}
	}
	
	function delete_mo2fa_users_data($user_id){
		
		delete_user_meta($user_id,'mo_2factor_2fa_shortcode');
		delete_user_meta($user_id,'mo2f_2factor_enable_2fa_byusers');
		delete_user_meta($user_id,'mo_2factor_user_registration_status');
		delete_user_meta($user_id,'mo_2factor_mobile_registration_status');
		delete_user_meta($user_id,'mo_2factor_user_registration_with_miniorange');
		delete_user_meta($user_id,'mo_2factor_map_id_with_email');
		delete_user_meta($user_id,'mo2f_user_phone');
		delete_user_meta($user_id,'mo2f_mobile_registration_status');
		delete_user_meta($user_id,'mo2f_otp_registration_status');
		delete_user_meta($user_id,'mo2f_configure_test_option');
		delete_user_meta($user_id,'mo2f_selected_2factor_method');
		delete_user_meta($user_id,'mo2f_google_authentication_status');
		delete_user_meta($user_id,'mo2f_kba_registration_status');
		delete_user_meta($user_id,'mo2f_email_verification_status');
		delete_user_meta($user_id,'mo2f_authy_authentication_status');
		
	}
	function add_2fa_my_bulk_actions($bulk_actions) {
	
  $bulk_actions['Select_for_2FA'] = 'Select for 2FA';
  return $bulk_actions;
}
function remove_2fa_my_bulk_actions($bulk_actions) {
	
  $bulk_actions['Remove_2FA'] = 'Remove 2FA';
  return $bulk_actions;
}
function my_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {

  if ( $doaction !== 'Select_for_2FA' && $doaction !== 'Remove_2FA') {
    return $redirect_to;
  }
 
	$list_ids=get_site_option('mo2f_select_user_for_2fa');
	
	if($doaction == 'Select_for_2FA'){
		  if(!in_array('No User Selected',$list_ids))
		  {	  
			 foreach ( $post_ids as $post_id ) {
				// Perform action for each post.
				if(!in_array($post_id,$list_ids))
				{
					array_push($list_ids,$post_id);
				}
			  }
			  update_site_option('mo2f_select_user_for_2fa',$list_ids);
			  
		  }
		  else{
			   update_site_option('mo2f_select_user_for_2fa',$post_ids);
		  }
	}else if($doaction == 'Remove_2FA'){	
	
		 if(!in_array('No User Selected',$list_ids))
		 {
			
			 foreach( $post_ids as $post_id)
			 {
				$pos = array_search($post_id,$list_ids);
			
				if($pos!==false){
				unset($list_ids[$pos]);
				
			    }
			 }
			 update_site_option('mo2f_select_user_for_2fa',$list_ids);
		 }
		 
	}
	$mo2f_select_user_for_2fa = get_site_option('mo2f_select_user_for_2fa');
	 if(empty($mo2f_select_user_for_2fa))
	   {
		   $list_rd = array("No User Selected");
			  update_site_option('mo2f_select_user_for_2fa',$list_rd);
		}
			return $redirect_to;
}

function mo2f_selected_user_column_content($value, $column_name, $user_id) {
	global $dbQueries;
		$user = get_userdata( $user_id );
		$email = get_user_meta($user->ID,'mo_2factor_map_id_with_email', true);
		$id=(int)$user->ID;
		$roles=$user->roles;
		if(get_option('mo2f_by_roles') ){
            $enabled = miniorange_check_if_2fa_enabled_for_roles($roles);
			if($enabled){
			$email='Yes';
			}
			else
			$email='No';
		}
		else{
			$list_ids=get_site_option('mo2f_select_user_for_2fa');
			if(in_array($user_id,$list_ids))
			{
				$email='Yes';
			}
			else
				$email='No';
		}
		
		
		
		if ( 'selected_user' == $column_name )
			return $email;
		return $value;
	}	
	
	function mo_2_factor_endsession() {
		update_site_option('mo2f-login-message','You are now logged out');
		session_start();
		$_SESSION = array();
		session_destroy();
	}
	

	function mo_auth_success_message() {
		$message = get_site_option('mo2f_message'); ?>
		<script> 
		
		jQuery(document).ready(function() {	
			var message = "<?php echo $message; ?>";
			jQuery('#messages').append("<div class='error notice is-dismissible mo2f_error_container'> <p class='mo2f_msgs'>" + message + "</p></div>");
		});
		</script>
		<?php
	}

	function mo_auth_error_message() {
		$message = get_site_option('mo2f_message'); ?>
		<script> 
		jQuery(document).ready(function() {
			var message = "<?php echo $message; ?>";
			jQuery('#messages').append("<div class='updated notice is-dismissible mo2f_success_container'> <p class='mo2f_msgs'>" + message + "</p></div>");
		
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
				}else if(currentMethod == 'OUT OF BAND EMAIL'){
					jQuery('#mo2f_2factor_test_out_of_band_email_form').submit();
				}else if(currentMethod == 'GOOGLE AUTHENTICATOR'){
					jQuery('#mo2f_2factor_test_google_auth_form').submit();
				}else if(currentMethod == 'AUTHY 2-FACTOR AUTHENTICATION'){
					jQuery('#mo2f_2factor_test_authy_app_form').submit();
				}else if(currentMethod == 'KBA'){
					jQuery('#mo2f_2factor_test_kba_form').submit();
				}
				
				
			});
		
		});
		</script>
		<?php
	}	
	
	
	
	
	
	function miniorange_auth_menu() {
		global $wpdb;
		global $current_user;
		$current_user = wp_get_current_user(); 
		if(get_site_option('mo2f_enable_custom_icon')!=1)
				$iconurl = plugin_dir_url(__FILE__) . 'includes/images/miniorange_icon.png';
			else
				$iconurl = site_url(). '/wp-content/uploads/miniorange/plugin_icon.png';
			
		$roles = $current_user->roles;
		$miniorange_role = array_shift($roles);	
		if(get_option('mo2f_by_roles') ){
            $enabled = miniorange_check_if_2fa_enabled_for_roles($current_user->roles);
			
		}
		else{
			$list_ids=get_site_option('mo2f_select_user_for_2fa');
			$enabled =in_array($current_user->ID,$list_ids);
		}
		
		if( !current_user_can( 'manage_options' ) && $enabled && get_site_option( 'mo_2factor_admin_registration_status') == 'MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS' && get_site_option( 'mo2f_miniorange_admin') != $current_user->ID && get_site_option('mo2f_activate_plugin') == 1){
			$user_register = new Miniorange_User_Register();
				$mo2fa_hook_page = add_menu_page ('miniOrange 2 Factor Auth', get_site_option('mo2f_custom_plugin_name') , 'read', 'miniOrange_2_factor_settings', array( $user_register, 'mo2f_register_user'), $iconurl);
		}else if(current_user_can( 'manage_options' )){
				$mo2fa_hook_page = add_menu_page ('miniOrange 2 Factor Auth',  get_site_option('mo2f_custom_plugin_name'), 'manage_options', 'miniOrange_2_factor_settings', array( $this, 'mo_auth_login_options' ),$iconurl);
				$mo2fa_hook_page = add_users_page ('Reset 2nd Factor',  null , 'manage_options', 'reset', array( $this, 'mo_reset_2fa_for_users' ),'',66);
				
		}
	}

	function  mo_auth_login_options () {
		global $wpdb;
		global $current_user;
		$current_user = wp_get_current_user();
		update_site_option('mo2f_host_name', 'https://auth.miniorange.com');
		mo_2_factor_register($current_user);
	}
	
	function mo_reset_2fa_for_users(){
		mo_reset_2fa_for_users_by_admin();
	}
	
	function miniorange_reset_users($actions, $user_object){
		
		if ( current_user_can( 'administrator', $user_object->ID ) && get_user_meta($user_object->ID,'mo_2factor_map_id_with_email', true)) {
			
			if(get_site_option( 'mo2f_miniorange_admin') != $user_object->ID){
				$actions['miniorange_reset_users'] = "<a class='miniorange_reset_users' href='" . admin_url( "users.php?page=reset&action=reset_edit&amp;user=$user_object->ID") . "'>" . __( 'Reset 2 Factor', 'cgc_ub' ) . "</a>";
			}
		}	
		return $actions;
		
	}
	
	function mo2f_mapped_email_column($columns) {
		$columns['mapped_email'] = 'Registered 2FA Email';
		$columns['selected_user'] = 'Two Factor Enabled';
		return $columns;
	}

	function mo2f_mapped_email_column_content($value, $column_name, $user_id) {
		$user = get_userdata( $user_id );
		$email = get_user_meta($user->ID,'mo_2factor_map_id_with_email', true);
		if(!$email){
			$email = 'Not Registered for 2FA';
		}
		
		if ( 'mapped_email' == $column_name )
			return $email;
		return $value;
	}	

	function mo_2_factor_enable_frontend_style() {
		wp_enqueue_style( 'mo2f_frontend_login_style', plugins_url('includes/css/front_end_login.css?version=12.2.0', __FILE__));
		wp_enqueue_style( 'bootstrap_style', plugins_url('includes/css/bootstrap.min.css?version=12.2.0', __FILE__));
		wp_enqueue_style( 'mo_2_factor_admin_settings_phone_style', plugins_url('includes/css/phone.css', __FILE__));
	}
	
	function plugin_settings_style($mo2fa_hook_page) {
		if ( 'toplevel_page_miniOrange_2_factor_settings' != $mo2fa_hook_page ) {
			return;
		}
		wp_enqueue_style( 'mo_2_factor_admin_settings_style', plugins_url('includes/css/style_settings.css?version=12.2.0', __FILE__));
		wp_enqueue_style( 'mo_2_factor_admin_settings_phone_style', plugins_url('includes/css/phone.css', __FILE__));
		wp_enqueue_style( 'bootstrap_style', plugins_url('includes/css/bootstrap.min.css?version=12.2.0', __FILE__));
	}

	function plugin_settings_script($mo2fa_hook_page) {
		if ( 'toplevel_page_miniOrange_2_factor_settings' != $mo2fa_hook_page ) {
			return;
		}
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'mo_2_factor_admin_settings_phone_script', plugins_url('includes/js/phone.js', __FILE__ ));
		wp_enqueue_script( 'bootstrap_script', plugins_url('includes/js/bootstrap.min.js', __FILE__ ));
	}

	private function mo_auth_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_auth_success_message') );
		add_action( 'admin_notices', array( $this, 'mo_auth_error_message') );
	}

	private function mo_auth_show_error_message() {
		
		remove_action( 'admin_notices', array( $this, 'mo_auth_error_message') );
		add_action( 'admin_notices', array( $this, 'mo_auth_success_message') );
	}

	function miniorange_auth_save_settings(){
		if( ! session_id() || session_id() == '' || !isset($_SESSION) ) {
			session_start();
		}
		global $current_user;
		$current_user = wp_get_current_user();
	
		
		if(current_user_can( 'manage_options' )){
			if(isset($_POST['option']) and $_POST['option'] == "mo_auth_register_customer"){	//register the admin to miniOrange
			//validate and sanitize
			$email = '';
			$phone = '';
			$password = '';
			$confirmPassword = '';
			if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['email'] ) || MO2f_Utility::mo2f_check_empty_or_null( $_POST['password'] ) || MO2f_Utility::mo2f_check_empty_or_null( $_POST['confirmPassword'] ) ) {
				update_site_option( 'mo2f_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_auth_show_error_message();
				return;
			}else if( strlen( $_POST['password'] ) < 6 || strlen( $_POST['confirmPassword'] ) < 6){
				update_site_option( 'mo2f_message', 'Choose a password with minimum length 8.');
				$this->mo_auth_show_error_message();
				return;
			} else{
				$email = sanitize_email( $_POST['email'] );
				$phone = sanitize_text_field( $_POST['phone'] );
				$password = sanitize_text_field( $_POST['password'] );
				$confirmPassword = sanitize_text_field( $_POST['confirmPassword'] );
			}			
			$email = strtolower($email);
			update_site_option( 'mo2f_email', $email );
			update_user_meta( $current_user->ID,'mo2f_user_phone', $phone );
		
		
			if(strcmp($password, $confirmPassword) == 0) {
				update_site_option( 'mo2f_password', $password );
				$customer = new Customer_Setup();
				$customerKey = json_decode($customer->check_customer(), true);
				if($customerKey['status'] == 'ERROR'){
					update_site_option( 'mo2f_message', $customerKey['message']);
					$this->mo_auth_show_error_message();
				}else{
					if( strcasecmp( $customerKey['status'], 'CUSTOMER_NOT_FOUND') == 0 ){ //customer not found then send OTP to verify email 
						$content = json_decode($customer->send_otp_token(get_site_option('mo2f_email'),'EMAIL',$this->defaultCustomerKey,$this->defaultApiKey), true);
						if(strcasecmp($content['status'], 'SUCCESS') == 0) {
							
							update_site_option( 'mo2f_message', 'An OTP has been sent to <b>' . ( get_site_option('mo2f_email') ) . '</b>. Please enter the OTP below to verify your email. ');
							update_user_meta($current_user->ID,'mo2f_email_otp_count',1);
							update_user_meta($current_user->ID,'mo_2fa_verify_otp_create_account',$content['txId']);
							update_user_meta($current_user->ID, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_SUCCESS');
							$this->mo_auth_show_success_message();
						}else{
							update_site_option('mo2f_message','There was an error in sending OTP over email. Please click on Resend OTP to try again.');
							update_user_meta($current_user->ID, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');
							$this->mo_auth_show_error_message();
						}
					}else{ //customer already exists,retrieve its keys
						
						$content = $customer->get_customer_key();
						$customerKey = json_decode($content, true);
						if(json_last_error() == JSON_ERROR_NONE) { /*Admin enter right credentials,if already exist */
						
					
							if(is_array($customerKey) && array_key_exists("status", $customerKey) && $customerKey['status'] == 'ERROR'){
								update_site_option('mo2f_message',$customerKey['message']);
								$this->mo_auth_show_error_message();
							}else if(is_array($customerKey)){
								
								update_site_option( 'mo2f_customerKey', $customerKey['id']);
								update_site_option( 'mo2f_api_key', $customerKey['apiKey']);
								update_site_option( 'mo2f_customer_token', $customerKey['token']);
								update_site_option( 'mo2f_app_secret', $customerKey['appSecret'] );
								update_site_option( 'mo2f_miniorange_admin',$current_user->ID);
								delete_site_option('mo2f_password');
								update_site_option( 'mo_2factor_admin_registration_status','MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS');
								update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
								update_user_meta($current_user->ID,'mo_2factor_map_id_with_email',get_site_option('mo2f_email'));
								update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
								update_user_meta($current_user->ID, 'mo2f_2factor_enable_2fa_byusers', 1);
								
								$enduser = new Two_Factor_Setup();
								$enduser->mo2f_update_userinfo(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true), 'OUT OF BAND EMAIL',null,'API_2FA',true);
								update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
								update_site_option( 'mo2f_message', 'Your account has been retrieved successfully. <b>Email Verification</b> has been set as your default 2nd factor method. <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure\" >Click Here </a>to configure another 2nd factor authentication method.');
								$this->mo_auth_show_success_message();
								
							}else{
								update_site_option( 'mo2f_message', 'Error occured while registration. Please try again.');
								$this->mo_auth_show_error_message();
							}
						} else { /*Admin account exist but enter wrong credentials*/
							update_site_option( 'mo2f_message', 'You already have an account with miniOrange. Please enter a valid password.');
							update_user_meta( $current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_VERIFY_CUSTOMER');
							$this->mo_auth_show_success_message();
						}
					} 
				}
			} else {
				update_site_option( 'mo2f_message', 'Password and Confirm password do not match.');
				$this->mo_auth_show_error_message();
			}
		}
		
		if(isset($_POST['option']) and $_POST['option'] == "mo2f_goto_verifycustomer"){
			update_site_option( 'mo2f_message', 'Please enter your registered email and password.');
			update_user_meta( $current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_VERIFY_CUSTOMER');
			$this->mo_auth_show_success_message();
		}
		
		if(isset($_POST['option']) and $_POST['option'] == "mo_auth_verify_customer"){	//register the admin to miniOrange if already exist
		
			//validation and sanitization
			$email = '';
			$password = '';
			if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['email'] ) || MO2f_Utility::mo2f_check_empty_or_null( $_POST['password'] ) ) {
				update_site_option( 'mo2f_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_auth_show_error_message();
				return;
			}else{
				$email = sanitize_email( $_POST['email'] );
				$password = sanitize_text_field( $_POST['password'] );
			}
		
			update_site_option( 'mo2f_email', $email );
			update_site_option( 'mo2f_password', $password );
			$customer = new Customer_Setup();
			$content = $customer->get_customer_key();
			$customerKey = json_decode($content, true);
			if(json_last_error() == JSON_ERROR_NONE) {
				if(is_array($customerKey) && array_key_exists("status", $customerKey) && $customerKey['status'] == 'ERROR'){
					update_site_option('mo2f_message',$customerKey['message']);
					$this->mo_auth_show_error_message();
				}else if(is_array($customerKey)){
					update_site_option( 'mo2f_customerKey', $customerKey['id']);
					update_site_option( 'mo2f_api_key', $customerKey['apiKey']);
					update_site_option( 'mo2f_customer_token', $customerKey['token']);
					update_site_option( 'mo2f_app_secret', $customerKey['appSecret'] );
					update_user_meta($current_user->ID,'mo2f_phone', $customerKey['phone']);
					update_site_option( 'mo2f_miniorange_admin',$current_user->ID);
					delete_site_option('mo2f_password');
					update_site_option( 'mo_2factor_admin_registration_status','MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS');
					update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
					update_user_meta($current_user->ID,'mo_2factor_map_id_with_email',get_site_option('mo2f_email'));
					update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
					update_user_meta($current_user->ID, 'mo2f_2factor_enable_2fa_byusers', 1);
					
					$enduser = new Two_Factor_Setup();
					$enduser->mo2f_update_userinfo(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true), 'OUT OF BAND EMAIL',null,'API_2FA',true);	
					update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
					update_site_option( 'mo2f_message', 'Your account has been retrieved successfully. <b>Email Verification</b> has been set as your default 2nd factor method. <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure\" >Click Here </a>to configure another 2nd factor authentication method.');
					$this->mo_auth_show_success_message();
					    
				}else{
					update_site_option( 'mo2f_message', 'Error occured while registration. Please try again.');
					$this->mo_auth_show_error_message();
				}
			} else {
				update_site_option( 'mo2f_message', 'Invalid email or password. Please try again.');
				update_user_meta($current_user->ID, 'mo_2factor_user_registration_status','MO_2_FACTOR_VERIFY_CUSTOMER');
				$this->mo_auth_show_error_message();
			}
			delete_site_option('mo2f_password');
		}
		if(isset($_POST['option']) and $_POST['option'] == 'mo_2factor_phone_verification'){ //at registration time
					$phone = sanitize_text_field($_POST['phone_number']);
				
					$phone = str_replace(' ', '', $phone);
					$auth_type = 'OTP_OVER_SMS';
					$customer = new Customer_Setup();
					$send_otp_response = json_decode($customer->send_otp_token($phone,$auth_type, $this->defaultCustomerKey,$this->defaultApiKey),true);
					if(strcasecmp($send_otp_response['status'], 'SUCCESS') == 0){
						//Save txId
					
						update_user_meta($current_user->ID,'mo_2fa_verify_otp_create_account',$send_otp_response['txId']);
						update_user_meta($current_user->ID, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_SUCCESS');
						if(get_user_meta($current_user->ID,'mo2f_sms_otp_count',true)){
							update_user_meta($current_user->ID,'mo2f_sms_otp_count',get_user_meta($current_user->ID,'mo2f_sms_otp_count',true) + 1);
							update_site_option('mo2f_message', 'Another One Time Passcode has been sent <b>( ' . get_user_meta($current_user->ID,'mo2f_sms_otp_count',true) . ' )</b> for verification to ' . $phone);
						}else{
								update_site_option('mo2f_message', 'One Time Passcode has been sent for verification to ' . $phone);
								update_user_meta($current_user->ID,'mo2f_sms_otp_count',1);
						}
					
						$this->mo_auth_show_success_message();
					}else{
				update_site_option('mo2f_message','There was an error in sending sms. Please click on Resend OTP to try again.');
				update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');
				$this->mo_auth_show_error_message();
			}
		}
	
		if(isset($_POST['option']) and trim($_POST['option']) == "mo_2factor_resend_otp"){ //resend OTP over email for admin
			$customer = new Customer_Setup();
			$content = json_decode($customer->send_otp_token(get_site_option('mo2f_email'),'EMAIL',$this->defaultCustomerKey,$this->defaultApiKey), true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				if(get_user_meta($current_user->ID,'mo2f_email_otp_count',true)){
					update_user_meta($current_user->ID,'mo2f_email_otp_count',get_user_meta($current_user->ID,'mo2f_email_otp_count',true) + 1);
					update_site_option( 'mo2f_message', 'Another OTP has been sent <b>( ' . get_user_meta($current_user->ID,'mo2f_email_otp_count',true) .' )</b> to <b>' . ( get_site_option('mo2f_email') ) . '</b>. Please enter the OTP below to verify your email. ');
				}else{
					update_site_option( 'mo2f_message', 'An OTP has been sent to <b>' . ( get_site_option('mo2f_email') ) . '</b>. Please enter the OTP below to verify your email. ');
					update_user_meta($current_user->ID,'mo2f_email_otp_count',1);
				}
				update_user_meta($current_user->ID,'mo_2fa_verify_otp_create_account',$content['txId']);
				update_user_meta($current_user->ID, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_SUCCESS');
				$this->mo_auth_show_success_message();
			}else{
				update_site_option('mo2f_message','There was an error in sending email. Please click on Resend OTP to try again.');
				update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');
				$this->mo_auth_show_error_message();
			}
		
		}
		
		if(isset($_POST['option']) and $_POST['option'] == "mo_2factor_validate_otp"){ //validate OTP over email for admin
			
			//validation and sanitization
			$otp_token = '';
			if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['otp_token'] ) ) {
				update_site_option( 'mo2f_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_auth_show_error_message();
				return;
			} else{
				$otp_token = sanitize_text_field( $_POST['otp_token'] );
			}
			
			$customer = new Customer_Setup();
			$transactionId = get_user_meta($current_user->ID,'mo_2fa_verify_otp_create_account',true);
			
			$content = json_decode($customer->validate_otp_token( 'EMAIL', null,$transactionId, $otp_token, $this->defaultCustomerKey, $this->defaultApiKey ),true);
			if($content['status'] == 'ERROR'){
				update_site_option( 'mo2f_message', $content['message']);
				$this->mo_auth_show_error_message();
			}else{
				if(strcasecmp($content['status'], 'SUCCESS') == 0) { //OTP validated and generate QRCode
					$this->mo2f_create_customer($current_user);
					delete_user_meta($current_user->ID,'mo_2fa_verify_otp_create_account');
				}else{  // OTP Validation failed.
					update_site_option( 'mo2f_message','Invalid OTP. Please try again.');
					update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');
					$this->mo_auth_show_error_message();
				}
			}
		}
		
		if(isset($_POST['option']) and $_POST['option'] == "mo_2factor_validate_user_otp"){ //validate OTP over email for additional admin
			
			//validation and sanitization
			$otp_token = '';
			if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['otp_token'] ) ) {
				update_site_option( 'mo2f_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_auth_show_error_message();
				return;
			} else{
				$otp_token = sanitize_text_field( $_POST['otp_token'] );
			}
			
			if(!MO2f_Utility::check_if_email_is_already_registered($current_user->ID, get_user_meta($current_user->ID,'mo_2factor_user_email',true))){
				$customer = new Customer_Setup();
				$content = json_decode($customer->validate_otp_token( 'EMAIL', null, $_SESSION[ 'mo2f_transactionId' ], $otp_token, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
				if($content['status'] == 'ERROR'){
					update_site_option( 'mo2f_message', $content['message']);
					$this->mo_auth_show_error_message();
				}else{
					if(strcasecmp($content['status'], 'SUCCESS') == 0) { //OTP validated and generate QRCode
						$this->mo2f_create_user($current_user,get_user_meta($current_user->ID,'mo_2factor_user_email',true));
						delete_user_meta($current_user->ID,'mo_2fa_verify_otp_create_account');
					}else{  // OTP Validation failed.
						update_site_option( 'mo2f_message','Invalid OTP. Please try again.');
						update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');
						$this->mo_auth_show_error_message();
					}
				}
			}else{
				update_site_option('mo2f_message','The email is already used by other user. Please register with other email by clicking on Back button.');	
				$this->mo_auth_show_error_message();
			}
		}
		
		if(isset($_POST['option']) and $_POST['option'] == "mo_2factor_send_query"){ //Help me or support
			$query = '';
			if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['query_email'] ) || MO2f_Utility::mo2f_check_empty_or_null( $_POST['query'] ) ) {
				update_site_option( 'mo2f_message', 'Please submit your query with email.');
				$this->mo_auth_show_error_message();
				return;
			} else{
				$query = sanitize_text_field( $_POST['query'] );
				$email = sanitize_text_field( $_POST['query_email'] );
				$phone = sanitize_text_field( $_POST['query_phone'] );
				$contact_us = new Customer_Setup();
				$submited = json_decode($contact_us->submit_contact_us($email, $phone, $query),true);
				if(json_last_error() == JSON_ERROR_NONE) {
					if(is_array($submited) && array_key_exists('status', $submited) && $submited['status'] == 'ERROR'){
						update_site_option( 'mo2f_message', $submited['message']);
						$this->mo_auth_show_error_message();
					}else{
						if ( $submited == false ) {
							update_site_option('mo2f_message', 'Your query could not be submitted. Please try again.');
							$this->mo_auth_show_error_message();
						} else {
							update_site_option('mo2f_message', 'Thanks for getting in touch! We shall get back to you shortly.');
							$this->mo_auth_show_success_message();
						}
					}
				}

			}
		}
		
		if(isset($_POST['option']) and $_POST['option'] == 'mo_auth_advanced_options_save'){
			
			//plugin customization
			update_site_option( 'mo2f_disable_poweredby', isset( $_POST['mo2f_disable_poweredby']) ? $_POST['mo2f_disable_poweredby'] : 0);
			update_site_option( 'mo2f_enable_custom_poweredby', isset( $_POST['mo2f_enable_custom_poweredby']) ? $_POST['mo2f_enable_custom_poweredby'] : 0);
			if (get_site_option('mo2f_disable_poweredby') == 1){
				update_site_option( 'mo2f_enable_custom_poweredby',0);
			}
			update_site_option( 'mo2f_enable_custom_icon', isset( $_POST['mo2f_enable_custom_icon']) ? $_POST['mo2f_enable_custom_icon'] : 0);
			update_site_option( 'mo2f_custom_plugin_name',  isset($_POST['mo2f_custom_plugin_name']) ? $_POST['mo2f_custom_plugin_name'] : 'miniOrange 2-Factor');
			
			update_site_option( 'mo2f_message', 'Your settings are saved successfully.');
			$this->mo_auth_show_success_message();
		}
		
		if(isset($_POST['option']) and $_POST['option'] == 'mo_auth_save_custom_settings'){
			update_site_option( 'mo2f_disable_kba', isset( $_POST['mo2f_disable_kba']) ? $_POST['mo2f_disable_kba'] : 0);
			update_site_option( 'mo2f_enable_rba', isset( $_POST['mo2f_enable_rba']) ? $_POST['mo2f_enable_rba'] : 0);
			update_site_option( 'mo2f_enable_rba_types', isset( $_POST['mo2f_enable_rba_types']) ? $_POST['mo2f_enable_rba_types'] : 0);
			
			update_site_option( 'mo2f_message', 'Your settings are saved successfully.');
			$this->mo_auth_show_success_message();
		}
		
		if(isset($_POST['option']) and $_POST['option'] == 'custom_login_form_save'){
			update_site_option( 'mo2f_rba_loginform_id',  isset($_POST['mo2f_rba_loginform_id']) ? $_POST['mo2f_rba_loginform_id'] : 'loginform');
		}
		
		if(isset($_POST['option']) and $_POST['option'] == 'mo_auth_save_custom_security_questions'){
			$questions_by_admin = array();
			$questions_by_admin = isset($_POST['mo2f_kbaquestion_custom_admin']) ? $_POST['mo2f_kbaquestion_custom_admin'] : array();
			$temp_array = array();
			foreach($questions_by_admin as $question){
				if(!MO2f_Utility::mo2f_check_empty_or_null( $question)){
					$question = sanitize_text_field($question);
					$question = addcslashes(stripslashes($question), '"\\');
					array_push($temp_array, $question);
				}
			}
			
			if(sizeof($temp_array) < 10){
				update_site_option( 'mo2f_message', 'Please select atleast 10 questions.');
				$this->mo_auth_show_success_message();
				return;
			}
			
			$default_questions_by_user='2';
			if(MO2f_Utility::mo2f_check_empty_or_null( $_POST['mo2f_default_kbaquestions_users'])){
				$default_questions_by_user = '2';
			}else{
				$default_questions_by_user = $_POST['mo2f_default_kbaquestions_users'];
				if($default_questions_by_user > 5){
					$default_questions_by_user = '5';
				}
			}
			
			$custom_questions_by_user = '1';
			if(MO2f_Utility::mo2f_check_empty_or_null( $_POST['mo2f_custom_kbaquestions_users'])){
				$custom_questions_by_user = '1';
			}else{
				$custom_questions_by_user = $_POST['mo2f_custom_kbaquestions_users'];
				if($custom_questions_by_user > 5){
					$custom_questions_by_user = '5';
				}
			}
			
			update_site_option( 'mo2f_auth_admin_custom_kbaquestions', $temp_array);
			update_site_option( 'mo2f_default_kbaquestions_users', $default_questions_by_user);
			update_site_option( 'mo2f_custom_kbaquestions_users', $custom_questions_by_user);
			
			update_site_option( 'mo2f_message', 'Your settings are saved successfully.');
			$this->mo_auth_show_success_message();
		}
		
		
		if(isset($_POST['option']) and $_POST['option'] == 'mo_auth_login_settings_save'){

			if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS' ) {
				
				update_option('mo2f_all_users_method',isset( $_POST['mo2f_all_users_method']) ? $_POST['mo2f_all_users_method'] : 1);
				update_site_option( 'mo2f_by_roles', isset( $_POST['mo2f_by_roles']) ? $_POST['mo2f_by_roles'] : 1);
				update_site_option( 'mo2f_inline_registration', isset( $_POST['mo2f_inline_registration']) ? $_POST['mo2f_inline_registration'] : 0);
					$authM = array();
					
					global $wp_roles;
				if (!isset($wp_roles))
					$wp_roles = new WP_Roles();
				foreach($wp_roles->role_names as $id => $name)
				{
					if(isset($_POST[$id]))
					$authM[$id]=$_POST[$id];
				
				}
				
				update_site_option( 'mo2f_enable_emailchange', isset( $_POST['mo2f_enable_emailchange']) ? $_POST['mo2f_enable_emailchange'] : 0);
				$authMethods = array();
				$authMethod = isset($_POST['mo2f_authmethods']) ? $_POST['mo2f_authmethods'] : array();
				foreach ($authMethod as $arrayvalue){
					$authMethods[$arrayvalue] = $arrayvalue;
				}
				update_option( 'mo2f_auth_methods_for_users', $authMethods);
				foreach($wp_roles->role_names as $id => $name)
				{
					if(isset($authM[$id]))
					update_option( 'mo2f_auth_methods_for_'.$id ,$authM[$id]);
					else
					update_option( 'mo2f_auth_methods_for_'.$id ,'');
				}
				
				update_site_option( 'mo2f_auth_methods_for_users', $authMethods);
				
				if(isset( $_POST['mo2f_login_policy']) && $_POST['mo2f_login_policy']){
					update_site_option( 'mo2f_login_policy', 1);
				} else{
					update_site_option( 'mo2f_login_policy', 0);
					update_site_option( 'mo2f_enable_rba', 0);
					
				}
				
				if(isset( $_POST['mo2f_loginwith_phone']) && $_POST['mo2f_loginwith_phone']){
					update_site_option( 'mo2f_show_loginwith_phone', 1);
				}else{
					update_site_option( 'mo2f_show_loginwith_phone', 0);
					update_site_option( 'mo2f_enable_rba', 0);
				}
				
				update_site_option( 'mo2f_enable_forgotphone', isset( $_POST['mo2f_forgotphone']) ? $_POST['mo2f_forgotphone'] : 0);
				if(get_site_option('mo2f_enable_forgotphone')){
					update_site_option( 'mo2f_enable_forgotphone_kba', isset( $_POST['mo2f_forgotphone_kba']) ? $_POST['mo2f_forgotphone_kba'] : 0);
					update_site_option( 'mo2f_enable_forgotphone_email', isset( $_POST['mo2f_forgotphone_email']) ? $_POST['mo2f_forgotphone_email'] : 0);
					if(!get_site_option( 'mo2f_enable_forgotphone_kba') && !get_site_option( 'mo2f_enable_forgotphone_email')){
						update_site_option( 'mo2f_enable_forgotphone_kba',  1);
					}
				}else{
					update_site_option( 'mo2f_enable_forgotphone_kba',  0);
					update_site_option( 'mo2f_enable_forgotphone_email', 0);
				}
				
				
				update_site_option( 'mo2f_activate_plugin', isset( $_POST['mo2f_activate_plugin']) ? $_POST['mo2f_activate_plugin'] : 0);
				update_site_option( 'mo2f_enable_mobile_support', isset( $_POST['mo2f_enable_mobile_support']) ? $_POST['mo2f_enable_mobile_support'] : 0);
				update_site_option( 'mo2f_enable_xmlrpc', isset( $_POST['mo2f_enable_xmlrpc']) ? $_POST['mo2f_enable_xmlrpc'] : 0);
				
				global $wp_roles;
				if (!isset($wp_roles))
					$wp_roles = new WP_Roles();
				foreach($wp_roles->role_names as $id => $name) {
					update_site_option('mo2fa_'.$id, isset( $_POST['mo2fa_'.$id] ) ? $_POST['mo2fa_'.$id] : 0);
					if($id == 'administrator'){
						update_option('mo2fa_'.$id.'_login_url', !empty( $_POST['mo2fa_'.$id.'_login_url'] ) ? $_POST['mo2fa_'.$id.'_login_url'] : null);
					}else{
						update_option('mo2fa_'.$id.'_login_url', !empty( $_POST['mo2fa_'.$id.'_login_url'] ) ? $_POST['mo2fa_'.$id.'_login_url'] : null);
					}
				}
				
				
				if(get_site_option('mo2f_activate_plugin')){
					$logouturl = wp_login_url() . '?action=logout';
					update_site_option( 'mo2f_message', 'Your login settings are saved successfully. Now <a href=\"'.$logouturl.'\"><b>Click Here</b></a> to logout and try login with 2-Factor.');
					update_site_option( 'mo2f_msg_counter',2);
					$this->mo_auth_show_success_message();
				}else{
					update_site_option( 'mo2f_message', 'Two-Factor plugin has been disabled.');
					update_site_option( 'mo2f_msg_counter',2);
					$this->mo_auth_show_error_message();
				}
				
			
				if(get_site_option( 'mo2f_enable_rba' ) && !get_site_option( 'mo2f_app_secret' )){
					$get_app_secret = new Miniorange_Rba_Attributes();
					$rba_response = json_decode($get_app_secret->mo2f_get_app_secret(),true); //fetch app secret
					if(json_last_error() == JSON_ERROR_NONE){
						if($rba_response['status'] == 'SUCCESS'){ 
							update_site_option( 'mo2f_app_secret',$rba_response['appSecret'] );
						}else{
							update_site_option( 'mo2f_enable_rba', 0);
							update_site_option( 'mo2f_message', 'Error occurred while saving the settings.Please try again.');
							$this->mo_auth_show_error_message();
						}
					}else{
						update_site_option( 'mo2f_enable_rba', 0);
						update_site_option( 'mo2f_message', 'Error occurred while saving the settings.Please try again.');
						$this->mo_auth_show_error_message();
					}
				}
			}else{
				update_site_option( 'mo2f_message', 'Invalid request. Please register with miniOrange and configure 2-Factor to save your login settings.');
				$this->mo_auth_show_error_message();
			}
		}
		
		if(isset($_POST['option']) and $_POST['option'] == 'mo_2factor_gobackto_registration_page'){ //back to registration page for admin
			delete_site_option('mo2f_email');
			delete_site_option('mo2f_password');
			delete_site_option('mo2f_customerKey');
			delete_site_option('mo2f_app_secret');
			unset($_SESSION[ 'mo2f_transactionId' ]);
			delete_user_meta($current_user->ID,'mo_2factor_map_id_with_email');
			delete_user_meta($current_user->ID,'mo_2factor_user_registration_status');
			delete_user_meta($current_user->ID,'mo2f_sms_otp_count');
			delete_user_meta($current_user->ID,'mo2f_email_otp_count');
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo_2factor_forgot_password'){ // if admin forgot password
			if(isset( $_POST['email']) ){
				if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['email'] ) ) {
					update_site_option( 'mo2f_message', 'Please enter your registered email below to reset your password.');
					
					
					$this->mo_auth_show_error_message();
					return;
				}else
					$email = sanitize_email($_POST['email']);
				
			}
			
			$customer = new Customer_Setup();
				$content = json_decode($customer->forgot_password($email),true);
					if(strcasecmp($content['status'], 'SUCCESS') == 0){
						update_site_option( 'mo2f_message','You password has been reset successfully.  A new password has been sent to your registered mail.');
						$this->mo_auth_show_success_message();
					}else{
						update_site_option( 'mo2f_message','Your password could not be reset. Please enter your correct email in the textbox below and then click on the link.');
						$this->mo_auth_show_error_message();
					}
					
				
			}
			
			if(isset($_POST['miniorange_reset_2fa_option']) && $_POST['miniorange_reset_2fa_option'] == 'mo_reset_2fa'){
				$user_id = isset($_POST['userid']) && !empty($_POST['userid']) ? $_POST['userid'] : '';
				if(!empty($user_id)){
					$this->delete_mo2fa_users_data($user_id);
					?>
					<div id="message" class="updated notice is-dismissible">
						<p><strong>Reset 2nd Factor Successfully.</strong></p>
						<p><a href="<?php echo esc_url('users.php'); ?>">&larr;Back to Users</a></p>
						<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
					<?php
				}
			}
		
		
		}
		
		
		
		if(isset($_POST['option']) and trim($_POST['option']) == "mo_2factor_resend_user_otp"){ //resend OTP over email for additional admin and non-admin user
			$customer = new Customer_Setup();
			$content = json_decode($customer->send_otp_token(get_user_meta($current_user->ID,'mo_2factor_user_email',true),'EMAIL',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key')), true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				update_site_option( 'mo2f_message', 'An OTP has been sent to <b>' . ( get_user_meta($current_user->ID,'mo_2factor_user_email',true) ) . '</b>. Please enter the OTP below to verify your email. ');
				update_user_meta($current_user->ID,'mo_2fa_verify_otp_create_account',$content['txId']);
				update_user_meta($current_user->ID, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_SUCCESS');
				$this->mo_auth_show_success_message();
			}else{
				update_site_option('mo2f_message','There was an error in sending email. Please click on Resend OTP to try again.');
				update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');
				$this->mo_auth_show_error_message();
			}
		}
		
		if(isset($_POST['option']) and ($_POST['option'] == "mo_auth_mobile_registration_complete" || $_POST['option'] == 'mo_auth_mobile_reconfiguration_complete')){ //mobile registration successfully complete for all users
			unset($_SESSION[ 'mo2f_qrCode' ]);
			unset($_SESSION[ 'mo2f_transactionId' ]);
			unset($_SESSION[ 'mo2f_show_qr_code'] );
			$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
			$enduser = new Two_Factor_Setup();
			$response = json_decode($enduser->mo2f_update_userinfo($email,get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true),null,null,null),true);
			if(json_last_error() == JSON_ERROR_NONE) { /* Generate Qr code */
					if($response['status'] == 'ERROR'){
						update_site_option( 'mo2f_message', $response['message']);
						$this->mo_auth_show_error_message();
					}else if($response['status'] == 'SUCCESS'){
							$selectedMethod = get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true);
							$testmethod = $selectedMethod;
							if( $selectedMethod == 'MOBILE AUTHENTICATION'){
									$selectedMethod = "QR Code Authentication";
							}
							$message = '<b>' . $selectedMethod.'</b> is set as your 2nd factor method. <a href=\"#test\" data-method=\"' . $testmethod . '\">Click Here</a> to test ' . $selectedMethod . ' method.';
							update_site_option( 'mo2f_message', $message);
							update_user_meta($current_user->ID,'mo2f_mobile_registration_status',true);
							delete_user_meta($current_user->ID,'mo2f_configure_test_option');
							update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
							update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
							delete_user_meta($current_user->ID,'mo_2factor_mobile_registration_status');
							$this->mo_auth_show_success_message();
					}else{
							update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
							$this->mo_auth_show_error_message();
					}
					
			}else{
					update_site_option( 'mo2f_message','Invalid request. Please try again');
					$this->mo_auth_show_error_message();
			}
		
		}
		
		if(isset($_POST['option']) and $_POST['option'] == 'mo2f_mobile_authenticate_success'){ // mobile registration for all users(common)
			if(current_user_can('manage_options')){
				update_site_option( 'mo2f_message','You have successfully completed the test. Now <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_login&true\"><b>Click Here</b></a> to go to Login Settings. ');
			}else{
				update_site_option( 'mo2f_message','You have successfully completed the test. <a href='.wp_login_url() . '?action=logout><b>Click Here</b></a> to logout and try login with 2-Factor.');
			}
			delete_user_meta($current_user->ID,'mo2f_configure_test_option');
			unset($_SESSION['mo2f_qrCode']);
			unset($_SESSION['mo2f_transactionId']);
			unset($_SESSION['mo2f_show_qr_code']);
			$this->mo_auth_show_success_message();
		}
		
		if(isset($_POST['option']) and $_POST['option'] == 'mo2f_mobile_authenticate_error'){ //mobile registration failed for all users(common)
			update_site_option( 'mo2f_message','Authentication failed. Please try again to test the configuration.');
			unset($_SESSION['mo2f_show_qr_code']);
			$this->mo_auth_show_error_message();
		}
	
		if(isset($_POST['option']) and $_POST['option'] == "mo_auth_setting_configuration"){ // redirect to setings page
			update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
		}
		
		if(isset($_POST['option']) and $_POST['option'] == "mo_auth_refresh_mobile_qrcode"){ // refrsh Qrcode for all users
			if(get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'
			||get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_INITIALIZE_MOBILE_REGISTRATION' 
			|| get_user_meta($current_user->ID,'mo_2factor_user_registration_status',true) == 'MO_2_FACTOR_PLUGIN_SETTINGS') {
				$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
				$this->mo2f_get_qr_code_for_mobile($email,$current_user->ID);
			}else{
				update_site_option( 'mo2f_message','Invalid request. Please register with miniOrange before configuring your mobile.');
				$this->mo_auth_show_error_message();
			}
		}
		
		if (isset($_POST['miniorange_get_started']) && isset($_POST['miniorange_user_reg_nonce'])){ //registration with miniOrange for additional admin and non-admin			
			$nonce = $_POST['miniorange_user_reg_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-user-reg-nonce' ) ) {
				update_site_option('mo2f_message','Invalid request');
			} else {
				$email = '';
				if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['mo_useremail'] )){
					update_site_option( 'mo2f_message', 'Please enter email-id to register.');
					return;
				}else{
					$email = sanitize_email( $_POST['mo_useremail'] );
				}
				
				if(!MO2f_Utility::check_if_email_is_already_registered($current_user->ID, $email)){
					update_user_meta($current_user->ID,'mo_2factor_user_email',$email);
					
					$enduser = new Two_Factor_Setup();
					$check_user = json_decode($enduser->mo_check_user_already_exist($email),true);
					if(json_last_error() == JSON_ERROR_NONE){
						if($check_user['status'] == 'ERROR'){
							update_site_option( 'mo2f_message', $check_user['message']);
							$this->mo_auth_show_error_message();
							return;
						}else if(strcasecmp($check_user['status'], 'USER_FOUND_UNDER_DIFFERENT_CUSTOMER') == 0){
							update_site_option( 'mo2f_message', 'The email you entered is already registered. Please register with another email to set up Two-Factor.');
							$this->mo_auth_show_error_message();
							return;
						}
						else if(strcasecmp($check_user['status'], 'USER_FOUND') == 0 || strcasecmp($check_user['status'], 'USER_NOT_FOUND') == 0){
					
							$enduser = new Customer_Setup();
							$content = json_decode($enduser->send_otp_token($email,'EMAIL',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key')), true);
							if(strcasecmp($content['status'], 'SUCCESS') == 0) {
								update_site_option( 'mo2f_message', 'An OTP has been sent to <b>' . ( $email ) . '</b>. Please enter the OTP below to verify your email. ');
								$_SESSION[ 'mo2f_transactionId' ] = $content['txId'];
								update_user_meta($current_user->ID, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_SUCCESS');
								$this->mo_auth_show_success_message();
							}else{
								update_site_option('mo2f_message','There was an error in sending OTP over email. Please click on Resend OTP to try again.');
								update_user_meta($current_user->ID, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');
								$this->mo_auth_show_error_message();
							}
						}
					}
				}else{
					update_site_option('mo2f_message','The email is already used by other user. Please register with other email.');	
					$this->mo_auth_show_error_message();
				}
			}
		}
		
		if(isset($_POST['option']) and $_POST['option'] == 'mo_2factor_backto_user_registration'){ //back to registration page for additional admin and non-admin
			delete_user_meta($current_user->ID,'mo_2factor_user_email');
			unset($_SESSION[ 'mo2f_transactionId' ]);
			delete_user_meta($current_user->ID,'mo_2factor_map_id_with_email');
			delete_user_meta($current_user->ID,'mo_2factor_user_registration_status');
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo_2factor_test_mobile_authentication'){ //test QR-Code authentication for all users
			
				$challengeMobile = new Customer_Setup();
				$content = $challengeMobile->send_otp_token(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true), 'MOBILE AUTHENTICATION',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key'));
				$response = json_decode($content, true);
				if(json_last_error() == JSON_ERROR_NONE) { /* Generate Qr code */
					if($response['status'] == 'ERROR'){
						update_site_option( 'mo2f_message', $response['message']);
						$this->mo_auth_show_error_message();
					}else{
						if($response['status'] == 'SUCCESS'){
						$_SESSION[ 'mo2f_qrCode' ] = $response['qrCode'];
						$_SESSION[ 'mo2f_transactionId' ] = $response['txId'];
						$_SESSION[ 'mo2f_show_qr_code'] = 'MO_2_FACTOR_SHOW_QR_CODE';
						update_site_option( 'mo2f_message','Please scan the QR Code now.');
						update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_TEST');
						update_user_meta( $current_user->ID,'mo2f_selected_2factor_method', 'MOBILE AUTHENTICATION');
						$this->mo_auth_show_success_message();
						}else{
							unset($_SESSION[ 'mo2f_qrCode' ]);
							unset($_SESSION[ 'mo2f_transactionId' ]);
							unset($_SESSION[ 'mo2f_show_qr_code'] );
							update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
							$this->mo_auth_show_error_message();
						}
					}
				}else{
					update_site_option( 'mo2f_message','Invalid request. Please try again');
					$this->mo_auth_show_error_message();
				}
			
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo_2factor_test_soft_token'){  // Click on Test Soft Toekn link for all users
			update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_TEST');
			update_user_meta($current_user->ID, 'mo2f_selected_2factor_method', 'SOFT TOKEN');
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_validate_soft_token'){  // validate Soft Token during test for all users
				$otp_token = '';
				if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['otp_token'] ) ) {
				update_site_option( 'mo2f_message', 'Please enter a value to test your authentication.');
				$this->mo_auth_show_error_message();
				return;
			} else{
				$otp_token = sanitize_text_field( $_POST['otp_token'] );
			}
			$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
			$customer = new Customer_Setup();
			$content = json_decode($customer->validate_otp_token( 'SOFT TOKEN', $email, null, $otp_token, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
			if($content['status'] == 'ERROR'){
				update_site_option( 'mo2f_message', $content['message']);
				$this->mo_auth_show_error_message();
			}else{
				if(strcasecmp($content['status'], 'SUCCESS') == 0) { //OTP validated and generate QRCode
					if(current_user_can('manage_options')){
						update_site_option( 'mo2f_message','You have successfully completed the test. Now <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_login&true\"><b>Click Here</b></a> to go to Login Settings. ');
					}else{
						update_site_option( 'mo2f_message','You have successfully completed the test. <a href='.wp_login_url() . '?action=logout><b>Click Here</b></a> to logout and try login with 2-Factor.');
					}
					delete_user_meta($current_user->ID,'mo2f_configure_test_option');
					$this->mo_auth_show_success_message();
					
				}else{  // OTP Validation failed.
					update_site_option( 'mo2f_message','Invalid OTP. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo_2factor_test_otp_over_sms'){ //sending otp for sms and phone call during test for all users
			update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_TEST');
			update_user_meta($current_user->ID, 'mo2f_selected_2factor_method', $_POST['mo2f_selected_2factor_method']);
			
			$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
			$phone = get_user_meta($current_user->ID,'mo2f_user_phone',true);
			$two_factor_selected = get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true);
			
			$enduser = new Customer_Setup();
			if($two_factor_selected == 'SMS AND EMAIL'){
				$currentMethod = 'OTP_OVER_SMS_AND_EMAIL';
				$parameters = array("email" => $email, "phone" => $phone);
				$content = json_decode($enduser->send_otp_token($parameters, $currentMethod, get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key')), true);
			}
			else{
				$content = json_decode($enduser->send_otp_token($email,$_POST['mo2f_selected_2factor_method'],get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key')), true);
			}
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'SMS'){
					update_site_option( 'mo2f_message', 'An OTP has been sent to <b>' . ( $phone ) . '</b>. Please enter the one time passcode below. ');
				}else if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'PHONE VERIFICATION'){
					update_site_option( 'mo2f_message','You will receive a phone call on this number ' . $phone . '. Please enter the one time passcode below.');
				}else if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'SMS AND EMAIL'){
					update_site_option( 'mo2f_message', 'An OTP has been sent to <b>' . ( $phone ) . ' and ' . ( $email ) .' </b>. Please enter the one time passcode below. ');
				}
				$_SESSION[ 'mo2f_transactionId' ] = $content['txId'];
				$this->mo_auth_show_success_message();
			}else{
					update_site_option('mo2f_message','There was an error in sending one time passcode. Please click on Resend OTP to try again.');
					$this->mo_auth_show_error_message();
				}		
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_validate_otp_over_sms'){ //validate otp over sms and phone call during test for all users
				$otp_token = '';
				if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['otp_token'] ) ) {
				update_site_option( 'mo2f_message', 'Please enter a value to test your authentication.');
				$this->mo_auth_show_error_message();
				return;
			} else{
				$otp_token = sanitize_text_field( $_POST['otp_token'] );
			}
			$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
			$customer = new Customer_Setup();
			$content = json_decode($customer->validate_otp_token( get_user_meta($current_user->ID, 'mo2f_selected_2factor_method',true), $email,$_SESSION[ 'mo2f_transactionId' ], $otp_token, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
			if($content['status'] == 'ERROR'){
				update_site_option( 'mo2f_message', $content['message']);
				$this->mo_auth_show_error_message();
			}else{
				if(strcasecmp($content['status'], 'SUCCESS') == 0) { //OTP validated
					if(current_user_can('manage_options')){
						update_site_option( 'mo2f_message','You have successfully completed the test. Now <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_login&true\"><b>Click Here</b></a> to go to Login Settings. ');
					}else{
						update_site_option( 'mo2f_message','You have successfully completed the test. <a href='.wp_login_url() . '?action=logout><b>Click Here</b></a> to logout and try login with 2-Factor.');
					}
					delete_user_meta($current_user->ID,'mo2f_configure_test_option');
					$this->mo_auth_show_success_message();
					
				}else{  // OTP Validation failed.
					update_site_option( 'mo2f_message','Invalid OTP. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo_2factor_test_push_notification'){
			
				$challengeMobile = new Customer_Setup();
				$content = $challengeMobile->send_otp_token(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true), 'PUSH NOTIFICATIONS',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key'));
				$response = json_decode($content, true);
				if(json_last_error() == JSON_ERROR_NONE) { /* Generate Qr code */
					if($response['status'] == 'ERROR'){
						update_site_option( 'mo2f_message', $response['message']);
						$this->mo_auth_show_error_message();
					}else{
						if($response['status'] == 'SUCCESS'){
						$_SESSION[ 'mo2f_transactionId' ] = $response['txId'];
						$_SESSION[ 'mo2f_show_qr_code'] = 'MO_2_FACTOR_SHOW_QR_CODE';
						update_site_option( 'mo2f_message','A Push notification has been sent to your miniOrange Authenticator App.');
						update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_TEST');
						update_user_meta( $current_user->ID,'mo2f_selected_2factor_method', 'PUSH NOTIFICATIONS');
						$this->mo_auth_show_success_message();
						}else{
							unset($_SESSION[ 'mo2f_qrCode' ]);
							unset($_SESSION[ 'mo2f_transactionId' ]);
							unset($_SESSION[ 'mo2f_show_qr_code'] );
							update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
							$this->mo_auth_show_error_message();
						}
					}
				}else{
					update_site_option( 'mo2f_message','Invalid request. Please try again');
					$this->mo_auth_show_error_message();
				}
			
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo_2factor_test_out_of_band_email'){
			$this->miniorange_email_verification_call($current_user);
		}

		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_out_of_band_success'){
			if(!current_user_can('manage_options') && get_user_meta( $current_user->ID,'mo2f_selected_2factor_method', true) == 'OUT OF BAND EMAIL'){
				if(get_user_meta($current_user->ID,'mo2f_email_verification_status',true)){
					update_site_option( 'mo2f_message','You have successfully completed the test.');
				}else{
					$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
					$enduser = new Two_Factor_Setup();
					$response = json_decode($enduser->mo2f_update_userinfo($email, get_user_meta( $current_user->ID,'mo2f_selected_2factor_method', true),null,null,null),true);
					update_site_option( 'mo2f_message','<b>Email Verification</b> has been set as your 2nd factor method.');
				}
			}else{
				update_site_option( 'mo2f_message','You have successfully completed the test. Now <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_login&true\"><b>Click Here</b></a> to go to Login Settings. ');
			}
			delete_user_meta($current_user->ID,'mo2f_configure_test_option');
			update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
			update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
			$this->mo_auth_show_success_message();
			
		}
		
		if(isset($_POST['option']) and $_POST['option'] == 'mo2f_out_of_band_error'){ //push and out of band email denied
			update_site_option( 'mo2f_message','You have denied the request.');
			delete_user_meta($current_user->ID,'mo2f_configure_test_option');
			update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
			update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
			$this->mo_auth_show_error_message();
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo_2factor_test_google_auth'){
			update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_TEST');
			update_user_meta($current_user->ID, 'mo2f_selected_2factor_method', 'GOOGLE AUTHENTICATOR');
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_validate_google_auth_test'){
			$otp_token = '';
			if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['otp_token'] ) ) {
			update_site_option( 'mo2f_message', 'Please enter a value to test your authentication.');
			$this->mo_auth_show_error_message();
			return;
			} else{
				$otp_token = sanitize_text_field( $_POST['otp_token'] );
			}
			$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
			$customer = new Customer_Setup();
			$content = json_decode($customer->validate_otp_token( 'GOOGLE AUTHENTICATOR', $email, null, $otp_token, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key')),true);
			if(json_last_error() == JSON_ERROR_NONE) {
		
				if(strcasecmp($content['status'], 'SUCCESS') == 0) { //Google OTP validated 
					if(current_user_can('manage_options')){
						update_site_option( 'mo2f_message','You have successfully completed the test. Now <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_login&true\"><b>Click Here</b></a> to go to Login Settings. ');
					}else{
						update_site_option( 'mo2f_message','You have successfully completed the test.');
					}
					delete_user_meta($current_user->ID,'mo2f_configure_test_option');
					$this->mo_auth_show_success_message();
					
				}else{  // OTP Validation failed.
					update_site_option( 'mo2f_message','Invalid OTP. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}else{
				update_site_option( 'mo2f_message','Error occurred while validating the OTP. Please try again.');
				$this->mo_auth_show_error_message();
			}
		}
		
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_configure_google_auth_phone_type' ){
			
			$phone_type = $_POST['mo2f_app_type_radio'];
			$google_auth = new Miniorange_Rba_Attributes();
			$google_response = json_decode($google_auth->mo2f_google_auth_service(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true)),true);
			if(json_last_error() == JSON_ERROR_NONE) {
				if($google_response['status'] == 'SUCCESS'){
					$mo2f_google_auth = array();
					$mo2f_google_auth['ga_qrCode'] = $google_response['qrCodeData'];
					$mo2f_google_auth['ga_secret'] = $google_response['secret'];
					$mo2f_google_auth['ga_phone'] = $phone_type;
					$_SESSION['mo2f_google_auth'] = $mo2f_google_auth;
				}else{
					update_site_option( 'mo2f_message','Error occurred while registering the user. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}else{
				update_site_option( 'mo2f_message','Error occurred while registering the user. Please try again.');
				$this->mo_auth_show_error_message();
			}
		}
		
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_validate_google_auth' ){
			$otpToken = $_POST['google_token'];
			$mo2f_google_auth = isset($_SESSION['mo2f_google_auth']) ? $_SESSION['mo2f_google_auth'] : null;
			$ga_secret = $mo2f_google_auth != null ? $mo2f_google_auth['ga_secret'] : null;
			if(MO2f_Utility::mo2f_check_number_length($otpToken)){
				$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
				$google_auth = new Miniorange_Rba_Attributes();
				$google_response = json_decode($google_auth->mo2f_validate_google_auth($email,$otpToken,$ga_secret),true);
				if(json_last_error() == JSON_ERROR_NONE) {
					if($google_response['status'] == 'SUCCESS'){
						$enduser = new Two_Factor_Setup();
						$response = json_decode($enduser->mo2f_update_userinfo($email,get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true),null,null,null),true);
						if(json_last_error() == JSON_ERROR_NONE) { 
							
							if($response['status'] == 'SUCCESS'){
							
								update_user_meta($current_user->ID,'mo2f_google_authentication_status',true);
								update_user_meta($current_user->ID,'mo2f_authy_authentication_status',false);
								delete_user_meta($current_user->ID,'mo2f_configure_test_option');
								delete_user_meta($current_user->ID,'mo_2factor_mobile_registration_status');
								update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
								update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
								update_user_meta($current_user->ID,'mo2f_external_app_type','GOOGLE AUTHENTICATOR');
								unset($_SESSION['mo2f_google_auth']);		
								$message = '<b>Google Authenticator</b> has been set as your 2nd factor method. <a href=\"#test\" data-method=\"GOOGLE AUTHENTICATOR\">Click Here</a> to test Google Authenticator method.';
								update_site_option( 'mo2f_message',$message );
								$this->mo_auth_show_success_message();
								
							}else{
								update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
								$this->mo_auth_show_error_message();
							}
						}else{
							update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
							$this->mo_auth_show_error_message();
						}
					}else{
						update_site_option( 'mo2f_message','Error occurred while validating the OTP. Please try again. Possible causes: <br />1. You have enter invalid OTP.<br />2. Your App Time is not in sync.Go to settings and tap on tap on Sync Time now .');
						$this->mo_auth_show_error_message();
					}
				}else{
					update_site_option( 'mo2f_message','Error occurred while validating the user. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}else{
				update_site_option( 'mo2f_message','Only digits are allowed. Please enter again.');
				$this->mo_auth_show_error_message();
			}
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo_2factor_test_authy_auth'){
			update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_TEST');
			update_user_meta($current_user->ID, 'mo2f_selected_2factor_method', 'GOOGLE AUTHENTICATOR');
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_configure_authy_app' ){
			$authy = new Miniorange_Rba_Attributes();
			$authy_response = json_decode($authy->mo2f_google_auth_service(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true)),true);
			if(json_last_error() == JSON_ERROR_NONE) {
				if($authy_response['status'] == 'SUCCESS'){
					$mo2f_authy_keys = array();
					$mo2f_authy_keys['authy_qrCode'] = $authy_response['qrCodeData'];
					$mo2f_authy_keys['authy_secret'] = $authy_response['secret'];
					$_SESSION['mo2f_authy_keys'] = $mo2f_authy_keys;
				}else{
					update_site_option( 'mo2f_message','Error occurred while registering the user. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}else{
				update_site_option( 'mo2f_message','Error occurred while registering the user. Please try again.');
				$this->mo_auth_show_error_message();
			}
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_validate_authy_auth' ){
			$otpToken = $_POST['authy_token'];
			$mo2f_google_auth = isset($_SESSION['mo2f_authy_keys']) ? $_SESSION['mo2f_authy_keys'] : null;
			$authy_secret = $mo2f_google_auth != null ? $mo2f_google_auth['authy_secret'] : null;
			if(MO2f_Utility::mo2f_check_number_length($otpToken)){
				$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
				$authy_auth = new Miniorange_Rba_Attributes();
				$authy_response = json_decode($authy_auth->mo2f_validate_google_auth($email,$otpToken,$authy_secret),true);
				if(json_last_error() == JSON_ERROR_NONE) {
					if($authy_response['status'] == 'SUCCESS'){
						$enduser = new Two_Factor_Setup();
						$response = json_decode($enduser->mo2f_update_userinfo($email,'GOOGLE AUTHENTICATOR',null,null,null),true);
						if(json_last_error() == JSON_ERROR_NONE) { 
							
							if($response['status'] == 'SUCCESS'){
							
								update_user_meta($current_user->ID,'mo2f_authy_authentication_status',true);
								update_user_meta($current_user->ID,'mo2f_google_authentication_status',false);
								delete_user_meta($current_user->ID,'mo2f_configure_test_option');
								delete_user_meta($current_user->ID,'mo_2factor_mobile_registration_status');
								update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
								update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
								update_user_meta($current_user->ID,'mo2f_external_app_type','AUTHY 2-FACTOR AUTHENTICATION');
								unset($_SESSION['mo2f_authy_keys']);		
								$message = '<b>Authy 2-Factor Authentication</b> has been set as your 2nd factor method. <a href=\"#test\" data-method=\"AUTHY 2-FACTOR AUTHENTICATION\">Click Here</a> to test Authy 2-Factor Authentication method.';
								update_site_option( 'mo2f_message',$message );
								$this->mo_auth_show_success_message();
								
							}else{
								update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
								$this->mo_auth_show_error_message();
							}
						}else{
							update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
							$this->mo_auth_show_error_message();
						}
					}else{
						update_site_option( 'mo2f_message','Error occurred while validating the OTP. Please try again. Possible causes: <br />1. You have enter invalid OTP.<br />2. Your App Time is not in sync.Go to settings and tap on tap on Sync Time now .');
						$this->mo_auth_show_error_message();
					}
				}else{
					update_site_option( 'mo2f_message','Error occurred while validating the user. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}else{
				update_site_option( 'mo2f_message','Only digits are allowed. Please enter again.');
				$this->mo_auth_show_error_message();
			}
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_save_kba'){
			
			$temp_array = array();
			$temp_array = isset($_POST['mo2f_kbaquestion']) ? $_POST['mo2f_kbaquestion'] : array();
			$kba_questions = array();
			foreach($temp_array as $question){
				if(MO2f_Utility::mo2f_check_empty_or_null( $question)){
					update_site_option( 'mo2f_message', 'All the fields are required. Please enter valid entries.');
					$this->mo_auth_show_error_message();
					return;
				}else{
					$ques = sanitize_text_field($question);
					$ques = addcslashes(stripslashes($ques), '"\\');
					array_push($kba_questions, $ques);
				}
			}
			
			if(!(array_unique($kba_questions) == $kba_questions)){
				update_site_option( 'mo2f_message', 'The questions you select must be unique.');
				$this->mo_auth_show_error_message();
				return;
			}
			
			$temp_array_ans = array();
			$temp_array_ans = isset($_POST['mo2f_kba_ans']) ? $_POST['mo2f_kba_ans'] : array();
			$kba_answers = array();
			foreach($temp_array_ans as $answer){
				if(MO2f_Utility::mo2f_check_empty_or_null( $answer)){
					update_site_option( 'mo2f_message', 'All the fields are required. Please enter valid entries.');
					$this->mo_auth_show_error_message();
					return;
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
			
			$kba_registration = new Two_Factor_Setup();
			
			$kba_reg_reponse = json_decode($kba_registration->register_kba_details(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true),$kba_q_a_list),true);
			if(json_last_error() == JSON_ERROR_NONE) { 
				if($kba_reg_reponse['status'] == 'SUCCESS'){
					if(isset($_POST['mobile_kba_option']) && $_POST['mobile_kba_option'] == 'mo2f_request_for_kba_as_emailbackup'){
						unset($_SESSION['mo2f_mobile_support']);
						delete_user_meta($current_user->ID,'mo2f_configure_test_option');
						update_user_meta($current_user->ID,'mo2f_kba_registration_status',true);
						delete_user_meta( $current_user->ID,'mo2f_selected_2factor_method');
						$message = 'Your KBA as alternate 2 factor is configured successfully.';
						update_site_option( 'mo2f_message',$message );
						$this->mo_auth_show_success_message();
					}else{
						$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
						$enduser = new Two_Factor_Setup();
						update_user_meta( $current_user->ID,'mo2f_selected_2factor_method', 'KBA'); 
						$response = json_decode($enduser->mo2f_update_userinfo($email,'KBA',null,null,null),true);
						if(json_last_error() == JSON_ERROR_NONE) { 
							if($response['status'] == 'ERROR'){
								update_site_option( 'mo2f_message', $response['message']);
								$this->mo_auth_show_error_message();
							}else if($response['status'] == 'SUCCESS'){
								delete_user_meta($current_user->ID,'mo2f_configure_test_option');
								update_user_meta($current_user->ID,'mo2f_kba_registration_status',true);
								update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
								$authType = 'KBA';
								$message = '<b>' . $authType.'</b> is set as your 2nd factor method. <a href=\"#test\" data-method=\"' . $authType . '\">Click Here</a> to test ' . $authType . ' method.';
								update_site_option( 'mo2f_message',$message );
								$this->mo_auth_show_success_message();
							}else{
								update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
								$this->mo_auth_show_error_message();
							}
						}else{
							update_site_option( 'mo2f_message','Invalid request. Please try again');
							$this->mo_auth_show_error_message();
						}
					}
				}else{
					update_site_option( 'mo2f_message', 'Error occured while saving your kba details. Please try again.');
					$this->mo_auth_show_error_message();
					return;
				}
			}else{
				update_site_option( 'mo2f_message', 'Error occured while saving your kba details. Please try again.');
				$this->mo_auth_show_error_message();
				return;
			}
		
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_2factor_test_kba'){
		
			$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
			$challengeKba = new Customer_Setup();
			$content = $challengeKba->send_otp_token($email, 'KBA',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key'));
			$response = json_decode($content, true);
			if(json_last_error() == JSON_ERROR_NONE) { /* Generate KBA Questions*/
				if($response['status'] == 'SUCCESS'){
					update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_TEST');
					$_SESSION[ 'mo2f_transactionId' ] = $response['txId'];
					$questions = array();
					$questions[0] = $response['questions'][0]['question'];
					$questions[1] = $response['questions'][1]['question'];
					$_SESSION[ 'mo_2_factor_kba_questions' ] = $questions;
					update_user_meta($current_user->ID,'mo2f_selected_2factor_method','KBA');
					update_site_option( 'mo2f_message','Please answer the following security questions.');
					$this->mo_auth_show_success_message();
				}else if($response['status'] == 'ERROR'){
					update_site_option('mo2f_message','There was an error fetching security questions. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}else{
				update_site_option('mo2f_message','There was an error fetching security questions. Please try again.');
				$this->mo_auth_show_error_message();
			}		
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_validate_kba_details'){
			$kba_ans_1 = '';
			$kba_ans_2 = '';
			if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['mo2f_answer_1'] ) || MO2f_Utility::mo2f_check_empty_or_null( $_POST['mo2f_answer_1'] )) {
				update_site_option( 'mo2f_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_auth_show_error_message();
				return;
			} else{
				$kba_ans_1 = sanitize_text_field( $_POST['mo2f_answer_1'] );
				$kba_ans_2 = sanitize_text_field( $_POST['mo2f_answer_2'] );
			}
			
			$kbaAns = array();
			$kbaAns[0] = $_SESSION['mo_2_factor_kba_questions'][0];
			$kbaAns[1] = $kba_ans_1;
			$kbaAns[2] = $_SESSION['mo_2_factor_kba_questions'][1];
			$kbaAns[3] = $kba_ans_2;
						
			$kba_validate = new Customer_Setup();
			$kba_validate_response = json_decode($kba_validate->validate_otp_token( 'KBA', null, $_SESSION[ 'mo2f_transactionId' ], $kbaAns, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
			
			if(json_last_error() == JSON_ERROR_NONE) {
				if(strcasecmp($kba_validate_response['status'], 'SUCCESS') == 0) {
					update_site_option( 'mo2f_message','You have successfully completed the test. Now <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mo2f_login&true\"><b>Click Here</b></a> to go to Login Settings. ');
					delete_user_meta($current_user->ID,'mo2f_configure_test_option');
					$this->mo_auth_show_success_message();
					
				}else{  // KBA Validation failed.
					update_site_option( 'mo2f_message','Invalid Answers. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}
		}
			
			
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_verify_phone'){ // sendin otp for configuring OTP over SMS and Phone Call Verification
			$phone = sanitize_text_field( $_POST['verify_phone'] );
						
			if( MO2f_Utility::mo2f_check_empty_or_null( $phone ) ){
				update_site_option( 'mo2f_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_auth_show_error_message();
				return;
			}
			$phone = str_replace(' ', '', $phone);
			$_SESSION['mo2f_phone'] = $phone;
			$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
			
			$customer = new Customer_Setup();
				
				if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'SMS'){
					$currentMethod = "OTP_OVER_SMS";
				}else if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'PHONE VERIFICATION'){
					$currentMethod = "PHONE_VERIFICATION";
				}else if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'SMS AND EMAIL'){
					$currentMethod = "OTP_OVER_SMS_AND_EMAIL";
				}
				
				if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'SMS AND EMAIL'){
					$parameters = array("phone" => $phone, "email" => $email);
					$content = json_decode($customer->send_otp_token($parameters,$currentMethod,get_site_option( 'mo2f_customerKey'),get_site_option( 'mo2f_api_key')), true);
				}
				else{
					$content = json_decode($customer->send_otp_token($phone,$currentMethod,get_site_option( 'mo2f_customerKey'),get_site_option( 'mo2f_api_key')), true);
				}
			if(json_last_error() == JSON_ERROR_NONE) { /* Generate otp token */
				if($content['status'] == 'ERROR'){
					update_site_option( 'mo2f_message', $response['message']);
					$this->mo_auth_show_error_message();
				}else if($content['status'] == 'SUCCESS'){
					$_SESSION[ 'mo2f_transactionId' ] = $content['txId'];
					
					if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'SMS'){
							update_site_option( 'mo2f_message','The One Time Passcode has been sent to ' . $phone . '. Please enter the one time passcode below to verify your number.');
					}else if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true)== 'PHONE VERIFICATION'){
						update_site_option( 'mo2f_message','You will receive a phone call on this number ' . $phone . '. Please enter the one time passcode below to verify your number.');
					}else if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true)== 'SMS AND EMAIL'){
						update_site_option( 'mo2f_message','The One Time Passcode has been sent to ' . $phone . ' and to '. $email . ' Please enter the one time passcode below to verify your number.');
					}
					$this->mo_auth_show_success_message();
				}else{
					update_site_option( 'mo2f_message',$content['message']);
					$this->mo_auth_show_error_message();
				}
				
			}else{
				update_site_option( 'mo2f_message','Invalid request. Please try again');
				$this->mo_auth_show_error_message();
			}
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_validate_otp'){
			$otp_token = '';
			if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['otp_token'] ) ) {
				update_site_option( 'mo2f_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_auth_show_error_message();
				return;
			} else{
				$otp_token = sanitize_text_field( $_POST['otp_token'] );
			}
			
			$customer = new Customer_Setup();
			$content = json_decode($customer->validate_otp_token( get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true), null, $_SESSION[ 'mo2f_transactionId' ], $otp_token, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
			if($content['status'] == 'ERROR'){
				update_site_option( 'mo2f_message', $content['message']);
			
			}else if(strcasecmp($content['status'], 'SUCCESS') == 0) { //OTP validated 
					if(get_user_meta($current_user->ID,'mo2f_user_phone',true) && strlen(get_user_meta($current_user->ID,'mo2f_user_phone',true)) >= 4){
						if($_SESSION['mo2f_phone'] != get_user_meta($current_user->ID,'mo2f_user_phone',true) ){
							update_user_meta($current_user->ID,'mo2f_mobile_registration_status',false);
						}
					}
					$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
					$phone = $_SESSION['mo2f_phone'];
					
					$enduser = new Two_Factor_Setup();
					$enduser->mo2f_update_userinfo($email,get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true),$phone,null,null);
					$response = json_decode($enduser->mo2f_update_userinfo($email,get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true),$phone,null,null),true);
					if(json_last_error() == JSON_ERROR_NONE) { 
							
							if($response['status'] == 'ERROR'){
								unset($_SESSION[ 'mo2f_phone']);
								update_site_option( 'mo2f_message', $response['message']);
								$this->mo_auth_show_error_message();
							}else if($response['status'] == 'SUCCESS'){
								delete_user_meta($current_user->ID,'mo2f_configure_test_option');
								update_user_meta($current_user->ID,'mo2f_otp_registration_status',true);
								delete_user_meta($current_user->ID,'mo_2factor_mobile_registration_status');
								update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
								update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
								update_user_meta($current_user->ID,'mo2f_user_phone',$_SESSION[ 'mo2f_phone']);
								unset($_SESSION[ 'mo2f_phone']);
								$testmethod = get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true);
								if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'SMS'){
									$authType = "OTP Over SMS";
								}else if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'PHONE VERIFICATION'){
									$authType = "Phone Call Verification";
								}else if(get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true) == 'SMS AND EMAIL'){
									$authType = "OTP Over SMS and EMail";
								}
								$message = '<b>' . $authType.'</b> is set as your 2nd factor method. <a href=\"#test\" data-method=\"' . $testmethod . '\">Click Here</a> to test ' . $authType . ' method.';
								update_site_option( 'mo2f_message',$message );
								$this->mo_auth_show_success_message();
							}else{
									unset($_SESSION[ 'mo2f_phone']);
									update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
									$this->mo_auth_show_error_message();
							}
					}else{
							unset($_SESSION[ 'mo2f_phone']);
							update_site_option( 'mo2f_message','Invalid request. Please try again');
							$this->mo_auth_show_error_message();
					}
					
			}else{  // OTP Validation failed.
					update_site_option( 'mo2f_message','Invalid OTP. Please try again.');
					$this->mo_auth_show_error_message();
			}
			
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_save_2factor_method'){  // configure 2nd factor for all users
			if(get_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange',true) == 'SUCCESS'){
				if($_POST['mo2f_selected_2factor_method'] == 'OUT OF BAND EMAIL' && !current_user_can('manage_options')){
					$this->miniorange_email_verification_call($current_user);
				}
				update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_CONFIGURE'); //status for configuring the specific 2nd-factor method
				update_user_meta( $current_user->ID,'mo2f_selected_2factor_method', $_POST['mo2f_selected_2factor_method']); //status for second factor selected by user
			}else{
				update_site_option( 'mo2f_message','Invalid request. Please register with miniOrange to configure 2 Factor plugin.');
				$this->mo_auth_show_error_message();
			}
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_update_2factor_method'){ // save 2nd factor method for all users
				
					$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
					$enduser = new Two_Factor_Setup();
					update_user_meta( $current_user->ID,'mo2f_selected_2factor_method', $_POST['mo2f_selected_2factor_method']); 
					
					$current_method = $_POST['mo2f_selected_2factor_method'] == 'AUTHY 2-FACTOR AUTHENTICATION' ? 'GOOGLE AUTHENTICATOR' : $_POST['mo2f_selected_2factor_method'];
					$response = json_decode($enduser->mo2f_update_userinfo($email, $current_method,null,null,null),true);
					
					if(json_last_error() == JSON_ERROR_NONE) { 
							if($response['status'] == 'ERROR'){
								update_site_option( 'mo2f_message', $response['message']);
								$this->mo_auth_show_error_message();
							}else if($response['status'] == 'SUCCESS'){
								$selectedMethod = get_user_meta( $current_user->ID,'mo2f_selected_2factor_method',true);
								if($selectedMethod == 'OUT OF BAND EMAIL'){
									$selectedMethod = "Email Verification";
								} else if($selectedMethod == 'MOBILE AUTHENTICATION'){
									$selectedMethod = "QR Code Authentication";
								}else if($selectedMethod == 'SMS'){
									$authType = "OTP Over SMS";
								}else if($selectedMethod == 'SMS AND EMAIL'){
									$selectedMethod = 'OTP Over SMS And Email';
									$authType = "OTP Over SMS And Email";
								}else if($selectedMethod == 'GOOGLE AUTHENTICATOR' || $selectedMethod == 'AUTHY 2-FACTOR AUTHENTICATION'){
									update_user_meta($current_user->ID,'mo2f_external_app_type',$selectedMethod);
								}
								update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
								delete_user_meta($current_user->ID,'mo2f_configure_test_option');
								delete_user_meta($current_user->ID,'mo_2factor_mobile_registration_status');
								update_site_option( 'mo2f_message', $selectedMethod. ' is set as your Two-Factor method.');
								$this->mo_auth_show_success_message();
							}else{
									update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
									$this->mo_auth_show_error_message();
							}
					}else{
							update_site_option( 'mo2f_message','Invalid request. Please try again');
							$this->mo_auth_show_error_message();
					}
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_cancel_configuration'){
			unset($_SESSION[ 'mo2f_qrCode' ]);
			unset($_SESSION[ 'mo2f_transactionId' ]);
			unset($_SESSION[ 'mo2f_show_qr_code']);
			unset($_SESSION[ 'mo2f_phone']);
			unset($_SESSION[ 'mo2f_google_auth' ]);
			unset($_SESSION[ 'mo2f_mobile_support' ]);
			unset($_SESSION[ 'mo2f_authy_keys' ]);
			delete_user_meta($current_user->ID,'mo2f_configure_test_option');
		}
		
		if(isset($_POST['option']) && $_POST['option'] == 'mo2f_2factor_configure_kba_backup'){
			$_SESSION['mo2f_mobile_support'] = 'MO2F_EMAIL_BACKUP_KBA';
			update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_CONFIGURE');
			update_user_meta($current_user->ID,'mo2f_selected_2factor_method','KBA');
		}
		
		
	}
	
	function miniorange_email_verification_call($current_user){
		$challengeMobile = new Customer_Setup();
		$email = get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true);
		$content = $challengeMobile->send_otp_token($email, 'OUT OF BAND EMAIL',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key'));
		$response = json_decode($content, true);
		if(json_last_error() == JSON_ERROR_NONE) { /* Generate out of band email */
			if($response['status'] == 'ERROR'){
				update_site_option( 'mo2f_message', $response['message']);
				$this->mo_auth_show_error_message();
			}else{
				if($response['status'] == 'SUCCESS'){
				
				$_SESSION[ 'mo2f_transactionId' ] = $response['txId'];
				update_site_option( 'mo2f_message','A verification email is sent to<b> '. $email . '</b>. Please click on accept link to verify your email.');
				update_user_meta($current_user->ID,'mo2f_configure_test_option','MO2F_TEST');
				update_user_meta( $current_user->ID,'mo2f_selected_2factor_method', 'OUT OF BAND EMAIL');
				$this->mo_auth_show_success_message();
				}else{
					unset($_SESSION[ 'mo2f_transactionId' ]);
					update_site_option( 'mo2f_message','An error occured while processing your request. Please Try again.');
					$this->mo_auth_show_error_message();
				}
			}
		}else{
			update_site_option( 'mo2f_message','Invalid request. Please try again');
			$this->mo_auth_show_error_message();
		}
	}
	
	function mo2f_create_customer($current_user){
		delete_user_meta($current_user->ID,'mo2f_sms_otp_count');
		delete_user_meta($current_user->ID,'mo2f_email_otp_count');
		$customer = new Customer_Setup();
		$customerKey = json_decode($customer->create_customer(), true);
		if($customerKey['status'] == 'ERROR'){
			update_site_option( 'mo2f_message', $customerKey['message']);
			$this->mo_auth_show_error_message();
		}else{
			if(strcasecmp($customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0) {	//admin already exists in miniOrange
				$content = $customer->get_customer_key();
				$customerKey = json_decode($content, true);
				if(json_last_error() == JSON_ERROR_NONE) {
					if(is_array($customerKey) && array_key_exists("status", $customerKey) && $customerKey['status'] == 'ERROR'){
						update_site_option('mo2f_message',$customerKey['message']);
						$this->mo_auth_show_error_message();
					}else if(is_array($customerKey)){
						update_site_option( 'mo2f_customerKey', $customerKey['id']);
						update_site_option( 'mo2f_api_key', $customerKey['apiKey']);
						update_site_option( 'mo2f_customer_token', $customerKey['token']);
						update_site_option( 'mo2f_app_secret', $customerKey['appSecret'] );
						update_site_option( 'mo2f_miniorange_admin',$current_user->ID);
						delete_site_option('mo2f_password');
						update_site_option( 'mo_2factor_admin_registration_status','MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS');
						update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
						update_user_meta($current_user->ID,'mo_2factor_map_id_with_email',get_site_option('mo2f_email'));
						update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
						update_user_meta($current_user->ID, 'mo2f_2factor_enable_2fa_byusers', 1);
						
						$enduser = new Two_Factor_Setup();
						$enduser->mo2f_update_userinfo(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true), 'OUT OF BAND EMAIL',null,'API_2FA',true);	
						update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
						update_site_option( 'mo2f_message', 'Your account has been retrieved successfully. <b>Email Verification</b> has been set as your default 2nd factor method. <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure\" >Click Here </a>to configure another 2nd factor authentication method.');
						$this->mo_auth_show_success_message();
						
					}else{
						update_site_option( 'mo2f_message', 'Error occured while registration. Please try again.');
						$this->mo_auth_show_error_message();
					}
				} else {
					update_site_option( 'mo2f_message', 'Invalid email or password. Please try again.');
					update_user_meta($current_user->ID, 'mo_2factor_user_registration_status','MO_2_FACTOR_VERIFY_CUSTOMER');
					$this->mo_auth_show_error_message();
				}
			}else{
				update_site_option( 'mo2f_customerKey', $customerKey['id']);
				update_site_option( 'mo2f_api_key', $customerKey['apiKey']);
				update_site_option( 'mo2f_customer_token', $customerKey['token']);
				update_site_option( 'mo2f_app_secret', $customerKey['appSecret'] );
				update_site_option( 'mo2f_miniorange_admin',$current_user->ID);
				delete_site_option('mo2f_password');
				update_site_option( 'mo_2factor_admin_registration_status','MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS');
				update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
				update_user_meta($current_user->ID,'mo_2factor_map_id_with_email',get_site_option('mo2f_email'));
				update_site_option( 'mo2f_message', 'Your account has been created successfully. ');
				update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
				update_user_meta($current_user->ID, 'mo2f_2factor_enable_2fa_byusers', 1);
				
				$enduser = new Two_Factor_Setup();
				$enduser->mo2f_update_userinfo(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true), 'OUT OF BAND EMAIL',null,'API_2FA',true);	
				update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
				update_site_option( 'mo2f_message', 'Your account has been created successfully. <b>Email Verification</b> has been set as your default 2nd factor method. <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure\" >Click Here </a>to configure another 2nd factor authentication method.');
				$this->mo_auth_show_success_message();
				header('Location: admin.php?page=miniOrange_2_factor_settings&mo2f_tab=mo2f_pricing');
				
			}
		}
	}
	
	function mo2f_create_user($current_user,$email){
		$email = strtolower($email);
		$enduser = new Two_Factor_Setup();
		$check_user = json_decode($enduser->mo_check_user_already_exist($email),true);
		if(json_last_error() == JSON_ERROR_NONE){
			if($check_user['status'] == 'ERROR'){
				update_site_option( 'mo2f_message', $check_user['message']);
				$this->mo_auth_show_error_message();
			}else{
				if(strcasecmp($check_user['status'], 'USER_FOUND') == 0){
					delete_user_meta($current_user->ID,'mo_2factor_user_email');
					update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
					update_user_meta($current_user->ID,'mo_2factor_map_id_with_email',$email);
					update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
					$enduser->mo2f_update_userinfo(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true), 'OUT OF BAND EMAIL',null,'API_2FA',true);	
					update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
					$message = 'You are registered successfully. <b>Email Verification</b> has been set as your default 2nd factor method. <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure\" >Click Here </a>to configure another 2nd factor authentication method.';
					update_site_option( 'mo2f_message', $message);
					$this->mo_auth_show_success_message();
				}else if(strcasecmp($check_user['status'], 'USER_NOT_FOUND') == 0){
					$content = json_decode($enduser->mo_create_user($current_user,$email), true);
						if(json_last_error() == JSON_ERROR_NONE) {
							if($content['status'] == 'ERROR'){
								update_site_option( 'mo2f_message', $content['message']);
								$this->mo_auth_show_error_message();
							}else{
								if(strcasecmp($content['status'], 'SUCCESS') == 0) {
									delete_user_meta($current_user->ID,'mo_2factor_user_email');
									update_user_meta($current_user->ID,'mo_2factor_user_registration_with_miniorange','SUCCESS');
									update_user_meta($current_user->ID,'mo_2factor_map_id_with_email',$email);
									update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
									$enduser->mo2f_update_userinfo(get_user_meta($current_user->ID,'mo_2factor_map_id_with_email',true), 'OUT OF BAND EMAIL',null,'API_2FA',true);	
									update_user_meta($current_user->ID,'mo2f_email_verification_status',true);
									$message = 'You are registered successfully. <b>Email Verification</b> has been set as your default 2nd factor method. <a href=\"admin.php?page=miniOrange_2_factor_settings&amp;mo2f_tab=mobile_configure\" >Click Here </a>to configure another 2nd factor authentication method.';
									update_site_option( 'mo2f_message', $message);
									$this->mo_auth_show_success_message();
									
								}else{
									update_site_option( 'mo2f_message','Error occurred while registering the user. Please try again.');
									$this->mo_auth_show_error_message();
								}
							}
						}else{
								update_site_option( 'mo2f_message','Error occurred while registering the user. Please try again or contact your admin.');
								$this->mo_auth_show_error_message();
						}
				}else{
					update_site_option( 'mo2f_message','Error occurred while registering the user. Please try again.');
					$this->mo_auth_show_error_message();
				}
			}
		}else{
			update_site_option( 'mo2f_message','Error occurred while registering the user. Please try again.');
			$this->mo_auth_show_error_message();
		}
	}

	function mo2f_get_qr_code_for_mobile($email,$id){
		$registerMobile = new Two_Factor_Setup();
		$content = $registerMobile->register_mobile($email);
		$response = json_decode($content, true);
		if(json_last_error() == JSON_ERROR_NONE) {
			if($response['status'] == 'ERROR'){
				update_site_option( 'mo2f_message', $response['message']);
				unset($_SESSION[ 'mo2f_qrCode' ]);
				unset($_SESSION[ 'mo2f_transactionId' ]);
				unset($_SESSION[ 'mo2f_show_qr_code']);
				$this->mo_auth_show_error_message();
			}else{
				if($response['status'] == 'IN_PROGRESS'){
				update_site_option( 'mo2f_message','Please scan the QR Code now.');
				$_SESSION[ 'mo2f_qrCode' ] = $response['qrCode'];
				$_SESSION[ 'mo2f_transactionId' ] = $response['txId'];
				$_SESSION[ 'mo2f_show_qr_code'] = 'MO_2_FACTOR_SHOW_QR_CODE';
				$this->mo_auth_show_success_message();
				}else{
						update_site_option( 'mo2f_message', "An error occured while processing your request. Please Try again.");
				unset($_SESSION[ 'mo2f_qrCode' ]);
				unset($_SESSION[ 'mo2f_transactionId' ]);
				unset($_SESSION[ 'mo2f_show_qr_code']);
				$this->mo_auth_show_error_message();
				}
			}
		}
	}
}

	function mo2f_is_customer_registered() {
		$email = get_site_option('mo2f_email');
		$customerKey = get_site_option('mo2f_customerKey');
		if(!$email || !$customerKey || !is_numeric(trim($customerKey))) {
			return 0;
		} else {
			return 1;
		}
	}
	
new Miniorange_Authentication;
?>