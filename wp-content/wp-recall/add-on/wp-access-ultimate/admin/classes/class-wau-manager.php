<?php

class WAU_Manager extends Rcl_Custom_Fields_Manager{
    
    public $accounts;
    public $tariffs;
    public $account_id;
    public $current_account;
    public $roles = array();
    
    function __construct(){

        rcl_sortable_scripts();
        
        $this->accounts = wau_get_accounts(array(
            'number' => 'ASC',
            'number' => -1
        ));
        
        $this->account_id = (isset($_GET['account-id']))? intval($_GET['account-id']): 0;
        
        if($this->accounts && !$this->account_id){
            $this->account_id = $this->accounts[0]->account_id;
        }
        
        if($this->account_id){
            
            $this->tariffs = wau_get_tariffs(array(
                'order' => 'ASC',
                'orderby' => 'tariff_seq',
                'account_id' => $this->account_id,
                'number' => -1
            ));
            
            $this->current_account = wau_get_account($this->account_id);
            
        }
        
        $this->setup_rolesdata();
        
    }
    
    function setup_rolesdata(){

        $this->roles = array( '' => __('Не назначать'));
        
        foreach(wp_roles()->roles as $role_id => $role){
            $this->roles[$role_id] = $role['name'];
        }
        
    }
    
    function get_form_account(){

        $content = $this->get_form($this->get_account_fields(),'save-account',__('Создать аккаунт доступа'));
        
        return $content;

    }
    
    function get_form_tariff(){
        
        $fields = $this->get_tariff_fields();
        
        if(!$fields) return false;
        
        $content = $this->get_form($fields,'save-tariff',__('Создать тарифный план'));
        
        return $content;

    }
    
    function get_form($fields,$action,$submit){
        
        $content = '<div class="manager-form">';
            $content .= '<form method="post">';

                foreach($fields as $field){

                    $required = (isset($field['required']) && $field['required'] == 1)? '<span class="required">*</span>': '';

                    $content .= '<div id="field-'.$field['slug'].'" class="form-field rcl-custom-field">';

                        if(isset($field['title'])){
                            $content .= '<label>';
                            $content .= $this->get_title($field).' '.$required;
                            $content .= '</label>';
                        }

                        $content .= $this->get_input($field);

                    $content .= '</div>';
                }

                $content .= '<div class="form-field fields-submit">';
                    $content .= '<input type="submit" class="button-primary" value="'.$submit.'">';
                $content .= '</div>';
                $content .= '<input type="hidden" name="wau-action" value="'.$action.'">';
                $content .= wp_nonce_field('wau-nonce','_wpnonce',true,false);
            $content .= '</form>';
        $content .= '</div>';
        
        return $content;
        
    }

    function get_account_fields($account = false){
        
        $parents = array();
        
        if($this->accounts){

            foreach($this->accounts as $acc){
                if($account && $account->account_id == $acc->account_id) continue;
                $parents[$acc->account_id] = $acc->account_name;
            }
        
        }
        
        if($parents){
            $newParents = array(0 => __('Родительского доступа нет'));
            foreach($parents as $parent_id => $parentName){
                $newParents[$parent_id] = $parentName;
            }
            $parents = $newParents;
        }else{
            $parents = array('' => __('Подходящих аккаунтов доступа нет'));
        }
        
        $fields = array(
            array(
                'type' => 'select',
                'slug' => 'parent_id',
                'title' => __('Родительский аккаунт доступа'),
                'values' => $parents
            ),
            array(
                'type' => 'text',
                'slug' => 'account_name',
                'title' => __('Наименование аккаунта доступа'),
                'required' => 1,
                'maxlength' => 50
            ),
            array(
                'type' => 'textarea',
                'slug' => 'account_desc',
                'title' => __('Краткое описание')
            ),
            array(
                'type' => 'radio',
                'slug' => 'is_prolong',
                'title' => __('Продление доступа'),
                'values' => array(
                    __('Запрещено'),
                    __('Разрешено')
                )
            ),
            array(
                'type' => 'text',
                'slug' => 'meta[uptime_discount]',
                'title' => __('Скидка при продлении'),
                'default' => $account? wau_get_meta($account->account_id, 'account', 'uptime_discount'): '',
                'notice' => __('Укажите размер скидки, который будет действовать для пользователя при продлении действующего доступа. '
                        . 'Укажите целое число или нецелое с точкой в качестве разделителя.')
            ),
            array(
                'type' => 'select',
                'slug' => 'meta[up_role]',
                'title' => __('Роль при назначении доступа'),
                'values' => $this->roles,
                'default' => $account? wau_get_meta($account->account_id, 'account', 'up_role'): '',
                'notice' => __('При назначении или приобретении этого '
                        . 'доступа пользователю будет назначаться указанная роль. '
                        . 'Администратор и редактор игнорируются.')
            ),
            array(
                'type' => 'select',
                'slug' => 'meta[down_role]',
                'title' => __('Роль по окончании доступа'),
                'values' => $this->roles,
                'default' => $account? wau_get_meta($account->account_id, 'account', 'down_role'): '',
                'notice' => __('По окончании этого '
                        . 'доступа пользователю будет назначаться указанная роль. '
                        . 'Администратор и редактор игнорируются.')
            ),
            array(
                'type' => 'hidden',
                'slug' => 'account_id',
                'value' => $account? $account->account_id: 0
            )
        );
        
        $fields = apply_filters('wau_account_fields', $fields, $account);
        
        return $fields;
    }
    
    function get_tariff_fields($tariff = false){
        
        if(!$this->accounts) return false;
        
        $accounts = array('' => __('Выберите аккаунт доступа'));
        
        foreach($this->accounts as $account){
            $accounts[$account->account_id] = $account->account_name;
        }
        
        $timeData = array();
        if($tariff){
            $timeData = wau_parse_time($tariff->access_time);
        }

        $fields = array(
            array(
                'type' => 'select',
                'slug' => 'account_id',
                'title' => __('Аккаунт доступа'),
                'required' => 1,
                'default' => $this->account_id,
                'values' => $accounts
            ),
            array(
                'type' => 'text',
                'slug' => 'tariff_name',
                'title' => __('Наименование тарифного плана'),
                'required' => 1,
                'maxlength' => 50
            ),
            array(
                'type' => 'textarea',
                'slug' => 'tariff_desc',
                'title' => __('Краткое описание')
            ),
            array(
                'type' => 'custom',
                'slug' => 'access-time',
                'title' => __('Время доступа'),
                'content' => '<input type="text" style="width: auto;" size="1" value="'.(isset($timeData['year'])? $timeData['year']: 0).'" name="year">г. '
                    . '<input type="text" style="width: auto;" size="1" value="'.(isset($timeData['month'])? $timeData['month']: 0).'" name="month">м. '
                    . '<input type="text" style="width: auto;" size="1" value="'.(isset($timeData['day'])? $timeData['day']: 0).'" name="day">д. '
                    . '<input type="text" style="width: auto;" size="1" value="'.(isset($timeData['hour'])? $timeData['hour']: 0).'" name="hour">ч. '
                    . '<input type="text" style="width: auto;" size="1" value="'.(isset($timeData['minute'])? $timeData['minute']: 0).'" name="minute">мин. '
            ),
            array(
                'type' => 'number',
                'slug' => 'tariff_price',
                'title' => __('Стоимость'),
                'required' => 1
            ),
            array(
                'type' => 'select',
                'slug' => 'is_best',
                'title' => __('Тип предложения'),
                'values' => array(
                    __('Обычное'),
                    __('Популярное')
                )
            ),
            array(
                'type' => 'hidden',
                'slug' => 'tariff_id',
                'value' => $tariff? $tariff->tariff_id: 0
            )
        );
        
        $fields = apply_filters('wau_tariff_fields', $fields, $tariff);
        
        return $fields;
        
    }
    
    function get_manager_accounts(){
        
        $content = '<div class="manager-box manage-accounts rcl-custom-fields-box">';
        
        $content .= '<h3>'.__('Аккаунты доступа').'</h3>';

        $content .= $this->get_accounts_list();    

        $content .= $this->get_form_account();
        
        $content .= '</div>';
        
        return $content;
        
    }
    
    function get_accounts_list(){

        if(!$this->accounts) 
            return '<p>'.__('Пока ни одного аккаунта не создано').'</p>';

        $content = '<div class="accounts-list">';

            foreach($this->accounts as $account){

                $this->fields[] = array(
                    'type' => 'accounts',
                    'type_id' => 'account_id',
                    'slug' => $account->account_id,
                    'title' => $account->account_name,
                    'account_name' => $account->account_name,
                    'account_desc' => $account->account_desc,
                    'is_prolong' => $account->is_prolong,
                    'parent_id' => $account->parent_id,
                    'options-field' => $this->get_account_fields($account)
                );

            }

            $content .= '<div id="wau-accounts-list">';
                $content .= '<ul>';
                    $content .= $this->loop($this->get_children_fields(0));
                $content .= '</ul>';
            $content .= '</div>';

            $content .= $this->sortable_script('accounts');

        $content .= '</div>';
        
        return $content;
        
    }

    function get_tariffs_list(){
        
        if(!$this->tariffs) 
            return '<p>'.__('Пока не создано ни одного тарифного плана').'</p>';

        $accounts = array();
        foreach($this->accounts as $account){
            $accounts[$account->account_id] = $account->account_name;
        }

        $content = '<div class="tariffs-list">';
        
            $content .= '<p>'.__('Тарифные планы аккаунта доступа').' "'.$this->current_account->account_name.'"</p>';

            foreach($this->tariffs as $tariff){
                
                $dateTime = wau_parse_time($tariff->access_time);
                
                $this->fields[] = array(
                    'type' => 'tariffs',
                    'type_id' => 'tariff_id',
                    'slug' => $tariff->tariff_id,
                    'title' => $tariff->tariff_name,
                    'tariff_name' => $tariff->tariff_name,
                    'tariff_desc' => $tariff->tariff_desc,
                    'account_id' => $tariff->account_id,
                    'tariff_price' => $tariff->tariff_price,
                    'is_best' => $tariff->is_best,
                    'year' => $dateTime['year'],
                    'month' => $dateTime['month'],
                    'day' => $dateTime['day'],
                    'hour' => $dateTime['hour'],
                    'minute' => $dateTime['minute'],
                    'options-field' => $this->get_tariff_fields($tariff)
                );

            }
            
            $content .= '<div id="wau-tariffs-list">';
                $content .= '<ul class="rcl-sortable-fields">';
                    $content .= $this->loop($this->get_children_fields(0));
                $content .= '</ul>';
            $content .= '</div>';

            $content .= $this->sortable_script('tariffs');

        $content .= '</div>';
        
        return $content;
        
    }
    
    function get_children_fields($parent_id){
        
        $childrens = array();
        foreach($this->fields as $field){
            if($field['parent_id'] != $parent_id) continue;
            $childrens[] = $field;
        }

        return $childrens;
        
    }
    
    function get_manager_tariffs(){
        
        $this->fields = array();
        
        $content = '<div class="manager-box manage-tariffs rcl-custom-fields-box">';
        
        $content .= '<h3>'.__('Управление тарифными планами').'</h3>';
        
        $content .= $this->get_tariffs_list();    

        $content .= $this->get_form_tariff();

        $content .= '</div>';
        
        return $content;
    }
    
    function get_manager(){

        $content = '<div id="wau-manager">';
        
        $content .= $this->get_manager_accounts();
            
        $content .= $this->get_manager_tariffs();
        
        $content .= '</div>';
        
        return $content;

    }
    
    function get_input_option($option, $value = false){
        
        $value = (isset($this->field[$option['slug']]))? $this->field[$option['slug']]: $value;

        $option['name'] = $option['slug'];
        
        return $this->get_input($option, $value);
        
    }
    
    function field($args){
        
        $this->field = $args;
        
        $this->status = true;

        $classes = array('rcl-custom-field');
        
        if($this->field['type'] == 'accounts' && $this->account_id == $this->field['slug']){
            $classes[] = 'active-account';
        }
  
        if(isset($this->field['class']))
            $classes[] = $this->field['class'];
        
        $title = ($this->field['type'] == 'accounts')? $this->field['slug'].': '.$this->field['title']: $this->field['title'];

        $content = '<li id="field-'.$this->field['slug'].'" data-parent="'.$this->field['parent_id'].'" data-slug="'.$this->field['slug'].'" data-type="'.$this->field['type'].'" class="'.implode(' ',$classes).'">
            <div class="field-header">
                <span class="field-type type-'.$this->field['type'].'"></span>
                <span class="field-title">'.$title.'</span>                           
                <span class="field-controls">
                    <a class="field-trash field-control" href="#" title="'.__('Delete','wp-recall').'" onclick="wau_delete_manager_item(this); return false;"></a>
                    <a class="field-edit field-control" href="#" title="'.__('Edit','wp-recall').'"></a>';
        
                if($this->field['type'] == 'accounts')
                    $content .= '<a class="get-tariffs field-control" href="'.admin_url('admin.php?page=wau-manager&account-id='.$this->field['slug']).'" title="'.__('Get tariffs','wp-recall').'"></a>';
                
                $content .= '</span>
            </div>
            <div class="field-settings">';

                $content .= '<form method="post">';

                    $content .= '<div class="options-custom-field">';
                    $content .= $this->get_options();
                    $content .= '</div>';

                    $content .= '<div class="form-buttons">';
                    $content .= '<input type="submit" class="button-primary" value="'.__('Save changes','wp-recall').'">';
                    $content .= '<input type="hidden" name="'.$this->field['type_id'].'" value="'.$this->field['slug'].'">';
                    $content .= '</div>';

                $content .= '</form>';

            $content .= '</div>';
            
            if($this->field['type'] == 'accounts'){
                $content .= '<ul class="rcl-sortable-fields children-box">';
                $content .= $this->loop($this->get_children_fields($this->field['slug']));
                $content .= '</ul>';
            }
                    
        $content .= '</li>';
                        
        $this->field = false;

        return $content;

    }
    
    function sortable_script($typeList){
        
        return '<script>
                jQuery(function(){
                    jQuery(".'.$typeList.'-list .rcl-sortable-fields").sortable({
                        handle: ".field-header",
                        cursor: "move",
                        /*containment: "parent",*/
                        connectWith: ".'.$typeList.'-list .rcl-sortable-fields",
                        placeholder: "ui-sortable-placeholder",
                        distance: 15,
                        start: function(ev, ui) {
                        
                            /*var field = jQuery(ui.item[0]);
                            
                            field.parents("#wau-'.$typeList.'-list > ul").find(".rcl-custom-field").each(function(a,b){
                                if(field.attr("id") == jQuery(this).attr("id")) return;
                                jQuery(this).children(".children-box").addClass("must-receive");
                            });
                            
                            field.parent().addClass("list-receive");*/

                        },
                        stop: function(ev, ui) {
                            
                            /*var field = jQuery(ui.item[0]);
                            
                            field.parents("#wau-'.$typeList.'-list > ul").find(".children-box").removeClass("must-receive");
                            
                            var parentUl = field.parent("ul");
                            
                            parentUl.removeClass("list-receive");
                            
                            var parentID = 0;
                            if(parentUl.hasClass("children-box")){
                                var parentID = parentUl.parent("li").data("slug");                               
                            }
                            
                            field.attr("data-parent",parentID);*/
                            
                            wau_manager_save_sort("'.$typeList.'");
                                
                        }
                    });
                });
            </script>';
        
    }
    
}

