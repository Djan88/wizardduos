<?php

add_action('wau_update_user_access', 'wau_up_user_role');
function wau_up_user_role($access_id){

    $access = wau_get_access($access_id);
    
    if(user_can($access->user_id, 'administrator') || user_can($access->user_id, 'editor')) return false;
    
    $upRole = wau_get_meta($access->account_id, 'account', 'up_role');

    if(!$upRole) return false;

    if(is_admin() && isset($_POST['role']) && isset($_POST['user_id'])){
        $_POST['role'] = $upRole;
        return;
    }

    wp_update_user(array(
        'ID' => $access->user_id, 
        'role' => $upRole
    )) ;  

}

add_action('wau_pre_delete_access', 'wau_down_user_role');
function wau_down_user_role($access_id){
    
    $access = wau_get_access($access_id);
    
    if(user_can($access->user_id, 'administrator') || user_can($access->user_id, 'editor')) return false;
    
    $downRole = wau_get_meta($access->account_id, 'account', 'down_role');
    
    if(!$downRole) return false;
    
    if(is_admin() && isset($_POST['role']) && isset($_POST['user_id'])){
        $_POST['role'] = $downRole;
        return;
    }

    wp_update_user(array(
        'ID' => $access->user_id, 
        'role' => $downRole
    )) ; 
    
}