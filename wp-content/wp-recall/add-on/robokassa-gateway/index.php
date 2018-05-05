<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rcl_robokassa_form
 *
 * @author Андрей
 */
if(class_exists('Rcl_Payment')){
    
add_action('plugins_loaded', 'rcl_robokassa_load_plugin_textdomain',10);
function rcl_robokassa_load_plugin_textdomain(){
    global $locale;
    load_textdomain( 'rcl-robokassa', rcl_addon_path(__FILE__) . '/languages/rcl-robokassa-' . $locale . '.mo' );
}

add_action('init','rcl_add_robokassa_payment');
function rcl_add_robokassa_payment(){
    $pm = new Rcl_Robokassa_Payment();
    $pm->register_payment('robokassa');
}

class Rcl_Robokassa_Payment extends Rcl_Payment{

    public $form_pay_id;

    function register_payment($form_pay_id){
        $this->form_pay_id = $form_pay_id;
        parent::add_payment($this->form_pay_id, array(
            'class'=>get_class($this),
            'request'=>'OutSum',
            'name'=>'Робокасса',
            'image'=>rcl_addon_url('assets/robokassa.jpg',__FILE__)
            ));
        if(is_admin()) $this->add_options();
    }

    function add_options(){
        add_filter('rcl_pay_option',(array($this,'options')));
        add_filter('rcl_pay_child_option',(array($this,'child_options')));
    }

    function options($options){
        $options[$this->form_pay_id] = __('Robokassa','rcl-robokassa');
        return $options;
    }

    function child_options($child){
        global $rmag_options;

        $opt = new Rcl_Options();
        
        $curs = array( 'RUB', 'USD', 'EUR' );

        if(false !== array_search($rmag_options['primary_cur'], $curs)) {

            $options =   array(
                $opt->title(__('Connection settings ROBOKASSA','rcl-robokassa')),
                $opt->label(__('The ID of the store','rcl-robokassa')),
                $opt->option('text',array('name'=>'robologin')),
                $opt->label(__('The status of the account ROBOKASSA','rcl-robokassa')),
                $opt->option('select',array(
                    'name'=>'robotest',
                    'parent'=>true,
                    'options'=>array(
                        __('Work','rcl-robokassa'),
                        __('Test','rcl-robokassa')
                    )
                )),
                $opt->child(
                    array(
                        'name'=>'robotest',
                        'value'=>0
                    ),
                    array(
                        $opt->label(__('1 Password','rcl-robokassa')),
                        $opt->option('password',array('name'=>'onerobopass')),
                        $opt->label(__('2 Password','rcl-robokassa')),
                        $opt->option('password',array('name'=>'tworobopass'))
                    )
                ),
                $opt->child(
                    array(
                        'name'=>'robotest',
                        'value'=>1
                    ),
                    array(
                        $opt->label(__('1 Password','rcl-robokassa')),
                        $opt->option('password',array('name'=>'test_onerobopass')),
                        $opt->label(__('2 Password','rcl-robokassa')),
                        $opt->option('password',array('name'=>'test_tworobopass'))
                    )
                )
            );
        
        }else{
            
            $options = array(
                $opt->title('Настройки подключения Pay2Pay'),
                $opt->notice('<span style="color:red">Данное подключение не поддерживает действующую валюту сайта.<br>'
                        . 'Поддерживается работа с RUB, USD, EUR</span>')
            );
            
        }
        
        $child .= $opt->child(
            array(
                'name'=>'connect_sale',
                'value'=>$this->form_pay_id
            ),
            $options
        );

        return $child;
    }

    function pay_form($data){
        global $rmag_options;
        
        $formaction = 'https://merchant.roboxchange.com/Index.aspx';

        if($rmag_options['robotest']==1){
            //$formaction = 'http://test.robokassa.ru/Index.aspx';
            $pass1 = $rmag_options['test_onerobopass'];
        }else{
            
            $pass1 = $rmag_options['onerobopass'];
        }

        $login = $rmag_options['robologin'];
        
        $currency = (isset($rmag_options['primary_cur'])&&$rmag_options['primary_cur']!='RUB')? $rmag_options['primary_cur']: ''; // Валюта заказа
        
        $baggage_data = ($data->baggage_data)? $data->baggage_data: 'false';
        
        $md_array = array(
            $login,
            $data->pay_summ,
            $data->pay_id,
            $pass1,
            'Shp_item=2',
            'shpa='.$data->user_id,
            'shpb='.$data->pay_type,
            'shpc='.$baggage_data
        );
        
        if($currency){
            array_splice($md_array, 3, 0, $currency);
        }

        $crc = md5(implode(':',$md_array));
        
        $desc = ($data->description)? $data->description: 'Платеж от '.get_the_author_meta('user_email',$data->user_id);

        $fields = array(
            'MrchLogin'=>$login,
            'OutSum'=>$data->pay_summ,
            'InvId'=>$data->pay_id,
            'shpa'=>$data->user_id,
            'shpb'=>$data->pay_type,
            'shpc'=>$baggage_data,
            'SignatureValue'=>$crc,
            'Shp_item'=>'2',
            'Culture'=>'ru',
            'Desc'=>$desc
        );
        
        if($currency){
            $fields['OutSumCurrency'] = $currency;
        }
        
        if($rmag_options['robotest']==1){
            $fields['isTest'] = 1;
        }

        $form = parent::form($fields,$data,$formaction);

        return $form;
    }

    function result($data){
        global $rmag_options;
        
        if(isset($_REQUEST["shpd"])) return false;
        
        if($rmag_options['robotest']==1){
            $pass2 = $rmag_options['test_tworobopass'];
        }else{           
            $pass2 = $rmag_options['tworobopass'];
        }

        $data->pay_summ = $_REQUEST["OutSum"];
        $currency = $_REQUEST["OutSumCurrency"];
        $data->pay_id = $_REQUEST["InvId"];
        $data->user_id = $_REQUEST["shpa"];
        $data->pay_type = $_REQUEST["shpb"];
        $data->baggage_data = $_REQUEST["shpc"];

        $crc = strtoupper($_REQUEST["SignatureValue"]);
        
        $md_array = array(
            $data->pay_summ,
            $data->pay_id,
            $pass2,
            'Shp_item='.$_REQUEST['Shp_item'],
            'shpa='.$data->user_id,
            'shpb='.$data->pay_type,
            'shpc='.$data->baggage_data
        );
        
        if($currency){
            array_splice($md_array, 2, 0, $currency);
        }

        $my_crc = strtoupper(md5(implode(':',$md_array)));

        if ($my_crc !=$crc){ 
            rcl_mail_payment_error($my_crc); 
            echo "bad sign\n"; exit(); 
        }

        if(!parent::get_pay($data)) parent::insert_pay($data);

        echo "OK".$data->pay_id."\n"; exit();

    }

    function success(){
        global $rmag_options;

        $data = array(
            'pay_id' => $_REQUEST["InvId"],
            'user_id' => $_REQUEST["shpa"]
        );

        if(parent::get_pay((object)$data)){
            wp_redirect(get_permalink($rmag_options['page_successfully_pay'])); exit;
        } else {
            wp_die(__('A record of the payment in the database was not found','rcl-robokassa'));
        }

    }
}

}
