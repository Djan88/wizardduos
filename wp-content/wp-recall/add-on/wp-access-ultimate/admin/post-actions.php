<?php

add_action('admin_init', 'wau_init_post_metabox', 1);
function wau_init_post_metabox(){
    
    if ( isset( $_GET['post'] ) )
        $post = $_GET['post'];
    else return false;

    if(isset( $post )){
        
        if(!wau_check_post_type(get_post_type($post))) return false;
        
        add_meta_box('wau-post-metabox', 'WP Access Ultimate', 'wau_post_metabox', get_post_type($post), 'normal', 'high');
    }
    
}

function wau_post_metabox($post){
    
    $Settings = new WAU_Post_Accounts_Settings(array(
        'post_id' => $post->ID
    ));

    if(!$Settings->accounts) return false;
    
    $content = '<h3>Доступ к публикации</h3>';

    $content .= '<div class="wau-post-access">';
    
    $content .= $Settings->get_settings_html();

    $content .= '</div>';
    
    echo $content;
}

add_action('save_post', 'wau_post_access_update', 10);
function wau_post_access_update($post_id){
    global $user_ID;

    if( !user_can($user_ID, 'administrator') || !isset($_POST['wau-post-settings'])) return false;
    
    if(!isset($_POST['wau-postdata'])){
        return delete_post_meta($post_id, 'wau-access');
    }

    $data = (object) $_POST['wau-postdata'];

    $updAccounts = array();
    foreach($data->access as $k => $accessData){
        
        if(!isset($accessData['account_id'])) continue;
        
        $data->access[$k] = (object) $accessData;
        
    }
    //print_r($data);exit;
    if($data->access){
        update_post_meta($post_id, 'wau-access', $data);
    }else{
        delete_post_meta($post_id, 'wau-access');
    }

}

