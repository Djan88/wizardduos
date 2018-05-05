<?php

add_shortcode('wau-close','wau_get_close_content_shortcode');
function wau_get_close_content_shortcode($atts, $content = null){
    global $WAU_User;

    $content = do_shortcode( shortcode_unautop( $content ) );
    if ( '</p>' == substr( $content, 0, 4 )
    and '<p>' == substr( $content, strlen( $content ) - 3 ) )
    $content = substr( $content, 4, strlen( $content ) - 7 );

    extract(shortcode_atts(array(
        'account_id' => false,
        'important' => 0,
        'price_table' => 1,
        'account_name' => 1,
        'description' => 1
    ),
    $atts));
    
    $args = array(
        'price_table' => $price_table,
        'account_name' => $account_name,
        'description' => $description
    );
    
    $account_ids = array_map('trim',explode(',',$account_id));
    
    if($WAU_User->is_branch_access($account_ids, $important)){
        return $content;
    }
    
    $content = '';
    
    if($important){
        foreach($account_ids as $account_id){
            if($WAU_User->is_branch_access($account_id)) continue;
            $content .= wau_get_account_box($account_id, $args);
        }
    }else{
        $content .= wau_get_accounts_box($account_ids, $args);
    }

    return $content;

}

add_shortcode('wau-accounts','wau_get_accounts_shortcode');
function wau_get_accounts_shortcode($atts){

    extract(shortcode_atts(array(
        'account_id' => false,
        'account_name' => 1,
        'description' => 1
    ),
    $atts));
    
    if(!$account_id){
        
        $WAUAccounts = new WAU_Accounts();
        
        $account_ids = $WAUAccounts->get_col(array(
            'number' => -1,
            'fields' => array(
                'account_id'
            )
        ));
        
        if(!$account_ids) return false;
        
    }else{
        
        $account_ids = array_map('trim',explode(',',$account_id));
        
    }

    $args = array(
        'account_name' => $account_name,
        'description' => $description
    );
    
    $content = wau_get_accounts_box($account_ids, $args);

    return $content;

}