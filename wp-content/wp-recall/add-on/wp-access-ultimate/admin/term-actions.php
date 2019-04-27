<?php

add_action('admin_init', 'wau_init_term_metabox', 10);
function wau_init_term_metabox(){
    
    if ( isset( $_GET['taxonomy'] ) && taxonomy_exists( $_GET['taxonomy'] ) )
        $taxonomy = $_GET['taxonomy'];
    else
        return;

    if( isset( $taxonomy ) ) {
        add_action ( $taxonomy. '_add_form_fields', 'wau_term_metabox', 10);
        add_action( $taxonomy. '_edit_form_fields', 'wau_term_metabox', 10 );
        //add_filter("manage_edit-".$taxonomy."_columns", 'add_wppay_taxonomy_column');
        //add_filter( 'manage_'.$taxonomy.'_custom_column','taxonomy_wppay_columns_manage',10,3);
    }
    
}

function wau_term_metabox($term){
    
    $Settings = new WAU_Term_Accounts_Settings(array(
        'term_id' => $term->term_id
    ));

    if(!$Settings->accounts) return false;
    
    $content = '<h3>Доступ к публикациям</h3>';

    $content .= '<div class="wau-term-access">';
    
    $content .= $Settings->get_settings_html();

    $content .= '</div>';
    
    echo $content;
}

add_action('create_term', 'wau_term_access_update', 10);
add_action('edit_term', 'wau_term_access_update', 10);
function wau_term_access_update($term_id){
    global $user_ID;
    
    if( !user_can($user_ID, 'administrator') || !isset($_POST['wau-term-settings'])) return false;
    
    if(!isset($_POST['wau-termdata'])){
        return delete_term_meta($term_id, 'wau-access');
    }

    $data = (object) $_POST['wau-termdata'];

    if($data->access){
        foreach($data->access as $k => $accessData){

            if(!isset($accessData['account_id'])) continue;

            $data->access[$k] = (object) $accessData;

        }
    }
    
    if($data->access){
        update_term_meta($term_id, 'wau-access', $data);
    }else{
        delete_term_meta($term_id, 'wau-access');
    }

}