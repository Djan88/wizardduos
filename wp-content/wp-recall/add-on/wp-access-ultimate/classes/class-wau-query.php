<?php

class WAU_Accounts extends Rcl_Query{
    
    function __construct() {
        
        $table = array(
            'name' => WP_PREFIX ."wau_accounts",
            'as' => 'wau_accounts',
            'cols' => array(
                'account_id',
                'account_name',
                'account_desc',
                'account_type',
                'account_status',
                'is_prolong',
                'parent_id'
            )
        );
        
        parent::__construct($table);
    }
    
}

class WAU_Tariffs extends Rcl_Query{
    
    function __construct() {
        
        $table = array(
            'name' => WP_PREFIX ."wau_tariffs",
            'as' => 'wau_tariffs',
            'cols' => array(
                'tariff_id',
                'account_id',
                'tariff_name',
                'tariff_desc',
                'tariff_price',
                'tariff_seq',
                'access_time'
            )
        );
        
        parent::__construct($table);
    }
    
}

class WAU_Access extends Rcl_Query{
    
    function __construct() {
        
        $table = array(
            'name' => WP_PREFIX ."wau_access",
            'as' => 'wau_access',
            'cols' => array(
                'access_id',
                'user_id',
                'account_id',
                'access_time',
                'access_date'
            )
        );
        
        parent::__construct($table);
    }
    
}

class WAU_Payments extends Rcl_Query{
    
    function __construct() {
        
        $table = array(
            'name' => WP_PREFIX ."wau_payments",
            'as' => 'wau_payments',
            'cols' => array(
                'payment_id',
                'user_id',
                'account_name',
                'tariff_price',
                'access_time',
                'payment_date'
            )
        );
        
        parent::__construct($table);
    }
    
}

class WAU_Meta extends Rcl_Query{
    
    function __construct() {
        
        $table = array(
            'name' => WP_PREFIX ."wau_meta",
            'as' => 'wau_meta',
            'cols' => array(
                'meta_id',
                'object_id',
                'object_type',
                'meta_key',
                'meta_value'
            )
        );
        
        parent::__construct($table);
    }
    
}