<?php

class WAU_Post_Accounts_Settings extends WAU_Post{

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
        $content .= '<select name="wau-postdata[options][important]">';
        $content .= '<option value="0">'.__('Нестрогое').'</option>';
        $content .= '<option value="1" '.selected($this->options['important'], 1, false).'>'.__('Строгое').'</option>';
        $content .= '</select> <label>'.__('Соответствие доступов пользователя').'</label></p>';

        if(wau_get_option('hidden-posts', false)){
            $content .= '<p>';
            $content .= '<select name="wau-postdata[options][hidden]">';
            $content .= '<option value="0">'.__('Не скрывать').'</option>';
            $content .= '<option value="1" '.selected($this->options['hidden'], 1, false).'>'.__('Скрывать').'</option>';
            $content .= '</select> <label>'.__('Полностью скрыть страницу публикации и ссылку на нее в архивах').'</label></p>';
        }

        $content .= '<h3>'.__('Настройки карточки доступа').'</h3>';

        $content .= '<p><select name="wau-postdata[options][price_table]">';
        $content .= '<option value="1" '.selected($this->options['price_table'], 1, false).'>'.__('Вывести').'</option>';
        $content .= '<option value="0" '.selected($this->options['price_table'], 0, false).'>'.__('Скрыть').'</option>';
        $content .= '</select> <label>'.__('Список тарифных планов').'</label></p>';

        $content .= '<p><select name="wau-postdata[options][account_name]">';
        $content .= '<option value="1" '.selected($this->options['account_name'], 1, false).'>'.__('Вывести').'</option>';
        $content .= '<option value="0" '.selected($this->options['account_name'], 0, false).'>'.__('Скрыть').'</option>';
        $content .= '</select> <label>'.__('Заголовок с наименованием доступа').'</label></p>';

        $content .= '<p><select name="wau-postdata[options][description]">';
        $content .= '<option value="1" '.selected($this->options['description'], 1, false).'>'.__('Вывести').'</option>';
        $content .= '<option value="0" '.selected($this->options['description'], 0, false).'>'.__('Скрыть').'</option>';
        $content .= '</select> <label>'.__('Описание доступа').'</label></p>';

        $content .= '<input type="hidden" name="wau-post-settings" value="1">';

        $content .= '</div>';

        return $content;

    }

    function get_account_html($account){

        $content = '<div class="wau-account">';

            $content .= '<input type="checkbox" '.checked($this->is_access($account->account_id), true, false).' class="account-check" value="'.$account->account_id.'" name="wau-postdata[access][][account_id]">';

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

