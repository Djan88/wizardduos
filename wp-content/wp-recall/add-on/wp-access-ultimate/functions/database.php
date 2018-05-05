<?php

function wau_get_accounts($args = false){
    $query = new WAU_Accounts();
    return $query->get_results($args);
}

function wau_get_account($account_id){
    $query = new WAU_Accounts();
    return $query->get_row(array(
        'account_id' => $account_id
    ));
}

function wau_get_account_field($account_id, $fieldName){
    
    $cachekey = md5(json_encode(array('wau_get_account_field',$account_id,$fieldName)));
    $cache = wp_cache_get( $cachekey );
    if ( $cache )
        return $cache;
    
    $query = new WAU_Accounts();
    
    $value = $query->get_var(array(
        'account_id' => $account_id,
        'fields' => is_array($fieldName)? $fieldName: array($fieldName)
    ));
    
    wp_cache_add( $cachekey, $value );
    
    return $value;
}

function wau_add_account($args){
    global $wpdb;
    
    if(!isset($args['account_desc']))
        $args['account_desc'] = '';
    
    if(!isset($args['account_type']))
        $args['account_type'] = 'temp';
    
    if(!isset($args['account_status']))
        $args['account_status'] = 1;
    
    if(!isset($args['is_prolong']))
        $args['is_prolong'] = 0;
    
    if(!$wpdb->insert(WP_PREFIX ."wau_accounts", $args)) return false;
    
    $account_id = $wpdb->insert_id;
    
    do_action('wau_add_account', $account_id);
    
    return $account_id;
}

function wau_update_account($account_id, $update){
    global $wpdb;
    
    $result = $wpdb->update(
        WP_PREFIX.'wau_accounts', $update, array(
            'account_id' => $account_id
        )
    );
    
    if($result)
        do_action('wau_update_account', $account_id);
    
    return $result;
    
}

function wau_delete_account($account_id){
    global $wpdb;
    
    do_action('wau_pre_delete_account',$account_id);
    
    $wpdb->update(WP_PREFIX."wau_accounts", array(
        'parent_id' => 0
    ),array(
        'parent_id' => $account_id
    ));
    
    $result = $wpdb->query("DELETE FROM ".WP_PREFIX."wau_accounts WHERE account_id='$account_id'");
    
    if($result){
        
        do_action('wau_delete_account', $account_id);
        
    }
    
    return $result;
}

add_action('wau_delete_account', 'wau_delete_account_tariffs');
function wau_delete_account_tariffs($account_id){
    
    $tariffs = wau_get_tariffs(array(
        'account_id' => $account_id,
        'number' => -1,
        'fields' => array(
            'tariff_id'
        )
    ));
    
    if(!$tariffs) return false;
    
    foreach($tariffs as $tariff){
        wau_delete_tariff($tariff->tariff_id);
    }
    
}

function wau_get_tariffs($args = false){
    $query = new WAU_Tariffs();
    return $query->get_results($args);
}

function wau_get_tariff($tariff_id){
    $query = new WAU_Tariffs();
    return $query->get_row(array(
        'tariff_id' => $tariff_id
    ));
}

function wau_get_tariff_field($tariff_id, $fieldName){
    $query = new WAU_Tariffs();
    return $query->get_var(array(
        'tariff_id' => $tariff_id,
        'fields' => is_array($fieldName)? $fieldName: array($fieldName)
    ));
}

function wau_add_tariff($args){
    global $wpdb;
    
    if(!isset($args['is_best']))
        $args['is_best'] = 0;
    
    if(!isset($args['tariff_seq'])){
        
        $query = new WAU_Tariffs();

        $args['tariff_seq'] = $query->count() + 1;
    
    }
    
    if(!isset($args['tariff_desc']))
        $args['tariff_desc'] = '';
    
    if(!$wpdb->insert(WP_PREFIX ."wau_tariffs", $args)) return false;
    
    $tariff_id = $wpdb->insert_id;
    
    do_action('wau_add_tariff', $tariff_id);
    
    return $tariff_id;
}

function wau_update_tariff($tariff_id, $update){
    global $wpdb;
    
    $result = $wpdb->update(
        WP_PREFIX.'wau_tariffs', $update, array(
            'tariff_id' => $tariff_id
        )
    );
    
    if($result)
        do_action('wau_update_tariff', $tariff_id);
    
    return $result;
    
}

function wau_delete_tariff($tariff_id){
    global $wpdb;
    
    do_action('wau_pre_delete_tariff',$tariff_id);
    
    $result = $wpdb->query("DELETE FROM ".WP_PREFIX."wau_tariffs WHERE tariff_id='$tariff_id'");
    
    if($result){
        
        do_action('wau_delete_tariff', $tariff_id);
        
    }
    
    return $result;
}

function wau_get_access_by_args($args = false){
    $query = new WAU_Access();
    return $query->get_results($args);
}

function wau_count_access($args = false){
    $query = new WAU_Access();
    return $query->count($args);
}

function wau_get_access($access_id){
    $query = new WAU_Access();
    return $query->get_row(array(
        'access_id' => $access_id
    ));
}

function wau_get_user_access_by_account($user_id, $access_id){
    $query = new WAU_Access();
    return $query->get_row(array(
        'user_id' => $user_id,
        'account_id' => $access_id
    ));
}

function wau_get_access_field($access_id, $fieldName){
    $query = new WAU_Access();
    return $query->get_var(array(
        'access_id' => $access_id,
        'fields' => is_array($fieldName)? $fieldName: array($fieldName)
    ));
}

function wau_add_access($args){
    global $wpdb;
    
    if(!isset($args['access_date']))
        $args['access_date'] = current_time('mysql');
    
    if(!$wpdb->insert(WP_PREFIX ."wau_access", $args)) return false;
    
    $access_id = $wpdb->insert_id;
    
    do_action('wau_add_access', $access_id);
    
    return $access_id;
}

function wau_update_access($access_id, $update){
    global $wpdb;
    
    $result = $wpdb->update(
        WP_PREFIX.'wau_access', $update, array(
            'access_id' => $access_id
        )
    );
    
    if($result)
        do_action('wau_update_access', $access_id);
    
    return $result;
    
}

function wau_update_user_access($user_id, $account_id, $access_time, $append = true){

    if($access = wau_get_user_access_by_account($user_id, $account_id)){
        
        if($append && wau_get_account_field($account_id, 'is_prolong')){
            $access_time += $access->access_time - (strtotime(current_time('mysql')) - strtotime($access->access_date));
        }
        
        $result = wau_update_access($access->access_id, array(
            'access_time' => $access_time
        ));
        
        $access_id = $access->access_id;
        
    }else{
        
        $access_id = wau_add_access(array(
            'user_id' => $user_id,
            'account_id' => $account_id,
            'access_time' => $access_time
        ));
        
    }
    
    do_action('wau_update_user_access', $access_id);

    return $access_id;
    
}

function wau_delete_access($access_id){
    global $wpdb;
    
    do_action('wau_pre_delete_access',$access_id);
    
    $result = $wpdb->query("DELETE FROM ".WP_PREFIX."wau_access WHERE access_id='$access_id'");
    
    if($result){
        
        do_action('wau_delete_access', $access_id);
        
    }
    
    return $result;
}

function wau_delete_user_access_by_account($user_id, $account_id){
    
    $access = wau_get_user_access_by_account($user_id, $account_id);
    
    if(!$access) return false;
    
    return wau_delete_access($access->access_id);
    
}

function wau_get_payments($args = false){
    $query = new WAU_Payments();
    return $query->get_results($args);
}

function wau_get_payment($payment_id){
    $query = new WAU_Payments();
    return $query->get_row(array(
        'payment_id' => $payment_id
    ));
}

function wau_count_payments($args = false){
    $query = new WAU_Payments();
    return $query->count($args);
}

function wau_add_payment($args){
    global $wpdb;
    
    if(!isset($args['payment_date']))
        $args['payment_date'] = current_time('mysql');
    
    if(!$wpdb->insert(WP_PREFIX ."wau_payments", $args)) return false;
    
    $payment_id = $wpdb->insert_id;
    
    do_action('wau_add_payment', $payment_id);
    
    return $payment_id;
}

function wau_get_metas($args = false){
    $Meta = new WAU_Meta();
    return $Meta->get_results($args);
}

function wau_get_meta($object_id,$object_type,$meta_key){
    
    $cachekey = md5(json_encode(array('wau_get_meta',$object_id,$object_type,$meta_key)));
    $cache = wp_cache_get( $cachekey );
    if ( $cache )
        return $cache;

    $Meta = new WAU_Meta();

    $value = maybe_unserialize(
        $Meta->get_var(
            array(
                'object_id' => $object_id,
                'object_type' => $object_type,
                'meta_key' => $meta_key,
                'fields' => array('meta_value')
            )
        )      
    );

    wp_cache_add( $cachekey, $value );
    
    return $value;
    
}

function wau_add_meta($object_id,$object_type,$meta_key,$meta_value){
    global $wpdb;
    
    $args = array(
        'object_id' => $object_id,
        'object_type' => $object_type,
        'meta_key' => $meta_key,
        'meta_value' => maybe_serialize($meta_value)
    );
    
    $result = $wpdb->insert(WP_PREFIX."wau_meta",$args);
    
    return $result;
}

function wau_update_meta($object_id,$object_type,$meta_key,$meta_value){
    global $wpdb;
    
    if(wau_get_meta($object_id,$object_type,$meta_key)){
        
        $result = $wpdb->update(WP_PREFIX."wau_meta",
            array(
                'meta_value' => maybe_serialize($meta_value)
            ),
            array(
                'object_id' => $object_id,
                'object_type' => $object_type,
                'meta_key' => $meta_key
            )
        );
        
    }else{
        
        $result = wau_add_meta($object_id,$object_type,$meta_key,$meta_value);
        
    }

    return $result;
}

function wau_delete_meta($object_id, $object_type, $meta_key, $meta_value = false){
    global $wpdb;
    
    $sql = "DELETE FROM ".WP_PREFIX."wau_meta "
            . "WHERE "
            . "object_id='$object_id' "
            . "AND object_type='$object_type' "
            . "AND meta_key='$meta_key'";
    
    if($meta_value){
        $sql .= " AND meta_value='$meta_value'";
    }
    
    $result = $wpdb->query($sql);
    
    return $result;
}
