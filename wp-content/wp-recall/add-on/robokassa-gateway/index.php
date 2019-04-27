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
                ),
                $opt->label(__('Фискализация платежа','rcl-robokassa')),
                $opt->option('select',array(
                    'name'=>'robo_fn',
                    'parent'=>true,
                    'options'=>array(
                        __('Отключено','rcl-robokassa'),
                        __('Включено','rcl-robokassa')
                    )
                )),
                $opt->child(
                    array(
                        'name'=>'robo_fn',
                        'value'=>1
                    ),
                    array(
                        $opt->label(__('Ставка НДС','rcl-robokassa')),
                        $opt->option('select',array(
                            'name'=>'robo_nds',
                            'options'=>array(
                                'none' => __('без НДС'),
                                'vat0' => __('НДС по ставке 0%'),
                                'vat10' => __('НДС по ставке 10%'),
                                'vat18' => __('НДС по ставке 18%'),
                                'vat110' => __('НДС по ставке 10/110'),
                                'vat118' => __('НДС по ставке 18/118'),
                                'vat20' => __('НДС по ставке 20%'),
                                'vat120' => __('НДС по ставке 20/120')
                            )
                        )),
                        $opt->label(__('Система налогообложения','rcl-robokassa')),
                        $opt->option('select',array(
                            'name'=>'robo_tax',
                            'options'=>array(
                                'osn' => __('ОСН'),
                                'usn_income' => __('УСН (доходы)'),
                                'usn_income_outcome' => __('УСН (доходы-расходы)'),
                                'envd' => __('ЕНДВ'),
                                'esn' => __('ЕСН'),
                                'patent' => __('Патент')
                            )
                        ))
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
            'shp_a='.$data->user_id,
            'shp_b='.$data->pay_type,
            'shp_c='.$baggage_data
        );

        if($currency){
            array_splice($md_array, 3, 0, $currency);
        }

        if($receipt = $this->get_receipt($data)){
            array_splice($md_array, 3, 0, $receipt);
        }

        $crc = md5(implode(':',$md_array));

        $desc = ($data->description)? $data->description: 'Платеж от '.get_the_author_meta('user_email',$data->user_id);

        $fields = array(
            'MrchLogin'=>$login,
            'OutSum'=>$data->pay_summ,
            'InvId'=>$data->pay_id,
            'SignatureValue'=>$crc,
            'InvDesc'=>$desc,
            'shp_a'=>$data->user_id,
            'shp_b'=>$data->pay_type,
            'shp_c'=>$baggage_data,
        );

        if($currency){
            $fields['OutSumCurrency'] = $currency;
        }

        if($receipt){
            $fields['Receipt'] = $receipt;
        }

        if($rmag_options['robotest']==1){
            $fields['isTest'] = 1;
        }

        $form = parent::form($fields,$data,$formaction);

        return $form;
    }

    function get_receipt($data){
        global $rmag_options;

        if(!$rmag_options['robo_fn']) return false;

        $items = array();

        if($data->pay_type == 1){

            $items[] = array(
                "name" => __('Пополнение личного счета'),
                "quantity" => 1,
                "sum" => $data->pay_summ,
                "tax" => $rmag_options['robo_nds']
            );

        }else if($data->pay_type == 2){

            $order = rcl_get_order($data->pay_id);

            if($order){

                foreach($order->products as $k => $product){

                    $items[] = array(
                        "name" => get_the_title($product->product_id),
                        "quantity" => $product->product_amount,
                        "sum" => $product->product_amount * $product->product_price,
                        "tax" => $rmag_options['robo_nds']
                    );

                }

            }

        }else{

            $items[] = array(
                "name" => $data->description,
                "quantity" => 1,
                "sum" => $data->pay_summ,
                "tax" => $rmag_options['robo_nds']
            );

        }

        return json_encode(array(
            'sno' => $rmag_options['robo_tax'],
            'items' => $items
        ));

    }

    function result($data){
        global $rmag_options;

        if(isset($_REQUEST["shp_d"])) return false;

        if($rmag_options['robotest']==1){
            $pass2 = $rmag_options['test_tworobopass'];
        }else{
            $pass2 = $rmag_options['tworobopass'];
        }

        $data->pay_summ = $_REQUEST["OutSum"];
        $currency = $_REQUEST["OutSumCurrency"];
        $data->pay_id = $_REQUEST["InvId"];
        $data->user_id = $_REQUEST["shp_a"];
        $data->pay_type = $_REQUEST["shp_b"];
        $data->baggage_data = $_REQUEST["shp_c"];

        $crc = strtoupper($_REQUEST["SignatureValue"]);

        $md_array = array(
            $data->pay_summ,
            $data->pay_id,
            $pass2,
            'shp_a='.$data->user_id,
            'shp_b='.$data->pay_type,
            'shp_c='.$data->baggage_data
        );

        if($currency){
            array_splice($md_array, 2, 0, $currency);
        }

        /*if($receipt = $this->get_receipt($data)){
            array_splice($md_array, 2, 0, $receipt);
        }*/

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
            'user_id' => $_REQUEST["shp_a"]
        );

        if(parent::get_pay((object)$data)){
            wp_redirect(get_permalink($rmag_options['page_successfully_pay'])); exit;
        } else {
            wp_die(__('A record of the payment in the database was not found','rcl-robokassa'));
        }

    }
}

}
