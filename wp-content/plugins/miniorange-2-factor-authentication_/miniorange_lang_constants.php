<?php

if (!defined('ABSPATH'))
    exit;

class Mo2fConstants
{
    
    static function langTranslate($text)
    {
        
        switch ($text) {
            case 'Successfully validated.':
                return __('Successfully validated.', 'miniorange-2-factor-authentication');
                break;
            case 'QR_SCAN':
                return __('Please scan the QR Code now.', 'miniorange-2-factor-authentication');
                break;
            case 'QR Code Authentication':
                return __('QR Code Authentication', 'miniorange-2-factor-authentication');
                break;
            case 'Email Verification':
                return __('Email Verification', 'miniorange-2-factor-authentication');
                break;
            case 'OTP Over SMS':
                return __('OTP Over SMS', 'miniorange-2-factor-authentication');
                break;
            case 'OTP Over SMS And Email':
                return __('OTP Over SMS And Email', 'miniorange-2-factor-authentication');
                break;
            case 'Your license has expired. Please renew your license to continue using our service.':
                return __('Your license has expired. Please renew your license to continue using our service.', 'miniorange-2-factor-authentication');
                break;
            case 'The total transaction limit has been exceeded. Please upgrade your premium plan.':
                return __('The total transaction limit has been exceeded. Please upgrade your premium plan.', 'miniorange-2-factor-authentication');
                break;
            case 'The transaction limit has exceeded.':
                return __('The transaction limit has exceeded.', 'miniorange-2-factor-authentication');
                break;
            case 'GenerateOtpRequest is null':
                return __('GenerateOtpRequest is null', 'miniorange-2-factor-authentication');
                break;
            case 'The sms transaction limit has been exceeded. Please upgrade to premium plan.':
                return __('The sms transaction limit has been exceeded. Please upgrade to premium plan.', 'miniorange-2-factor-authentication');
                break;
            case 'The email transaction limit has been exceeded. Please upgrade to premium plan.':
                return __('The email transaction limit has been exceeded. Please upgrade to premium plan.', 'miniorange-2-factor-authentication');
                break;
            case 'Transaction limit exceeded. Please contact your administrator':
                return __('Transaction limit exceeded. Please contact your administrator', 'miniorange-2-factor-authentication');
                break;
            case 'Free Trial has already been taken or expired for this plugin. Please upgrade to a premium plan.':
                return __('Free Trial has already been taken or expired for this plugin. Please upgrade to a premium plan.', 'miniorange-2-factor-authentication');
                break;
            case 'Invalid format.':
                return __('Invalid format.', 'miniorange-2-factor-authentication');
                break;
            case 'Mobile registration failed.':
                return __('Mobile registration failed.', 'miniorange-2-factor-authentication');
                break;
            case 'Invalid mobile authentication request.':
                return __('Invalid mobile authentication request.', 'miniorange-2-factor-authentication');
                break;
            case 'Exception during SMS sending':
                return __('Exception during SMS sending', 'miniorange-2-factor-authentication');
                break;
            case 'There was an error during sending an SMS.':
                return __('There was an error during sending an SMS.', 'miniorange-2-factor-authentication');
                break;
            case 'Exception during logUserTransaction':
                return __('Exception during logUserTransaction', 'miniorange-2-factor-authentication');
                break;
            case 'There was an error processing the challenge user request.':
                return __('There was an error processing the challenge user request.', 'miniorange-2-factor-authentication');
                break;
            case 'What is your first company name?':
                return __('What is your first company name?', 'miniorange-2-factor-authentication');
                break;
            case 'What was your childhood nickname?':
                return __('What was your childhood nickname?', 'miniorange-2-factor-authentication');
                break;
            case 'In what city did you meet your spouse/significant other?':
                return __('In what city did you meet your spouse/significant other?', 'miniorange-2-factor-authentication');
                break;
            case 'What is the name of your favorite childhood friend?':
                return __('What is the name of your favorite childhood friend?', 'miniorange-2-factor-authentication');
                break;
            case "What was your first vehicle's registration number?":
                return __("What was your first vehicle's registration number?", 'miniorange-2-factor-authentication');
                break;
            case "What is your grandmother's maiden name?":
                return __("What is your grandmother's maiden name?", 'miniorange-2-factor-authentication');
                break;
            case 'Who is your favourite sports player?':
                return __('Who is your favourite sports player?', 'miniorange-2-factor-authentication');
                break;
            case 'What is your favourite sport?':
                return __('What is your favourite sport?', 'miniorange-2-factor-authentication');
                break;
            case 'In what city or town was your first job?':
                return __('In what city or town was your first job?', 'miniorange-2-factor-authentication');
                break;
            case 'What school did you attend for sixth grade?':
                return __('What school did you attend for sixth grade?', 'miniorange-2-factor-authentication');
                break;
            
            case 'Google Authenticator':
                return __('Google Authenticator', 'miniorange-2-factor-authentication');
                break;
            case 'GOOGLE AUTHENTICATOR':
                return __('GOOGLE AUTHENTICATOR', 'miniorange-2-factor-authentication');
                break;
            case 'G_AUTH':
                return __('Google Authenticator', 'miniorange-2-factor-authentication');
                break;
            case 'Authy 2-Factor Authentication':
                return __('Authy 2-Factor Authentication', 'miniorange-2-factor-authentication');
                break;
            case 'AUTHY_2FA':
                return __('Authy 2-Factor Authentication', 'miniorange-2-factor-authentication');
                break;
            case 'An unknown error occurred while creating the end user.':
                return __('An unknown error occurred while creating the end user.', 'miniorange-2-factor-authentication');
                break;
            case 'An unknown error occurred while challenging the user':
                return __('An unknown error occurred while challenging the user.', 'miniorange-2-factor-authentication');
                break;
            case 'An unknown error occurred while generating QR Code for registering mobile.':
                return __('An unknown error occurred while generating QR Code for registering mobile.', 'miniorange-2-factor-authentication');
                break;
            case 'The sms transaction limit has been exceeded. Please upgrade to premium plan.':
                return __('The sms transaction limit has been exceeded. Please upgrade to premium plan.', 'miniorange-2-factor-authentication');
                break;
            case 'An unknown error occurred while validating the user\'s identity.':
                return __('An unknown error occurred while validating the user\'s identity.', 'miniorange-2-factor-authentication');
                break;
            case 'Customer not found.':
                return __('Customer not found.', 'miniorange-2-factor-authentication');
                break;
            case 'The customer is not valid ':
                return __('The customer is not valid', 'miniorange-2-factor-authentication');
                break;
            case 'The user is not valid ':
                return __('The user is not valid ', 'miniorange-2-factor-authentication');
                break;
            case 'Customer already exists.':
                return __('Customer already exists.', 'miniorange-2-factor-authentication');
                break;
            case 'Customer Name is null':
                return __('Customer Name is null', 'miniorange-2-factor-authentication');
                break;
            case 'Customer check request failed.':
                return __('Customer check request failed.', 'miniorange-2-factor-authentication');
                break;
            case 'Invalid username or password. Please try again.':
                return __('Invalid username or password. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'You are not authorized to perform this operation.':
                return __('You are not authorized to perform this operation.', 'miniorange-2-factor-authentication');
                break;
            case 'Invalid request. No such challenge request was initiated.':
                return __('Invalid request. No such challenge request was initiated.', 'miniorange-2-factor-authentication');
                break;
            case 'No OTP Token for the given request was found.':
                return __('No OTP Token for the given request was found.', 'miniorange-2-factor-authentication');
                break;
            case 'Query submitted.':
                return __('Query submitted.', 'miniorange-2-factor-authentication');
                break;
            case 'Invalid parameters.':
                return __('Invalid parameters.', 'miniorange-2-factor-authentication');
                break;
            case 'Alternate email cannot be same as primary email.':
                return __('Alternate email cannot be same as primary email.', 'miniorange-2-factor-authentication');
                break;
            case 'CustomerId is null.':
                return __('CustomerId is null.', 'miniorange-2-factor-authentication');
                break;
            case 'You are not authorized to create users. Please upgrade to premium plan. ':
                return __('You are not authorized to create users. Please upgrade to premium plan. ', 'miniorange-2-factor-authentication');
                break;
            case 'Your user creation limit has been completed. Please upgrade your license to add more users.':
                return __('Your user creation limit has been completed. Please upgrade your license to add more users.', 'miniorange-2-factor-authentication');
                break;
            case 'Username cannot be blank.':
                return __('Username cannot be blank.', 'miniorange-2-factor-authentication');
                break;
            case 'End user created successfully.':
                return __('End user created successfully.', 'miniorange-2-factor-authentication');
                break;
            case 'There was an exception processing the update user request.':
                return __('There was an exception processing the update user request.', 'miniorange-2-factor-authentication');
                break;
            case 'End user found.':
                return __('End user found.', 'miniorange-2-factor-authentication');
                break;
            case 'End user found under different customer. ':
                return __('End user found under different customer. ', 'miniorange-2-factor-authentication');
                break;
            case 'End user not found.':
                return __('End user not found.', 'miniorange-2-factor-authentication');
                break;
            case 'Customer successfully registered.':
                return __('Customer successfully registered.', 'miniorange-2-factor-authentication');
                break;
            case 'Customer registration failed.':
                return __('Customer registration failed.', 'miniorange-2-factor-authentication');
                break;
            case 'There was an error processing the register mobile request.':
                return __('There was an error processing the register mobile request.', 'miniorange-2-factor-authentication');
                break;
            case 'There was an exception processing the get user request.':
                return __('There was an exception processing the get user request.', 'miniorange-2-factor-authentication');
                break;
            case 'End User retrieved successfully.':
                return __('End User retrieved successfully.', 'miniorange-2-factor-authentication');
                break;
            case 'COMPLETED_TEST':
                Return __('You have successfully completed the test. Now', 'miniorange-2-factor-authentication');
                break;
            case 'INVALID_REG_FIELDS':
                Return __('All the fields are required. Please enter valid entries.', 'miniorange-2-factor-authentication');
                break;
            case 'INVALID_PASS':
                Return __('You already have an account with miniOrange. Please enter a valid password.', 'miniorange-2-factor-authentication');
                break;
            case 'INVALID_REQ':
                Return __('Invalid request. Please try again', 'miniorange-2-factor-authentication');
                break;
            case 'INVALID_REG_MO':
                Return __('Invalid request. Please register with miniOrange to configure 2 Factor plugin.', 'miniorange-2-factor-authentication');
                break;
            case 'INVALID_OTP':
                Return __('Invalid OTP. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'INVALID_EMAIL_PASS':
                Return __('Invalid email or password. Please try again.', 'miniorange-2-factor-authentication');
                break;
            
            case 'CONFIRM_MISMATCH':
                Return __('Password and Confirm password do not match.', 'miniorange-2-factor-authentication');
                break;
            case 'ENTER_EMAIL_PASS':
                Return __('Please enter your registered email and password.', 'miniorange-2-factor-authentication');
                break;
            case 'OTP_SENT':
                Return __('One Time Passcode has been sent for verification to ', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_OTP_EMAIL':
                Return __('There was an error in sending OTP over email. Please click on Resend OTP to try again.', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_REG':
                Return __('Error occured while registration. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_ON_PROCESS':
                Return __('An error occured while processing your request. Please Try again.', 'miniorange-2-factor-authentication');
                break;
            
            case 'ERROR_SMS':
                Return __('There was an error in sending sms. Please click on Resend OTP to try again.', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_REG_USER':
                Return __('Error occurred while registering the user. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'FACTOR_2ND':
                Return __('is set as your 2nd factor method.', 'miniorange-2-factor-authentication');
                break;
            case 'ERR_SAV_KBA':
                Return __('Error occured while saving your kba details. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'ANS_SEC_QUES':
                Return __('Please answer the following security questions.', 'miniorange-2-factor-authentication');
                break;
            case 'PHONE VERIFICATION':
                Return __('PHONE VERIFICATION.', 'miniorange-2-factor-authentication');
                break;
            case 'ERR_FET_QUES':
                Return __('There was an error fetching security questions. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'INVALID_ANS':
                Return __('Invalid Answers. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'MIN_PASS_LEN':
                Return __('Choose a password with minimum length 8.', 'miniorange-2-factor-authentication');
                break;
            
            case 'REV_SUCCESS':
                Return __('Your account has been retrieved successfully.', 'miniorange-2-factor-authentication');
                break;
            case 'EMAIL_VERFI':
                Return __('Email Verification', 'miniorange-2-factor-authentication');
                break;
            case 'DEF_2FACT':
                Return __('has been set as your default 2nd factor method.', 'miniorange-2-factor-authentication');
                break;
            case 'NEW_OTP':
                Return __('Another One Time Passcode has been sent', 'miniorange-2-factor-authentication');
                break;
            case 'VERIFY':
                Return __('for verification to', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_EMAIL':
                Return __('There was an error in sending email. Please click on Resend OTP to try again.', 'miniorange-2-factor-authentication');
                break;
            case 'EMAIL_IN_USE':
                Return __('The email is already used by other user. Please register with other email.', 'miniorange-2-factor-authentication');
                break;
            case 'QUERY_EMAIL':
                Return __('Please submit your query with email', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_SUPPORT_FORM':
                Return __('Your query could not be submitted. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'SENT_SUPPORT_FORM':
                Return __('Thanks for getting in touch! We shall get back to you shortly.', 'miniorange-2-factor-authentication');
                break;
            case 'SAV_SETTING':
                Return __('Your settings are saved successfully.', 'miniorange-2-factor-authentication');
                break;
            case 'SEL_QUES':
                Return __('Please select atleast 10 questions.', 'miniorange-2-factor-authentication');
                break;
            case 'LOG_SET':
                Return __('Your login settings are saved successfully. Now', 'miniorange-2-factor-authentication');
                break;
            case 'LOGIN_AGAIN':
                Return __('to logout and try login with 2-Factor.', 'miniorange-2-factor-authentication');
                break;
            case 'TO_LOGIN_SET':
                Return __('to go to Login Settings.', 'miniorange-2-factor-authentication');
                break;
            case 'FAIL_AUTH':
                Return __('Authentication failed. Please try again to test the configuration.', 'miniorange-2-factor-authentication');
                break;
            case 'REG_BEFORE_CONFIG':
                Return __('Invalid request. Please register with miniOrange before configuring your mobile.', 'miniorange-2-factor-authentication');
                break;
            case 'EMAIL_REG':
                Return __('Please enter email-id to register.', 'miniorange-2-factor-authentication');
                break;
            case 'VAL_TEST_AUTH':
                Return __('Please enter a value to test your authentication.', 'miniorange-2-factor-authentication');
                break;
            case 'ENTER_OTP':
                Return __('Please enter the one time passcode below.', 'miniorange-2-factor-authentication');
                break;
            case 'RECEIVE_CALL':
                Return __('You will receive a phone call on this number', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_OTP':
                Return __('There was an error in sending one time passcode. Please click on Resend OTP to try again.', 'miniorange-2-factor-authentication');
                break;
            
            case 'PUSH_NOT_SENT':
                Return __('A Push notification has been sent to your miniOrange Authenticator App.', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_VALID_OTP':
                Return __('Error occurred while validating the OTP. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'TEST_GAUTH_METHOD':
                Return __('to test Google Authenticator method.', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_OTP_CAUSES':
                Return __('Error occurred while validating the OTP. Please try again. Possible causes:', 'miniorange-2-factor-authentication');
                break;
            case 'APP_TIME_SYNC':
                Return __('Your App Time is not in sync.Go to settings and tap on tap on Sync Time now .', 'miniorange-2-factor-authentication');
                break;
            case 'ERR_VALID_USER':
                Return __('Error occurred while validating the user. Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'ONLY_DIGITS':
                Return __('Only digits are allowed. Please enter again.', 'miniorange-2-factor-authentication');
                break;
            case 'TEST_AUTHY_2FA':
                Return __('to test Authy 2-Factor Authentication method.', 'miniorange-2-factor-authentication');
                break;
            case 'UNIQ_QUES':
                Return __('The questions you select must be unique.', 'miniorange-2-factor-authentication');
                break;
            case 'METHOD':
                Return __('method.', 'miniorange-2-factor-authentication');
                break;
            case 'TO_TEST':
                Return __('to test', 'miniorange-2-factor-authentication');
                break;
            case 'SET_2FA':
                Return __('is set as your Two-Factor method.', 'miniorange-2-factor-authentication');
                break;
            case 'VERIFY_EMAIL_SENT':
                Return __('A verification email is sent to', 'miniorange-2-factor-authentication');
                break;
            case 'VERIFY_EMAIL':
                Return __('Please click on accept link to verify your email.', 'miniorange-2-factor-authentication');
                break;
            case 'ACC_CREATED':
                Return __('Your account has been created successfully.', 'miniorange-2-factor-authentication');
                break;
            case 'ACC_REMOVED':
                Return __('Your account has been removed.Please contact your administrator.', 'miniorange-2-factor-authentication');
                break;
            
            case 'REG_SUCCESS':
                Return __('You are registered successfully.', 'miniorange-2-factor-authentication');
                break;
            case 'REQ_DENIED':
                Return __('You have denied the request.', 'miniorange-2-factor-authentication');
                break;
            case 'DISABLED_2FA':
                Return __('Two-Factor plugin has been disabled.', 'miniorange-2-factor-authentication');
                break;
            case 'ERROR_SAV_SETTING':
                Return __('Error occurred while saving the settings.Please try again.', 'miniorange-2-factor-authentication');
                break;
            case 'INVALID_REG_CONFIG_MO':
                Return __('Invalid request. Please register with miniOrange and configure 2-Factor to save your login settings.', 'miniorange-2-factor-authentication');
                break;
            case 'RESET_PASS':
                Return __('Please enter your registered email below to reset your password.', 'miniorange-2-factor-authentication');
                break;
            case 'RESET_NEW_PASS':
                Return __('You password has been reset successfully.  A new password has been sent to your registered mail.', 'miniorange-2-factor-authentication');
                break;
            case 'INCORRECT_EMAIL_NO_RESET':
                Return __('Your password could not be reset. Please enter your correct email in the textbox below and then click on the link.', 'miniorange-2-factor-authentication');
                break;
            case 'CONFIG_2FA':
                Return __('to configure another 2nd factor authentication method.', 'miniorange-2-factor-authentication');
                break;
            case 'CLICK_HERE':
                Return __('Click Here', 'miniorange-2-factor-authentication');
                break;
            case 'OUT OF BAND EMAIL':
                Return __('OUT OF BAND EMAIL', 'miniorange-2-factor-authentication');
                break;
            case 'KBA':
                Return __('KBA', 'miniorange-2-factor-authentication');;
                break;
            case 'SMS AND EMAIL':
                Return __('SMS AND EMAIL', 'miniorange-2-factor-authentication');;
                break;
            case 'SOFT TOKEN':
                Return __('SOFT TOKEN', 'miniorange-2-factor-authentication');;
                break;
            case 'PUSH NOTIFICATIONS':
                Return __('PUSH NOTIFICATIONS', 'miniorange-2-factor-authentication');;
                break;
            case 'SMS':
                Return __('SMS', 'miniorange-2-factor-authentication');;
                break;
            case 'MOBILE AUTHENTICATION':
                Return __('MOBILE AUTHENTICATION', 'miniorange-2-factor-authentication');;
                break;
            Default:
                return $text;
        }
    }
}
new Mo2fConstants;
?>
