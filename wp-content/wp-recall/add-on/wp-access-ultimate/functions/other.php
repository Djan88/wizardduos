<?php

function wau_check_post_type( $post_type ) {

	$postTypes = wau_get_option( 'post-types' );

	if ( ! $postTypes )
		return false;

	return (in_array( $post_type, $postTypes ));
}

function wau_get_option( $name, $default = false ) {

	$WAUOptions = get_option( 'wau_options' );

	if ( ! isset( $WAUOptions[$name] ) || $WAUOptions[$name] == '' ) {
		return $default;
	}

	return $WAUOptions[$name];
}

function wau_setup_time( $data ) {

	$time = 0;

	$time += (isset( $data['year'] ) && $data['year']) ? $data['year'] * 31104000 : 0;
	$time += (isset( $data['month'] ) && $data['month']) ? $data['month'] * 2592000 : 0;
	$time += (isset( $data['day'] ) && $data['day']) ? $data['day'] * 86400 : 0;
	$time += (isset( $data['hour'] ) && $data['hour']) ? $data['hour'] * 3600 : 0;
	$time += (isset( $data['minute'] ) && $data['minute']) ? $data['minute'] * 60 : 0;

	return $time;
}

function wau_parse_time( $time ) {

	$data = array(
		'year'	 => 0,
		'month'	 => 0,
		'day'	 => 0,
		'hour'	 => 0,
		'minute' => 0
	);

	$data['year']	 = floor( $time / 31104000 );
	if ( $data['year'] < 0 )
		$data['year']	 = 0;

	$sec_month		 = $time - ($data['year'] * 31104000);
	$data['month']	 = floor( $sec_month / 2592000 );
	if ( $data['month'] < 0 )
		$data['month']	 = 0;

	$sec_day	 = $sec_month - ($data['month'] * 2592000);
	$data['day'] = floor( $sec_day / 86400 );

	$sec_hour		 = $sec_day - ($data['day'] * 86400);
	$data['hour']	 = floor( $sec_hour / 3600 );

	$sec_min		 = $sec_hour - ($data['hour'] * 3600);
	$data['minute']	 = floor( $sec_min / 60 );

	return $data;
}

function wau_time_to_strdate( $time ) {

	$timeData = wau_parse_time( $time );

	$parent	 = false;
	$string	 = '';

	if ( $timeData['year'] ) {
		$parent = true;
		$string .= $timeData['year'] . ' г. ';
	}

	if ( $timeData['month'] || $parent ) {
		$parent = true;
		$string .= $timeData['month'] . ' м. ';
	}

	if ( $timeData['day'] || $parent ) {
		$parent = true;
		$string .= $timeData['day'] . ' д. ';
	}

	if ( $timeData['hour'] || $parent ) {
		$string .= $timeData['hour'] . ' ч. ';
	}


	$string .= $timeData['minute'] . ' мин. ';

	return $string;
}

function wau_get_accounts_box( $account_ids, $args = false ) {

	$content = '';

	foreach ( $account_ids as $account_id ) {
		$content .= wau_get_account_box( $account_id, $args );
	}

	return $content;
}

function wau_get_account_box( $account_id, $args = false ) {
	global $WAU_User;

	$account = wau_get_account( $account_id );

	if ( ! $account )
		return false;

	rcl_dialog_scripts();

	$args = wp_parse_args( $args, array(
		'price_table'	 => 1,
		'account_name'	 => 1,
		'description'	 => 1
		) );

	$args = apply_filters( 'wau_account_box_args', $args, $account_id );

	$box = '';

	if ( $args['account_name'] ) {
		$box .= '<div class="wau-account-box-title">'
			. '<span>' . __( 'Аккаунт доступа' ) . ' "' . $account->account_name . '"</span>'
			. '</div>';
	}

	if ( $args['description'] && $account->account_desc ) {
		$box .= '<div class="wau-account-description">' . $account->account_desc . '</div>';
	}

	if ( $access = $WAU_User->get_access_by_account( $account_id ) ) {

		$time = $access->access_time - (strtotime( current_time( 'mysql' ) ) - strtotime( $access->access_date ));

		$box .= '<div class="wau-active-notice">';
		$box .= '<span>' . __( 'Действующий доступ!' ) . '</span>';
		$box .= '<span>' . __( 'Осталось' ) . ': ' . wau_time_to_strdate( $time ) . '</span>';
		$box .= '</div>';
	}

	if ( $args['price_table'] ) {

		if ( ! $access || $account->is_prolong ) {

			$tarriffBox = wau_get_tariffs_box( $account_id );

			$box .= $tarriffBox ? $tarriffBox : '<p>' . __( 'Тарифные планы для аккаунта доступа не созданы' ) . '</p>';
		}
	}

	if ( ! $box )
		return false;

	$content = '<div class="wau-account-box">';
	$content .= $box;
	$content .= '</div>';

	return $content;
}

function wau_get_tariffs_box( $account_id ) {

	$tariffs = wau_get_tariffs( array(
		'account_id' => $account_id,
		'order'		 => 'ASC',
		'orderby'	 => 'tariff_seq'
		) );

	if ( ! $tariffs )
		return false;

	$tariffs = apply_filters( 'wau_tariffs', $tariffs, $account_id );

	if ( ! $tariffs )
		return false;

	$content = rcl_get_include_template( 'wau-tariff-list.php', __FILE__, array(
		'tariffs' => $tariffs
		) );

	return $content;
}

function wau_set_discount_price( $tariff_price, $discount ) {

	return ceil( $discount * $tariff_price / 100 );
}

function wau_get_tariff_price( $tariff_id, $user_id = false ) {
	global $WAU_User;

	if ( $user_id ) {
		$WAU_User = new WAU_User( array(
			'user_id' => $user_id
			) );
	}

	$tariff = wau_get_tariff( $tariff_id );

	if ( $WAU_User->is_access( $tariff->account_id ) && $discount = wau_get_meta( $tariff->account_id, 'account', 'uptime_discount' ) ) {
		$tariff_price = $tariff->tariff_price - wau_set_discount_price( $tariff->tariff_price, $discount );
	} else {
		$tariff_price = $tariff->tariff_price;
	}

	return $tariff_price;
}

function wau_is_access_post( $post_id ) {
	global $WAU_User, $WAU_Post, $post;

	if ( $post->ID == $post_id ) {

		$WAUPOST = $WAU_Post;
		$WPPOST	 = $post;
	} else {

		$WPPOST = get_post( $post_id );

		$WAUPOST = new WAU_Post( array(
			'post_id' => $WPPOST->ID
			) );
	}

	if ( wau_get_option( 'author-show' ) && $WAU_User->user_id == $WPPOST->post_author )
		return true;

	if ( ! wau_check_post_type( get_post_type( $WPPOST ) ) )
		return true;

	if ( $accountIds = wau_get_post_closed_term_ids( $WPPOST->ID ) ) {

		return false;
	}

	if ( ! $WAUPOST || ! $WAUPOST->access || $WAUPOST->post_id != $WPPOST->ID )
		return true;

	$account_ids = array();

	if ( $WAUPOST->options['important'] ) {

		foreach ( $WAUPOST->access as $access ) {
			if ( ! $WAU_User->is_branch_access( $access->account_id, 1 ) ) {
				$account_ids[] = $access->account_id;
			}
		}

		if ( ! $account_ids )
			return true;
	} else {

		$account_ids = $WAUPOST->get_account_ids();

		if ( $WAU_User->is_branch_access( $account_ids, 0 ) ) {
			return true;
		}
	}

	return false;
}
