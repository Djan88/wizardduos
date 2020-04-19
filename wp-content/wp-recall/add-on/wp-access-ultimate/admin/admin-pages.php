<?php
add_action( 'admin_menu', 'wau_init_admin_menu', 10 );
function wau_init_admin_menu() {
	add_menu_page( 'WP Access Ultimate', 'Access Ultimate', 'manage_options', 'wau-options', 'wau_page_options' );
	add_submenu_page( 'wau-options', __( 'Настройки' ), __( 'Настройки' ), 'manage_options', 'wau-options', 'wau_page_options' );
	add_submenu_page( 'wau-options', __( 'Аккаунты доступа' ), __( 'Аккаунты доступа' ), 'manage_options', 'wau-manager', 'wau_page_manager' );
	$hook	 = add_submenu_page( 'wau-options', __( 'Текущий доступ' ), __( 'Текущий доступ' ), 'manage_options', 'wau-access', 'wau_page_access' );
	add_action( "load-$hook", 'wau_page_access_options' );
	$hook	 = add_submenu_page( 'wau-options', __( 'История оплат' ), __( 'История оплат' ), 'manage_options', 'wau-history', 'wau_page_history' );
	add_action( "load-$hook", 'wau_page_history_options' );
}

function wau_page_access() {
	global $WAU_Access_List;

	$WAU_Access_List->prepare_items();
	?>

	</pre>
	<div class="wrap">
		<h2><?php _e( 'Действующие доступы' ); ?></h2>
		<form method="post">
			<?php $WAU_Access_List->display(); ?>
			<input type="hidden" name="page" value="wau-access">
		</form>
	</div>

	<?php
}

function wau_page_access_options() {
	global $WAU_Access_List;

	$WAU_Access_List = new WAU_Access_List();

	add_screen_option( 'per_page', array(
		'label'		 => __( 'Доступы' ),
		'default'	 => 50,
		'option'	 => 'wau_access_per_page'
	) );
}

function wau_page_history() {
	global $WAU_History_Payments;

	$WAU_History_Payments->prepare_items();
	?>

	</pre>
	<div class="wrap">
		<h2><?php _e( 'История оплат' ); ?></h2>
		<form method="post">
			<?php $WAU_History_Payments->display(); ?>
			<input type="hidden" name="page" value="wau-history">
		</form>
	</div>

	<?php
}

function wau_page_history_options() {
	global $WAU_History_Payments;

	$WAU_History_Payments = new WAU_History_Payments();

	add_screen_option( 'per_page', array(
		'label'		 => __( 'Платежи' ),
		'default'	 => 50,
		'option'	 => 'wau_history_per_page'
	) );
}

function wau_page_options() {

	$accounts = wau_get_accounts( array(
		'number' => -1,
		'fields' => array(
			'account_id',
			'account_name'
		)
		) );

	if ( $accounts ) {
		$accountsData = array( '' => __( 'Аккаунт доступа не выбран' ) );
		foreach ( $accounts as $acc ) {
			$accountsData[$acc->account_id] = $acc->account_name;
		}
	} else {
		$accountsData = array( '' => __( 'Аккаунты доступа еще не созданы' ) );
	}

	$post_types = get_post_types( array(
		'public'	 => true,
		'_builtin'	 => false
		), 'objects' );

	$types = array(
		'post'	 => __( 'Записи' ),
		'page'	 => __( 'Страницы' )
	);

	foreach ( $post_types as $post_type ) {
		$types[$post_type->name] = $post_type->label;
	}

	require_once RCL_PATH . 'classes/class-rcl-options.php';

	$opt = new Rcl_Options( __FILE__, 'wau_options' );

	$WAUOptions = get_option( 'wau_options' );

	$options = array(
		array(
			'type'	 => 'checkbox',
			'slug'	 => 'post-types',
			'title'	 => __( 'Типы записей' ),
			'values' => $types,
			'notice' => __( 'Укажите типы записей для которых будет '
				. 'ограничиваться доступ' )
		),
		array(
			'type'	 => 'select',
			'slug'	 => 'reg-account',
			'title'	 => __( 'Назначать доступ при регистрации' ),
			'values' => $accountsData,
			'notice' => __( 'Укажите аккаунт доступа и время его действия для '
				. 'автоматического назначения вновь зарегистрированному пользователю' )
		),
		array(
			'type'		 => 'custom',
			'slug'		 => 'reg-time',
			'content'	 => '<label>' . __( 'Время доступа' ) . '</label> '
			. '<input type="text" style="width: auto;" size="1" value="' . (isset( $WAUOptions['reg-time']['year'] ) ? $WAUOptions['reg-time']['year'] : 0) . '" name="wau_options[reg-time][year]">г. '
			. '<input type="text" style="width: auto;" size="1" value="' . (isset( $WAUOptions['reg-time']['month'] ) ? $WAUOptions['reg-time']['month'] : 0) . '" name="wau_options[reg-time][month]">м. '
			. '<input type="text" style="width: auto;" size="1" value="' . (isset( $WAUOptions['reg-time']['day'] ) ? $WAUOptions['reg-time']['day'] : 0) . '" name="wau_options[reg-time][day]">д. '
			. '<input type="text" style="width: auto;" size="1" value="' . (isset( $WAUOptions['reg-time']['hour'] ) ? $WAUOptions['reg-time']['hour'] : 0) . '" name="wau_options[reg-time][hour]">ч. '
			. '<input type="text" style="width: auto;" size="1" value="' . (isset( $WAUOptions['reg-time']['minute'] ) ? $WAUOptions['reg-time']['minute'] : 0) . '" name="wau_options[reg-time][minute]">мин. '
		),
		array(
			'type'		 => 'editor',
			'slug'		 => 'access-text-archive',
			'title'		 => __( 'Текст о закрытом доступе на архивных страницах' ),
			'tinymce'	 => true,
			'default'	 => '<span style="color:red;font-weight:bold;">Данный контент имеет ограниченный доступ</span>',
			'notice'	 => __( 'Разрешается использование тегов:<br>'
				. '{excerpt} - краткое содержание записи установленное в редакторе записи через поле "Отрывок"<br>'
				. '{accountName} - наименование аккаунта доступа, которым публикация закрыта' )
		),
		array(
			'type'		 => 'editor',
			'slug'		 => 'access-text-single',
			'title'		 => __( 'Текст о закрытом доступе на одиночных страницах' ),
			'tinymce'	 => true,
			'default'	 => '<span style="color:red;font-weight:bold;">Данный контент имеет ограниченный доступ</span>',
			'notice'	 => __( 'Разрешается использование тегов:<br>'
				. '{excerpt} - краткое содержание записи установленное в редакторе записи через поле "Отрывок"<br>'
				. '{accountName} - наименование аккаунта доступа, которым публикация закрыта' )
		),
		array(
			'type'		 => 'editor',
			'slug'		 => 'access-member-notice',
			'title'		 => __( 'Текст о закрытом доступе для зарегистрированных пользователей' ),
			'tinymce'	 => true,
			//'default' => '<span style="color:red;font-weight:bold;">Данный контент имеет ограниченный доступ</span>',
			'notice'	 => __( 'Разрешается использование тегов:<br>'
				. '{excerpt} - краткое содержание записи установленное в редакторе записи через поле "Отрывок"<br>'
				. '{accountName} - наименование аккаунта доступа, которым публикация закрыта' )
		),
		array(
			'type'		 => 'editor',
			'slug'		 => 'access-guest-notice',
			'title'		 => __( 'Текст о закрытом доступе для гостей' ),
			'tinymce'	 => true,
			//'default' => '<span style="color:red;font-weight:bold;">Данный контент имеет ограниченный доступ</span>',
			'notice'	 => __( 'Разрешается использование тегов:<br>'
				. '{excerpt} - краткое содержание записи установленное в редакторе записи через поле "Отрывок"<br>'
				. '{accountName} - наименование аккаунта доступа, которым публикация закрыта' )
		),
		array(
			'type'	 => 'select',
			'slug'	 => 'access-guest-card',
			'title'	 => __( 'Карточка доступа для гостей' ),
			'values' => array(
				__( 'Скрыть' ),
				__( 'Показывать' )
			),
			'notice' => __( 'Показывайте или скройте карточку доступа для гостей сайта. '
				. 'Если скрыто, то будет выводится только установленный выше текст о закрытом доступе.' )
		),
		array(
			'type'	 => 'select',
			'slug'	 => 'show-branch-cards',
			'title'	 => __( 'Показывать карточки всей иерархии' ),
			'values' => array(
				__( 'Скрыть' ),
				__( 'Показывать' )
			),
			'notice' => __( 'Если присутствует иерархия доступов к материалу, то будут показаны все карточки из иерархии' )
		),
		array(
			'type'		 => 'editor',
			'slug'		 => 'mail-text-remind',
			'title'		 => __( 'Текст письма-напоминания об окончании доступа' ),
			'tinymce'	 => true,
			'default'	 => __( 'Уважаемый, {userName}!<br />'
				. 'Сообщаем Вам, о том, что время действия вашего аккаунта {accountName}, '
				. 'подходит к концу.<br />До момента окончания действия этого аккаунта '
				. 'осталось {timeEnd}' ),
			'notice'	 => __( 'Разрешается использование тегов:<br>'
				. '{userName} - отображаемое имя пользователя, которому отсылаем письмо<br>'
				. '{accountName} - наименование аккаунта доступа<br>'
				. '{timeEnd} - оставшееся время доступа' )
		),
		array(
			'type'		 => 'runner',
			'slug'		 => 'days-remind',
			'title'		 => __( 'Отправлять письмо-уведомление за указанное кол-во суток до окончания доступа' ),
			'value_max'	 => 10,
			'value_min'	 => 1,
			'value_step' => 1,
			'default'	 => 1
		),
		array(
			'type'	 => 'select',
			'slug'	 => 'author-show',
			'title'	 => __( 'Показ контента автору' ),
			'values' => array(
				__( 'Скрывать' ),
				__( 'Показывать' )
			),
			'notice' => __( 'Укажите показывать ли контент закрытой публикации '
				. 'ее автору, если у него нет доступа.' )
		),
		array(
			'type'	 => 'select',
			'slug'	 => 'hidden-posts',
			'title'	 => __( 'Активировать опцию скрытия публикации из выдачи' ),
			'values' => array(
				__( 'Отключено' ),
				__( 'Включено' )
			),
			'notice' => __( 'В настройках доступа публикаций и терминов таксономий '
				. 'появляется новая опция, позволяющая полностью скрывать '
				. 'публикации на одиночных и/или архивных страницах для пользователей без доступа. '
				. 'Скрытые публикации будут удалены из архивных страниц, одиночная страница публикации будет отдавать 404 ошибку.' )
		),
		array(
			'type'	 => 'select',
			'slug'	 => 'hidden-when',
			'title'	 => __( 'Порядок скрытия из выдачи' ),
			'values' => array(
				__( 'На архивных и одиночных' ),
				__( 'Только на архивных страницах' ),
				__( 'Только на одиночных страницах' )
			),
			'notice' => __( 'Если для публикации или термина указана опция скрытия '
				. 'из выдачи, то здесь можете указать где именно производить удаление из выдачи.' )
		),
		array(
			'type'		 => 'runner',
			'slug'		 => 'filter-priority',
			'title'		 => __( 'Приоритет фильтрации скрытого контента' ),
			'value_max'	 => 1000,
			'value_step' => 10,
			'default'	 => 10,
			'notice'	 => __( 'Изменяя приоритет фильтрации контента можно '
				. 'влиять на вывод контента прикрепленного к публикации '
				. 'через фильтр the_content, также скрывая его или наоборот - показывая' )
		)
	);

	$options = apply_filters( 'wau_options_array', $options );

	if ( $WAUOptions ) {
		foreach ( $options as $k => $option ) {

			if ( isset( $WAUOptions[$option['slug']] ) )
				$options[$k]['default'] = $WAUOptions[$option['slug']];
		}
	}
	?>

	<h2><?php _e( 'Настройки WP Access Ultimate' ); ?></h2>

	<div><?php echo reg_form_wpp( 'wau' ); ?></div>

	<div id="prime-options" class="rcl-form wrap-recall-options" style="display:block;">

		<form method="post" action="options.php">

			<?php
			echo $opt->options(
				false, array(
				$opt->options_box( __( 'General settings', 'wp-recall' ), $options )
				)
			);
			?>

			<p align="right">
				<input type="submit" name="Submit" class="button button-primary button-large" value="<?php _e( 'Save', 'wp-recall' ); ?>" />
			</p>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="wau_options" />
			<?php wp_nonce_field( 'update-options' ); ?>

		</form>

	</div>
	<?php
}

function wau_page_manager() {

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_style( 'wp-jquery-ui-dialog' );
	?>

	<h2><?php _e( 'Управление аккаунтами доступа и тарифными планами' ); ?></h2>

	<?php
	$manager = new WAU_Manager();

	echo $manager->get_manager();
}
