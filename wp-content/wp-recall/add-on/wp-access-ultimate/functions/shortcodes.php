<?php

add_shortcode( 'wau-close', 'wau_get_close_content_shortcode' );
function wau_get_close_content_shortcode( $atts, $content = null ) {
	global $WAU_User;

	$content = do_shortcode( shortcode_unautop( $content ) );
	if ( '</p>' == substr( $content, 0, 4 )
		and '<p>' == substr( $content, strlen( $content ) - 3 ) )
		$content = substr( $content, 4, strlen( $content ) - 7 );

	extract( shortcode_atts( array(
		'account_id'	 => false,
		'important'		 => 0,
		'price_table'	 => 1,
		'account_name'	 => 1,
		'description'	 => 1,
		'access_notice'	 => false
			), $atts ) );

	$args = array(
		'price_table'	 => $price_table,
		'account_name'	 => $account_name,
		'description'	 => $description
	);

	$account_ids = array_map( 'trim', explode( ',', $account_id ) );

	if ( $WAU_User->is_branch_access( $account_ids, $important ) ) {
		return $content;
	}

	$content = '';

	if ( $access_notice ) {
		$content = wau_get_option( 'access-text-single' );
	}

	if ( $important ) {
		foreach ( $account_ids as $account_id ) {
			if ( $WAU_User->is_branch_access( $account_id ) )
				continue;
			$content .= wau_get_account_box( $account_id, $args );
		}
	}else {
		$content .= wau_get_accounts_box( $account_ids, $args );
	}

	return $content;
}

add_shortcode( 'wau-close-content', 'wau_get_close_notice_content_shortcode' );
function wau_get_close_notice_content_shortcode( $atts, $content = null ) {
	global $WAU_User;

	$content = do_shortcode( shortcode_unautop( $content ) );
	if ( '</p>' == substr( $content, 0, 4 )
		and '<p>' == substr( $content, strlen( $content ) - 3 ) )
		$content = substr( $content, 4, strlen( $content ) - 7 );

	extract( shortcode_atts( array(
		'account_id' => false,
		'important'	 => 0
			), $atts ) );

	$account_ids = array_map( 'trim', explode( ',', $account_id ) );

	if ( $WAU_User->is_branch_access( $account_ids, $important ) ) {
		return false;
	}

	return $content;
}

add_shortcode( 'wau-accounts', 'wau_get_accounts_shortcode' );
function wau_get_accounts_shortcode( $atts ) {
	global $user_ID;

	extract( shortcode_atts( array(
		'account_id'	 => false,
		'account_name'	 => 1,
		'description'	 => 1,
		'hide_guest'	 => false
			), $atts ) );

	if ( $hide_guest && ! $user_ID ) {
		return false;
	}

	if ( ! $account_id ) {

		$WAUAccounts = new WAU_Accounts();

		$account_ids = $WAUAccounts->get_col( array(
			'number' => -1,
			'fields' => array(
				'account_id'
			)
			) );

		if ( ! $account_ids )
			return false;
	}else {

		$account_ids = array_map( 'trim', explode( ',', $account_id ) );
	}

	$args = array(
		'account_name'	 => $account_name,
		'description'	 => $description
	);

	$content = wau_get_accounts_box( $account_ids, $args );

	return $content;
}

add_shortcode( 'wau-tariffs', 'wau_get_tariffs_shortcode' );
function wau_get_tariffs_shortcode( $atts ) {
	global $user_ID;

	extract( shortcode_atts( array(
		'orderby'		 => 'tariff_seq',
		'order'			 => 'DESC',
		'tariff_id'		 => 0,
		'title'			 => false,
		'description'	 => false
			), $atts ) );

	$tariff_id = array_map( 'trim', explode( ',', $tariff_id ) );

	if ( ! $tariff_id ) {
		return rcl_get_notice( array(
			'text' => __( 'Не указаны тарифные планы!' )
			) );
	}

	$tariffs = wau_get_tariffs( array(
		'tariff_id__in'	 => $tariff_id,
		'orderby'		 => $orderby,
		'order'			 => $order
		) );

	if ( ! $tariffs )
		return rcl_get_notice( array(
			'text' => __( 'Не найдены тарифные планы!' )
			) );

	rcl_dialog_scripts();

	$content = '<div class="wau-account-box">';

	if ( $title ) {
		$content .= '<div class="wau-account-box-title">'
			. '<span>' . $title . '</span>'
			. '</div>';
	}

	if ( $description ) {
		$content .= '<div class="wau-account-description">' . $description . '</div>';
	}

	$content .= rcl_get_include_template( 'wau-tariff-list.php', __FILE__, array(
		'tariffs' => $tariffs
		) );

	$content .= '</div>';

	return $content;
}
