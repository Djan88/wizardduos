<?php

add_action( 'admin_init', 'wau_init_admin_actions' );
function wau_init_admin_actions() {
	global $user_ID;

	if ( ! isset( $_REQUEST['wau-action'] ) )
		return;

	if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wau-nonce' ) )
		return;

	$action = $_REQUEST['wau-action'];

	switch ( $action ) {
		case 'save-account':

			$account_id = $_REQUEST['account_id'];

			if ( $account_id ) {

				wau_update_account( $account_id, array(
					'account_name'	 => $_REQUEST['account_name'],
					'account_desc'	 => $_REQUEST['account_desc']
				) );
			} else {

				$account_id = wau_add_account( array(
					'account_name'	 => $_REQUEST['account_name'],
					'account_desc'	 => $_REQUEST['account_desc'],
					'is_prolong'	 => $_REQUEST['is_prolong'],
					'parent_id'		 => $_REQUEST['parent_id']
					) );

				do_action( 'wau_save_account', $account_id );
			}

			break;
		case 'save-tariff':

			$tariff_id = $_REQUEST['tariff_id'];

			if ( $tariff_id ) {

				wau_update_tariff( $tariff_id, array(
				) );
			} else {

				$tariff_id = wau_add_tariff( array(
					'account_id'	 => $_REQUEST['account_id'],
					'tariff_name'	 => $_REQUEST['tariff_name'],
					'tariff_desc'	 => $_REQUEST['tariff_desc'],
					'tariff_price'	 => $_REQUEST['tariff_price'],
					'is_best'		 => $_REQUEST['is_best'],
					'access_time'	 => wau_setup_time( array(
						'year'	 => $_REQUEST['year'],
						'month'	 => $_REQUEST['month'],
						'day'	 => $_REQUEST['day'],
						'hour'	 => $_REQUEST['hour'],
						'minute' => $_REQUEST['minute']
					) )
					) );

				do_action( 'wau_save_tariff', $tariff_id );
			}

			break;
	}

	wp_redirect( $_POST['_wp_http_referer'] );
	exit;
}

rcl_ajax_action( 'wau_ajax_manager_update_data' );
function wau_ajax_manager_update_data() {

	$post = $_POST;

	if ( isset( $post['account_id'] ) ) {

		if ( isset( $post['tariff_id'] ) )
			$result	 = wau_manager_update_tariff( $post );
		else
			$result	 = wau_manager_update_account( $post );

		wp_send_json( $result );
	}

	exit;
}

function wau_check_parent_account( $account_id, $maybeParent ) {

	$parent_id = wau_get_account_field( $maybeParent, 'parent_id' );

	if ( ! $parent_id )
		return true;

	if ( $parent_id == $account_id )
		return false;

	return wau_check_parent_account( $account_id, $parent_id );
}

function wau_manager_update_account( $options ) {

	$result = array(
		'success'	 => __( 'Изменения сохранены' ),
		'title'		 => $options['account_name'],
		'id'		 => $options['account_id']
	);

	if ( $options['parent_id'] ) {

		if ( ! wau_check_parent_account( $options['account_id'], $options['parent_id'] ) ) {
			return array(
				'error' => __( 'Нельзя назначить дочерний доступ в качестве родителя' )
			);
		}
	}

	$parent_id = wau_get_account_field( $options['account_id'], 'parent_id' );

	if ( $parent_id != $options['parent_id'] ) {
		$result['update-page'] = true;
	}

	wau_update_account( $options['account_id'], array(
		'parent_id'		 => $options['parent_id'],
		'account_name'	 => $options['account_name'],
		'account_desc'	 => $options['account_desc'],
		'is_prolong'	 => $options['is_prolong']
	) );

	do_action( 'wau_save_account', $options['account_id'] );

	return $result;
}

function wau_manager_update_tariff( $options ) {

	$result = array(
		'success'	 => __( 'Изменения сохранены' ),
		'title'		 => $options['tariff_name'],
		'id'		 => $options['tariff_id']
	);

	$account_id = wau_get_tariff_field( $options['tariff_id'], 'account_id' );

	if ( $account_id != $options['account_id'] ) {
		$result['update-page'] = true;
	}

	wau_update_tariff( $options['tariff_id'], array(
		'account_id'	 => $options['account_id'],
		'tariff_name'	 => $options['tariff_name'],
		'tariff_desc'	 => $options['tariff_desc'],
		'tariff_price'	 => $options['tariff_price'],
		'is_best'		 => $options['is_best'],
		'access_time'	 => wau_setup_time( array(
			'year'	 => $options['year'],
			'month'	 => $options['month'],
			'day'	 => $options['day'],
			'hour'	 => $options['hour'],
			'minute' => $options['minute']
		) )
	) );

	do_action( 'wau_save_tariff', $options['tariff_id'] );

	return $result;
}

rcl_ajax_action( 'wau_ajax_update_sort_tariffs', false );
function wau_ajax_update_sort_tariffs() {
	global $wpdb;

	$sort = json_decode( wp_unslash( $_POST['sort'] ) );

	foreach ( $sort as $s => $tariff ) {
		$wpdb->update(
			WP_PREFIX . 'wau_tariffs', array(
			'tariff_seq' => $s + 1
			), array(
			'tariff_id' => $tariff->id
			)
		);
	}

	wp_send_json( array(
		'success' => __( 'Changes saved!', 'wp-recall' )
	) );
}

rcl_ajax_action( 'wau_ajax_get_manager_item_delete_form', false );
function wau_ajax_get_manager_item_delete_form() {

	$itemType	 = $_POST['item-type'];
	$itemID		 = $_POST['item-id'];

	if ( $itemType == 'accounts' ) {

		wau_delete_account( $itemID );
	} else if ( $itemType == 'tariffs' ) {

		wau_delete_tariff( $itemID );
	}

	wp_send_json( array(
		'success'	 => __( 'Успешно удалено' ),
		'reload'	 => true
	) );
}

add_action( 'wau_save_account', 'wau_save_account_meta' );
function wau_save_account_meta( $account_id ) {

	$meta = $_POST['meta'];

	if ( ! $meta )
		return false;

	foreach ( $meta as $meta_key => $meta_value ) {

		if ( $meta_value ) {
			wau_update_meta( $account_id, 'account', $meta_key, $meta_value );
		} else {
			wau_delete_meta( $account_id, 'account', $meta_key );
		}
	}
}

add_action( 'wau_save_tariff', 'wau_save_tariff_meta' );
function wau_save_tariff_meta( $tariff_id ) {

	$meta = $_POST['meta'];

	if ( ! $meta )
		return false;

	foreach ( $meta as $meta_key => $meta_value ) {

		if ( $meta_value ) {
			wau_update_meta( $tariff_id, 'tariff', $meta_key, $meta_value );
		} else {
			wau_delete_meta( $tariff_id, 'tariff', $meta_key );
		}
	}
}

add_action( 'wau_delete_account', 'wau_delete_account_meta' );
function wau_delete_account_meta( $account_id ) {

	$metas = wau_get_metas( array(
		'object_type'	 => 'account',
		'object_id'		 => $account_id,
		'number'		 => -1
		) );

	if ( ! $metas )
		return false;

	foreach ( $metas as $meta ) {

		wau_delete_meta( $meta->object_id, $meta->object_type, $meta->meta_key );
	}
}
