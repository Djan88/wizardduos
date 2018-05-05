<?php

function wau_check_post_type($post_type){
    
    $postTypes = wau_get_option('post-types');
        
    if(!$postTypes) return false;

    return (in_array($post_type, $postTypes));
    
}

function wau_get_option($name, $default = false){
    
    $WAUOptions = get_option('wau_options');
    
    if(!isset($WAUOptions[$name]) || $WAUOptions[$name] == ''){
        return $default;
    }
    
    return $WAUOptions[$name];
}

function wau_setup_time($data){
    
    $time = 0;
    
    $time += (isset($data['year']) && $data['year'])? $data['year']*31104000: 0;
    $time += (isset($data['month']) && $data['month'])? $data['month']*2592000: 0;
    $time += (isset($data['day']) && $data['day'])? $data['day']*86400: 0;
    $time += (isset($data['hour']) && $data['hour'])? $data['hour']*3600: 0;
    $time += (isset($data['minute']) && $data['minute'])? $data['minute']*60: 0;
    
    return $time;
}

function wau_parse_time($time){
    
    $data = array(
        'year' => 0,
        'month' => 0,
        'day' => 0,
        'hour' => 0,
        'minute' => 0
    );
    
    $data['year'] = floor($time/31104000);
    if($data['year']<0) $data['year'] = 0;
    
    $sec_month = $time - ($data['year']*31104000);
    $data['month'] = floor($sec_month/2592000);
    if($data['month']<0) $data['month'] = 0;
    
    $sec_day = $sec_month - ($data['month']*2592000);
    $data['day'] = floor($sec_day/86400);
    
    $sec_hour = $sec_day - ($data['day']*86400);
    $data['hour'] = floor($sec_hour/3600);
    
    $sec_min = $sec_hour - ($data['hour']*3600);
    $data['minute'] = floor($sec_min/60);

    return $data;
    
}

function wau_time_to_strdate($time){
    
    $timeData = wau_parse_time($time);
    
    return $timeData['year'].' г. '
            . $timeData['month'].' м. '
            . $timeData['day'].' д. '
            . $timeData['hour'].' ч. '
            . $timeData['minute'].' мин. ';
    
}

function wau_get_accounts_box($account_ids, $args = false){
    
    $content = '';
    
    foreach($account_ids as $account_id){
        $content .= wau_get_account_box($account_id, $args);
    }
    
    return $content;
    
}

function wau_get_account_box($account_id, $args = false){
    global $WAU_User;
    
    rcl_dialog_scripts();
    
    $args = wp_parse_args( $args, array(
        'price_table' => 1,
        'account_name' => 1,
        'description' => 1
    ));
    
    $account = wau_get_account($account_id);
    
    if(!$account) return false;

    $content = '<div class="wau-account-box">';
    
    if($args['account_name']){
        $content .= '<div class="wau-account-box-title">'
                . '<span>'.__('Аккаунт доступа').' "'.$account->account_name.'"</span>'
            . '</div>';
    }
    
    if($args['description'] && $account->account_desc){
        $content .= '<div class="wau-account-description">' . $account->account_desc . '</div>';
    }
    
    if($access = $WAU_User->get_access_by_account($account_id)){
        
        $time = $access->access_time - (strtotime(current_time('mysql')) - strtotime($access->access_date));
        
        $content .= '<div class="wau-active-notice">';
        $content .= '<span>'.__('Действующий доступ!').'</span>';
        $content .= '<span>'.__('Осталось').': '.wau_time_to_strdate($time).'</span>';
        $content .= '</div>';
        
    }

    if($args['price_table']){
        
        if(!$access || $account->is_prolong){
        
            $tarriffBox = wau_get_tariffs_box($account_id);

            $content .= $tarriffBox? $tarriffBox: '<p>'.__('Тарифные планы для аккаунта доступа не созданы').'</p>';
            
        }
    }
    
    $content .= '</div>';

    return $content;
    
}

function wau_get_tariffs_box($account_id){
    
    $tariffs = wau_get_tariffs(array(
        'account_id' => $account_id,
        'order' => 'ASC',
        'orderby' => 'tariff_seq'
    ));
    
    if(!$tariffs) return false;
    
    $content = rcl_get_include_template('wau-tariff-list.php',__FILE__, array(
        'tariffs' => $tariffs
    ));
    
    return $content;
    
}

function wau_set_discount_price($tariff_price, $discount){
    
    return ceil($discount * $tariff_price / 100);
    
}

function wau_get_tariff_price($tariff_id, $user_id = false){
    global $WAU_User;
    
    if($user_id){
        $WAU_User = new WAU_User(array(
            'user_id' => $user_id
        ));
    }
    
    $tariff = wau_get_tariff($tariff_id);
    
    if($WAU_User->is_access($tariff->account_id) && $discount = wau_get_meta($tariff->account_id, 'account', 'uptime_discount')){
        $tariff_price = $tariff->tariff_price - wau_set_discount_price($tariff->tariff_price, $discount);
    }else{
        $tariff_price = $tariff->tariff_price;
    }
    
    return $tariff_price;
    
}
