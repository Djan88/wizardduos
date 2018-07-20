<?php
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit();
	}
 include_once dirname( __FILE__ ) . '/database/database_functions.php';
global $wpdb;
$Mo2fdbQueries = new Mo2fDB();

$table_name = $wpdb->prefix . 'mo2f_user_details';
$Mo2fdbQueries->drop_table( $table_name );

		delete_site_option('mo2f_email');
		delete_site_option('mo2f_is_error');
		delete_site_option('mo2f_host_name');
		delete_site_option('mo2f_phone');
		delete_site_option('mo2f_customerKey');
		delete_site_option('mo2f_api_key');
		delete_site_option('mo2f_customer_token');
		delete_site_option('mo2f_message');
		delete_site_option('mo_2factor_admin_registration_status');
		delete_site_option('mo2f-login-message');
		delete_site_option('mo_2f_login_type_enabled');
		delete_site_option('mo2f_admin_disabled_status');
		delete_site_option('mo2f_select_user_for_2fa');
		delete_site_option('mo2f_by_roles');
		delete_site_option('mo2f_disabled_status');
		delete_site_option('mo2f_miniorange_admin');
		delete_site_option('mo2f_enable_forgotphone');
		delete_site_option('mo2f_enable_forgotphone_kba');
		delete_site_option('mo2f_enable_forgotphone_email');
		delete_site_option('mo2f_enable_xmlrpc');
		delete_site_option('mo2f_show_loginwith_phone');
		delete_site_option('mo2f_login_policy');
		delete_site_option( 'mo2f_msg_counter');
		delete_site_option( 'mo2f_activate_plugin');
		delete_site_option( 'mo2f_enable_2fa_for_woocommerce');
		delete_site_option( 'mo2f_auth_methods_for_users');
		delete_site_option( 'mo2f_loginwith_phone' );
		delete_site_option( 'mo2f_app_secret' );
		delete_site_option( 'mo2f_inline_registration' );
		delete_site_option( 'mo2f_enable_emailchange' );
		delete_site_option( 'mo2f_enable_custom');
		delete_site_option( 'mo2f_disable_poweredby');
		delete_site_option( 'mo2f_rba_loginform_id');
		delete_site_option( 'mo2f_custom_plugin_name');
		delete_site_option( 'mo2f_enable_custom_poweredby' );
		delete_site_option( 'mo2f_disable_kba' );
		delete_site_option( 'mo2f_remember_device' );
		delete_site_option( 'mo2f_enable_rba_types' );
		//delete_site_option( 'mo2f_enable_reconfig' );
		//delete_site_option( 'mo2f_enable_reconfig_google' );
		//delete_site_option( 'mo2f_enable_reconfig_kba' );
		delete_option( 'mo2f_existing_user_values_updated' );
		delete_option( 'mo2f_dbversion' );
		delete_site_option( 'mo2f_enable_custom_icon' );
		delete_site_option( 'mo2f_enable_mobile_support'); 
		delete_site_option( 'mo2f_new_customer' );
		delete_site_option( 'mo2f_auth_admin_custom_kbaquestions' );
		delete_site_option( 'mo2f_default_kbaquestions_users' );
		delete_site_option( 'mo2f_custom_kbaquestions_users' );
		delete_site_option( 'mo2f_no_license_needed' );
		delete_site_option( 'mo2f_all_users_method' );
		delete_option( 'mo2f_proxy_host' );
		delete_option( 'mo2f_port_number' );
		delete_option( 'mo2f_proxy_username' );
		delete_option( 'mo2f_proxy_password' );
		delete_option( 'mo2f_users_notify_image' );
		delete_option( 'mo2f_users_notify_msg1' );
		delete_option( 'mo2f_users_notify_msg2' );
		delete_option( 'mo2f_users_notify_msg3' );
		delete_option( 'mo2f_users_notify_site_url' );
		delete_option( 'mo2f_enable_gauth_name' );
		delete_option( 'mo2f_enable_gdpr_policy' );
		delete_option( 'mo2f_users_notify_subject' );
		delete_option( 'mo2f_GA_account_name' );
		delete_option( 'mo2f_privacy_policy_link' );
		//delete all stored key-value pairs for the roles
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		foreach($wp_roles->role_names as $id => $name) {	
			delete_site_option('mo2fa_'.$id);
			delete_site_option('mo2fa_'.$id.'_login_url');
			delete_option('mo2f_auth_methods_for_'.$id);
		}
		
		//delete user specific key-value pair
		$users = get_users( array() );
		foreach ( $users as $user ) {
			delete_user_meta($user->ID,'mo2f_backup_codes');
			delete_user_meta($user->ID,'mo_2factor_user_registration_status');
			delete_user_meta($user->ID,'mo_2factor_mobile_registration_status');
			delete_user_meta($user->ID,'mo_2factor_user_registration_with_miniorange');
			delete_user_meta($user->ID,'mo_2factor_map_id_with_email');
			delete_user_meta($user->ID,'mo2f_user_phone');
			delete_user_meta($user->ID,'mo2f_mobile_registration_status');
			delete_user_meta($user->ID,'mo2f_otp_registration_status');
			delete_user_meta($user->ID,'mo2f_email_otp_registration_status');
			delete_user_meta($user->ID,'mo2f_configure_test_option');
			delete_user_meta($user->ID,'mo2f_selected_2factor_method');
			delete_user_meta($user->ID,'mo2f_google_authentication_status');
			delete_user_meta($user->ID,'mo2f_kba_registration_status');
			delete_user_meta($user->ID,'mo2f_email_verification_status');
			delete_user_meta($user->ID,'mo2f_authy_authentication_status');
		}
		
		//delete previous version key-value pairs
		delete_site_option('mo_2factor_admin_mobile_registration_status');
		delete_site_option('mo_2factor_registration_status');
		delete_site_option('mo_2factor_temp_status');
		delete_site_option('mo2f_login_username');
		delete_site_option('mo2f-login-qrCode');
		delete_site_option('mo2f-login-transactionId');
		delete_site_option('mo_2factor_login_status');
		delete_site_option('mo2f_mowplink');
		delete_site_option('mo2f_no_license_needed');
		
		 
	if ( !is_multisite() ) {
     //delete all your options
    //E.g: delete_option( {option name} );
	//delete all stored key-value pairs which are available to all users
		delete_option('mo2f_no_license_needed');
		delete_option('mo2f_email');
		delete_option('mo2f_is_error');
		delete_option('mo2f_host_name');
		delete_option('mo2f_phone');
		delete_option('mo2f_customerKey');
		delete_option('mo2f_api_key');
		delete_option('mo2f_customer_token');
		delete_option('mo2f_message');
		delete_option('mo_2factor_admin_registration_status');
		delete_option('mo2f-login-message');
		delete_option('mo_2f_login_type_enabled');
		delete_option('mo2f_admin_disabled_status');
		delete_option('mo2f_disabled_status');
		delete_option('mo2f_miniorange_admin');
		delete_option('mo2f_enable_forgotphone');
		delete_option('mo2f_enable_forgotphone_kba');
		delete_option('mo2f_enable_forgotphone_email');
		delete_option('mo2f_enable_xmlrpc');
		delete_option('mo2f_show_loginwith_phone');
		//delete_option('mo2f_login_policy');
		delete_option( 'mo2f_msg_counter');
		delete_option( 'mo2f_activate_plugin');
		delete_option( 'mo2f_enable_2fa_for_woocommerce');
		delete_option( 'mo2f_auth_methods_for_users');
		//delete_option( 'mo2f_deviceid_enabled' );
		delete_option( 'mo2f_app_secret' );
		delete_option( 'mo2f_inline_registration' );
		delete_option( 'mo2f_enable_emailchange' );
		delete_option( 'mo2f_enable_custom');
		delete_option( 'mo2f_disable_poweredby');
		delete_option( 'mo2f_rba_loginform_id');
		delete_option( 'mo2f_custom_plugin_name');
		delete_option( 'mo2f_enable_custom_poweredby' );
		delete_option( 'mo2f_disable_kba' );
		delete_option( 'mo2f_remember_device' );
		delete_option( 'mo2f_enable_rba_types' );
		//delete_option( 'mo2f_enable_reconfig' );
		//delete_option( 'mo2f_enable_reconfig_google' );
		//delete_option( 'mo2f_enable_reconfig_kba' );
		delete_option( 'mo2f_enable_custom_icon' );
		delete_option('mo2f_enable_mobile_support'); 
		delete_option( 'mo2f_new_customer' );
		delete_option('mo2f_select_user_for_2fa');
		delete_option('mo2f_by_roles');
		delete_option( 'mo2f_users_notify_image' );
		delete_option( 'mo2f_users_notify_msg1' );
		delete_option( 'mo2f_users_notify_msg2' );
		delete_option( 'mo2f_users_notify_msg3' );
		delete_option( 'mo2f_users_notify_site_url' );
		delete_option( 'mo2f_enable_gauth_name' );
		delete_option( 'mo2f_enable_gdpr_policy' );
		delete_option( 'mo2f_users_notify_subject' );
		delete_option( 'mo2f_GA_account_name' );
		delete_option( 'mo2f_privacy_policy_link' );
		$users = get_users( array() );
		foreach ( $users as $user ) {
				delete_user_meta($user->ID,'mo2f_backup_codes');
			delete_user_meta( $user->ID, 'phone_verification_status' );
			delete_user_meta( $user->ID, 'test_2FA' );
			delete_user_meta( $user->ID, 'mo2f_2FA_method_to_configure' );
			delete_user_meta( $user->ID, 'configure_2FA' );
			delete_user_meta( $user->ID, 'skipped_flow_driven_setup' );
			delete_user_meta( $user->ID, 'current_modal' );
			delete_user_meta( $user->ID, 'mo2f_2FA_method_to_test' );
			delete_user_meta( $user->ID, 'mo2f_phone' );
			delete_user_meta( $user->ID, 'mo_2factor_user_registration_status' );
			delete_user_meta( $user->ID, 'mo2f_external_app_type' );
			
		}
		
		//delete all stored key-value pairs for the roles
		global $wp_roles;
		if (!isset($wp_roles))
			$wp_roles = new WP_Roles();
		foreach($wp_roles->role_names as $id => $name) {	
			delete_option('mo2fa_'.$id);	
			delete_option('mo2fa_'.$id.'_login_url');
			delete_option('mo2f_auth_methods_for_'.$id);
		}
	} 
	else {
		global $wpdb;
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		$original_blog_id = get_current_blog_id();

		foreach ( $blog_ids as $blog_id ){
			switch_to_blog( $blog_id );
			//delete all your options
			//E.g: delete_option( {option name} );  
			delete_option('mo2f_is_error');
			delete_option('mo2f_email');
			delete_option('mo2f_host_name');
			delete_option('mo2f_phone');
			delete_option('mo2f_customerKey');
			delete_option('mo2f_api_key');
			delete_option('mo2f_customer_token');
			delete_option('mo2f_message');
			delete_option('mo_2factor_admin_registration_status');
			delete_option('mo2f-login-message');
			delete_option('mo_2f_login_type_enabled');
			delete_option('mo2f_admin_disabled_status');
			delete_option('mo2f_disabled_status');
			delete_option('mo2f_miniorange_admin');
			delete_option('mo2f_enable_forgotphone');
			delete_option('mo2f_enable_forgotphone_kba');
			delete_option('mo2f_enable_forgotphone_email');
			delete_option('mo2f_show_loginwith_phone');
			//delete_option('mo2f_login_policy');
			delete_option( 'mo2f_msg_counter');
			delete_option( 'mo2f_activate_plugin');
			delete_option( 'mo2f_enable_2fa_for_woocommerce');
			delete_option( 'mo2f_auth_methods_for_users');
			//delete_option( 'mo2f_deviceid_enabled' );
			delete_option( 'mo2f_app_secret' );
			delete_option( 'mo2f_inline_registration' );
			delete_option( 'mo2f_enable_emailchange');
			delete_option( 'mo2f_enable_custom');
			delete_option( 'mo2f_disable_poweredby');
			delete_option( 'mo2f_rba_loginform_id');
			delete_option( 'mo2f_custom_plugin_name');
			delete_option( 'mo2f_enable_custom_poweredby' );
			delete_option( 'mo2f_disable_kba' );
			delete_option( 'mo2f_remember_device' );
			delete_option( 'mo2f_enable_rba_types' );
			//delete_option( 'mo2f_enable_reconfig' );
			//delete_option( 'mo2f_enable_reconfig_google' );
			//delete_option( 'mo2f_enable_reconfig_kba' );
			delete_option( 'mo2f_enable_custom_icon' );
			delete_option( 'mo2f_enable_mobile_support'); 
			delete_option( 'mo2f_new_customer' );
			delete_option( 'mo2f_auth_admin_custom_kbaquestions' );
			delete_option( 'mo2f_default_kbaquestions_users' );
			delete_option( 'mo2f_custom_kbaquestions_users' );
			delete_option( 'mo2f_select_user_for_2fa' );
			delete_option( 'mo2f_by_roles' );
			delete_option( 'mo2f_users_notify_image' );
			delete_option( 'mo2f_users_notify_msg1' );
			delete_option( 'mo2f_users_notify_msg2' );
			delete_option( 'mo2f_users_notify_msg3' );
			delete_option( 'mo2f_users_notify_site_url' );
			delete_option( 'mo2f_enable_gauth_name' );
			delete_option( 'mo2f_enable_gdpr_policy' );
			delete_option( 'mo2f_privacy_policy_link' );
			delete_option( 'mo2f_GA_account_name' );
			delete_option( 'mo2f_users_notify_subject' );

			foreach ( $users as $user ) {
					delete_user_meta($user->ID,'mo2f_backup_codes');
				delete_user_meta( $user->ID, 'phone_verification_status' );
				delete_user_meta( $user->ID, 'test_2FA' );
				delete_user_meta( $user->ID, 'mo2f_2FA_method_to_configure' );
				delete_user_meta( $user->ID, 'configure_2FA' );
				delete_user_meta( $user->ID, 'skipped_flow_driven_setup' );
				delete_user_meta( $user->ID, 'current_modal' );
				delete_user_meta( $user->ID, 'mo2f_2FA_method_to_test' );
				delete_user_meta( $user->ID, 'mo2f_phone' );
				delete_user_meta( $user->ID, 'mo_2factor_user_registration_status' );
				delete_user_meta( $user->ID, 'mo2f_external_app_type' );
			}

			
			//delete all stored key-value pairs for the roles
			global $wp_roles;
			if (!isset($wp_roles))
				$wp_roles = new WP_Roles();
			foreach($wp_roles->role_names as $id => $name) {	
				delete_option('mo2fa_'.$id);
				delete_option('mo2fa_'.$id.'_login_url');
				delete_option('mo2f_auth_methods_for_'.$id);
			}
		
		}
		switch_to_blog( $original_blog_id );
	}
	
?>