<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WAU_Access_List extends WP_List_Table {

    var $per_page = 50;
    var $current_page = 1;
    var $total_items;
    var $offset = 0;
    var $sum = 0;

    function __construct(){
        global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'доступ' ),
            'plural'    => __( 'доступы' ),
            'ajax'      => false
        ) );

        $this->per_page = $this->get_items_per_page('wau_access_per_page', 50);
        $this->current_page = $this->get_pagenum();
        $this->offset = ($this->current_page-1)*$this->per_page;

        add_action( 'admin_head', array( &$this, 'admin_header' ) );

    }

    function admin_header() {

        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;

        if( 'wau-access' != $page ) return;

        echo '<style type="text/css">';
        //echo '.wp-list-table .column-access_id { width: 10%; }';
        echo '.wp-list-table .column-user_id { width: 25%; }';
        echo '.wp-list-table .column-account_id { width: 25%; }';
        echo '.wp-list-table .column-access_time { width: 15%;}';
        echo '.wp-list-table .column-access_date { width: 15%;}';
        echo '.wp-list-table .column-access_remine { width: 15%;}';
        echo '</style>';

    }

    function no_items() {
        _e( 'В данный момент, действующего доступа ни у кого нет' );
    }

    function column_default( $item, $column_name ) {

        switch( $column_name ) {
            case 'access_id':
                return $item->payment_id;
            case 'user_id':
                return $item->user_id.': '.get_the_author_meta('display_name',$item->user_id);
            case 'account_id':
                return $item->account_id.': '.wau_get_account_field($item->account_id, 'account_name');
            case 'access_time':
                return wau_time_to_strdate($item->access_time);
            case 'access_date':
                return $item->access_date;
            case 'access_remine':
                $time = $item->access_time - (strtotime(current_time('mysql')) - strtotime($item->access_date));
                return wau_time_to_strdate($time);
            default:
                return print_r( $item, true ) ;
        }

    }

    function get_columns(){
        $columns = array(
            //'cb'        => '<input type="checkbox" />',
            //'access_id' => __( 'ID платежа' ),
            'user_id' => __( 'Пользователи' ),
            'account_id' => __( 'Наименование доступа' ),
            'access_time'    => __( 'Время доступа' ),
            'access_date'      => __( 'Дата назначения' ),
            'access_remine'      => __( 'Осталось' )
        );
         return $columns;
    }

    function column_user_id($item){

        $actions = array(
            'all-access'    => sprintf('<a href="?page=%s&action=%s&user_id=%s">'.__( 'Все доступы' ).'</a>', $_REQUEST['page'], 'all-access', $item->user_id),
        );

        return sprintf('%1$s %2$s', $item->user_id.': '.get_the_author_meta('display_name',$item->user_id), $this->row_actions($actions) );

    }

    function get_data(){

        $args = array();

        if(isset($_GET['user_id']) && $_GET['user_id']){
            $args['user_id'] = intval($_GET['user_id']);
        }

        $args['number'] = $this->per_page;
        $args['offset'] = $this->offset;

        $this->total_items = wau_count_access($args);

        if(!$this->total_items) return false;

        $items = wau_get_access_by_args($args);

        return $items;

    }

    function prepare_items() {

        $data = $this->get_data();
        $this->_column_headers = $this->get_column_info();
        $this->set_pagination_args( array(
            'total_items' => $this->total_items,
            'per_page'    => $this->per_page
        ) );

        $this->items = $data;

    }
}
