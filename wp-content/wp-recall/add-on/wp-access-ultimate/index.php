<?php

include_once 'classes/class-wau-query.php';
include_once 'classes/class-wau-accounts-walker.php';
include_once 'classes/class-wau-user.php';
include_once 'classes/class-wau-post.php';
include_once 'classes/class-wau-term.php';
include_once 'classes/class-wau-post-terms.php';

include_once 'functions/database.php';
include_once 'functions/post-content.php';
include_once 'functions/ajax.php';
include_once 'functions/shortcodes.php';
include_once 'functions/user-tabs.php';
include_once 'functions/switch-roles.php';
include_once 'functions/other.php';

if ( is_admin() ):
	include_once 'admin/index.php';
endif;

if ( ! is_admin() ):
	add_action( 'rcl_enqueue_scripts', 'wau_scripts' );
endif;
function wau_scripts() {
	rcl_enqueue_style( 'wau-style', rcl_addon_url( 'assets/css/style.css', __FILE__ ) );
	rcl_enqueue_style( 'wau-prices', rcl_addon_url( 'assets/css/price-plans.css', __FILE__ ) );
	rcl_enqueue_script( 'wau-script', rcl_addon_url( 'assets/js/scripts.js', __FILE__ ) );
}

add_action( 'rcl_activate_wp-access-ultimate', 'wau_activate_actions' );
function wau_activate_actions() {

	if ( ! get_option( 'wau_options' ) ) {
		update_option( 'wau_options', array(
			'access-text-archive'	 => '<span style="color:red;font-weight:bold;">Данный контент имеет ограниченный доступ</span>',
			'access-text-single'	 => '<span style="color:red;font-weight:bold;">Данный контент имеет ограниченный доступ</span>',
			'mail-text-remind'		 => __( 'Уважаемый, {userName}!<br />'
				. 'Сообщаем Вам, о том, что время действия вашего аккаунта {accountName}, '
				. 'подходит к концу.<br />До момента окончания действия этого аккаунта '
				. 'осталось {timeEnd}' )
		) );
	}
}

add_action( 'init', 'wau_init_user', 10 );
function wau_init_user() {
	global $WAU_User, $user_ID;

	if ( $WAU_User )
		return;

	$WAU_User = new WAU_User( array(
		'user_id' => $user_ID
		) );

	do_action( 'wau_init_user' );
}

add_action( 'wp', 'wau_init_post', 10 );
function wau_init_post() {
	global $WAU_Post, $post;

	if ( is_singular() ) {

		if ( ! wau_check_post_type( get_post_type( $post ) ) )
			return false;

		$WAU_Post = new WAU_Post( array(
			'post_id' => $post->ID
			) );
	}
}

add_action( 'user_register', 'wau_app_user_access_by_register' );
function wau_app_user_access_by_register( $user_id ) {

	$account_id = wau_get_option( 'reg-account' );

	if ( ! $account_id )
		return false;

	wau_update_user_access( $user_id, $account_id, wau_setup_time( wau_get_option( 'reg-time' ) ) );
}

add_action( 'wau_init_user', 'wau_check_current_user_access' );
function wau_check_current_user_access() {
	global $WAU_User;

	if ( ! $WAU_User || ! $WAU_User->access )
		return false;

	foreach ( $WAU_User->access as $access ) {
		$time = $access->access_time - (strtotime( current_time( 'mysql' ) ) - strtotime( $access->access_date ));
		if ( $time <= 0 ) {

			wau_delete_access( $access->access_id );

			do_action( 'wau_delete_access_of_time_end', $access->access_id, $access );
		}
	}
}

add_action( 'rcl_cron_daily', 'wau_daily_cron_actions', 10 );
function wau_daily_cron_actions() {

	$access = wau_get_access_by_args( array(
		'number' => -1
		) );

	if ( ! $access )
		return false;

	$mailText	 = wau_get_option( 'mail-text-remind' );
	$daySeconds	 = wau_get_option( 'days-remind', 1 ) * 86400;

	foreach ( $access as $acc ) {

		$time = $acc->access_time - (strtotime( current_time( 'mysql' ) ) - strtotime( $acc->access_date ));

		if ( $time < 0 ) {

			wau_delete_access( $acc->access_id );

			do_action( 'wau_delete_access_of_time_end', $acc->access_id, $acc );
		} else if ( $time < $daySeconds && $time > ($daySeconds - 86400) ) {

			$timeData = wau_parse_time( $time );

			$mailTextParse = str_replace( array(
				'{accountName}',
				'{userName}',
				'{timeEnd}'
				), array(
				wau_get_account_field( $acc->account_id, 'account_name' ),
				get_the_author_meta( 'display_name', $acc->user_id ),
				($timeData['day'] ? $timeData['day'] . ' дн. ' : '') . $timeData['hour'] . ' час. ' . $timeData['minute'] . ' минут'
				), $mailText );

			rcl_mail( get_the_author_meta( 'email', $acc->user_id ), __( 'Время вашего доступа истекает!' ), $mailTextParse );
		}
	}
}

add_action( 'rcl_success_pay', 'wau_payment', 10 );
function wau_payment( $payData ) {

	if ( $payData->pay_type != 'wau-payment' )
		return false;

	$baggage = $payData->baggage_data;

	$tariff = wau_get_tariff( $baggage->tariff_id );

	$tariff_price = wau_get_tariff_price( $baggage->tariff_id, $payData->user_id );

	if ( $tariff_price != $payData->pay_summ )
		return false;

	do_action( 'wau_pre_payment_access', $payData, $tariff );

	$payment_id = wau_add_payment( array(
		'user_id'		 => $payData->user_id,
		'account_name'	 => wau_get_account_field( $tariff->account_id, 'account_name' ),
		'tariff_price'	 => $tariff_price,
		'access_time'	 => $tariff->access_time
		) );

	$access_id = wau_update_user_access( $payData->user_id, $tariff->account_id, $tariff->access_time );

	do_action( 'wau_payment_access', $payment_id, $access_id, $baggage->tariff_id );
}

add_action( 'wau_payment_access', 'wau_send_email_about_payment_access', 10 );
function wau_send_email_about_payment_access( $payment_id ) {

	$payment = wau_get_payment( $payment_id );

	$subject = __( 'Приобретен доступ!' );

	//Отправляем письмо об оплате админу
	$textmail = '
    <p>Пользователь оплатил аккаунт платного доступа на сайте "' . get_bloginfo( 'name' ) . '".</p>
    <h3>Информация о покупателе:</h3>
    <p><b>Имя</b>: ' . get_the_author_meta( 'display_name', $payment->user_id ) . '</p>
    <p><b>Email</b>: ' . get_the_author_meta( 'user_email', $payment->user_id ) . '</p>
    <p>Приобретенный аккаунт: ' . $payment->account_name . '.</p>
    <p>Время по тарифу: ' . wau_time_to_strdate( $payment->access_time ) . '</p>';

	rcl_mail( get_option( 'admin_email' ), $subject, $textmail );

	//Отправляем письмо об оплате покупателю
	$textmail = '
    <p>Вы оплатили аккаунт платного доступа на сайте "' . get_bloginfo( 'name' ) . '".</p>
    <h3>Информация о покупателе:</h3>
    <p><b>Имя</b>: ' . get_the_author_meta( 'display_name', $payment->user_id ) . '</p>
    <p><b>Email</b>: ' . get_the_author_meta( 'user_email', $payment->user_id ) . '</p>
    <p>Приобретенный аккаунт: ' . $payment->account_name . '.</p>
    <p>Время по тарифу: ' . wau_time_to_strdate( $payment->access_time ) . '</p>';

	rcl_mail( get_the_author_meta( 'user_email', $payment->user_id ), $subject, $textmail, $headers );
}

/* интеграция с партнерской программой */
add_filter( 'rcl_partner_actions', 'wau_add_payment_action' );
function wau_add_payment_action( $actions ) {
	$actions['wau-payment'] = __( 'Оплата платного доступа' );
	return $actions;
}

add_action( 'wau_payment_access', 'wpa_insert_partners_refill_paid', 10, 2 );
function wpa_insert_partners_refill_paid( $payment_id ) {
	if ( ! function_exists( 'add_partner_incentive' ) )
		return false;
	$payment = wau_get_payment( $payment_id );
	add_partner_incentive( $payment->user_id, $payment->tariff_price, 'wau-payment' );
}

//добавление своего поля в дефолтные произвольные поля формы публикации
add_filter( 'rcl_default_public_form_fields', 'wau_add_access_field', 10, 2 );
function wau_add_access_field( $fields, $post_type ) {

	$accounts = wau_get_accounts( array(
		'number' => -1
		) );

	if ( ! $accounts )
		return $fields;

	$options	 = array( 0 => 'Базовый доступ' );
	$defaultVals = array( 0 );
	foreach ( $accounts as $account ) {
		$options[$account->account_id]	 = $account->account_name;
		$defaultVals[]					 = $account->account_id;
	}

	$fields[] = array(
		'slug'			 => 'wau-access-switch',
		'title'			 => __( 'Платный доступ' ),
		'type'			 => 'custom',
		'wau-options'	 => $defaultVals,
		'options-field'	 => array(
			array(
				'type'	 => 'checkbox',
				'slug'	 => 'wau-options',
				'title'	 => __( 'Доступы к выбору' ),
				'values' => $options
			)
		)
	);

	return $fields;
}

//редактирование данных вывода своего дефолтного поля перед выводом в форме публикации
add_filter( 'rcl_public_form_fields', 'wau_add_access_field_in_form', 10, 2 );
function wau_add_access_field_in_form( $fields, $form ) {

	if ( ! $fields )
		return $fields;

	if ( is_admin() ) {

		foreach ( $fields as $k => $field ) {

			if ( $field['slug'] != 'wau-access-switch' )
				continue;

			unset( $fields[$k] );
		}

		return $fields;
	}

	$accounts = wau_get_accounts( array(
		'number' => -1
		) );

	if ( ! $accounts )
		return $fields;

	$access = array( 0 => 'Базовый доступ' );
	foreach ( $accounts as $account ) {
		$access[$account->account_id] = $account->account_name;
	}

	foreach ( $fields as $k => $field ) {

		if ( $field['slug'] != 'wau-access-switch' )
			continue;

		if ( ! isset( $field['wau-options'] ) || ! $field['wau-options'] )
			return $fields;

		foreach ( $field['wau-options'] as $access_id ) {

			if ( isset( $access[$access_id] ) ) {
				$values[$access_id] = $access[$access_id];
			}
		}

		$fields[$k]['type']			 = 'select';
		$fields[$k]['value_in_key']	 = false;
		$fields[$k]['values']		 = $values;
	}

	return $fields;
}

add_action( 'update_post_rcl', 'wau_update_product_access_data', 50, 3 );
function wau_update_product_access_data( $post_id, $postdata, $update ) {

	if ( ! isset( $_POST['wau-access-switch'] ) )
		return false;

	if ( ! $_POST['wau-access-switch'] ) {
		return delete_post_meta( $post_id, 'wau-access' );
	}

	$data = ( object ) array(
			'access'	 => array(
				( object ) array(
					'account_id' => $_POST['wau-access-switch']
				)
			),
			'options'	 => array(
				'important'	 => 0,
				'hidden'	 => wau_get_option( 'hidden-posts', 0 )
			)
	);

	update_post_meta( $post_id, 'wau-access', $data );
}

add_action( 'template_redirect', 'wau_filter_singular_posts', 20 );
function wau_filter_singular_posts() {
	global $post, $WAU_User, $WAU_Post;

	if ( ! is_singular() )
		return;

	if ( wau_get_option( 'author-show' ) && $WAU_User->user_id == $post->post_author )
		return;

	if ( ! wau_get_option( 'hidden-posts', false ) )
		return;

	if ( wau_get_option( 'hidden-when', 0 ) == 1 ) {
		return false;
	}

	$closedAccess	 = array();
	$hiddenPost		 = false;

	$PostTerms = new WAU_Post_Terms( array( 'post_id' => $post->ID ) );

	if ( $PostTerms->terms_access ) {

		foreach ( $PostTerms->terms_access as $term_access ) {

			if ( $term_access->options['hidden'] ) {
				$hiddenPost = true;
			}

			$account_ids = array();

			if ( $term_access->options['important'] ) {

				foreach ( $term_access->access as $access ) {
					if ( ! $WAU_User->is_branch_access( $access->account_id, 1 ) ) {
						$closedAccess[] = $access->account_id;
					}
				}
			} else {

				foreach ( $term_access->access as $access ) {
					$account_ids[] = $access->account_id;
				}

				if ( ! $WAU_User->is_branch_access( $account_ids ) ) {
					$closedAccess = array_merge( $closedAccess, $account_ids );
				}
			}
		}
	}

	//если нет доступа и для термина активировано скрытие
	if ( $hiddenPost && $closedAccess ) {
		status_header( 404 );
		include( get_query_template( '404' ) );
		exit;
	}

	//если пост не закрыт доступом или не активно скрытие
	if ( ! $WAU_Post->access || ! $WAU_Post->options['hidden'] )
		return;

	$access = true;

	if ( $WAU_Post->options['important'] ) {

		foreach ( $WAU_Post->access as $access ) {
			if ( ! $WAU_User->is_branch_access( $access->account_id, 1 ) ) {
				$access = false;
			}
		}
	} else {

		if ( ! $WAU_User->is_branch_access( $WAU_Post->get_account_ids(), 0 ) ) {
			$access = false;
		}
	}

	if ( ! $access ) {
		status_header( 404 );
		include( get_query_template( '404' ) );
		exit;
	}
}

add_action( 'pre_get_posts', 'wau_filter_pre_get_posts', 10 );
function wau_filter_pre_get_posts( $query ) {

	if ( ! is_admin() && $query->is_main_query() && ! $query->is_singular ) {

		if ( ! wau_get_option( 'hidden-posts', false ) )
			return false;

		if ( wau_get_option( 'hidden-when', 0 ) == 2 && ($query->is_archive || $query->is_home) ) {
			return false;
		}

		global $WAU_User;

		// for guests
		if ( ! $WAU_User->user_id ) {

			$query->set( 'meta_query', array(
				array(
					'key'		 => 'wau-access',
					'compare'	 => 'NOT EXISTS'
				)
			) );

			if ( $hidden_terms = $WAU_User->get_hidden_terms() ) {

				$taxQuery = array();

				foreach ( $hidden_terms as $taxonomy => $terms_ids ) {
					$taxQuery[] = array(
						'taxonomy'	 => $taxonomy,
						'field'		 => 'id',
						'terms'		 => $terms_ids,
						'operator'	 => 'NOT IN'
					);
				}

				$query->set( 'tax_query', $taxQuery );
			}

			return;
		}

		// for users
		if ( wau_get_option( 'author-show' ) && $WAU_User->user_id ) {
			if ( $hidden_posts = $WAU_User->get_hidden_posts() ) {
				$query->set( 'post__not_in', $hidden_posts );
			}
		} else {

			if ( $hidden_posts = $WAU_User->get_hidden_single_posts() ) {
				$query->set( 'post__not_in', $hidden_posts );
			}

			if ( $hidden_terms = $WAU_User->get_hidden_terms() ) {

				$taxQuery = array();

				foreach ( $hidden_terms as $taxonomy => $terms_ids ) {
					$taxQuery[] = array(
						'taxonomy'	 => $taxonomy,
						'field'		 => 'id',
						'terms'		 => $terms_ids,
						'operator'	 => 'NOT IN'
					);
				}

				$query->set( 'tax_query', $taxQuery );
			}
		}
	}
}
