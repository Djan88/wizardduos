<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

    <head>
        <meta charset="utf-8">

        <?php // force Internet Explorer to use the latest rendering engine available ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title><?php wp_title(''); ?></title>

        <?php // mobile meta (hooray!) ?>
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
        <!-- <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png"> -->
        <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
        <!--[if IE]>
            <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
        <![endif]-->
        <?php // or, set /favicon.ico for IE10 win ?>
        <meta name="msapplication-TileColor" content="#f01d4f">
        <!-- <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/favicon.png"> -->

        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

        <?php // wordpress head functions ?>
        <?php wp_head(); ?>
        <?php // end of wordpress head ?>

        <?php // drop Google Analytics Here ?>
        <?php // end analytics ?>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/jquery-ui.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/jquery.Jcrop.min.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/style-magic.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/animate.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/sweet-alert.css">

    </head>

    <body <?php body_class(); ?>>

        <div id="container" <?php if(current_user_can('subscriber')){ ?>class="subscriber_wrapper"<?php } ?>>

            <?php if(!is_front_page()){ ?>
                <header class="header" role="banner">

                    <div id="inner-header" class="wrap cf">

                        <?php // to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> ?>
                        <!-- <p id="logo" class="h1"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></p> -->

                        <?php // if you'd like to use the site description you can un-comment it below ?>
                        <?php // bloginfo('description'); ?>


                        <!-- <nav role="navigation">
                            <?php wp_nav_menu(array(
                            'container' => false,                           // remove nav container
                            'container_class' => 'menu cf',                 // class of container (should you choose to use it)
                            'menu' => ( 'The Main Menu' ),  // nav name
                            'menu_class' => 'nav top-nav cf',               // adding custom nav class
                            'theme_location' => 'main-nav',                 // where it's located in the theme
                            'before' => '',                                 // before the menu
                        'after' => '',                                  // after the menu
                        'link_before' => '',                            // before each link
                        'link_after' => '',                             // after each link
                        'depth' => 0,                                   // limit the depth of the nav
                            'fallback_cb' => ''                             // fallback function (if there is one)
                            )); ?>

                        </nav> -->
                        <?php if(is_user_logged_in()){ ?>
                            <a class="btn btn_sm btn_warning btn_logout" href="<?php echo home_url(); ?>/wp-login.php?action=logout&amp;_wpnonce=a6cad512ba">Выйти</a>
                            <div class="btn btn_sm btn_warning btn__wizard hidden" >Выполнить</div>
                            <div class="btn btn_sm btn_warning no_second_header no_second_btn hidden">Активирован режим с 2 фото</div>
                            <div class="btn btn_sm btn_warning btn__crop hidden" >Редактировать фото</div>
                            <a href="/" class="btn btn_sm btn_warning to_home">На главную</a>
                            <div class="btn btn_sm btn_warning btn_back invisible"><span>‹</span> Назад</div>
                        <?php }?>

                    </div>

                </header>
            <?php } ?>
