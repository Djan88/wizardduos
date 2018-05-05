<?php

class WAU_Post extends WAU_Accounts_Walker{
    
    public $post_id;
    public $access;
    public $options;
    
    function __construct($args) {
        
        parent::__construct(array(
            'number' => -1
        ));
        
        $this->init_properties($args);
        
        if(!$this->access){

            $access = get_post_meta($this->post_id, 'wau-access', 1);
            
            $this->access = $access->access;
            $this->options = $access->options;

        }
    }
    
    function init_properties($args){
        
        $properties = get_class_vars(get_class($this));

        foreach ($properties as $name=>$val){
            if(isset($args[$name])) $this->$name = $args[$name];
        }
        
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
    
    function get_time_value($valueName, $account_id){
        
        $access = $this->get_access_by_account($account_id);
        
        if(!$access) return 0;
        
        $timeValues = wau_parse_time($access->access_time);
        
        return $timeValues[$valueName];
        
    }
    
}

