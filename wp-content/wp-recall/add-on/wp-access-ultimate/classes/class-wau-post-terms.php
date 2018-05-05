<?php

class WAU_Post_Terms{
    
    public $post_id;
    public $taxonomies;
    public $terms_access;
    
    function __construct($args) {
        
        $this->init_properties($args);
        
        $this->setup_taxonomies();
        
        $this->terms_access = $this->get_terms_access();
        
    }
    
    function init_properties($args){
        
        $properties = get_class_vars(get_class($this));

        foreach ($properties as $name=>$val){
            if(isset($args[$name])) $this->$name = $args[$name];
        }
        
    }
    
    function setup_taxonomies(){
        $this->taxonomies = get_taxonomies(array(
            'public' => true,
            'object_type' => array(get_post_type($this->post_id))
        ), 'names', 'and');
    }
    
    function get_post_terms(){
        
        if(!$this->taxonomies) return false;
        
        $postTerms = array();
        foreach($this->taxonomies as $taxonomy){
            if($terms = get_the_terms($this->post_id, $taxonomy)){
                $postTerms = array_merge($postTerms, $terms);
            }
        }
        
        return $postTerms;
        
    }
    
    function get_terms_access(){
        
        $terms = $this->get_post_terms();
        
        if(!$terms) return false;
        
        $term_ids = array();
        foreach($terms as $term){
            $term_ids[] = $term->term_id;
        }
        
        $cachekey = md5(json_encode(array('get_terms_access',$term_ids)));
        $cache = wp_cache_get( $cachekey );
        if ( $cache )
            return $cache;
        
        global $wpdb;
        
        $termAccess = $wpdb->get_results("SELECT "
                . "meta_value "
                . "FROM $wpdb->termmeta "
                . "WHERE meta_key = 'wau-access' "
                . "AND term_id IN (".implode(',',$term_ids).")");
        
        if(!$termAccess) return false;
        
        foreach($termAccess as $k => $access){
            $termAccess[$k] = maybe_unserialize($access->meta_value);
        }
        
        wp_cache_add( $cachekey, $termAccess );
        
        return $termAccess;
        
    }
    
    function get_account_ids(){
        
        if(!$this->access) return false;
        
        $accoint_ids = array();
        foreach($this->access as $access){
            $accoint_ids[] = $access->account_id;
        }
        
        return $accoint_ids;
        
    }
    
    function is_access($account_id){
        
        if(!$this->access) return false;
        
        foreach($this->access as $access){
            if($access->account_id != $account_id) continue;
            return true;
        }
        
        return false;
        
    }
    
    function get_access_by_account($account_id){
        
        if(!$this->access) return false;
        
        foreach($this->access as $access){
            if($access->account_id != $account_id) continue;
            return $access;
        }
        
        return false;
        
    }
    
}

