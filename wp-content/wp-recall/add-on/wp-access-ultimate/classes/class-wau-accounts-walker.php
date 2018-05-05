<?php

class WAU_Accounts_Walker extends WAU_Accounts{
    
    public $accounts;
    
    function __construct($args) {
        
        parent::__construct();
        
        $args['cache'] = true;

        $this->accounts = $this->get_results($args);
        
    }
    
    function get_only_parents(){
        
        if(!$this->accounts) return false;
        
        $parents = array();
        foreach($this->accounts as $account){
            if($account->parent_id) continue;
            $parents[] = $account;
        }
        
        return $parents;
        
    }
    
    function get_all_childrens($account_id){
        
        if(!$this->accounts) return false;
        
        $childrens = array();
        foreach($this->accounts as $account){
            
            if($account->parent_id != $account_id) continue;
            
            $childrens[] = $account;
            
            $subChilds = $this->get_all_childrens($account->account_id);
            
            if($subChilds){
                $childrens = array_merge($childrens, $subChilds);
            }
        }

        return $childrens;
        
    }
    
    function get_direct_childrens($account_id){
        
        if(!$this->accounts) return false;
        
        $childrens = array();
        foreach($this->accounts as $account){
            
            if($account->parent_id != $account_id) continue;
            
            $childrens[] = $account;

        }

        return $childrens;
        
    }
    
    function get_account($account_id){
        
        if(!$this->accounts) return false;
        
        foreach($this->accounts as $account){
            if($account->account_id != $account_id) continue;
            return $account;
        }
        
        return false;
        
    }
    
    function get_branch($account_id){
        
        $account = $this->get_account($account_id);
        
        if(!$account) return false;
        
        $branch[] = $account;
        
        $childrens = $this->get_all_childrens($account_id);
        
        if($childrens){
            $branch = array_merge($branch, $childrens);
        }
        
        return $branch;
    }
    
}

