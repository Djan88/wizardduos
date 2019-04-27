<?php

class WAU_Term_Accounts_Settings extends WAU_Term{

    function __construct($args) {

        parent::__construct($args);

    }

    function init_properties($args){

        $properties = get_class_vars(get_class($this));

        foreach ($properties as $name=>$val){
            if(isset($args[$name])) $this->$name = $args[$name];
        }

    }

    function get_settings_html(){

        $content = '<div class="wau-accounts-list">';

        foreach($this->get_only_parents() as $k => $account){

            $content .= $this->get_account_html($account);

        }

        $content .= '<p>';
        $content .= '<select name="wau-termdata[options][important]">';
        $content .= '<option value="0">'.__('Нестрогое').'</option>';
        $content .= '<option value="1" '.selected($this->options['important'], 1, false).'>'.__('Строгое').'</option>';
        $content .= '</select> '.__('Соответствие доступов пользователя').'</p>';

        if(wau_get_option('hidden-posts', false)){
            $content .= '<p>';
            $content .= '<select name="wau-termdata[options][hidden]">';
            $content .= '<option value="0">'.__('Не скрывать').'</option>';
            $content .= '<option value="1" '.selected($this->options['hidden'], 1, false).'>'.__('Скрывать').'</option>';
            $content .= '</select> '.__('Скрыть публикации помеченные этим термином').'</p>';
        }

        $content .= '<input type="hidden" name="wau-term-settings" value="1">';

        $content .= '</div>';

        return $content;

    }

    function get_account_html($account){

        $content = '<div class="wau-account">';

            $content .= '<input type="checkbox" '.checked($this->is_access($account->account_id), true, false).' class="account-check" value="'.$account->account_id.'" name="wau-termdata[access][][account_id]">';

            $content .= '<span class="account-name">'.$account->account_name.'</span>';

            $content .= $this->get_childrens_html($account->account_id);

        $content .= '</div>';

        return $content;
    }

    function get_childrens_html($account_id){

        $directChilds = $this->get_direct_childrens($account_id);

        if(!$directChilds) return false;

        $content .= '<div class="wau-childrens-list">';

        foreach($directChilds as $account){
            $content .= $this->get_account_html($account);
        }

        $content .= '</div>';

        return $content;

    }

}

