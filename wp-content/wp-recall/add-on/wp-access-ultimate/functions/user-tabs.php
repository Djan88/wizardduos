<?php

add_action('init','wau_init_tab');
function wau_init_tab(){

    $tab_data = array(
        'id'        =>'wau-tab',
        'name'      =>__('Доступ'),
        'supports'  =>array('ajax'),
        'public'    =>0,
        'icon'      =>'fa-lock',
        'content'   => array(
            array(
                'id' => 'access',
                'icon' => 'fa-unlock',
                'name' => __('Текущий доступ'),
                'callback' => array(
                    'name' => 'wau_access_tab'
                )
            ),
            array(
                'id' => 'payments',
                'icon' => 'fa-clock-o fa-clock',
                'name' => __('История оплат'),
                'callback' => array(
                    'name' => 'wau_payments_tab'
                )
            )
        )
    );

    rcl_tab($tab_data);

}

function wau_access_tab($master_id){
    global $WAU_User;

    $content = '<h3>'.__('Текущие доступы').'</h3>';

    if(!$WAU_User->access)
        return $content . '<p>'.__('В данный момент, текущего доступа нет.').'</p>';

    foreach($WAU_User->access as $access){
        $content .= wau_get_account_box($access->account_id);
    }

    return $content;
}

function wau_payments_tab($master_id){
    global $WAU_User;

    $paysAmount = wau_count_payments(array(
        'user_id' => $master_id
    ));

    $content = '<h3>'.__('История оплат доступа').'</h3>';

    if(!$paysAmount)
        return $content . '<p>'.__('Пока ни одной оплаты доступа не было.').'</p>';

    $pagenavi = new Rcl_PageNavi('pays',$paysAmount);

    $payments = wau_get_payments(array(
        'user_id' => $master_id,
        'offset' => $pagenavi->offset
    ));

    $content .= $pagenavi->pagenavi();

    $content .= '<table class="rcl-form">';

    $content .= '<tr>';
        $content .= '<th>'.__('ID платежа').'</th>';
        $content .= '<th>'.__('Наименование доступа').'</th>';
        $content .= '<th>'.__('Время доступа').'</th>';
        $content .= '<th>'.__('Размер оплаты').'</th>';
        $content .= '<th>'.__('Дата оплаты').'</th>';
    $content .= '</tr>';

    foreach($payments as $payment){

        $content .= '<tr>';
            $content .= '<td>'.$payment->payment_id.'</td>';
            $content .= '<td>'.$payment->account_name.'</td>';
            $content .= '<td>'.wau_time_to_strdate($payment->access_time).'</td>';
            $content .= '<td>'.$payment->tariff_price.' '.rcl_get_primary_currency(1).'</td>';
            $content .= '<td>'.$payment->payment_date.'</td>';
        $content .= '</tr>';
    }

    $content .= '</table>';

    $content .= $pagenavi->pagenavi();

    return $content;
}

