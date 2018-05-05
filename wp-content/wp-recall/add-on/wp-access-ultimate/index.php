<?php

include_once 'classes/class-wau-query.php';
include_once 'classes/class-wau-accounts-walker.php';
include_once 'classes/class-wau-user.php';
include_once 'classes/class-wau-post.php';
include_once 'classes/class-wau-term.php';
include_once 'classes/class-wau-post-terms.php';

include_once 'functions/database.php';
include_once 'functions/post-content.php';
include_once 'functions/ajax.php';
include_once 'functions/shortcodes.php';
include_once 'functions/user-tabs.php';
include_once 'functions/switch-roles.php';
include_once 'functions/other.php';

if (is_admin()):
    include_once 'admin/index.php';
endif;

if (!is_admin()):
    add_action('rcl_enqueue_scripts', 'wau_scripts');
endif;

function wau_scripts(){
    rcl_enqueue_style( 'wau-style', rcl_addon_url('assets/css/style.css', __FILE__) );
    rcl_enqueue_style( 'wau-prices', rcl_addon_url('assets/css/price-plans.css', __FILE__) );
    rcl_enqueue_script( 'wau-script', rcl_addon_url('assets/js/scripts.js', __FILE__) );
}

add_action('rcl_activate_wp-access-ultimate', 'wau_activate_actions');
function wau_activate_actions(){
    
    if(!get_option('wau_options')){
        update_option('wau_options', array(
            'access-text-archive' => '<span style="color:red;font-weight:bold;">Данный контент имеет ограниченный доступ</span>',
            'access-text-single' => '<span style="color:red;font-weight:bold;">Данный контент имеет ограниченный доступ</span>',
            'mail-text-remind' => __('Уважаемый, {userName}!<br />'
            . 'Сообщаем Вам, о том, что время действия вашего аккаунта {accountName}, '
            . 'подходит к концу.<br />До момента окончания действия этого аккаунта '
            . 'осталось {timeEnd}')
        ));
    }
 
}

add_action('init', 'wau_init_user');
function wau_init_user(){
    global $WAU_User, $user_ID;
    
    $WAU_User = new WAU_User(array(
        'user_id' => $user_ID
    ));
    
    do_action('wau_init_user');

}

add_action('wp', 'wau_init_post');
function wau_init_post(){
    global $WAU_Post, $post;
    
    if(is_singular()){
        
        if(!wau_check_post_type(get_post_type($post))) return false;
        
        $WAU_Post = new WAU_Post(array(
            'post_id' => $post->ID
        ));
        
    }
    
}

add_action('user_register', 'wau_app_user_access_by_register');
function wau_app_user_access_by_register($user_id){
    
    $account_id = wau_get_option('reg-account');
    
    if(!$account_id) return false;
    
    wau_update_user_access($user_id, $account_id, wau_setup_time(wau_get_option('reg-time')));
    
}

add_action('wau_init_user', 'wau_check_current_user_access');
function wau_check_current_user_access(){
    global $WAU_User;
    
    if(!$WAU_User->access) return false;
    
    foreach($WAU_User->access as $access){
        $time = $access->access_time - (strtotime(current_time('mysql')) - strtotime($access->access_date));
        if($time <= 0){
            wau_delete_access($access->access_id);
        }
    }

}

add_action('rcl_cron_daily', 'wau_daily_cron_actions');
function wau_daily_cron_actions(){
    
    $access = wau_get_access_by_args(array(
        'number' => -1
    ));
    
    if(!$access) return false;
    
    $mailText = wau_get_option('mail-text-remind');
    
    foreach($access as $acc){
        
        $time = $acc->access_time - (strtotime(current_time('mysql')) - strtotime($acc->access_date));
        
        if($time < 0){
            
            wau_delete_access($acc->access_id);
            
        }else if($time < 86400){
            
            $timeData = wau_parse_time($time);
            
            $mailText = str_replace(array(
                '{accountName}', 
                '{userName}', 
                '{timeEnd}'
            ), array(
                wau_get_account_field($acc->account_id, 'account_name'),
                get_the_author_meta('display_name', $acc->user_id),
                $timeData['hour'].' час. '.$timeData['minute'].' минут'
            ), $mailText);

            rcl_mail(get_the_author_meta('email', $acc->user_id), __('Время вашего доступа истекает!') ,$mailText);
            
        }
    }

}

add_action('rcl_success_pay', 'wau_payment', 10);
function wau_payment($payData){

    if($payData->pay_type != 'wau-payment') return false;
    
    $baggage = $payData->baggage_data;
    
    $tariff = wau_get_tariff($baggage->tariff_id);
    
    $tariff_price = wau_get_tariff_price($baggage->tariff_id, $payData->user_id);
    
    if($tariff_price != $payData->pay_summ) return false;
    
    $payment_id = wau_add_payment(array(
        'user_id' => $payData->user_id,
        'account_name' => wau_get_account_field($tariff->account_id, 'account_name'),
        'tariff_price' => $tariff_price,
        'access_time' => $tariff->access_time
    ));
    
    $access_id = wau_update_user_access($payData->user_id, $tariff->account_id, $tariff->access_time);

    do_action('wau_payment_access', $payment_id, $access_id);
    
}

add_action('wau_payment_access','wau_send_email_about_payment_access', 10);
function wau_send_email_about_payment_access($payment_id){
            
    $payment = wau_get_payment($payment_id);
    
    $subject = __('Приобретен доступ!');

    //Отправляем письмо об оплате админу
    $textmail = '
    <p>Пользователь оплатил аккаунт платного доступа на сайте "'.get_bloginfo('name').'".</p>
    <h3>Информация о покупателе:</h3>
    <p><b>Имя</b>: '.get_the_author_meta('display_name',$payment->user_id).'</p>
    <p><b>Email</b>: '.get_the_author_meta('user_email',$payment->user_id).'</p>
    <p>Приобретенный аккаунт: '.$payment->account_name.'.</p>
    <p>Время по тарифу: '.wau_time_to_strdate($payment->access_time).'</p>';

    rcl_mail(get_option('admin_email'), $subject, $textmail);

    //Отправляем письмо об оплате покупателю
    $textmail = '
    <p>Вы оплатили аккаунт платного доступа на сайте "'.get_bloginfo('name').'".</p>
    <h3>Информация о покупателе:</h3>
    <p><b>Имя</b>: '.get_the_author_meta('display_name',$payment->user_id).'</p>
    <p><b>Email</b>: '.get_the_author_meta('user_email',$payment->user_id).'</p>
    <p>Приобретенный аккаунт: '.$payment->account_name.'.</p>
    <p>Время по тарифу: '.wau_time_to_strdate($payment->access_time).'</p>';
    
    rcl_mail(get_the_author_meta('user_email',$payment->user_id), $subject, $textmail, $headers);

}

/* интеграция с партнерской программой */
add_filter('rcl_partner_actions','wau_add_payment_action');
function wau_add_payment_action($actions){
    $actions['wau-payment'] = __('Оплата платного доступа');
    return $actions;
}

add_action('wau_payment_access','insert_partners_refill_paid',10,2);
function insert_partners_refill_paid($payment_id){
    if(!function_exists('add_partner_incentive')) return false;   
    $payment = wau_get_payment($payment_id);   
    add_partner_incentive($payment->user_id, $payment->tariff_price, 'wau-payment');
}

//добавление своего поля в дефолтные произвольные поля формы публикации
add_filter('rcl_default_public_form_fields','wau_add_access_field',10,2);
function wau_add_access_field($fields, $post_type){
    
    $accounts = wau_get_accounts(array(
        'number' => -1
    ));
    
    if(!$accounts) return $fields;
    
    $options = array(0 => 'Базовый доступ');
    $defaultVals = array(0);
    foreach($accounts as $account){
        $options[$account->account_id] = $account->account_name;
        $defaultVals[] = $account->account_id;
    }

    $fields[] = array(
        'slug' => 'wau-access-switch',
        'title' => __('Платный доступ'),
        'type' => 'custom',
        'wau-options' => $defaultVals,
        'options-field' => array(
            array(
                'type' => 'checkbox',
                'slug' => 'wau-options',
                'title' => __('Доступы к выбору'),
                'values' => $options
            )
        )
    );
    
    return $fields;
    
}

//редактирование данных вывода своего дефолтного поля перед выводом в форме публикации
add_filter('rcl_public_form_fields','wau_add_access_field_in_form',10,2);
function wau_add_access_field_in_form($fields, $form){
    
    if(!$fields) return $fields;
    
    if(is_admin()){
        
        foreach($fields as $k => $field){
        
            if($field['slug'] != 'wau-access-switch') continue;

            unset($fields[$k]);

        }
        
        return $fields;
        
    }
    
    $accounts = wau_get_accounts(array(
        'number' => -1
    ));
    
    if(!$accounts) return $fields;
    
    $access = array(0 => 'Базовый доступ');
    foreach($accounts as $account){
        $access[$account->account_id] = $account->account_name;
    }
    
    foreach($fields as $k => $field){
        
        if($field['slug'] != 'wau-access-switch') continue;
        
        if(!isset($field['wau-options']) || !$field['wau-options']) return $fields;
        
        foreach($field['wau-options'] as $access_id){
            
            if(isset($access[$access_id])){
                $values[$access_id] = $access[$access_id];
            }

        }
        
        $fields[$k]['type'] = 'select';
        $fields[$k]['value_in_key'] = false;
        $fields[$k]['values'] = $values;
        
    }
    
    return $fields;
    
}

add_action('update_post_rcl', 'wau_update_product_access_data', 50, 3);
function wau_update_product_access_data($post_id, $postdata, $update){
    
    if(!isset($_POST['wau-access-switch'])) return false;
    
    if(!$_POST['wau-access-switch']){
        return delete_post_meta($post_id, 'wau-access');
    }
    
    $data = (object)array(
        'access' => array(
            (object)array(
                'account_id' => $_POST['wau-access-switch']
            )
        ),
        'options' => array(
            'important' => 0
        )
    );
    
    update_post_meta($post_id, 'wau-access', $data);
    
}


//add_action('wp','wau_init_replace_wpa_data');
function wau_init_replace_wpa_data(){
    if(isset($_GET['wpa2wau'])) wau_replace_wpa_data2();  
}

function wau_replace_wpa_data2(){
    
    global $wpdb;
    
    /*$payments = $wpdb->get_results("SELECT * FROM ".WP_PREFIX."wppay_pay_history ORDER BY ID ASC");
    
    foreach($payments as $payment){
        $wpdb->insert(WP_PREFIX ."wau_payments", array(
            'user_id' => $payment->user,
            'account_name' => $payment->account,
            'tariff_price' => $payment->count,
            'access_time' => $payment->tariff_time,
            'payment_date' => $payment->time_action
        ));
    }*/
    
    /*$access = $wpdb->get_results("SELECT * FROM ".WP_PREFIX."wppay_active_access ORDER BY ID ASC");
    
    foreach($access as $acs){
        $wpdb->insert(WP_PREFIX ."wau_access", array(
            'user_id' => $acs->user,
            'account_id' => $acs->account,
            'access_time' => $acs->tariff_time,
            'access_date' => $acs->time_start
        ));
    }*/
    
    //wau_daily_cron_actions();
    
}