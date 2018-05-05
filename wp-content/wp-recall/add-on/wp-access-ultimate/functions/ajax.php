<?php

rcl_ajax_action('wau_ajax_get_payment_form');
function wau_ajax_get_payment_form(){
    global $user_ID, $WAU_User;
    
    $tariff_id = intval($_POST['tariff_id']);
    
    if(!$tariff_id) exit;
    
    if(!function_exists('rcl_get_pay_form')){
        wp_send_json(array(
            'error' => __('Не активирован функционал приема платежей')
        ));
    }
    
    $tariff = wau_get_tariff($tariff_id);
    
    $timeData = wau_parse_time($tariff->access_time);
    
    $tariff_price = wau_get_tariff_price($tariff_id);
    
    $accountName = wau_get_account_field($tariff->account_id, 'account_name');
    
    $result = array(
        'dialog' => array(
            'content' => 
                '<h3>'.__('Оплата доступа').' "'.$accountName.'"'.'</h3>'
                .'<p>'.__('оплачиваемый срок доступа').': '.$timeData['year'].'г. '.$timeData['month'].'м. '.$timeData['day'].'д. '.$timeData['hour'].'ч. '.$timeData['minute'].'мин. '.'</p>'
                .rcl_get_pay_form(array(
                    'baggage_data' => array(
                        'tariff_id' => $tariff_id
                    ),
                    'pay_type' => 'wau-payment',
                    'pay_systems_not_in' => array('yandexdengi'),
                    'user_id' => $user_ID,
                    'pay_summ' => $tariff_price,
                    'description' => __('Приобретение аккаунта платного доступа') . ' "'.$accountName.'"',
                    'merchant_icon' => 1
                )
            ),
            'title' => __('Форма оплаты платного доступа'),
            'size' => 'auto',
            'class' => 'wau-dialog'
        )
    );
    
    wp_send_json($result);
    
}

