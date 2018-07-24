<?Php
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
include_once dirname( __FILE__ ) . '/miniorange_2_factor_common_login.php';
include_once dirname( __FILE__ ) . '/miniorange_2_factor_user_inline_registration.php';
include_once dirname( __FILE__ ) . '/class-rba-attributes.php';

class Miniorange_Password_2Factor_Login{
	
	function remove_current_activity(){
		unset($_SESSION[ 'mo2f_current_user' ]);
		unset($_SESSION[ 'mo2f_1stfactor_status' ]);
		unset($_SESSION[ 'mo_2factor_login_status' ]);
		unset($_SESSION[ 'mo2f-login-qrCode' ]);
		unset($_SESSION[ 'mo2f-login-transactionId' ]);
		unset($_SESSION[ 'mo2f-login-message' ]);
		unset($_SESSION[ 'mo2f_rba_status' ]);
		unset($_SESSION[ 'mo_2_factor_kba_questions' ]);
		unset($_SESSION[ 'mo2f_show_qr_code']);
		unset($_SESSION['mo2f_google_auth']);
		unset($_SESSION['mo2f_authy_keys']);	
	}
	
	function mo2fa_pass2login($redirect_to=null){
		if(isset($_SESSION[ 'mo2f_current_user' ]) && isset($_SESSION[ 'mo2f_1stfactor_status' ]) && $_SESSION[ 'mo2f_1stfactor_status' ] = 'VALIDATE_SUCCESS'){
			$currentuser = unserialize( $_SESSION[ 'mo2f_current_user' ] );
			$user_id = $currentuser->ID;
			wp_set_current_user($user_id, $currentuser->user_login);
			$this->remove_current_activity();
			wp_set_auth_cookie( $user_id, true );
			do_action( 'wp_login', $currentuser->user_login, $currentuser );
			redirect_user_to($currentuser, $redirect_to);
			exit;
		}else{
			$this->remove_current_activity();
		}
	}
	
	public function miniorange_pass2login_start_session(){
		
 		if( ! session_id() || session_id() == '' || !isset($_SESSION) ) {
			
 			session_start();
 		}
	}
	
	public function miniorange_pass2login_redirect() {
		global $dbQueries;
	
		$this->miniorange_pass2login_start_session();
		
		if(isset($_POST['mo2f_trust_device_confirm_nonce'])){ /*register device as rba profile */
			
			$nonce = $_POST['mo2f_trust_device_confirm_nonce'];
			
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-trust-device-confirm-nonce' ) ) {
				$this->remove_current_activity();
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				
				$this->miniorange_pass2login_start_session();
				try{
					$currentuser = unserialize( $_SESSION[ 'mo2f_current_user' ] );	
					$email = $dbQueries->get_user_detail( 'mo2f_user_email',$currentuser->ID);					
					mo2f_register_profile($email,'true',$_SESSION[ 
					'mo2f_rba_status' ]);
				}catch(Exception $e){
					echo $e->getMessage();
				}
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				$this->mo2fa_pass2login($redirect_to);
			}
		}
		
		if(isset($_POST['mo2f_trust_device_cancel_nonce'])){ /*do not register device as rba profile */
			$nonce = $_POST['mo2f_trust_device_cancel_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-trust-device-cancel-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				$this->mo2fa_pass2login($redirect_to);
				
			}
		}
			
		if(isset($_POST['miniorange_challenge_forgotphone_nonce'])){ /*check kba validation*/
			$nonce = $_POST['miniorange_challenge_forgotphone_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-challenge-forgotphone-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			}else {
				$this->miniorange_pass2login_start_session();
				$forgot_phone_enable = get_site_option('mo2f_enable_forgotphone');
				$forgot_phone_kba_enable = get_site_option('mo2f_enable_forgotphone_kba');
				$forgot_phone_email_enable = get_site_option('mo2f_enable_forgotphone_email');
				
				$second_factor = isset($_POST[ 'mo2f_selected_2factor_method' ]) ? $_POST[ 'mo2f_selected_2factor_method' ] : 'KBA';
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				$current_user = unserialize($_SESSION[ 'mo2f_current_user' ]);
				$id = $current_user->ID;
				$kba_registration_status = $dbQueries->get_user_detail( 'mo2f_SecurityQuestions_config_status',$current_user->ID);
				if($forgot_phone_enable && $forgot_phone_email_enable && $second_factor == 'OTP OVER EMAIL'){
					$customer = new Customer_Setup();
					$email = $dbQueries->get_user_detail('mo2f_user_email', $id);	
					$content = json_decode($customer->send_otp_token($email,'EMAIL',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key')), true);
					
					$mo2fa_login_message = '';
					$mo2f_login_status = '' ;
					
					if(strcasecmp($content['status'], 'SUCCESS') == 0) {
						$_SESSION[ 'mo2f-login-transactionId' ] = $content['txId'];
						$mo2fa_login_message =  __('A one time passcode has been sent to','miniorange-2-factor-authentication'). ' <b>' .  MO2f_Utility::mo2f_get_hiden_email($email ) . '</b>. '.__('Please enter the OTP to verify your identity. ','miniorange-2-factor-authentication');
						$mo2f_login_status = 'MO_2_FACTOR_CHALLENGE_OTP_OVER_EMAIL' ;
					}else{
						$mo2fa_login_message = __('Error occured while sending OTP over your regsitered email. Please try again. ','miniorange-2-factor-authentication');
						$mo2f_login_status = 'MO_2_FACTOR_CHALLENGE_KBA_AND_OTP_OVER_EMAIL' ;
					}
					$this->miniorange_pass2login_form_fields($mo2f_login_status, $mo2fa_login_message, $redirect_to);
				}else if($forgot_phone_enable && $forgot_phone_kba_enable){
					if($kba_registration_status){
						$this->mo2f_pass2login_kba_verification($current_user->ID, $redirect_to);
					}else{
						$mo2fa_login_message = __('Your KBA is not configured. Please choose other option to proceed further.','miniorange-2-factor-authentication');
						$mo2f_login_status = 'MO_2_FACTOR_CHALLENGE_KBA_AND_OTP_OVER_EMAIL' ;
						$this->miniorange_pass2login_form_fields($mo2f_login_status, $mo2fa_login_message, $redirect_to);
					}
				}
			}
		}
		
		if(isset($_POST['miniorange_alternate_login_kba_nonce'])){ /*check kba validation*/
			$nonce = $_POST['miniorange_alternate_login_kba_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-alternate-login-kba-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			}else {
				$this->miniorange_pass2login_start_session();
				$currentuser = isset($_SESSION[ 'mo2f_current_user' ]) ? unserialize( $_SESSION[ 'mo2f_current_user' ] ) : null;
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				$this->mo2f_pass2login_kba_verification($currentuser->ID, $redirect_to);
			}
		}
		if(isset($_POST['miniorange_backup_nonce'])){ /*check backup validation*/
			$nonce = $_POST['miniorange_backup_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-backup-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			}else {
				$this->miniorange_pass2login_start_session();
				$currentuser = isset($_SESSION[ 'mo2f_current_user' ]) ? unserialize( $_SESSION[ 'mo2f_current_user' ] ) : null;
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				$mo2fa_login_message = __('Please provide your backup codes.','miniorange-2-factor-authentication');
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_BACKUP';
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
			}
		}
		if(isset($_POST['miniorange_validate_backup_nonce'])){ /*check backup validation*/
			$nonce = $_POST['miniorange_validate_backup_nonce'];
			// var_dump("here");exit;
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-validate-backup-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				
				$this->miniorange_pass2login_start_session();
				$currentuser = isset($_SESSION[ 'mo2f_current_user' ]) ? unserialize( $_SESSION[ 'mo2f_current_user' ] ): null;
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				if(isset($_SESSION[ 'mo2f_current_user' ])){
					if(MO2f_Utility::mo2f_check_empty_or_null($_POST[ 'mo2f_backup_code' ]) ){
						$mo2fa_login_message = __('Please provide backup code.','miniorange-2-factor-authentication');
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_BACKUP';
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
					}
					// update_site_option('mo2f_backup_codes',array('qwertyuiop'));
			
					// update_user_meta($currentuser->ID,'mo2f_backup_codes', array('qwertyuiop','qwertyuio','qwertyuiop'));
					$backup_codes=get_user_meta($currentuser->ID,'mo2f_backup_codes',true);
					$mo2f_backup_code= $_POST[ 'mo2f_backup_code' ];
					$mo2f_backup_code=md5($mo2f_backup_code);
					// $backup_codes = get_site_option('mo2f_backup_codes');
					if(!empty($backup_codes)){
						if(in_array($mo2f_backup_code,$backup_codes)){
							
							foreach ($backup_codes as $key => $value) {
							if($value==$mo2f_backup_code){
								// var_dump($backup_codes[$key]);
								unset($backup_codes[$key]);
								 update_user_meta($currentuser->ID,'mo2f_backup_codes', $backup_codes);
								
							}
							//var_dump($key,$value);
							}
								// var_dump($backup_codes);
								
							
						$this->mo2fa_pass2login($redirect_to);
						}else{
							
							$mo2fa_login_message = __('The codes you have provided are either expired or incorrect.','miniorange-2-factor-authentication');
							$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_BACKUP';
							$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
						}
					}else{
					$mo2fa_login_message = __('There are no codes left please generate codes from dashboard.','miniorange-2-factor-authentication');
							$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_BACKUP';
							$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
					}
					
					
				}else{
					$this->remove_current_activity();
					return new WP_Error('invalid_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') . '</strong>: ' . __('Please try again..', 'miniorange-2-factor-authentication'));
				}
			}
		}
		if(isset($_POST['miniorange_kba_nonce'])){ /*check kba validation*/
			$nonce = $_POST['miniorange_kba_nonce'];
			
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-kba-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				
				$this->miniorange_pass2login_start_session();
				$currentuser = isset($_SESSION[ 'mo2f_current_user' ]) ? unserialize( $_SESSION[ 'mo2f_current_user' ] ): null;
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				if(isset($_SESSION[ 'mo2f_current_user' ])){
					if(MO2f_Utility::mo2f_check_empty_or_null($_POST[ 'mo2f_answer_1' ]) || MO2f_Utility::mo2f_check_empty_or_null($_POST[ 'mo2f_answer_2' ])){
						$mo2fa_login_message = __('Please provide both the answers.','miniorange-2-factor-authentication');
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_KBA_AUTHENTICATION';
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
					}
					$otpToken = array();
					$otpToken[0] = $_SESSION['mo_2_factor_kba_questions'][0];
					$otpToken[1] = sanitize_text_field( $_POST[ 'mo2f_answer_1' ] );
					$otpToken[2] = $_SESSION['mo_2_factor_kba_questions'][1];
					$otpToken[3] = sanitize_text_field( $_POST[ 'mo2f_answer_2' ] );
					if(get_site_option('mo2f_enable_rba_types')==0)
					{
						$check_trust_device = 'on';
					}else{
						$check_trust_device = isset($_POST[ 'mo2f_trust_device' ] ) ? $_POST[ 'mo2f_trust_device' ] : 'false';
					}
					
					$kba_validate = new Customer_Setup();
					$kba_validate_response = json_decode($kba_validate->validate_otp_token( 'KBA', null, $_SESSION[ 'mo2f-login-transactionId' ], $otpToken, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
		             
					 // var_dump($kba_validate_response);
					 // var_dump($redirect_to);
					 // exit;
					 $email = $dbQueries->get_user_detail('mo2f_user_email',$currentuser->ID);	
					if(strcasecmp($kba_validate_response['status'], 'SUCCESS') == 0) {
						
						if(get_site_option('mo2f_remember_device') && $check_trust_device == 'on' && get_site_option('mo2f_login_policy')){
							try{								
								mo2f_register_profile($email,'true',$_SESSION[ 'mo2f_rba_status' ]);
							}catch(Exception $e){
								echo $e->getMessage();
							}
							$this->mo2fa_pass2login($redirect_to);
						}else{
							
							$this->mo2fa_pass2login($redirect_to);
						}
					}else{
						
						$mo2fa_login_message = __('The answers you have provided are incorrect.','miniorange-2-factor-authentication');
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_KBA_AUTHENTICATION';
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
					}
				}else{
					$this->remove_current_activity();
					return new WP_Error('invalid_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') . '</strong>: ' . __('Please try again..', 'miniorange-2-factor-authentication'));
				}
			}
		}
		
		if(isset($_POST['miniorange_mobile_validation_nonce'])){ /*check mobile validation */
		
			$nonce = $_POST['miniorange_mobile_validation_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-mobile-validation-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {	
			
				$this->miniorange_pass2login_start_session();
				$currentuser = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				$checkMobileStatus = new Two_Factor_Setup();
				$content = $checkMobileStatus->check_mobile_status($_SESSION[ 'mo2f-login-transactionId' ]);
				$response = json_decode($content, true);
				 $email = $dbQueries->get_user_detail('mo2f_user_email',$currentuser->ID);
				if(json_last_error() == JSON_ERROR_NONE) {
					if($response['status'] == 'SUCCESS'){	
						if(get_site_option('mo2f_remember_device')&& get_site_option('mo2f_login_policy')){ //add check here
							if(get_site_option('mo2f_enable_rba_types')==0)
							{
								try{
									$currentuser = unserialize( $_SESSION[ 'mo2f_current_user' ] );
									mo2f_register_profile( $email,'true',$_SESSION[ 
									'mo2f_rba_status' ]);
								}catch(Exception $e){
									echo $e->getMessage();
								}
								$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
								$this->mo2fa_pass2login($redirect_to);
							}else{
								
							$mo2fa_login_status = 'MO_2_FACTOR_REMEMBER_TRUSTED_DEVICE';
							$login_message = mo2f_get_user_2ndfactor($currentuser);
							$this->miniorange_pass2login_form_fields($mo2fa_login_status, $login_message, $redirect_to);
							}
						}else{
							
							$this->mo2fa_pass2login($redirect_to);
						}
					}else{
						$this->remove_current_activity();
						return new WP_Error('invalid_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') . '</strong>: ' . __('Please try again..', 'miniorange-2-factor-authentication'));
					}
				}else{
					$this->remove_current_activity();
					    return new WP_Error('invalid_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') . '</strong>: '.__('Please try again..', 'miniorange-2-factor-authentication'));
				}
			}
		}
		
		if (isset($_POST['miniorange_mobile_validation_failed_nonce'])){ /*Back to miniOrange Login Page if mobile validation failed and from back button of mobile challenge, soft token and default login*/
			$nonce = $_POST['miniorange_mobile_validation_failed_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-mobile-validation-failed-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$this->remove_current_activity();
			}
		}
		
		if(isset($_POST['miniorange_forgotphone'])){ /*Click on the link of forgotphone */
			$nonce = $_POST['miniorange_forgotphone'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-forgotphone' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else{
				$mo2fa_login_status = isset($_POST['request_origin_method']) ? $_POST['request_origin_method'] : null;
				$redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : null;
				$mo2fa_login_message = '';
				
				$forgot_phone_enable = get_site_option('mo2f_enable_forgotphone');
				$forgot_phone_kba_enable = get_site_option('mo2f_enable_forgotphone_kba');
				$forgot_phone_email_enable = get_site_option('mo2f_enable_forgotphone_email');
				
				$this->miniorange_pass2login_start_session();
				$customer = new Customer_Setup();
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$id = $current_user->ID;
				$email = $dbQueries->get_user_detail('mo2f_user_email',$id);
				$kba_registration_status = $dbQueries->get_user_detail( 'mo2f_SecurityQuestions_config_status',$current_user->ID);
			
				if($forgot_phone_enable){
					if($forgot_phone_kba_enable && $forgot_phone_email_enable){
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_KBA_AND_OTP_OVER_EMAIL';
					}else if($forgot_phone_kba_enable && $kba_registration_status){
						$this->mo2f_pass2login_kba_verification($current_user->ID, $redirect_to);
					}else if($forgot_phone_email_enable){
						$content = json_decode($customer->send_otp_token($email,'EMAIL',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key')), true);
						if(strcasecmp($content['status'], 'SUCCESS') == 0) {
							unset($_SESSION[ 'mo2f-login-qrCode' ]);
							unset($_SESSION[ 'mo2f-login-transactionId' ]);
							$_SESSION[ 'mo2f-login-transactionId' ] = $content['txId'];
							$mo2fa_login_message = __('A one time passcode has been sent to','miniorange-2-factor-authentication'). ' <b> ' . MO2f_Utility::mo2f_get_hiden_email($email ). '</b>. '.__('Please enter the OTP to verify your identity.','miniorange-2-factor-authentication');
							$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_OTP_OVER_EMAIL';
						}else{
							$mo2fa_login_message = __('Error occurred while sending OTP over email. Please try again.','miniorange-2-factor-authentication');
						}
					}else{
						$mo2fa_login_message = __('You have not configured any forgot phone method. Please contact your administrator.','miniorange-2-factor-authentication');
					}
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
				}
			}
		} 
		
		
		if ( isset($_POST['miniorange_inline_user_reg_nonce'])){	
			
			if((isset($_POST['mo2f_gdpr']) and $_POST['mo2f_gdpr']=='on')|| (!get_site_option('mo2f_enable_gdpr_policy'))){
				$nonce = $_POST['miniorange_inline_user_reg_nonce'];
				if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-user-reg-nonce' ) ) {
					$error = new WP_Error();
					$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
					return $error;
				} else {
					
					$this->miniorange_pass2login_start_session();
					$email = '';
					$mo2fa_login_status = '';
					$mo2fa_login_message = '';
					if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['mo_useremail'] )){
						$mo2fa_login_message = __('Please enter email-id to register.','miniorange-2-factor-authentication');
						$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REGISTRATION';
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
					}else{
						$email = sanitize_email( $_POST['mo_useremail'] );
					}
					$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
					$currentUserId = $current_user->ID;
					$dbQueries->insert_user( $current_user->ID, array( 'user_id' => $current_user->ID ) );
					
					if(!MO2f_Utility::check_if_email_is_already_registered($currentUserId, $email)){
						
						// update_user_meta($currentUserId,'mo_2factor_user_email',$email);
						$dbQueries->update_user_details( $currentUserId, array('mo_2factor_user_email' => $email) );
						$enduser = new Two_Factor_Setup();
						$check_user = json_decode($enduser->mo_check_user_already_exist($email),true);
						if(json_last_error() == JSON_ERROR_NONE){
							if($check_user['status'] == 'ERROR'){
								$mo2fa_login_message = Mo2fConstants::langTranslate($check_user['message']);
								$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REGISTRATION';
								$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
							}else if(strcasecmp($check_user['status'], 'USER_FOUND_UNDER_DIFFERENT_CUSTOMER') == 0){
								$mo2fa_login_message = __('The email you entered is already registered. Please register with another email to set up Two-Factor.','miniorange-2-factor-authentication');
								$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REGISTRATION';
								$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
							}
							else if(strcasecmp($check_user['status'], 'USER_FOUND') == 0 || strcasecmp($check_user['status'], 'USER_NOT_FOUND') == 0){
						
								$enduser = new Customer_Setup();
								$content = json_decode($enduser->send_otp_token($email,'EMAIL',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key')), true);
								if(strcasecmp($content['status'], 'SUCCESS') == 0) {
									update_user_meta($currentUserId,'mo_2fa_verify_otp_create_account',$content['txId']);
									// update_user_meta($currentUserId, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_SUCCESS');
									$dbQueries->update_user_details( $currentUserId, array('mo_2factor_user_registration_status' => 'MO_2_FACTOR_OTP_DELIVERED_SUCCESS') );
									$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
									$mo2fa_login_message = __('An OTP has been sent to','miniorange-2-factor-authentication') .' <b>' . ( $email ) . '</b>. '. __('Please enter the OTP below to verify your email. If you did not get the email, please check your','miniorange-2-factor-authentication'). ' <b> ' . __('SPAM','miniorange-2-factor-authentication') . ' </b> ' . __('folder.','miniorange-2-factor-authentication');
										
									// var_dump($_POST);exit;
										
								}else{
									$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
									$mo2fa_login_message = __('There was an error in sending OTP over email. Please click on Resend OTP to try again.','miniorange-2-factor-authentication');
									// update_user_meta($currentUserId, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');	
								$dbQueries->update_user_details( $currentUserId, array('mo_2factor_user_registration_status' => 'MO_2_FACTOR_OTP_DELIVERED_FAILURE') );
								}
							
							}
						}
					}else{
						
						$mo2fa_login_message = __('The email is already used by other user. Please register with other email.','miniorange-2-factor-authentication');
						$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REGISTRATION';
						
					}
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
				}
			}else{
				
				$mo2fa_login_message = __('Please Accept the terms and Conditons.','miniorange-2-factor-authentication');
						$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REGISTRATION';
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if( isset($_POST['miniorange_inline_two_factor_setup'])){ /* return back to choose second factor screen */
			$nonce = $_POST['miniorange_inline_two_factor_setup'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-setup-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				unset($_SESSION['mo2f_google_auth']);
				unset($_SESSION['mo2f_authy_keys']);
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				// delete_user_meta($current_user->ID,'mo2f_selected_2factor_method');
				$dbQueries->update_user_details( $current_user->ID, array( "mo2f_configured_2FA_method" => '' ) );
				$mo2fa_login_message = '';
				$mo2fa_login_status ='MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if ( isset($_POST['miniorange_inline_resend_otp_nonce'])){	//resend otp during user inline registration
			$nonce = $_POST['miniorange_inline_resend_otp_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-resend-otp-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
			$selected_2factor_method = $dbQueries->get_user_detail( 'mo2f_configured_2FA_method',$current_user->ID);
				$currentUserId = $current_user->ID;
				$mo2fa_login_message = '';
				$mo2fa_login_status = '';
				$userkey = '';
				if($selected_2factor_method == 'SMS'){
					$currentMethod = "OTP_OVER_SMS";
					$userkey = isset($_SESSION['mo2f_phone']) ? $_SESSION['mo2f_phone'] : null;
					$message = isset($_SESSION['mo2f_phone']) ? __('The One Time Passcode has been sent to ','miniorange-2-factor-authentication'). $userkey . __('Please enter the one time passcode below to verify your number.','miniorange-2-factor-authentication') : __('Please click on Verify button to receive OTP over your phone number.','miniorange-2-factor-authentication');
					$mo2fa_login_message = $message;
				}else if($selected_2factor_method == 'SMS AND EMAIL'){
					$currentMethod = "OTP_OVER_SMS_AND_EMAIL";
					$userkey = array();
					$userkey["phone"] = isset($_SESSION['mo2f_phone']) ? $_SESSION['mo2f_phone'] : null;
					// $email = $dbQueries->get_user_detail($currentUserId,'mo_2factor_map_id_with_email');
					$userkey["email"] = $dbQueries->get_user_detail('mo2f_user_email',$currentUserId);
					// $userkey["email"] = get_user_meta( $currentUserId,'mo_2factor_map_id_with_email',true);
					$message = isset($_SESSION['mo2f_phone']) ? __('The One Time Passcode has been sent to ','miniorange-2-factor-authentication') . $userkey["phone"] . __(' and ','miniorange-2-factor-authentication') . $userkey["email"] . __('Please enter the one time passcode below to verify your number and email.','miniorange-2-factor-authentication') : __('Please click on Verify button to receive OTP over your phone number and your email.','miniorange-2-factor-authentication');
					$mo2fa_login_message = $message;
				}else if($selected_2factor_method == 'OTP OVER EMAIL'){
					$currentMethod = "OTP_OVER_EMAIL";
					// var_dump(get_user_meta( $currentUserId,'mo2f_configured_2FA_method',true));exit;
					// $userkey=array();
					//$userkey= get_user_meta( $currentUserId,'mo_2factor_map_id_with_email',true);
					$userkey["email"] = $dbQueries->get_user_detail('mo2f_user_email',$currentUserId);
					
					$message = 'The One Time Passcode has been sent to '. $userkey . ' Please enter the one time passcode below to verify your email.';
					$mo2fa_login_message = $message;
				}
				else if($selected_2factor_method == 'PHONE VERIFICATION'){
					$currentMethod = "PHONE_VERIFICATION";
					$userkey = isset($_SESSION['mo2f_phone']) ? $_SESSION['mo2f_phone'] : null;
					$message = isset($_SESSION['mo2f_phone']) ? __('You will receive a phone call on this number ','miniorange-2-factor-authentication') . $userkey . '.'. __('Please enter the one time passcode below to verify your number.','miniorange-2-factor-authentication') : __('Please click on Verify button to receive the phone call.','miniorange-2-factor-authentication');
					$mo2fa_login_message = $message;
				}else{
					$currentMethod = 'EMAIL';
					$userkey = $dbQueries->get_user_detail('mo_2factor_user_email',$currentUserId);
					$mo2fa_login_message = __('An OTP has been sent to ','miniorange-2-factor-authentication').' <b> ' . ( $userkey ) . '</b>.'. __('Please enter the OTP below to verify your email.','miniorange-2-factor-authentication');
				}
				
				$customer = new Customer_Setup();
				$content = json_decode($customer->send_otp_token($userkey,$currentMethod,get_site_option( 'mo2f_customerKey'),get_site_option( 'mo2f_api_key')), true);
				
				
				if(strcasecmp($content['status'], 'SUCCESS') == 0) {
					update_user_meta($currentUserId,'mo_2fa_verify_otp_create_account',$content['txId']);
					if($currentMethod == 'EMAIL'){
						$dbQueries->update_user_details( $currentUserId, array('mo_2factor_user_registration_status' => 'MO_2_FACTOR_OTP_DELIVERED_SUCCESS') );
						// update_user_meta($currentUserId, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_SUCCESS');
						$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
					}else{
						$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
					}
										
				}else{
					$mo2fa_login_message = __('There was an error in sending one time passcode. Please click on Resend OTP to try again.','miniorange-2-factor-authentication');
					if($currentMethod == 'EMAIL'){
						$dbQueries->update_user_details( $currentUserId, array('mo_2factor_user_registration_status' => 'MO_2_FACTOR_OTP_DELIVERED_FAILURE') );
						// update_user_meta($currentUserId, 'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');
						$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
					}else {
						$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
					}
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		
		if ( isset($_POST['mo2f_inline_ga_phone_type_nonce'])){	//select google phone type during user inline registration when google authenticator is selected
		
			$nonce = $_POST['mo2f_inline_ga_phone_type_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-ga-phone-type-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$phone_type = $_POST['google_phone_type'];
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
				$google_auth = new Miniorange_Rba_Attributes();
				if(get_site_option('mo2f_enable_gauth_name')){
				$google_account_name = isset($_POST['google_account_name']) ? $_POST['google_account_name'] : "miniOrangeAuth";
				// update_site_option('mo2f_GA_account_name', $google_account_name);
				// user_meta
				update_user_meta($current_user->ID,'mo2f_GA_account_name', $google_account_name);
				}else{
				$google_account_name=get_site_option('mo2f_GA_account_name');
				}
				
				$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user->ID);
				// $google_response = json_decode($google_auth->mo2f_google_auth_service($email),true);
				$google_response = json_decode($google_auth->mo2f_google_auth_service($email,
				$google_account_name),true);
				if(json_last_error() == JSON_ERROR_NONE) {
					if($google_response['status'] == 'SUCCESS'){
						$mo2f_google_auth = array();
						$mo2f_google_auth['ga_qrCode'] = $google_response['qrCodeData'];
						$mo2f_google_auth['ga_secret'] = $google_response['secret'];
						$mo2f_google_auth['ga_phone'] = $phone_type;
						$_SESSION['mo2f_google_auth'] = $mo2f_google_auth;
						
					}else{
						$mo2fa_login_message = __('Error occurred while registering the user for google authenticator. Please try again.','miniorange-2-factor-authentication');
					}
				}else{
					$mo2fa_login_message = __('Invalid request. Please try again.','miniorange-2-factor-authentication');
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if(isset($_POST['mo2f_inline_validate_ga_nonce'])){
			$nonce = $_POST['mo2f_inline_validate_ga_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-google-auth-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$otpToken = $_POST['google_auth_code'];
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$mo2f_google_auth = isset($_SESSION['mo2f_google_auth']) ? $_SESSION['mo2f_google_auth'] : null;
				$ga_secret = $mo2f_google_auth != null ? $mo2f_google_auth['ga_secret'] : null;
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
				
				if(MO2f_Utility::mo2f_check_number_length($otpToken)){
					$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user->ID);
					$selected_2factor_method = $dbQueries->get_user_detail('mo2f_configured_2FA_method',$current_user->ID);
					// $email = get_user_meta($current_user->ID,'mo2f_user_email',true);
					$google_auth = new Miniorange_Rba_Attributes();
					$google_response = json_decode($google_auth->mo2f_validate_google_auth($email,$otpToken,$ga_secret),true);
					if(json_last_error() == JSON_ERROR_NONE) {
						if($google_response['status'] == 'SUCCESS'){
							$enduser = new Two_Factor_Setup();
							$response = json_decode($enduser->mo2f_update_userinfo($email,$selected_2factor_method,null,null,null),true);
							if(json_last_error() == JSON_ERROR_NONE) { 
								
								if($response['status'] == 'SUCCESS'){
								
									// update_user_meta($current_user->ID,'mo2f_google_authentication_status',true);
									// update_user_meta($current_user->ID,'mo2f_authy_authentication_status',false);
									
									$dbQueries->update_user_details( $current_user->ID, array(
									'mo2f_GoogleAuthenticator_config_status' => true,
									'mo2f_AuthyAuthenticator_config_status' => false
									) );
									
									update_user_meta($current_user->ID,'mo2f_external_app_type','GOOGLE AUTHENTICATOR');
									$mo2fa_login_status = 'MO_2_FACTOR_SETUP_SUCCESS';
									
								}else{
									$mo2fa_login_message = __('An error occured while processing your request. Please Try again.','miniorange-2-factor-authentication');
								}
							}else{
								$mo2fa_login_message = __('An error occured while processing your request. Please Try again.','miniorange-2-factor-authentication');
							}
						}else{
							$mo2fa_login_message = __('An error occured while processing your request. Please Try again.','miniorange-2-factor-authentication');	
						}
					}else{
						$mo2fa_login_message = __('An error occured while validating the user. Please Try again.','miniorange-2-factor-authentication');
						
					}
				}else{
					$mo2fa_login_message = __('Only digits are allowed. Please enter again.','miniorange-2-factor-authentication');
					
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if ( isset($_POST['mo2f_inline_authy_configure_nonce'])){	//select google phone type during user inline registration when google authenticator is selected
		
			$nonce = $_POST['mo2f_inline_authy_configure_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-authy-configuration-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$authy = new Miniorange_Rba_Attributes();
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
				$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user->ID);
				$authy_response = json_decode($authy->mo2f_google_auth_service($email),true);
				if(json_last_error() == JSON_ERROR_NONE) {
					if($authy_response['status'] == 'SUCCESS'){
						$mo2f_authy_keys = array();
						$mo2f_authy_keys['authy_qrCode'] = $authy_response['qrCodeData'];
						$mo2f_authy_keys['authy_secret'] = $authy_response['secret'];
						$_SESSION['mo2f_authy_keys'] = $mo2f_authy_keys;
					}else{
						$mo2fa_login_message = __('Error occurred while registering the user for authy 2-factor authentication. Please try again.','miniorange-2-factor-authentication');
					}
				}else{
					$mo2fa_login_message = __('Invalid request. Please try again.','miniorange-2-factor-authentication');
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if(isset($_POST['mo2f_inline_validate_authy_authentication_nonce'])){
			$nonce = $_POST['mo2f_inline_validate_authy_authentication_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-authy-authentication-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$otpToken = isset($_POST['authy_auth_code']) ? $_POST['authy_auth_code'] : null;
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$mo2f_google_auth = isset($_SESSION['mo2f_authy_keys']) ? $_SESSION['mo2f_authy_keys'] : null;
				$authy_secret = $mo2f_google_auth != null ? $mo2f_google_auth['authy_secret'] : null;
				
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
				
				if(MO2f_Utility::mo2f_check_number_length($otpToken)){
					$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user->ID);
					// $email = get_user_meta($current_user->ID,'mo2f_user_email',true);
					$google_auth = new Miniorange_Rba_Attributes();
					$google_response = json_decode($google_auth->mo2f_validate_google_auth($email,$otpToken,$authy_secret),true);
					if(json_last_error() == JSON_ERROR_NONE) {
						if($google_response['status'] == 'SUCCESS'){
							$enduser = new Two_Factor_Setup();
							$response = json_decode($enduser->mo2f_update_userinfo($email,'GOOGLE AUTHENTICATOR',null,null,null),true);
							if(json_last_error() == JSON_ERROR_NONE) { 
								
								if($response['status'] == 'SUCCESS'){
									// update_user_meta($current_user->ID,'mo2f_selected_2factor_method', 'GOOGLE AUTHENTICATOR');
									// update_user_meta($current_user->ID,'mo2f_authy_authentication_status',true);
									// update_user_meta($current_user->ID,'mo2f_google_authentication_status',false);
									
									$dbQueries->update_user_details( $current_user->ID, array(
									'mo2f_configured_2FA_method' =>'GOOGLE AUTHENTICATOR',
									'mo2f_GoogleAuthenticator_config_status' => false,
									'mo2f_AuthyAuthenticator_config_status' => true
									) );
									
									update_user_meta($current_user->ID,'mo2f_external_app_type','AUTHY 2-FACTOR AUTHENTICATION');
									$mo2fa_login_status = 'MO_2_FACTOR_SETUP_SUCCESS';
									
								}else{
									$mo2fa_login_message = __('An error occured while validating the user. Please Try again.','miniorange-2-factor-authentication');
						
								}
							}else{
								$mo2fa_login_message = __('An error occured while validating the user. Please Try again.','miniorange-2-factor-authentication');
						
							}
						}else{
							$mo2fa_login_message = __('Error occurred while validating the OTP. Please try again. Possible causes','miniorange-2-factor-authentication').': <br />1. '. __('You have entered an invalid OTP','miniorange-2-factor-authentication').'.<br />2.'. __('Your App Time is not in sync.Go to settings and tap on tap on Sync Time now .','miniorange-2-factor-authentication');
						}
					}else{
						$mo2fa_login_message = __('Error occurred while validating the user. Please try again.','miniorange-2-factor-authentication');
						
					}
				}else{
					$mo2fa_login_message = __('Only digits are allowed. Please enter again.','miniorange-2-factor-authentication');
					
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if(isset($_POST['miniorange_inline_validate_user_otp_nonce'])){
			$nonce = $_POST['miniorange_inline_validate_user_otp_nonce'];
			$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
			$user_email = $dbQueries->get_user_detail('mo_2factor_user_email',$current_user->ID);
			// var_dump($user_email);exit;
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-validate-user-otp-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$otp_token = '';
				if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['otp_token'] ) ) {
					$mo2fa_login_message = __('OTP can not be empty. Please enter OTP to verify.','miniorange-2-factor-authentication');
					$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
				} else{
					$otp_token = sanitize_text_field( $_POST['otp_token'] );
				}
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$id = $current_user->ID;
				if(!MO2f_Utility::check_if_email_is_already_registered($id, $user_email)){
					$customer = new Customer_Setup();
					$transactionId = get_user_meta($id,'mo_2fa_verify_otp_create_account',true);
					$content = json_decode($customer->validate_otp_token( 'EMAIL', null, $transactionId, $otp_token, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
					if($content['status'] == 'ERROR'){
						$mo2fa_login_message = Mo2fConstants::langTranslate($content['message']);
						$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
					}else{
						if(strcasecmp($content['status'], 'SUCCESS') == 0) { //OTP validated and generate QRCode
							$this->mo2f_register_user_inline($user_email);
						}else{  // OTP Validation failed.
							// update_user_meta($id,'mo_2factor_user_registration_status','MO_2_FACTOR_OTP_DELIVERED_FAILURE');
							$dbQueries->update_user_details( $id, array(
									'mo_2factor_user_registration_status' =>'MO_2_FACTOR_OTP_DELIVERED_FAILURE',
									) );
							$mo2fa_login_message = __('Invalid OTP. Please try again.','miniorange-2-factor-authentication');
							$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
							$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
						}
					}

				}else{
					$mo2fa_login_message = __('The email is already used by other user. Please register with other email by clicking on Back button.','miniorange-2-factor-authentication');
					$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
				}
			}
		}
		
		if(isset($_POST['miniorange_inline_save_2factor_method_nonce'])){
			$nonce = $_POST['miniorange_inline_save_2factor_method_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-save-2factor-method-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
//				var_dump("here");exit;
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$currentUserId = $current_user->ID;
				$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user->ID);
				$user_registration_with_miniorange = $dbQueries->get_user_detail('mo_2factor_user_registration_with_miniorange',$current_user->ID);
			// var_dump($user_registration_with_miniorange);exit;
				if($user_registration_with_miniorange == 'SUCCESS'){
					$selected_method = isset($_POST['mo2f_selected_2factor_method']) ? $_POST['mo2f_selected_2factor_method'] : 'NONE';
					// update_user_meta( $currentUserId,'mo2f_selected_2factor_method', $selected_method); //status for second factor selected by user
					$dbQueries->update_user_details( $current_user->ID, array(
									'mo2f_configured_2FA_method' =>$selected_method,
									) );
			
					if($selected_method == 'OUT OF BAND EMAIL'){
						$enduser = new Two_Factor_Setup();
						$enduser->mo2f_update_userinfo($email, 'OUT OF BAND EMAIL',null,null,null);
						// update_user_meta($currentUserId,'mo2f_email_verification_status',true);
						$dbQueries->update_user_details( $currentUserId, array(
									'mo2f_email_verification_status' =>true,
									) );
						$mo2fa_login_status = 'MO_2_FACTOR_SETUP_SUCCESS';
					}else if($selected_method == 'OTP OVER EMAIL'){
						// if(0){//not edit email{
						$enduser = new Two_Factor_Setup();
						$enduser->mo2f_update_userinfo($email, 'EMAIL',null,null,null);
						update_user_meta($currentUserId,'mo2f_email_otp_registration_status',true);
						$mo2fa_login_status = 'MO_2_FACTOR_SETUP_SUCCESS';
						// }
					}
				}else{ 
					$mo2fa_login_message = __('Invalid request. Please register with miniOrange to configure 2 Factor plugin.','miniorange-2-factor-authentication');
					
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if(isset($_POST['miniorange_inline_verify_phone_nonce'])){
			$nonce = $_POST['miniorange_inline_verify_phone_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-verify-phone-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$phone = sanitize_text_field( $_POST['verify_phone'] );
				$session_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$current_user = $session_user->ID;
				$customer = new Customer_Setup();
					$selected_2factor_method = $dbQueries->get_user_detail('mo2f_configured_2FA_method',$current_user);
				$parameters = array();
					$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user);
				
				// $email = get_user_meta($current_user,'mo_2factor_map_id_with_email',true);
				$mo2fa_login_message = '';	
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
if($selected_2factor_method=='SMS' || $selected_2factor_method=='PHONE VERIFICATION' ||$selected_2factor_method== 'SMS AND EMAIL'){
			$phone = sanitize_text_field( $_POST['verify_phone'] );
				
				$phone = sanitize_text_field( $_POST['verify_phone'] );
				if( MO2f_Utility::mo2f_check_empty_or_null( $phone ) ){
					$mo2fa_login_message = __('Please enter your phone number.','miniorange-2-factor-authentication');
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
				}
				$phone = str_replace(' ', '', $phone);
				$_SESSION['mo2f_phone'] = $phone;
					
					}
			
				if($selected_2factor_method == 'SMS'){
					$currentMethod = "OTP_OVER_SMS";
				}else if($selected_2factor_method == 'SMS AND EMAIL'){
					$currentMethod = "OTP_OVER_SMS_AND_EMAIL";
					$parameters = array("phone" => $phone, "email" => $email);
				}else if($selected_2factor_method == 'PHONE VERIFICATION'){
					$currentMethod = "PHONE_VERIFICATION";
				}else if($selected_2factor_method == 'OTP OVER EMAIL'){

					$currentMethod = "OTP_OVER_EMAIL";
					$parameters = $email;
				}
					
				if($selected_2factor_method == 'SMS AND EMAIL'){
					$content = json_decode($customer->send_otp_token($parameters,$currentMethod,get_site_option( 'mo2f_customerKey'),get_site_option( 'mo2f_api_key')), true);
				}
				else if($selected_2factor_method == 'OTP OVER EMAIL'){
					$content = json_decode($customer->send_otp_token($email,$currentMethod,get_site_option( 'mo2f_customerKey'),get_site_option( 'mo2f_api_key')), true);						
				}
				else{
				$content = json_decode($customer->send_otp_token($phone,$currentMethod,get_site_option( 'mo2f_customerKey'),get_site_option( 'mo2f_api_key')), true);
				}
					
				if(json_last_error() == JSON_ERROR_NONE) { /* Generate otp token */
					if($content['status'] == 'ERROR'){
						$mo2fa_login_message = Mo2fConstants::langTranslate($response['message']);
					}else if($content['status'] == 'SUCCESS'){
						$_SESSION[ 'mo2f_transactionId' ] = $content['txId'];
						
						if($selected_2factor_method == 'SMS'){
								$mo2fa_login_message = __('The One Time Passcode has been sent to','miniorange-2-factor-authentication'). $phone . '.' . __('Please enter the one time passcode below to verify your number.','miniorange-2-factor-authentication');
						}else if($selected_2factor_method == 'SMS AND EMAIL'){
								$mo2fa_login_message = 'The One Time Passcode has been sent to ' . $parameters["phone"] . ' and '. $parameters["email"] . '. Please enter the one time passcode sent to your email and phone to verify.';
						}else if($selected_2factor_method == 'OTP OVER EMAIL'){
								$mo2fa_login_message = __('The One Time Passcode has been sent to ','miniorange-2-factor-authentication') .  $parameters . '.' .  __('Please enter the one time passcode sent to your email to verify.','miniorange-2-factor-authentication');
						
						}else if($selected_2factor_method== 'PHONE VERIFICATION'){
							$mo2fa_login_message = __('You will receive a phone call on this number ','miniorange-2-factor-authentication') . $phone . '.' .  __('Please enter the one time passcode below to verify your number.','miniorange-2-factor-authentication');
						}
						
					}else{
$mo2fa_login_message = __('An error occured while validating the user. Please Try again.','miniorange-2-factor-authentication');
						
					}
					
				}else{
					$mo2fa_login_message = __('Invalid request. Please try again','miniorange-2-factor-authentication');
					
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if(isset($_POST['miniorange_inline_validate_otp_nonce'])){
			$nonce = $_POST['miniorange_inline_validate_otp_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-validate-otp-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$otp_token = '';
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
				$mo2fa_login_message = '';
				if( MO2f_Utility::mo2f_check_empty_or_null( $_POST['otp_token'] ) ) {
					$mo2fa_login_message =  __('All the fields are required. Please enter valid entries.','miniorange-2-factor-authentication');
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
				} else{
					$otp_token = sanitize_text_field( $_POST['otp_token'] );
				}
				$session_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$current_user = $session_user->ID;
				$selected_2factor_method = $dbQueries->get_user_detail('mo2f_configured_2FA_method',$current_user);
				$user_phone = $dbQueries->get_user_detail('mo2f_user_phone',$current_user);
				$customer = new Customer_Setup();
				$content = json_decode($customer->validate_otp_token( $selected_2factor_method, null, $_SESSION[ 'mo2f_transactionId' ], $otp_token, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
				if($content['status'] == 'ERROR'){
					$mo2fa_login_message = Mo2fConstants::langTranslate($content['message']);
				
				}else if(strcasecmp($content['status'], 'SUCCESS') == 0) { //OTP validated 
						if($user_phone && strlen($user_phone) >= 4){
							if($_SESSION['mo2f_phone'] != $user_phone ){
								// update_user_meta($current_user,'mo2f_mobile_registration_status',false);
								$dbQueries->update_user_details( $current_user, array(
									'mo2f_mobile_registration_status' =>false
									) );
							}
						}
						// $email = get_user_meta($current_user,'mo2f_user_email',true);
						$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user);
						$phone = $_SESSION['mo2f_phone'];
						
						$enduser = new Two_Factor_Setup();
						$enduser->mo2f_update_userinfo($email,$selected_2factor_method,$phone,null,null);
						$response = json_decode($enduser->mo2f_update_userinfo($email,$selected_2factor_method,$phone,null,null),true);
						if(json_last_error() == JSON_ERROR_NONE) { 
								
								if($response['status'] == 'ERROR'){
									unset($_SESSION[ 'mo2f_phone']);
									$mo2fa_login_message = $response['message'];
								}else if($response['status'] == 'SUCCESS'){

								if(!($dbQueries->get_user_detail('mo2f_configured_2FA_method',$current_user)=='OTP OVER EMAIL')){
									$dbQueries->update_user_details( $current_user, array(
									'mo2f_otp_registration_status' =>true,
									'mo2f_user_phone' =>$_SESSION[ 'mo2f_phone']
									) );
								}
									else{
										update_user_meta($current_user,'mo2f_email_otp_registration_status',true);
									}	
									
									unset($_SESSION[ 'mo2f_phone']);
									$mo2fa_login_status = 'MO_2_FACTOR_SETUP_SUCCESS';
									
								}else{
										unset($_SESSION[ 'mo2f_phone']);
										$mo2fa_login_message = __('An error occured while validating the user. Please Try again.','miniorange-2-factor-authentication');
								}
						}else{
								unset($_SESSION[ 'mo2f_phone']);
								$mo2fa_login_message = __('Invalid request. Please try again','miniorange-2-factor-authentication');
								
						}
						
				}else{  // OTP Validation failed.
						$mo2fa_login_message =  __('Invalid OTP. Please try again.','miniorange-2-factor-authentication');
						
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			} 
		}
		
		if(isset($_POST['miniorange_inline_show_qrcode_nonce'])){
			$nonce = $_POST['miniorange_inline_show_qrcode_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-show-qrcode-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
				$user_registration_status = $dbQueries->get_user_detail('mo_2factor_user_registration_status',$current_user->ID);
				// var_dump($user_registration_status);exit;
				if($user_registration_status == 'MO_2_FACTOR_INITIALIZE_TWO_FACTOR') {
					// $email = get_user_meta($current_user->ID,'mo2f_user_email',true);
					$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user->ID);
					$mo2fa_login_message = $this->mo2f_inline_get_qr_code_for_mobile($email,$current_user->ID);
				}else{
					$mo2fa_login_message = __('Invalid request. Please register with miniOrange before configuring your mobile.','miniorange-2-factor-authentication');
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		
		if(isset($_POST['mo_auth_inline_mobile_registration_complete_nonce'])){
			$nonce = $_POST['mo_auth_inline_mobile_registration_complete_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-mobile-registration-complete-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				unset($_SESSION[ 'mo2f-login-qrCode' ]);
				unset($_SESSION[ 'mo2f-login-transactionId' ]);
				unset($_SESSION[ 'mo2f_show_qr_code'] );
				$session_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$current_user = $session_user->ID;
				$selected_2factor_method = $dbQueries->get_user_detail('mo2f_configured_2FA_method',$current_user);
				// $email = get_user_meta($current_user,'mo_2factor_map_id_with_email',true);
				$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user);
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
				$enduser = new Two_Factor_Setup();
				$response = json_decode($enduser->mo2f_update_userinfo($email,$selected_2factor_method,null,null,null),true);
				
				if(json_last_error() == JSON_ERROR_NONE) { /* Generate Qr code */
						if($response['status'] == 'ERROR'){
							$mo2fa_login_message = Mo2fConstants::langTranslate($response['message']);
						}else if($response['status'] == 'SUCCESS'){
							$dbQueries->update_user_details( $current_user, array(
									'mo2f_mobile_registration_status' =>true,
									) );
									
							// update_user_meta($current_user,'mo2f_mobile_registration_status',true);
							$mo2fa_login_status = 'MO_2_FACTOR_SETUP_SUCCESS';
						}else{
							$mo2fa_login_message = __('An error occured while validating the user. Please Try again.','miniorange-2-factor-authentication');
						}
				}else{
						$mo2fa_login_message = __('Invalid request. Please try again','miniorange-2-factor-authentication');
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		
		if(isset($_POST['mo2f_inline_save_kba_nonce'])){
			$nonce = $_POST['mo2f_inline_save_kba_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-save-kba-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$mo2fa_login_message = '';
				$mo2fa_login_status = isset($_POST['mo2f_inline_kba_status']) ? 'MO_2_FACTOR_SETUP_SUCCESS' : 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
				
				
				$temp_array = array();
				$temp_array = isset($_POST['mo2f_kbaquestion']) ? $_POST['mo2f_kbaquestion'] : array();
				$kba_questions = array();
				foreach($temp_array as $question){
					if(MO2f_Utility::mo2f_check_empty_or_null( $question)){
						$mo2fa_login_message =  __('All the fields are required. Please enter valid entries.','miniorange-2-factor-authentication');
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
					}else{
						$ques = sanitize_text_field($question);
						$ques = addcslashes(stripslashes($ques), '"\\');
						array_push($kba_questions, $ques);
					}
				}
				
				if(!(array_unique($kba_questions) == $kba_questions)){
					$mo2fa_login_message = __('The questions you select must be unique.','miniorange-2-factor-authentication');
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
				}
				
				$temp_array_ans = array();
				$temp_array_ans = isset($_POST['mo2f_kba_ans']) ? $_POST['mo2f_kba_ans'] : array();
				$kba_answers = array();
				foreach($temp_array_ans as $answer){
					if(MO2f_Utility::mo2f_check_empty_or_null( $answer)){
						$mo2fa_login_message =  __('All the fields are required. Please enter valid entries.','miniorange-2-factor-authentication');
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
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
				
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				// $email = get_user_meta($current_user->ID,'mo2f_user_email',true);
				$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user->ID);
				$kba_registration = new Two_Factor_Setup();
				$kba_reg_reponse = json_decode($kba_registration->register_kba_details($email, $kba_q_a_list),true);
				if(json_last_error() == JSON_ERROR_NONE) { 
					if($kba_reg_reponse['status'] == 'SUCCESS'){
						if(isset($_POST['mo2f_inline_kba_option']) && $_POST['mo2f_inline_kba_option'] == 'mo2f_inline_kba_registration'){
							// update_user_meta($current_user->ID,'mo2f_kba_registration_status',true);
							// update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
							
							$dbQueries->update_user_details( $current_user->ID, array(
									'mo2f_SecurityQuestions_config_status' =>true,
									'mo_2factor_user_registration_status' =>'MO_2_FACTOR_PLUGIN_SETTINGS'
									) );
							
							$mo2fa_login_status = 'MO_2_FACTOR_SETUP_SUCCESS';
					
						}else{
							$enduser = new Two_Factor_Setup();
							$selected_2factor_method = $dbQueries->get_user_detail('mo2f_configured_2FA_method',$current_user->ID);
							$response = json_decode($enduser->mo2f_update_userinfo($email,$selected_2factor_method,null,null,null),true);
							if(json_last_error() == JSON_ERROR_NONE) { /* Generate Qr code */
								if($response['status'] == 'ERROR'){
									$mo2fa_login_message = Mo2fConstants::langTranslate($response['message']);
								
								}else if($response['status'] == 'SUCCESS'){
									$dbQueries->update_user_details( $current_user->ID, array(
									'mo2f_SecurityQuestions_config_status' =>true,
									'mo_2factor_user_registration_status' =>'MO_2_FACTOR_PLUGIN_SETTINGS'
									) );
									// update_user_meta($current_user->ID,'mo2f_kba_registration_status',true);
									// update_user_meta($current_user->ID,'mo_2factor_user_registration_status','MO_2_FACTOR_PLUGIN_SETTINGS');
									$mo2fa_login_status = 'MO_2_FACTOR_SETUP_SUCCESS';
								}
							}else{
								$mo2fa_login_message = __('Error occured while saving your kba details. Please try again.','miniorange-2-factor-authentication');
							}
						}
					}else{
						$mo2fa_login_message = __('Error occured while saving your kba details. Please try again.','miniorange-2-factor-authentication');
					}
				}else{
					$mo2fa_login_message = __('Error occured while saving your kba details. Please try again.','miniorange-2-factor-authentication');
				}
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
			}
		}
		
		if(isset($_POST['miniorange_softtoken'])){ /*Click on the link of phone is offline */
			$nonce = $_POST['miniorange_softtoken'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-softtoken' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else{
				$this->miniorange_pass2login_start_session();
				unset($_SESSION[ 'mo2f-login-qrCode' ]);
				unset($_SESSION[ 'mo2f-login-transactionId' ]);
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				$mo2fa_login_message =  __('Please enter the one time passcode shown in the','miniorange-2-factor-authentication') .' <b>miniOrange Authenticator</b>'.__(' app.','miniorange-2-factor-authentication');
				$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_SOFT_TOKEN';
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
			}
		}
		
		if (isset($_POST['miniorange_soft_token_nonce'])){ /*Validate Soft Token,OTP over SMS,OTP over EMAIL,Phone verification */
			$nonce = $_POST['miniorange_soft_token_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-soft-token-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$mo2fa_login_status = isset($_POST['request_origin_method']) ? $_POST['request_origin_method'] : null;
				$redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : null;
				$softtoken = '';
				if( MO2f_utility::mo2f_check_empty_or_null( $_POST[ 'mo2fa_softtoken' ] ) ) {
					$mo2fa_login_message = __('Please enter OTP to proceed.','miniorange-2-factor-authentication');
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
				} else{
					$softtoken = sanitize_text_field( $_POST[ 'mo2fa_softtoken' ] );
					if(!MO2f_utility::mo2f_check_number_length($softtoken)){
						$mo2fa_login_message = __('Invalid OTP. Only digits within range 4-8 are allowed. Please try again.','miniorange-2-factor-authentication');
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
					}
				}
				$currentuser = isset($_SESSION[ 'mo2f_current_user' ]) ? unserialize( $_SESSION[ 'mo2f_current_user' ] ) : null;
				if(isset($_SESSION[ 'mo2f_current_user' ])){
					$customer = new Customer_Setup();
					$content ='';
					$email = $dbQueries->get_user_detail('mo2f_user_email',$currentuser->ID);
					if(isset($mo2fa_login_status) && $mo2fa_login_status == 'MO_2_FACTOR_CHALLENGE_OTP_OVER_EMAIL'){
						$content = json_decode($customer->validate_otp_token( 'EMAIL', null, $_SESSION[ 'mo2f-login-transactionId' ], $softtoken, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
					}else if(isset($mo2fa_login_status) && ($mo2fa_login_status == 'MO_2_FACTOR_CHALLENGE_OTP_OVER_SMS')){
						$content = json_decode($customer->validate_otp_token( 'SMS', null, $_SESSION[ 'mo2f-login-transactionId' ], $softtoken, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
					}else if(isset($mo2fa_login_status) && ($mo2fa_login_status == 'MO_2_FACTOR_CHALLENGE_OTP_OVER_SMS_AND_EMAIL')){
						$content = json_decode($customer->validate_otp_token( 'SMS AND EMAIL', null, $_SESSION[ 'mo2f-login-transactionId' ], $softtoken, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
					}else if(isset($mo2fa_login_status) && $mo2fa_login_status == 'MO_2_FACTOR_CHALLENGE_PHONE_VERIFICATION'){
						$content = json_decode($customer->validate_otp_token( 'PHONE VERIFICATION', null, $_SESSION[ 'mo2f-login-transactionId' ], $softtoken, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key') ),true);
					}else if(isset($mo2fa_login_status) && $mo2fa_login_status == 'MO_2_FACTOR_CHALLENGE_SOFT_TOKEN'){
						$content = json_decode($customer->validate_otp_token( 'SOFT TOKEN', $email, null, $softtoken, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key')),true);
					}else if(isset($mo2fa_login_status) && $mo2fa_login_status == 'MO_2_FACTOR_CHALLENGE_GOOGLE_AUTHENTICATION'){
						$content = json_decode($customer->validate_otp_token( 'GOOGLE AUTHENTICATOR', $email, null, $softtoken, get_site_option('mo2f_customerKey'), get_site_option('mo2f_api_key')),true);
					}else{
						$this->remove_current_activity();
						return new WP_Error('invalid_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication'). '</strong>: ' . __('Invalid Request. Please try again.','miniorange-2-factor-authentication'));
					}
					
					
					
					if(strcasecmp($content['status'], 'SUCCESS') == 0) {
						
						if(get_site_option('mo2f_remember_device') && get_site_option('mo2f_login_policy')){ //add check here
							if(get_site_option('mo2f_enable_rba_types')==0){
								try{
									$currentuser = unserialize( $_SESSION[ 'mo2f_current_user' ] );
									$email = $dbQueries->get_user_detail('mo2f_user_email',$currentuser->ID);
									mo2f_register_profile($email,'true',$_SESSION[ 
									'mo2f_rba_status' ]);
								}catch(Exception $e){
									echo $e->getMessage();
								}
								$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
								$this->mo2fa_pass2login($redirect_to);
							}else{
								$mo2fa_login_status = 'MO_2_FACTOR_REMEMBER_TRUSTED_DEVICE';
								$login_message = mo2f_get_user_2ndfactor($currentuser);
								$this->miniorange_pass2login_form_fields($mo2fa_login_status, $login_message, $redirect_to);
							}
			
						}else{
							$this->mo2fa_pass2login($redirect_to);
						}
					}else{
						
						$message = $mo2fa_login_status == 'MO_2_FACTOR_CHALLENGE_SOFT_TOKEN' ? __('Invalid OTP ...Possible causes ','miniorange-2-factor-authentication').'<br />1. ' . __('You mis-typed the OTP, find the OTP again and type it.','miniorange-2-factor-authentication').' <br /> 2. ' . __('Your phone time is not in sync with miniOrange servers.','miniorange-2-factor-authentication').' <br /><b>'. __('How to sync?','miniorange-2-factor-authentication').'</b> ' . __('In the app,tap on Settings icon and then press Sync button.','miniorange-2-factor-authentication') : __('Invalid OTP. Please try again','miniorange-2-factor-authentication');
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $message, $redirect_to);
					}
					
				}else{
					$this->remove_current_activity();
					return new WP_Error('invalid_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') . '</strong>: '.__('Please try again..', 'miniorange-2-factor-authentication'));
				
				}
			}
		}
		
		if (isset($_POST['miniorange_inline_skip_registration_nonce'])){ /*Validate Soft Token,OTP over SMS,OTP over EMAIL,Phone verification */
			$nonce = $_POST['miniorange_inline_skip_registration_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-skip-registration-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				// delete_user_meta($current_user->ID,'mo2f_selected_2factor_method');
				$dbQueries->update_user_details( $current_user->ID, array( "mo2f_configured_2FA_method" => '' ) );
				$this->mo2fa_pass2login();
			}
		}
		
		if (isset($_POST['miniorange_inline_goto_user_registration_nonce'])){ /*Validate Soft Token,OTP over SMS,OTP over EMAIL,Phone verification */
			$nonce = $_POST['miniorange_inline_goto_user_registration_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-inline-goto-user-registration-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				$this->miniorange_pass2login_start_session();
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				// delete_user_meta($current_user->ID,'mo_2factor_user_email');
				delete_user_meta($current_user->ID,'mo_2fa_verify_otp_create_account');
				// delete_user_meta($current_user->ID, 'mo_2factor_user_registration_status');
				$dbQueries->update_user_details( $current_user->ID, array(
												'mo_2factor_user_registration_status' => '',
												'mo_2factor_user_registration_with_miniorange' => '',
												'mo_2factor_user_email' => '',
												'mo2f_user_email' => ''
																	) );
				// delete_user_meta($current_user->ID, 'mo_2factor_user_registration_with_miniorange');
				// delete_user_meta($current_user->ID,'mo_2factor_map_id_with_email');
				
				// $dbQueries->update_user_details( $current_user->ID, array( "mo_2factor_map_id_with_email" => '' ) );
				$mo2fa_login_message = '';
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REGISTRATION';
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
				
			}
		}
		
		if (isset($_POST['miniorange_attribute_collection_nonce'])){ /*Handling Rba Attributes during inline registration */
			$nonce = $_POST['miniorange_attribute_collection_nonce'];
			
			if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-login-attribute-collection-nonce' ) ) {
				$error = new WP_Error();
				$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication') .'</strong>: '. __('Invalid Request.', 'miniorange-2-factor-authentication'));
				return $error;
			} else {
				
				$this->miniorange_pass2login_start_session();
				$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
				$attributes = isset($_POST[ 'miniorange_rba_attribures' ]) ? $_POST[ 'miniorange_rba_attribures' ] : null;
				$redirect_to = isset($_POST[ 'redirect_to' ]) ? $_POST[ 'redirect_to' ] : null;
				$this->miniorange_initiate_during_rba($current_user, $attributes, $redirect_to);
						
			}	
		}
	}
	
	function miniorange_initiate_during_rba($currentuser, $attributes=null, $redirect_to=null){
		$this->miniorange_pass2login_start_session();
		$_SESSION[ 'mo2f_current_user' ] = serialize( $currentuser );
		$_SESSION[ 'mo2f_1stfactor_status' ] = 'VALIDATE_SUCCESS';
		$current_roles = miniorange_get_user_role($currentuser);
		$enabled = miniorange_check_if_2fa_enabled_for_roles($current_roles);
		global $dbQueries;
		$user_registration_status = $dbQueries->get_user_detail('mo_2factor_user_registration_status',$current_user->ID);			
		if($enabled){
			// $email = get_user_meta($currentuser->ID,'mo_2factor_map_id_with_email',true);
			$email = $dbQueries->get_user_detail('mo2f_user_email',$currentuser->ID);
			if( $email && $user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){ //checking if user has configured any 2nd factor method
			try{
					$mo2f_rba_status = mo2f_collect_attributes($email,stripslashes($attributes)); // Rba flow
					$_SESSION[ 'mo2f_rba_status' ] = $mo2f_rba_status;
					
				}catch(Exception $e){
					echo $e->getMessage();
				}
			}
			
			if(($mo2f_rba_status['status'] == 'WAIT_FOR_INPUT' || $mo2f_rba_status['status'] == 'SUCCESS') && $mo2f_rba_status['decision_flag']){ //add check here
				
				$mo2f_second_factor = mo2f_get_user_2ndfactor($currentuser);
				$login_message = $mo2f_second_factor;
				$mo2fa_login_status = 'MO_2_FACTOR_REMEMBER_TRUSTED_DEVICE';
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $login_message , $redirect_to);
			}else if(($mo2f_rba_status['status'] == 'ERROR' )){
					mo2f_device_exceeded_error();
					exit;
			}else{
				$kba_registration_status = $dbQueries->get_user_detail('mo2f_SecurityQuestions_config_status',$current_user->ID);	
				if(MO2f_Utility::check_if_request_is_from_mobile_device($_SERVER['HTTP_USER_AGENT']) && $kba_registration_status && get_site_option('mo2f_enable_mobile_support')){
						$this->mo2f_pass2login_kba_verification($currentuser->ID, $redirect_to);
					}else{
						
						$mo2f_second_factor = mo2f_get_user_2ndfactor($currentuser);
						
						if($mo2f_second_factor == 'MOBILE AUTHENTICATION'){
							$this->mo2f_pass2login_mobile_verification($currentuser, $redirect_to);
						}else if($mo2f_second_factor == 'PUSH NOTIFICATIONS' || $mo2f_second_factor == 'OUT OF BAND EMAIL'){
							$this->mo2f_pass2login_push_oobemail_verification($currentuser,$mo2f_second_factor, $redirect_to);
						}else if($mo2f_second_factor == 'SOFT TOKEN' || $mo2f_second_factor == 'SMS' ||$mo2f_second_factor == 'OTP_OVER_EMAIL' || $mo2f_second_factor == 'PHONE VERIFICATION' || $mo2f_second_factor == 'GOOGLE AUTHENTICATOR' ){
							$this->mo2f_pass2login_otp_verification($currentuser,$mo2f_second_factor, $redirect_to);
						}else if($mo2f_second_factor == 'KBA'){
								$this->mo2f_pass2login_kba_verification($currentuser->ID, $redirect_to);
						}else{
							$this->remove_current_activity();
							$error = new WP_Error();
							$error->add('empty_username', '<strong>'. __('ERROR','miniorange-2-factor-authentication').'</strong>: '. __('Please try again or contact your admin.', 'miniorange-2-factor-authentication'));
							return $error;
						}
						
					}
				
			
			
			}
		}else{ //plugin is not activated for current role then logged him in without asking 2 factor
				$this->mo2fa_pass2login($redirect_to);
		}
	}
	
	function mo2f_collect_device_attributes_for_authenticated_user($currentuser, $redirect_to = null){
		if(get_site_option('mo2f_remember_device') && get_site_option('mo2f_login_policy')){
			$this->miniorange_pass2login_start_session();
			$_SESSION[ 'mo2f_current_user' ] = serialize( $currentuser );
			mo2f_collect_device_attributes_handler($redirect_to);
			exit;
		}else{
			$this->miniorange_initiate_2nd_factor($currentuser, null, $redirect_to);
		}
	}					
	
	function mo2f_check_username_password($user, $username, $password, $redirect_to=null ){
			
			$username_password_verification = false;
			if(get_site_option('mo2f_login_policy')){
				if(is_a($user, 'WP_Error') && !empty($user) ){
					return $user;
				}
			}
			$currentuser = '';
			if ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST && get_option('mo2f_enable_xmlrpc')){
					
					$currentuser = wp_authenticate_username_password($user, $username, $password);
					if (is_wp_error($currentuser)) {
						$this->error = new IXR_Error(403, __('Bad login/pass combination.'));
						return false;
					}else{
						return $currentuser;
					}
			}else{
				
				if(get_site_option('mo2f_login_policy')){		
					$currentuser = wp_authenticate_username_password($user, $username, $password);
					if(is_wp_error($currentuser)) {
						return $currentuser;
					}
					$username_password_verification = true;
				}else{
					
					if(isset($_POST['mo2fa_username']) && !empty($_POST['mo2fa_username'])){
						$username = sanitize_text_field( $_POST['mo2fa_username'] );
							
						if ( username_exists( $username ) ){
							$currentuser = get_user_by('login', $username );		
						}else if(email_exists($username)){
							$currentuser = get_user_by('email', $username );
						}else{
							$username_error = new WP_Error( 'invalid_username', __('Please enter correct username.','miniorange-2-factor-authentication') );
							return $username_error;
						}
					}else{
						
						$currentuser = wp_authenticate_username_password($user, $username, $password);
						if(is_wp_error($currentuser)) {	
							if(array_key_exists('empty_username', $currentuser->errors) && array_key_exists('empty_password', $currentuser->errors)){
								return $currentuser;
							} else if(array_key_exists('empty_username', $currentuser->errors)){
								$user_error = new WP_Error( 'empty_username', __('The username or email field is empty.','miniorange-2-factor-authentication'));
							}else if(array_key_exists('empty_password', $currentuser->errors)){
								$user_error = new WP_Error( 'empty_password', 'The password field is empty.' );
							}else if(array_key_exists('invalid_username', $currentuser->errors)){
								$invalid_username_error = $currentuser->errors['invalid_username'][0];
								$user_error = new WP_Error( 'invalid_username', $invalid_username_error );
							} else if(array_key_exists('incorrect_password', $currentuser->errors)){
								$invalid_password_error = $currentuser->errors['incorrect_password'][0];
								$user_error = new WP_Error( 'invalid_username', $invalid_password_error );
							}else{
								$user_error = new WP_Error( 'invalid_username', __('Please enter correct username.','miniorange-2-factor-authentication'));
							}
							return $user_error;
						}
						$username_password_verification = true;
						
					}
				}
				
				$attributes = isset($_POST[ 'miniorange_rba_attribures' ]) ? $_POST[ 'miniorange_rba_attribures' ] : null;
				// var_dump($_REQUEST[ 'redirect_to' ]);exit;
					$redirect_to = isset($_REQUEST[ 'redirect_to' ]) ? $_REQUEST[ 'redirect_to' ] : (isset($_REQUEST[ 'redirect' ]) ? $_REQUEST[ 'redirect' ] : null);
			// var_dump($redirect_to,$_REQUEST[ 'redirect_to' ],isset($_REQUEST[ 'redirect_to' ]) );exit;
				$currentuser = $this->miniorange_initiate_2nd_factor($currentuser, $attributes, $redirect_to, $username_password_verification);
				if(is_wp_error($currentuser)) {
					return $currentuser;
				}
			}	
	}			
		
	function miniorange_initiate_2nd_factor($currentuser, $attributes=null, $redirect_to=null, $username_password_verification = false){
						
		$this->miniorange_pass2login_start_session();
		$_SESSION[ 'mo2f_current_user' ] = serialize( $currentuser );
		$_SESSION[ 'mo2f_1stfactor_status' ] = 'VALIDATE_SUCCESS';
					
		$current_roles = miniorange_get_user_role($currentuser);
		
		$enabled = miniorange_check_if_2fa_enabled_for_roles($current_roles);
		/**Uncomment these two lines to enable the shortcode in case the admin wants his users to decide whether they want 2FA or not!
		 *
		 *$enable_2fa_byusers = $dbQueries->get_user_detail('mo2f_2factor_enable_2fa_byusers',$current_user->ID);
		 *if($enabled && $enabled_2fa_byusers){
		 */

		if(get_site_option('mo2f_by_roles') ){
            $enabled = miniorange_check_if_2fa_enabled_for_roles($current_roles);
		}
		else{
			$list_ids=get_site_option('mo2f_select_user_for_2fa');
			$enabled =in_array($currentuser->ID,$list_ids);
		}
		global $dbQueries;
		if($enabled){
				
			// $email = get_user_meta($currentuser->ID,'mo_2factor_map_id_with_email',true);
				$email = $dbQueries->get_user_detail('mo2f_user_email',$currentuser->ID);
				$user_registration_status = $dbQueries->get_user_detail('mo_2factor_user_registration_status',$currentuser->ID);
			if( $email && $user_registration_status == 'MO_2_FACTOR_PLUGIN_SETTINGS'){
					
					try{
						$mo2f_rba_status = mo2f_collect_attributes($email,stripslashes($attributes)); // Rba flow
						$_SESSION[ 'mo2f_rba_status' ] = $mo2f_rba_status;
						
					}catch(Exception $e){
						echo $e->getMessage();
					}
					
					if($mo2f_rba_status['status'] == 'SUCCESS' && $mo2f_rba_status['decision_flag']){
						$this->mo2fa_pass2login($redirect_to);
					}else if($mo2f_rba_status['status'] == 'ERROR' ){
						mo2f_device_exceeded_error();
						exit;
					}else{
					$kba_registration_status = $dbQueries->get_user_detail('mo2f_SecurityQuestions_config_status',$currentuser->ID);
					if(MO2f_Utility::check_if_request_is_from_mobile_device($_SERVER['HTTP_USER_AGENT']) && $kba_registration_status && get_site_option('mo2f_enable_mobile_support')){
							$this->mo2f_pass2login_kba_verification($currentuser->ID, $redirect_to);
						}else{
							
							$mo2f_second_factor = mo2f_get_user_2ndfactor($currentuser);
						if($mo2f_second_factor =='OTP OVER EMAIL' || $mo2f_second_factor =='EMAIL')
						{
							$mo2f_second_factor ='OTP_OVER_EMAIL';
						}
							if($mo2f_second_factor == 'MOBILE AUTHENTICATION'){
								$this->mo2f_pass2login_mobile_verification($currentuser, $redirect_to);
							}else if($mo2f_second_factor == 'PUSH NOTIFICATIONS' || $mo2f_second_factor == 'OUT OF BAND EMAIL'){
								$this->mo2f_pass2login_push_oobemail_verification($currentuser,$mo2f_second_factor, $redirect_to);
							}else if($mo2f_second_factor == 'SOFT TOKEN' || $mo2f_second_factor == 'OTP_OVER_EMAIL' || $mo2f_second_factor == 'SMS' ||  $mo2f_second_factor == 'SMS AND EMAIL' || $mo2f_second_factor == 'PHONE VERIFICATION' || $mo2f_second_factor == 'GOOGLE AUTHENTICATOR' ){
								$this->mo2f_pass2login_otp_verification($currentuser,$mo2f_second_factor, $redirect_to);
							}else if($mo2f_second_factor == 'KBA'){
									$this->mo2f_pass2login_kba_verification($currentuser->ID, $redirect_to);
							}else{
								$this->remove_current_activity();
								$error = new WP_Error();
								$error->add('empty_username', '<strong>'.__('ERROR','miniorange-2-factor-authentication').'</strong>: '. __('Please try again or contact your admin.', 'miniorange-2-factor-authentication'));
								return $error;
							}
							
						}
					}
				}else{ //if user has not configured any 2nd factor method, then prompt him to configure 2nd factor
					
					if(!get_site_option('mo2f_inline_registration')){	// do not enforce inline registration
						//$this->mo2fa_pass2login($redirect_to);
						
						if($username_password_verification){
							//both username and passwords are chekced. Allow user to login.
							$this->mo2fa_pass2login($redirect_to);
						}else{				
							$this->remove_current_activity();
							$error = new WP_Error( 'error', __('Two-factor is not enabled for you. Please login using username and password.', 'miniorange-2-factor-authentication') );
							return $error;
						}
					}else{
						// if user has disabled email edit feature during inline registration, he is being registered in the backend and being redirected to the setup 2FA Page directly

                        if(!get_site_option('mo2f_enable_emailchange')){

                            $email = $currentuser->user_email;
//                            $email = $dbQueries->get_user_detail('mo2f_configured_2FA_method',$currentuser->ID);
//                            $dbQueries->update_user_details( $currentuser->ID, array( "mo2f_configured_2FA_method" => '' ) );
                            $dbQueries->insert_user( $currentuser->ID, array( 'user_id' => $currentuser->ID ) );
							if(MO2f_Utility::check_if_email_is_already_registered($currentuser->ID, $email)){ //email is already registered.
							
								$mo2fa_login_message = __('The email associated with your account is already registered. Please contact your admin to change the email.','miniorange-2-factor-authentication');
								$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_RELOGIN';
								$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
							}else{
								 
								// registering the user if he is not already registered with miniOrange
								$mo2fa_login_message = '';
								$enduser = new Two_Factor_Setup();
								$check_user = json_decode($enduser->mo_check_user_already_exist($email),true);
								$currentUserId = $currentuser->ID;
							delete_user_meta($currentUserId,'mo2f_user_email');
								$dbQueries->update_user_details( $currentUserId, array( "mo2f_user_email" => '' ) );
									if(json_last_error() == JSON_ERROR_NONE){
										
									if($check_user['status'] == 'ERROR'){
										$mo2fa_login_message = Mo2fConstants::langTranslate($check_user['message']);
										$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_RELOGIN';
									}else if(strcasecmp($check_user['status' ], 'USER_FOUND') == 0){
										// delete_user_meta($currentUserId,'mo_2factor_user_email');
										$dbQueries->update_user_details( $currentUserId, array( "mo_2factor_user_email" => '' ) );
									$dbQueries->update_user_details( $currentUserId, array(
									'mo_2factor_user_registration_with_miniorange' =>'SUCCESS',
									'mo2f_user_email' =>$email,
									'mo_2factor_user_registration_status' =>'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'
									) );
									// update_user_meta($currentUserId,'mo_2factor_user_registration_with_miniorange','SUCCESS');
                                    // update_user_meta($currentUserId,'mo_2factor_map_id_with_email',$email);
                                    // update_user_meta($currentUserId,'mo_2factor_user_registration_status','MO_2_FACTOR_INITIALIZE_TWO_FACTOR');
										$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
									}else if(strcasecmp($check_user['status'], 'USER_NOT_FOUND') == 0) {
										$content = json_decode($enduser->mo_create_user($currentuser,$email), true);
										if(json_last_error() == JSON_ERROR_NONE) {
											if(strcasecmp($content['status'], 'SUCCESS') == 0) {
												// delete_user_meta($currentUserId,'mo_2factor_user_email');
												$dbQueries->update_user_details( $currentUserId, array( "mo_2factor_user_email" => '' ) );
											$dbQueries->update_user_details( $currentUserId, array(
											'mo_2factor_user_registration_with_miniorange' =>'SUCCESS',
											'mo2f_user_email' =>$email,
											'mo_2factor_user_registration_status' =>'MO_2_FACTOR_INITIALIZE_TWO_FACTOR'
											) );
											// update_user_meta($currentUserId,'mo_2factor_user_registration_with_miniorange','SUCCESS');
                                            // update_user_meta($currentUserId,'mo_2factor_map_id_with_email',$email);
                                            // update_user_meta($currentUserId,'mo_2factor_user_registration_status','MO_2_FACTOR_INITIALIZE_TWO_FACTOR');
												$mo2fa_login_message = '';
												$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
											}
											else if(strcasecmp($content['status'], 'ERROR') == 0){
						
											/* // If the number of users have been used up, defaulting to 'Skip 2 Factor registration at login' under Login Settings.
										if($content['message'] == "Your user creation limit has been completed. Please upgrade your license to add more users."){
										update_site_option( 'mo2f_inline_registration', 0);
										} */
												
							$mo2fa_login_message = Mo2fConstants::langTranslate($content['message']);
							$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_RELOGIN';
							}
										}
									}else if(strcasecmp($check_user['status'], 'USER_FOUND_UNDER_DIFFERENT_CUSTOMER') == 0){
										   $mo2fa_login_message = __('The email associated with your account is already registered. Please contact your admin to change the email.','miniorange-2-factor-authentication');
										   $mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_RELOGIN';
									}
								}
								delete_user_meta($currentUserId,'mo_2fa_verify_otp_create_account');
							}
								
						}else {
							// if email edit feature is enabled, we register the user by asking him for the OTP sent to his email
							
							// delete_user_meta($currentuser->ID,'mo2f_selected_2factor_method');
							$dbQueries->update_user_details( $currentuser->ID, array( "mo2f_configured_2FA_method" => '' ) );
							
							$mo2fa_login_message = '';
							$mo2fa_login_status= '';
						$user_registration_status = $dbQueries->get_user_detail('mo2f_SecurityQuestions_config_status',$currentuser->ID);
						if( $user_registration_status =='MO_2_FACTOR_INITIALIZE_TWO_FACTOR'){
								$mo2fa_login_message = '';
								$mo2fa_login_status ='MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
							}else{
								$mo2fa_login_message = '';
								$mo2fa_login_status ='MO_2_FACTOR_PROMPT_FOR_USER_REGISTRATION';
							}
						} 
							// var_dump($mo2fa_login_status);exit;
						$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
					}
				}
				
			}else{ //plugin is not activated for current role then logged him in without asking 2 factor
				if($username_password_verification){
					//both username and passwords are chekced. Allow user to login.
					$this->mo2fa_pass2login($redirect_to);
				}else{				
					$this->remove_current_activity();
					$error = new WP_Error( 'error', __('Two-factor is not enabled for you. Please login using username and password.', 'miniorange-2-factor-authentication') );
					return $error;
				}
			}
		// }else{
			// //plugin is not activated for current role then logged him in without asking 2 factor
			// if($username_password_verification){
				// //both username and passwords are chekced. Allow user to login.
				// $this->mo2fa_pass2login($redirect_to);
			// }else{				
				// $this->remove_current_activity();
				// $error = new WP_Error( 'error', __('Two-factor is not enabled for you. Please login using username and password.', 'miniorange-2-factor-authentication') );
				// return $error;
			// }
		// }
    }
	
	function mo_2_factor_enable_jquery_default_login(){
		wp_enqueue_script('jquery');
	}
	
	function miniorange_pass2login_form_fields($mo2fa_login_status=null, $mo2fa_login_message=null, $redirect_to=null){
		$mo2f_shortcode = new MO2F_ShortCode();
		$login_status = $mo2fa_login_status;
		$login_message = $mo2fa_login_message;
		
		$current_user = isset($_SESSION[ 'mo2f_current_user' ]) ? unserialize( $_SESSION[ 'mo2f_current_user' ] ) : null;
		// var_dump($current_user	);exit;
		$current_user_id = is_null($current_user) ? null : $current_user->ID;
		// $current_roles = miniorange_get_user_role($current_user);
		global $dbQueries;
		if($this->miniorange_pass2login_check_mobile_status($login_status)){ //for mobile
			mo2f_getqrcode($login_status, $login_message, $redirect_to);
			exit;
		}else if($this->miniorange_pass2login_check_otp_status($login_status)){ //for soft-token,otp over email,sms,phone verification,google auth
			mo2f_getotp_form($current_user_id,$login_status, $login_message, $redirect_to);
			exit;
		}else if($this->miniorange_pass2login_check_forgotphone_status($login_status)){ // forgot phone page if both KBA and Email are configured.
			mo2f_get_forgotphone_form($login_status, $login_message, $redirect_to);
			exit;
		}else if($this->miniorange_pass2login_check_push_oobemail_status($login_status)){ //for push and out of band email.
			mo2f_getpush_oobemail_response($current_user_id, $login_status, $login_message, $redirect_to);
			exit;
		}else if($this->miniorange_pass2login_check_kba_status($login_status)){ // for Kba 
			mo2f_getkba_form($login_status, $login_message, $redirect_to);
			exit;
		}else if($this->miniorange_pass2login_check_backup_status($login_status)){ // for Kba 
			mo2f_backup_form($login_status, $login_message, $redirect_to);
			exit;
		}else if($this->miniorange_pass2login_check_trusted_device_status($login_status)){
			//trusted device form
			mo2f_get_device_form($current_user_id, $login_status, $login_message, $redirect_to);
			exit;
		}else if($this->miniorange_pass2login_reconfig_google($login_status)){ //MO_2_FACTOR_RECONFIG_GOOGLE 
			$mo2f_shortcode->prompt_user_for_reconfigure_google($current_user_id, $login_status, $login_message);
			exit;
		}else if($this->miniorange_pass2login_reconfig_kba($login_status)){ //MO_2_FACTOR_RECONFIG_KBA
			$mo2f_shortcode->prompt_user_for_reconfigure_kba($current_user_id, $login_status, $login_message);
			exit;
		}else if($this->miniorange_pass2login_relogin($login_status)){ //MO_2_FACTOR_PROMPT_FOR_RELOGIN
			prompt_user_for_relogin($current_user_id, $login_status, $login_message);
			exit;
		}
		else if($this->miniorange_pass2login_check_inline_user_registration($login_status)){ // inline registration started
			prompt_user_to_register($current_user, $login_status, $login_message);
			exit;
		}else if($this->miniorange_pass2login_check_inline_user_otp($login_status)){ //otp verification after user enter email during inline registration
			prompt_user_for_validate_otp($login_status, $login_message);
			exit;
		}else if($this->miniorange_pass2login_inline_setup_success($login_status)){ //MO_2_FACTOR_SETUP_SUCCESS
		// var_dump($redirect_to);exit;
			prompt_user_for_setup_success($current_user_id,$login_status, $login_message);
			exit;
		}else if($this->miniorange_pass2login_check_inline_user_2fa_methods($login_status)){ // two-factor methods
			
		$current_roles = miniorange_get_user_role($current_user);
					 // var_dump(get_option('mo2f_all_users_method'));exit;
				 if(get_option('mo2f_all_users_method')){
						$opt = (array) get_site_option('mo2f_auth_methods_for_users');
					 }
				 else{
				      $opt=(array)get_option( 'mo2f_auth_methods_for_'.$current_roles[0]);
		// var_dump($opt);exit;
				 }
			// var_dump($opt); 
			// var_dump(sizeof($opt));exit; 
			
			if (sizeof($opt) > 1) {
				prompt_user_to_select_2factor_mthod_inline($current_user_id, $login_status, $login_message);
				exit;
			}else if( in_array("SMS", $opt) || in_array("SMS AND EMAIL", $opt) ||in_array("PHONE VERIFICATION", $opt)|| in_array("OTP OVER EMAIL", $opt) ){
				$authtype = array_shift($opt);
				$dbQueries->update_user_details( $current_user_id, array(
											'mo2f_configured_2FA_method' =>$authtype
											) );
				// update_user_meta($current_user_id,'mo2f_selected_2factor_method',$authtype);
				prompt_user_for_phone_setup($current_user_id, $login_status, $login_message);
				exit;
			}else if( in_array("SOFT TOKEN", $opt) || in_array("PUSH NOTIFICATIONS", $opt) || in_array("MOBILE AUTHENTICATION", $opt)  ){
				$authtype = array_shift($opt);
				$dbQueries->update_user_details( $current_user_id, array(
											'mo2f_configured_2FA_method' =>$authtype
											) );
				// update_user_meta($current_user_id,'mo2f_selected_2factor_method',$authtype);
				prompt_user_for_miniorange_app_setup($current_user_id, $login_status, $login_message);
				exit;
			}else if( in_array("GOOGLE AUTHENTICATOR", $opt) ){
				$dbQueries->update_user_details( $current_user_id, array(
								'mo2f_configured_2FA_method' =>'GOOGLE AUTHENTICATOR'
											) );
				// update_user_meta($current_user_id,'mo2f_configured_2FA_method','GOOGLE AUTHENTICATOR');
				prompt_user_for_google_authenticator_setup($current_user_id, $login_status, $login_message);
				exit;
			}else if( in_array("AUTHY 2-FACTOR AUTHENTICATION", $opt) ){
				$dbQueries->update_user_details( $current_user_id, array(
								'mo2f_configured_2FA_method' =>'GOOGLE AUTHENTICATOR'
											) );
				// update_user_meta($current_user_id,'mo2f_configured_2FA_method','GOOGLE AUTHENTICATOR');
				prompt_user_for_authy_authenticator_setup($current_user_id, $login_status, $login_message);
				exit;
			}else if( in_array("KBA", $opt) ){
				$dbQueries->update_user_details( $current_user_id, array(
								'mo2f_configured_2FA_method' =>'KBA'
											) );
				// update_user_meta($current_user_id,'mo2f_configured_2FA_method','KBA');
				prompt_user_for_kba_setup($current_user_id, $login_status, $login_message);
				exit;
			}else{
				// update_user_meta($current_user_id,'mo2f_selected_2factor_method','OUT OF BAND EMAIL');
				$dbQueries->update_user_details( $current_user_id, array(
								'mo2f_configured_2FA_method' =>'OUT OF BAND EMAIL'
											) );
				$enduser = new Two_Factor_Setup();
				$email = $dbQueries->get_user_detail('mo2f_user_email',$current_user_id);
				$enduser->mo2f_update_userinfo($email, 'OUT OF BAND EMAIL',null,null,null);
				prompt_user_for_setup_success($current_user_id, $login_status, $login_message);
				exit;
			}
			
		}else{  
			if(get_site_option('mo2f_show_loginwith_phone')){ //login with phone overwrite default login form
			
				if(isset($_POST['miniorange_login_nonce']) && wp_verify_nonce( $_POST['miniorange_login_nonce'], 'miniorange-2-factor-login-nonce' ) && !get_site_option('mo2f_inline_registration')){
					$this->mo_2_factor_show_login_with_password_when_phonelogin_enabled();
					$this->mo_2_factor_show_wp_login_form_when_phonelogin_enabled();
					$mo2f_user_login = is_null($current_user) ? null : $current_user->user_login;
					?><script>
						jQuery('#user_login').val(<?php echo "'" . $mo2f_user_login . "'";?>);
					</script><?php
				}else{
					$this->mo_2_factor_show_login();
					$this->mo_2_factor_show_wp_login_form();
				}
				
			}else{ //Login with phone is along with default login form
				$this->mo_2_factor_show_login();
				$this->mo_2_factor_show_wp_login_form();
			}				
		}
	}

	function mo_2_factor_show_login() {
		if(get_site_option('mo2f_show_loginwith_phone')){
			wp_register_style( 'show-login', plugins_url( 'includes/css/hide-login-form.css?version=4.4.1', __FILE__ ) );
		}else{
			wp_register_style( 'show-login', plugins_url( 'includes/css/show-login.css?version=4.4.1', __FILE__ ) );
		}
		wp_enqueue_style( 'show-login' );
	}	

	function mo_2_factor_show_wp_login_form(){
	?>
		<div class="mo2f-login-container">
			<?php if(!get_site_option('mo2f_show_loginwith_phone')){ ?>
			<div style="position: relative" class="or-container">
				<div style="border-bottom: 1px solid #EEE; width: 90%; margin: 0 5%; z-index: 1; top: 50%; position: absolute;"></div>
				<h2 style="color: #666; margin: 0 auto 20px auto; padding: 3px 0; text-align:center; background: white; width: 20%; position:relative; z-index: 2;"><?php echo mo2f_lt('or')?></h2>
			</div>
			<?php } ?>
			<div class="mo2f-button-container" id="mo2f_button_container">
				<input type="text" name="mo2fa_usernamekey" id="mo2fa_usernamekey" autofocus="true" placeholder="<?php echo mo2f_lt('Username');?>"/>
					<p>
						<input type="button" name="miniorange_login_submit"  style="width:100% !important;" onclick="mouserloginsubmit();" id="miniorange_login_submit" class="miniorange-button button-add" value="<?php echo __('Login with 2nd Factor');?>" />
					</p>
					<?php if(!get_site_option('mo2f_show_loginwith_phone')){ ?><br /><br /><?php } ?>
			</div>
		</div>
		
		<script>
			jQuery(window).scrollTop(jQuery('#mo2f_button_container').offset().top);
			function mouserloginsubmit(){
				var username = jQuery('#mo2fa_usernamekey').val();
				document.getElementById("mo2f_show_qrcode_loginform").elements[0].value = username;
				jQuery('#mo2f_show_qrcode_loginform').submit();
				
			 }
			 
			 jQuery('#mo2fa_usernamekey').keypress(function(e){
				  if(e.which == 13){//Enter key pressed
					e.preventDefault();
					var username = jQuery('#mo2fa_usernamekey').val();
					document.getElementById("mo2f_show_qrcode_loginform").elements[0].value = username;
					jQuery('#mo2f_show_qrcode_loginform').submit();
				  }
				 
			});
		</script>
	<?php
	}
	
	function miniorange_pass2login_inline_setup_success($login_status){
		if($login_status == 'MO_2_FACTOR_SETUP_SUCCESS'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_reconfig_google($login_status){
		if($login_status == 'MO_2_FACTOR_RECONFIG_GOOGLE'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_reconfig_kba($login_status){
		if($login_status == 'MO_2_FACTOR_RECONFIG_KBA'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_relogin($login_status){
		if($login_status == 'MO_2_FACTOR_PROMPT_FOR_RELOGIN'){
			return true;
		}
		return false;
	}
	
	
	function miniorange_pass2login_reconfig_success_google($login_status){
		if($login_status == 'MO2F_RECONFIGURE_SUCCESS_GOOGLE'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_reconfig_success_kba($login_status){
		if($login_status == 'MO2F_RECONFIGURE_SUCCESS_KBA'){
			return true;
		}
		return false;
	}
	
	function custom_login_enqueue_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'bootstrap_script', plugins_url('includes/js/bootstrap.min.js', __FILE__ ));
	}	
	
	function miniorange_pass2login_check_inline_user_2fa_methods($login_status,$sso=false){
		if($login_status == 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_check_inline_user_otp($login_status){
		
		if($login_status == 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_check_inline_user_registration($login_status){

		if($login_status == 'MO_2_FACTOR_PROMPT_FOR_USER_REGISTRATION'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_check_forgotphone_status($login_status){  // after clicking on forgotphone link when both kba and email are configured
		if($login_status == 'MO_2_FACTOR_CHALLENGE_KBA_AND_OTP_OVER_EMAIL'){
			return true;
		}
		return false;
	}
	
	
	function miniorange_pass2login_check_trusted_device_status($login_status){
		
		if($login_status == 'MO_2_FACTOR_REMEMBER_TRUSTED_DEVICE'){
			
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_check_push_oobemail_status($login_status){  // for push and out of and email
		if($login_status == 'MO_2_FACTOR_CHALLENGE_PUSH_NOTIFICATIONS' || $login_status == 'MO_2_FACTOR_CHALLENGE_OOB_EMAIL'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_check_otp_status($login_status,$sso=false){
		
		if($login_status == 'MO_2_FACTOR_CHALLENGE_SOFT_TOKEN' || $login_status == 'MO_2_FACTOR_CHALLENGE_OTP_OVER_EMAIL' || $login_status == 'MO_2_FACTOR_CHALLENGE_OTP_OVER_SMS' || $login_status == 'MO_2_FACTOR_CHALLENGE_OTP_OVER_SMS_AND_EMAIL'  || $login_status == 'MO_2_FACTOR_CHALLENGE_PHONE_VERIFICATION' || $login_status == 'MO_2_FACTOR_CHALLENGE_GOOGLE_AUTHENTICATION'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_check_mobile_status($login_status){    //mobile authentication
		if($login_status == 'MO_2_FACTOR_CHALLENGE_MOBILE_AUTHENTICATION'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_check_kba_status($login_status){
		if($login_status == 'MO_2_FACTOR_CHALLENGE_KBA_AUTHENTICATION'){
			return true;
		}
		return false;
	}
	function miniorange_pass2login_check_backup_status($login_status){
		if($login_status == 'MO_2_FACTOR_CHALLENGE_BACKUP'){
			return true;
		}
		return false;
	}
	
	function miniorange_pass2login_footer_form(){
		if(get_site_option('mo2f_remember_device') && get_site_option('mo2f_login_policy')){
	?>
		<script>
			jQuery(document).ready(function(){
				if(document.getElementById('loginform') != null){
					 jQuery('#loginform').on('submit', function(e){
						jQuery('#miniorange_rba_attribures').val(JSON.stringify(rbaAttributes.attributes));
					});
				}else{
					if(document.getElementsByClassName('login') != null){
						jQuery('.login').on('submit', function(e){
							jQuery('#miniorange_rba_attribures').val(JSON.stringify(rbaAttributes.attributes));
						});
					}
				}
			});
		</script>
	<?php }?>
		<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo wp_login_url(); ?>" hidden>
			<input type="hidden" name="miniorange_mobile_validation_failed_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-mobile-validation-failed-nonce'); ?>" />
		</form>
		<form name="f" id="mo2f_show_qrcode_loginform" method="post" action="" hidden>
			<input type="text" name="mo2fa_username" id="mo2fa_username" hidden/>
			<input type="hidden" name="miniorange_login_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-login-nonce'); ?>" />
		</form>
	<?php	
	}
	
	function mo2f_pass2login_otp_verification($user,$mo2f_second_factor, $redirect_to){
		global $dbQueries;
		if($mo2f_second_factor == 'SOFT TOKEN'){
			$mo2fa_login_message = __('Please enter the one time passcode shown in the ','miniorange-2-factor-authentication').'<b>miniOrange Authenticator</b>'. __('app.','miniorange-2-factor-authentication');
			$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_SOFT_TOKEN';
			$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
		}else if($mo2f_second_factor == 'GOOGLE AUTHENTICATOR'){
			$mo2fa_login_message = get_user_meta($user->ID,'mo2f_external_app_type',true) == 'AUTHY 2-FACTOR AUTHENTICATION' ? __('Please enter the one time passcode shown in the','miniorange-2-factor-authentication') .' <b> '.__('Authy 2-Factor Authentication','miniorange-2-factor-authentication').' </b> '.__(' app.','miniorange-2-factor-authentication') : __('Please enter the one time passcode shown in the ','miniorange-2-factor-authentication').' <b> '.__('Google Authenticator','miniorange-2-factor-authentication').' </b> '. __('app.','miniorange-2-factor-authentication');
			$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_GOOGLE_AUTHENTICATION';
			$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
		}else{
			$challengeMobile = new Customer_Setup();
			$email = "";
			$phone = "";
			if($dbQueries->get_user_detail('mo2f_user_email',$user->ID) != null){
			$email = $dbQueries->get_user_detail('mo2f_user_email',$user->ID);
			    // $email = get_user_meta($user->ID,'mo2f_user_email',true);
			}
			$user_phone = $dbQueries->get_user_detail('mo2f_user_phone',$user->ID);
			if($user_phone != null){
			    $phone = $user_phone;
			}
			

			if ($mo2f_second_factor == 'SMS AND EMAIL') {
				$currentMethod = 'OTP_OVER_SMS_AND_EMAIL';
				$parameters = array("email" => $email,
				                    "phone" => $phone);
				
				$content = $challengeMobile->send_otp_token($parameters, $currentMethod,get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key'));
					
			}else{
				$content = $challengeMobile->send_otp_token($email, $mo2f_second_factor,get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key'));
			}
	
			$response = json_decode($content, true);
			if(json_last_error() == JSON_ERROR_NONE) {
				
				if($mo2f_second_factor=='OTP_OVER_EMAIL'){
					$mo2f_second_factor = 'OTP OVER EMAIL';
				}
				if($response['status'] == 'SUCCESS'){
					$email = $dbQueries->get_user_detail('mo2f_user_email',$user->ID);
					if ($mo2f_second_factor == 'SMS AND EMAIL') {
						$message = __('The OTP has been sent to','miniorange-2-factor-authentication').' '. MO2f_Utility::get_hidden_phone($response['phoneDelivery']['contact']) . ' and ' . MO2f_Utility::mo2f_get_hiden_email($email) . ' .' . __('Please enter the OTP you received to Validate.','miniorange-2-factor-authentication');
						
						$mo2fa_login_status =  'MO_2_FACTOR_CHALLENGE_OTP_OVER_SMS_AND_EMAIL';
						
					}else if ($mo2f_second_factor == 'SMS'){
						$message = __('The OTP has been sent to','miniorange-2-factor-authentication').' '. MO2f_Utility::get_hidden_phone($response['phoneDelivery']['contact']) . '.' . __('Please enter the OTP you received to Validate.','miniorange-2-factor-authentication');
		
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_OTP_OVER_SMS';
						
					}else if ($mo2f_second_factor == 'OTP OVER EMAIL'){
						$message = __('The OTP has been sent to','miniorange-2-factor-authentication').' '. MO2f_Utility::mo2f_get_hiden_email($email) . ' .' . __('Please enter the OTP you received to Validate.','miniorange-2-factor-authentication');
		
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_OTP_OVER_EMAIL';
						
					}else if ($mo2f_second_factor == 'PHONE VERIFICATION'){
						$message = __('You will receive phone call on ','miniorange-2-factor-authentication') . MO2f_Utility::get_hidden_phone($response['phoneDelivery']['contact']) .  __('with OTP. Please enter the OTP to Validate.','miniorange-2-factor-authentication');
						
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_PHONE_VERIFICATION';
					}
					$_SESSION[ 'mo2f-login-transactionId' ] = $response[ 'txId' ];
					
					$mo2fa_login_message = $message;
					
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
				}else{
					$message = ($mo2f_second_factor == 'SMS' || $mo2f_second_factor == 'SMS AND EMAIL')? Mo2fConstants::langTranslate($response['message']) . __('You can click on','miniorange-2-factor-authentication').' <b>' . __('Forgot your phone','miniorange-2-factor-authentication').' </b> '. __('link to login via alternate method.','miniorange-2-factor-authentication') : __('We are unable to send the OTP via phone call on your registered phone. You can click on','miniorange-2-factor-authentication').' <b>' . __('Forgot your phone','miniorange-2-factor-authentication').' </b> ' . __('link to receive OTP to your registered email.','miniorange-2-factor-authentication');
					
					$_SESSION[ 'mo2f-login-transactionId' ] = $response[ 'txId' ];
					
					$mo2fa_login_message = $message;
					if(($mo2f_second_factor == 'SMS')){
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_OTP_OVER_SMS';
					}else if ($mo2f_second_factor == 'SMS AND EMAIL') {
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_OTP_OVER_SMS_AND_EMAIL';
					}else if($mo2f_second_factor == 'PHONE VERIFICATION'){
						$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_PHONE_VERIFICATION';
					}
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
				}
			}else{
				$this->remove_current_activity();
				$error = new WP_Error();
				$error->add('empty_username', '<strong>' . __('ERROR','miniorange-2-factor-authentication'). '</strong>: '. __('An error occured while processing your request. Please Try again.', 'miniorange-2-factor-authentication'));
				return $error;
			}
		}
	}
	function mo2f_pass2login_push_oobemail_verification($user,$mo2f_second_factor, $redirect_to){
		global $dbQueries;
		$email = $dbQueries->get_user_detail('mo2f_user_email',$user->ID);
		$challengeMobile = new Customer_Setup();
		$content = $challengeMobile->send_otp_token($email,$mo2f_second_factor ,get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key'));
		$response = json_decode($content, true);
		if(json_last_error() == JSON_ERROR_NONE) { /* Generate Qr code */
			if($response['status'] == 'SUCCESS'){

				$_SESSION[ 'mo2f-login-transactionId' ] = $response['txId'];
				$mo2fa_login_message = $mo2f_second_factor == 'PUSH NOTIFICATIONS' ? __('A Push Notification has been sent to your phone. We are waiting for your approval.','miniorange-2-factor-authentication') : __('An email has been sent to ','miniorange-2-factor-authentication'). MO2f_Utility::mo2f_get_hiden_email($email) . '. ' .__('We are waiting for your approval.','miniorange-2-factor-authentication');
				
				
				$mo2fa_login_status = $mo2f_second_factor == 'PUSH NOTIFICATIONS' ? 'MO_2_FACTOR_CHALLENGE_PUSH_NOTIFICATIONS' : 'MO_2_FACTOR_CHALLENGE_OOB_EMAIL';
				
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
				
			}else if($response['status'] == 'ERROR' || $response['status'] == 'FAILED' ){
				$_SESSION[ 'mo2f-login-transactionId' ] = $response['txId'];
				
				$mo2fa_login_message = $mo2f_second_factor == 'PUSH NOTIFICATIONS' ? __('An error occured while sending push notification to your app. You can click on','miniorange-2-factor-authentication').' <b>' . __('Phone is Offline','miniorange-2-factor-authentication').' </b>' . __('button to enter soft token from app or ','miniorange-2-factor-authentication').' <b> '. __('Forgot your phone','miniorange-2-factor-authentication').' </b> '. __('button to receive OTP to your registered email.','miniorange-2-factor-authentication') : __('An error occured while sending email. Please try again.','miniorange-2-factor-authentication');
				
				$mo2fa_login_status = $mo2f_second_factor == 'PUSH NOTIFICATIONS' ? 'MO_2_FACTOR_CHALLENGE_PUSH_NOTIFICATIONS' : 'MO_2_FACTOR_CHALLENGE_OOB_EMAIL';
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
			}
		}else{
			$this->remove_current_activity();
			$error = new WP_Error();
			$error->add('empty_username', '<strong>' . __('ERROR','miniorange-2-factor-authentication').'</strong>: '. __('An error occured while processing your request. Please Try again.', 'miniorange-2-factor-authentication'));
			return $error;
		}
	}
	
	function mo2f_pass2login_kba_verification($user_id, $redirect_to){
		global $dbQueries;
	    $email = $dbQueries->get_user_detail('mo2f_user_email',$user_id);
		$challengeKba = new Customer_Setup();
		$content = $challengeKba->send_otp_token( $email, 'KBA',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key'));
		$response = json_decode($content, true);
		if(json_last_error() == JSON_ERROR_NONE) { /* Generate Qr code */
			if($response['status'] == 'SUCCESS'){
				$_SESSION[ 'mo2f-login-transactionId' ] = $response['txId'];
				$questions = array();
				$questions[0] = $response['questions'][0]['question'];
				$questions[1] = $response['questions'][1]['question'];
				$_SESSION[ 'mo_2_factor_kba_questions' ] = $questions;
				
				$mo2fa_login_message = __('Please answer the following questions:','miniorange-2-factor-authentication');
				$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_KBA_AUTHENTICATION';
				$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
			}else if($response['status'] == 'ERROR'){
				$this->remove_current_activity();
				$error = new WP_Error();
				$error->add('empty_username', '<strong>' .__('ERROR','miniorange-2-factor-authentication').'</strong>: '. __('An error occured while processing your request. Please Try again.', 'miniorange-2-factor-authentication'));
				return $error;
			}
		}else{
			$this->remove_current_activity();
			$error = new WP_Error();
			$error->add('empty_username', '<strong>' . __('ERROR','miniorange-2-factor-authentication').'</strong>: '. __('An error occured while processing your request. Please Try again.', 'miniorange-2-factor-authentication'));
			return $error;
		}
	}
	
	function mo2f_pass2login_mobile_verification($user, $redirect_to){
		global $dbQueries;
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		if(MO2f_Utility::check_if_request_is_from_mobile_device($useragent)){
			unset($_SESSION[ 'mo2f-login-qrCode' ]);
			unset($_SESSION[ 'mo2f-login-transactionId' ]);
			
			$mo2fa_login_message = __('Please enter the one time passcode shown in the','miniorange-2-factor-authentication'). '<b>miniOrange Authenticator</b> app.';
			$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_SOFT_TOKEN';
			$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
		}else{
			 $email = $dbQueries->get_user_detail('mo2f_user_email',$user->ID);
			$challengeMobile = new Customer_Setup();
			$content = $challengeMobile->send_otp_token($email, 'MOBILE AUTHENTICATION',get_site_option('mo2f_customerKey'),get_site_option('mo2f_api_key'));
			$response = json_decode($content, true);
			if(json_last_error() == JSON_ERROR_NONE) { /* Generate Qr code */
				if($response['status'] == 'SUCCESS'){
					
					$_SESSION[ 'mo2f-login-qrCode' ] = $response['qrCode'];
					$_SESSION[ 'mo2f-login-transactionId' ] = $response['txId'];
					
					$mo2fa_login_message = '';
					$mo2fa_login_status = 'MO_2_FACTOR_CHALLENGE_MOBILE_AUTHENTICATION';
					$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message, $redirect_to);
					
				}else if($response['status'] == 'ERROR'){
					$this->remove_current_activity();
					$error = new WP_Error();
					$error->add('empty_username', '<strong>' . __('ERROR','miniorange-2-factor-authentication').'</strong>: '. __('An error occured while processing your request. Please Try again.', 'miniorange-2-factor-authentication'));
					return $error;
				}
			}else{
				$this->remove_current_activity();
				$error = new WP_Error();
				$error->add('empty_username', '<strong>' . __('ERROR','miniorange-2-factor-authentication').'</strong>: '. __('An error occured while processing your request. Please Try again.', 'miniorange-2-factor-authentication'));
				return $error;
			}
		}
		
	}
	
	function mo_2_factor_pass2login_show_wp_login_form(){
	?>
		<p><input type="hidden" name="miniorange_login_nonce" value="<?php echo wp_create_nonce('miniorange-2-factor-login-nonce'); ?>" />
		   <a href="http://miniorange.com/cloud-identity-broker-service" style="display:none;"></a>
		   <a href="http://miniorange.com/strong_auth" style="display:none;"></a>
		   <a href="http://miniorange.com/single-sign-on-sso" style="display:none;"></a>
		   <a href="http://miniorange.com/fraud" style="display:none;"></a>
		</p>
		
		<?php 
			if(get_site_option('mo2f_remember_device') && get_site_option('mo2f_login_policy')){ // if remember device is enabled
		?>
				<p><input type="hidden" id="miniorange_rba_attribures" name="miniorange_rba_attribures" value="" /></p>
		<?php
				wp_enqueue_script( 'jquery_script', plugins_url('includes/js/rba/js/jquery-1.9.1.js', __FILE__ ));
				wp_enqueue_script( 'flash_script', plugins_url('includes/js/rba/js/jquery.flash.js', __FILE__ ));
				wp_enqueue_script( 'uaparser_script', plugins_url('includes/js/rba/js/ua-parser.js', __FILE__ ));
				wp_enqueue_script( 'client_script', plugins_url('includes/js/rba/js/client.js', __FILE__ ));
				wp_enqueue_script( 'device_script', plugins_url('includes/js/rba/js/device_attributes.js', __FILE__ ));
				wp_enqueue_script( 'swf_script', plugins_url('includes/js/rba/js/swfobject.js', __FILE__ ));
				wp_enqueue_script( 'font_script', plugins_url('includes/js/rba/js/fontdetect.js', __FILE__ ));
				wp_enqueue_script( 'murmur_script', plugins_url('includes/js/rba/js/murmurhash3.js', __FILE__ ));
				wp_enqueue_script( 'miniorange_script', plugins_url('includes/js/rba/js/miniorange-fp.js', __FILE__ ));
			}
	}
	
	function mo2f_register_user_inline($email){
		$mo2fa_login_message = '';
		$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
		$enduser = new Two_Factor_Setup();
		$check_user = json_decode($enduser->mo_check_user_already_exist($email),true);
		$current_user = unserialize( $_SESSION[ 'mo2f_current_user' ] );
		$currentUserId = $current_user->ID;
		global $dbQueries;
		if(json_last_error() == JSON_ERROR_NONE){
			if($check_user['status'] == 'ERROR'){
				
				$mo2fa_login_message = Mo2fConstants::langTranslate($check_user['message']); 
				$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
			}else{
				if(strcasecmp($check_user['status'], 'USER_FOUND') == 0){
					// delete_user_meta($currentUserId,'mo_2factor_user_email');
					// $dbQueries->update_user_details( $currentUserId, array( "mo_2factor_user_email" => '' ) );
					// update_user_meta($currentUserId,'mo_2factor_user_registration_with_miniorange','SUCCESS');
					// update_user_meta($currentUserId,'mo_2factor_map_id_with_email',$email);
					// update_user_meta($currentUserId,'mo_2factor_user_registration_status','MO_2_FACTOR_INITIALIZE_TWO_FACTOR');
					
					$dbQueries->update_user_details( $currentUserId, array(
								'mo_2factor_user_registration_with_miniorange' =>'SUCCESS',
								'mo_2factor_user_registration_status' =>'MO_2_FACTOR_INITIALIZE_TWO_FACTOR',
							'mo2f_user_email' =>$email,
								"mo_2factor_user_email" => ''
											) );
					
					$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
					
				}else if(strcasecmp($check_user['status'], 'USER_NOT_FOUND') == 0){
					$content = json_decode($enduser->mo_create_user($current_user,$email), true);
						if(json_last_error() == JSON_ERROR_NONE) {
							if($content['status'] == 'ERROR'){
								$mo2fa_login_message = $content['message'];
								/* if($content['message'] == "Your user creation limit has been completed. Please upgrade your license to add more users."){
							            update_site_option( 'mo2f_inline_registration', 0);
						                }; */
								$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
							}else{
								if(strcasecmp($content['status'], 'SUCCESS') == 0) {
									// delete_user_meta($currentUserId,'mo_2factor_user_email');
									$dbQueries->update_user_details( $currentUserId, array(
									'mo_2factor_user_registration_with_miniorange' =>'SUCCESS',
									'mo_2factor_user_registration_status' =>'MO_2_FACTOR_INITIALIZE_TWO_FACTOR',
									'mo2f_user_email' =>$email,
									'mo_2factor_user_email' =>''
									
												) );
												
									// update_user_meta($currentUserId,'mo_2factor_user_registration_with_miniorange','SUCCESS');
									// update_user_meta($currentUserId,'mo_2factor_map_id_with_email',$email);
									// update_user_meta($currentUserId,'mo_2factor_user_registration_status','MO_2_FACTOR_INITIALIZE_TWO_FACTOR');
									$email = $dbQueries->get_user_detail('mo2f_user_email',$currentUserId);
									$enduser->mo2f_update_userinfo($email, 'OUT OF BAND EMAIL',null,null,null);
									$message = '';
									$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_USER_FOR_2FA_METHODS';
									
								}else{
									$mo2fa_login_message = __('Error occurred while registering the user. Please try again.','miniorange-2-factor-authentication');
									$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
								}
							}
						}else{
								$mo2fa_login_message = __('Error occurred while registering the user. Please try again or contact your admin.','miniorange-2-factor-authentication');
								$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
						}
				}else{
					$mo2fa_login_message = __('Error occurred while registering the user. Please try again.','miniorange-2-factor-authentication');
					$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
				}
			}
		}else{
			$mo2fa_login_message = __('Error occurred while registering the user. Please try again.','miniorange-2-factor-authentication');
			$mo2fa_login_status = 'MO_2_FACTOR_PROMPT_FOR_USER_REG_OTP';
		}
		delete_user_meta($currentUserId,'mo_2fa_verify_otp_create_account');
		$this->miniorange_pass2login_form_fields($mo2fa_login_status, $mo2fa_login_message);
	}
	
	function mo2f_inline_get_qr_code_for_mobile($email,$id){
		$registerMobile = new Two_Factor_Setup();
		$content = $registerMobile->register_mobile($email);
		$response = json_decode($content, true);
		$message = '';
		if(json_last_error() == JSON_ERROR_NONE) {
			if($response['status'] == 'ERROR'){
				$message =  Mo2fConstants::langTranslate($response['message']);
				unset($_SESSION[ 'mo2f-login-qrCode' ]);
				unset($_SESSION[ 'mo2f-login-transactionId' ]);
				unset($_SESSION[ 'mo2f_show_qr_code']);
			}else{
				if($response['status'] == 'IN_PROGRESS'){
					
					$_SESSION[ 'mo2f-login-qrCode' ] = $response['qrCode'];
					$_SESSION[ 'mo2f-login-transactionId' ] = $response['txId'];
					$_SESSION[ 'mo2f_show_qr_code'] = 'MO_2_FACTOR_SHOW_QR_CODE';
				}else{
					$message =  __('An error occured while processing your request. Please Try again.','miniorange-2-factor-authentication');
					unset($_SESSION[ 'mo2f-login-qrCode' ]);
					unset($_SESSION[ 'mo2f-login-transactionId' ]);
					unset($_SESSION[ 'mo2f_show_qr_code']);
				}
			}
		}
		return $message;
	}
	
	function mo_2_factor_show_login_with_password_when_phonelogin_enabled(){
		wp_register_style( 'show-login', plugins_url( 'includes/css/show-login.css?version=4.4.1', __FILE__ ) );
		wp_enqueue_style( 'show-login' );
	}
	
	function mo_2_factor_show_wp_login_form_when_phonelogin_enabled(){
	?>
		<script>
			var content = '<a href="javascript:void(0)" id="backto_mo" onClick="mo2fa_backtomologin()" style="float:right">← __('Back','miniorange-2-factor-authentication')</a>';
			jQuery('#login').append(content);
			function mo2fa_backtomologin(){
				jQuery('#mo2f_backto_mo_loginform').submit();
			}
		</script>
	<?php
	}
}
?>