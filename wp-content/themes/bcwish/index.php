<!DOCTYPE html>
<html class="all" lang="en">
<head>
  <meta charset="utf-8">
  <title><?php wp_title(''); ?></title>
  <meta name="viewport" content="user-scalable=no" />
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/fav180.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/img/fav32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/img/fav16.png">
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i|Montserrat:300,400,500,600,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="<?php bloginfo('template_url'); ?>/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="<?php bloginfo('template_url'); ?>/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php bloginfo('template_url'); ?>/lib/animate/animate.min.css" rel="stylesheet">
  <link href="<?php bloginfo('template_url'); ?>/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="<?php bloginfo('template_url'); ?>/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="<?php bloginfo('template_url'); ?>/lib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="<?php bloginfo('template_url'); ?>/css/JCrop.min.css" rel="stylesheet">
  <link href="<?php bloginfo('template_url'); ?>/css/jquery-ui.min.css" rel="stylesheet">
  <link href="<?php bloginfo('template_url'); ?>/css/sweetalert.css" rel="stylesheet">
  <link href="<?php bloginfo('template_url'); ?>/style.css" rel="stylesheet">
  <link href="<?php bloginfo('template_url'); ?>/css/zones_position.css" rel="stylesheet">
  <?php wp_head(); ?>
</head>

<body>
  <!--==========================
  Header
  ============================-->
  <header id="header">
    <button type="button" class="wizard_crop hidden header_btn"><i class="fa fa-crop"></i></button>
    <a href="/" class="wizard_to_start hidden header_btn"><i class="fa fa-arrow-left"></i></a>
    <button type="button" class="wizard_to_what_way hidden header_btn">
      <i class="fa fa-arrow-left"></i>
    </button>
    <button type="button" class="wizard_to_protList hidden header_btn">
      <i class="fa fa-arrow-left"></i>
    </button>
    <button type="button" class="wizard_to_protDiag hidden header_btn">
      <i class="fa fa-arrow-left"></i>
    </button>
    <button type="button" class="wizard_clean_graf hidden header_btn">
      <i class="fa fa-trash"></i>
    </button>
    <button type="button" class="wizard_play header_btn hidden">
      <i class="fa fa-play-circle wizard_start_icon"></i>
    </button>
    <button type="button" class="wizard_stop hidden header_btn" data-toggle="popover" data-placement="bottom" title="Протокол остановлен!" data-content="Выполнение протокола будет приостановлено по окончании текущей фазы. Все данные будут сохранены">
      <span class="wizard_percent">0%</span>
      <i class="fa fa-stop-circle wizard_stop_icon"></i>
    </button>
    <?php if(is_user_logged_in()){ ?>
      <?php 
        $cur_user_id = get_current_user_id();
        $user = get_userdata($cur_user_id);
        $user_date  =  strtotime($user->user_registered);
        $cur_date  =  strtotime("now");
        $date_diff = $cur_date - $user_date;
        if ($date_diff < 259000) {
          $new_registred = 1;
        } else {
          $new_registred = 0;
        }
      ?>
    <?php } ?>
    <?php if(current_user_can('subscriber') && $new_registred == 0) { ?>

    <?php } else if(current_user_can('contributor') || current_user_can('administrator') || (current_user_can('subscriber') && $new_registred == 1)) { ?>
      <div class="wizard_heading float-left">Загрузите первое фото</div>
    <?php } ?>
    <div id="topbar">
      <div class="container">
        <div class="social-links">
          <a target="_blank" href="https://www.instagram.com/dr.chikurov/" class="instagram"><i class="fa fa-instagram"></i></a>
          <a target="_blank" href="https://www.youtube.com/watch?v=nu73-7soe8g&t=191s" class="youtube"><i class="fa fa-youtube"></i></a>
          <a target="_blank" href="https://www.facebook.com/profile.php?id=100012253260685&pnref" class="facebook"><i class="fa fa-facebook"></i></a>
          <a target="_blank" href="https://vk.com/id139677998" class="vk"><i class="fa fa-vk"></i></a>
          <a target="_blank" href="https://chikurov.com" class="site"><i class="fa fa-globe"></i></a>
        </div>
      </div>
    </div>
    <div class="container container_main container container_main_main">

      <div class="logo float-left">
        <!-- Uncomment below if you prefer to use an image logo -->
        <h1 class="text-light"><a href="/" class="to_home scrollto"><span><span class="acent">W</span>IZARD<span class="acent">D</span>UOS</span></a></h1>
        <!-- <a href="#header" class="scrollto"><img src="img/logo.png" alt="" class="img-fluid"></a> -->
      </div>

      <nav class="main-nav float-right d-none d-lg-block">
        <ul>
          <?php if(is_user_logged_in()){ ?>
            <li><a href="/">Программа</a></li>
            <li><a href="/kabinet">Личный кабинет</a></li>
            <?php if(current_user_can('administrator')){ ?>
              <li><a href="/wp-admin">Панель управления</a></li>
            <?php } ?>
          <?php } else { ?>
            <li class="active"><a href="#intro">Вход</a></li>
            <li><a href="#services">Инструкция</a></li>
          <?php } ?>
          <li>
            <?php //echo do_shortcode( '[theme_switcha_select text="Выберите версию"]' ); ?>
          </li>
          <li><a class="mobile-nav-toggle mobile-nav-toggle_open" href="" data-toggle="modal" data-target="#mail">Обратная связь</a></li>
          <li><a target="_blank" href="https://www.youtube.com/watch?v=9XI9Z7kHmmY&t=95s">Обучающее видео</a></li>
          <?php if(is_user_logged_in()){ ?>
            <li><a href="/wp-login.php?action=logout&_wpnonce=0d90a53269">Выход</a></li>
          <?php } ?>
          <li>
            <div class="social-links">
              <a target="_blank" href="https://www.instagram.com/dr.chikurov/" class="instagram"><i class="fa fa-instagram"></i></a>
              <a target="_blank" href="https://www.youtube.com/user/ThePractik01/" class="youtube"><i class="fa fa-youtube"></i></a>
              <a target="_blank" href="https://www.facebook.com/profile.php?id=100012253260685&pnref" class="facebook"><i class="fa fa-facebook"></i></a>
              <a target="_blank" href="https://vk.com/id139677998" class="vk"><i class="fa fa-vk"></i></a>
              <a target="_blank" href="https://chikurov.com" class="site"><i class="fa fa-globe"></i></a>
            </div>
          </li>
        </ul>
      </nav><!-- .main-nav -->
      
    </div>
  </header><!-- #header -->

  <!--==========================
    Intro Section
  ============================-->
  <?php if(is_page(183)) { ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <main id="main" class="kabinet">
        <div class="container container_main">
          <div class="row">
            <div class="col-sm-2 col-xs-12"></div>
            <div class="col-sm-8 col-xs-12">
              <?php the_content(); ?>
            </div>
            <div class="col-sm-2 col-xs-12"></div>
          </div>
        </div>
      </main>

    <?php endwhile; ?>

    <?php else : ?>

        <article id="post-not-found" class="hentry cf">
            <header class="article-header">
              <h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
          </header>
            <section class="entry-content">
              <p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
          </section>
          <footer class="article-footer">
              <p><?php _e( 'This is the error message in the index.php template.', 'bonestheme' ); ?></p>
          </footer>
        </article>

    <?php endif; ?>
  <?php } else { ?>
    <section id="intro" class="clearfix">
      <div class="container container_main h-100">
        <div class="row justify-content-center align-self-center">
          <div class="col-md-12 intro-info order-md-first order-last">
            <?php if(is_user_logged_in()){ ?>
              <?php if(current_user_can('subscriber') && $new_registred == 0){ ?>
                <h2>Доступ к программе закрыт!<br>Оплатите доступ в личном кабинете<span> и пользуйтесь программой целый год!</span></h2>
                <div>
                  <a href="/kabinet" class="btn-get-started">Личный кабинет</a>
                </div>
              <?php } else if (current_user_can('contributor') || current_user_can('administrator') || (current_user_can('subscriber') && $new_registred == 1)) { ?>
                <h2>
                  Программа для коррекции 
                  <br><span>кармических отношений!</span>
                  <?php if (current_user_can('subscriber') && $new_registred == 1) { ?>
                    <br><span style=" color: red; font-size: 20px;">Благодарим за интерес к программе "WizardDuos"! Дарим Вам 3 дня доступа к протоколу "Инверсный"!</span>
                  <?php } ?> 
                </h2>
                <div>
                  <a href="#services" class="btn-get-started scrollto wm_init">Начать</a>
                  <a href="#" class="btn btn-warning wizard_continue hidden">Продолжить</a>
                  <div class="for_desktop">
                    <svg class="steps_icon main_arrow" id="Слой_2" data-name="Слой 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1233 567"><title></title><path d="M44.55,142.88c1.22-.77,2.42-1.54,3.54-2.39a33.42,33.42,0,0,0,3.17-2.75,36.55,36.55,0,0,0,5.2-6.54c1.51-2.37,2.79-4.9,4.11-7.46.65-1.28,1.32-2.56,2.06-3.82a16.17,16.17,0,0,1,2.65-3.52l.34-.32v.42h0c1.58,5.52,3.07,11,5,16.19a53.57,53.57,0,0,0,3.56,7.54,24.52,24.52,0,0,0,5.24,6.48l-.21.32a18,18,0,0,1-6.58-5.78,34.28,34.28,0,0,1-4.18-7.71A47.3,47.3,0,0,1,66,125.19a38.61,38.61,0,0,1-.79-8.64h0l.33.1a22.81,22.81,0,0,0-1.89,3.8l-1.62,4a50.58,50.58,0,0,1-3.87,7.85A27.77,27.77,0,0,1,52.43,139a21.93,21.93,0,0,1-7.73,4.2Z"/><path d="M64.19,123a144.53,144.53,0,0,0-.09,17.41c.05,1.45.18,2.89.27,4.32l.44,4.3c.18,1.43.33,2.86.55,4.27s.43,2.84.72,4.24c2.07,11.24,5.93,22.15,12.36,31.33a59.45,59.45,0,0,0,11.41,12.19l3.39,2.47c1.14.82,2.39,1.46,3.59,2.2.6.36,1.19.74,1.82,1.07l1.89.93,1.91.93a16.67,16.67,0,0,0,1.93.86c10.5,4.29,22,6.25,33.61,6.95l0,1a85.56,85.56,0,0,1-35.12-4.11,62.61,62.61,0,0,1-16-8.52,59.51,59.51,0,0,1-12.59-13.1,65.76,65.76,0,0,1-8.24-16.11,87.51,87.51,0,0,1-4-17.46c-.19-1.48-.3-3-.45-4.44s-.18-3-.28-4.44l-.05-4.44c0-1.48,0-3,.12-4.43A92.41,92.41,0,0,1,63.81,123Z"/></svg>
                    <span class="arrow_title main_arrow_title">Начните работу в <b>"WizardDuos"</b> с загрузки фотографий клиентов.</span>
                  </div>
                  <div class="for_desktop wizard_continue hidden">
                    <svg class="steps_icon second_arrow" id="Слой_2" data-name="Слой 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1233 567"><title></title><path d="M67.43,213.53c-.77-1.22-1.54-2.42-2.39-3.54a31,31,0,0,0-2.76-3.17,36.2,36.2,0,0,0-6.54-5.2c-2.36-1.51-4.9-2.79-7.45-4.11-1.28-.65-2.56-1.32-3.82-2.06A15.91,15.91,0,0,1,41,192.79l-.32-.33h.42c5.52-1.58,11-3.07,16.18-5.06a52.54,52.54,0,0,0,7.55-3.55,24.85,24.85,0,0,0,6.48-5.24l.32.21a17.9,17.9,0,0,1-5.78,6.57,34.53,34.53,0,0,1-7.71,4.19,47.55,47.55,0,0,1-8.35,2.47,39.24,39.24,0,0,1-8.65.79h0l.11-.34A22.38,22.38,0,0,0,45,194.4L49,196a50.58,50.58,0,0,1,7.85,3.87,27.72,27.72,0,0,1,6.74,5.76,22,22,0,0,1,4.21,7.73Z"/><path d="M47.54,193.89a188.92,188.92,0,0,1,23.07-.25c3.85.17,7.69.44,11.53.74l5.75.59,5.74.68a289.1,289.1,0,0,1,45.23,9.6c3.69,1.15,7.39,2.26,11,3.55,1.82.64,3.66,1.22,5.46,1.92l5.41,2.07c7.16,2.89,14.29,5.89,21.15,9.44,1.74.84,3.44,1.74,5.14,2.66s3.4,1.82,5.08,2.77c3.31,2,6.7,3.84,9.91,6a203.83,203.83,0,0,1,18.75,13.46l-.58.8c-6.5-4.05-13-8.13-19.68-11.72-3.31-1.89-6.73-3.57-10.1-5.33l-5.14-2.48c-1.7-.84-3.42-1.66-5.16-2.42l-5.21-2.33-5.26-2.19c-1.74-.76-3.51-1.45-5.28-2.13s-3.53-1.4-5.32-2.05c-7.14-2.6-14.34-5.05-21.62-7.21s-14.64-4.12-22-5.82S100.52,201,93,199.65l-5.64-1-5.65-.86c-3.77-.56-7.55-1.09-11.34-1.52-7.57-.93-15.19-1.64-22.84-2Z"/></svg>
                    <span class="arrow_title second_arrow_title">Есть незавершенная сессия. Хотите продолжить?</span>
                  </div>
                </div>
              <?php } ?>
            <?php } else { ?>
              <h2>
                Программа для коррекции 
                <br><span>кармических отношений!</span>
              </h2>
              <div class="row">
                <div class="col-md-12 login_form bounceInUp wow" data-wow-duration="1.4s">
                  <h3 class="form_heading">Вход</h3>
                  <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' )); ?>" method="post">
                      <p class="login_field">
                          <input type="text" name="log" id="user_login" class="input form-control" value="<?php echo esc_attr($user_login); ?>" placeholder="Логин" size="20" /></label>
                      </p>
                      <p class="login_field">
                          <input type="password" name="pwd" id="user_pass" placeholder="Пароль" class="input form-control" value="" size="20" /></label>
                      </p>
                      <?php
                      /**
                       * Fires following the 'Password' field in the login form.
                       *
                       * @since 2.1.0
                       */
                      do_action( 'login_form' );
                      ?>
                      <p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <?php esc_attr_e('Remember Me'); ?></label></p>
                      <p class="submit">
                          <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large btn-get-started" value="<?php esc_attr_e('Log In'); ?>" />
                          <?php   if ( $interim_login ) { ?>
                              <input type="hidden" name="interim-login" value="1" />
                          <?php   } else { ?>
                              <input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
                          <?php   } ?>
                          <?php   if ( $customize_login ) : ?>
                              <input type="hidden" name="customize-login" value="1" />
                          <?php   endif; ?>
                          <input type="hidden" name="testcookie" value="1" />
                      </p>
                      <p class="note_small">У Вас еще нет учетной записи? <span class="toRegistration">Зарегистрируйтесь</span> в "WizardDuos" и узнайте как получить доступ</p>
                  </form>
                </div>
                <div class="col-md-12 register_form bounceInUp wow hidden" data-wow-duration="1.4s">
                  <h3 class="form_heading">Регистрация</h3>
                  <form id="registerform" action="<?php echo site_url('wp-login.php?action=register'); ?>" method="post">
                      <p class="login_field">
                          <input type="text" name="user_login" id="user_login" class="input form-control" value="" placeholder="Придумайте логин" size="20" style="">
                          </label>
                      </p>
                      <p class="login_field">
                          <input type="email" name="user_email" id="user_email" class="input form-control" value="" placeholder="Ваш email" size="25">
                          </label>
                      </p>
                      <p id="reg_passmail">Подтверждение регистрации будет отправлено email.</p>
                      <br class="clear">
                      <input type="hidden" name="redirect_to" value="">
                      <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large btn-get-started" value="Регистрация"></p>
                      <p class="note_small">У Вас уже есть учетная запись? <span class="toLogin">Войдите</span> в "WizardDuos" используя свои логин и пароль</p>
                  </form>
                </div>
              </div>
            <?php } ?>
            
            <div>
            </div>
          </div>
        </div>
      </div>
      <?php if(!is_user_logged_in()){ ?>
        <a href="#services" class="scroller scrollto"><i class="fa fa-chevron-down"></i></a>
      <?php } ?>
    </section><!-- #intro -->

    <main id="main">

      <?php if(is_user_logged_in()){ ?>
      <!--==========================
        Main Section
      ============================-->
      <section id="services" class="wm_start unopacity section-wh" style="height: 550px;">
        <div class="container container_main">

          <!-- <header class="section-header">
            <h3 class="wizard_heading">Загрузите первое фото</h3>
          </header> -->

          <div class="row">
            <div class="col-md-12 wizard">
              <div class="wizard_returned">
                <?php
                    if($_POST['mci_magic']){
                        $sImage = uploadImageFile();
                        echo '<img src="'.$sImage.'" />';
                    }
                ?>
              </div>
              <div class="machine_screen clearfix">
                <div class="bbody">
                  <!-- upload form -->
                  <form id="upload_form" action="/wizard/" enctype="multipart/form-data" method="post"><!-- hidden crop params -->
                  <input id="x1" name="mci_x1" type="hidden" />
                  <input id="y1" name="mci_y1" type="hidden" />
                  <input id="x2" name="mci_x2" type="hidden" />
                  <input id="y2" name="mci_y2" type="hidden" />
                  <!-- <h2>Выберите изображение</h2> -->
                  <div>
                    <input id="image_file" class="hidden" name="mci_image_file" type="file" />
                    <label class="btn btn-success photo_upload wizard_blue"  data-wow-duration="1.4s" data-wow-delay="0.4s" for="image_file">Выберите файл</label>
                    <img src="<?php bloginfo('template_url'); ?>/img/template_.jpg" alt="template" class="template_load">
                  </div>
                  <div class="error"></div>
                  <div class="step2">
                  <h3>Выделите область для обрезки</h3>
                  <img id="preview" alt="" />
                  <!--<canvas id="preview-canvas" style="border: 3px red solid;/*position: absolute; visibility: hidden; /*left: -20000px*/"></canvas>-->
                  <div class="info hidden"><label>Размер файла</label> <input id="filesize" name="mci_filesize" type="text" />
                  <label>Тип</label> <input id="filetype" name="mci_filetype" type="text" />
                  <label>Разрешение изображения</label> <input id="filedim" name="mci_filedim" type="text" />
                  <label>Ширина</label> <input id="w" name="mci_w" type="text" />
                  <label>Высота</label> <input id="h" name="mci_h" type="text" /></div>
                  <input type="submit" class="btn btn-success crop_photo" value="Редактировать фото" name="mci_magic" />
                  </div>
                  </form>
                </div>
              </div>
              <div class="row wizard_operation">
                <div class="col-sm-1 col-md-1 wizard_prots hidden" id="faq">
                  <div id="faq-list" class="faq-list wow fadeInLeft">
                    <?php if(current_user_can('subscriber') && $new_registred == 1) { ?>
                        <div class="faq_item faq_item_1">ИНВЕРСНЫЙ<span>Повтор</span></div>
                        <div class="faq_item_closed" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" title="Станет доступен после оплаты доступа!">КЛАССИЧЕСКИЙ</div>
                        <div class="faq_item_closed" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" title="Станет доступен после оплаты доступа!">ЖЕНСКИЙ</div>
                        <div class="faq_item_closed" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" title="Станет доступен после оплаты доступа!">МУЖСКОЙ</div>
                    <?php } else if(current_user_can('contributor') || current_user_can('administrator')) { ?>
                      <div class="faq_item faq_item_1">ИНВЕРСНЫЙ<span>Повтор</span></div>
                      <div class="faq_item faq_item_2">КЛАССИЧЕСКИЙ<span>Повтор</span></div>
                      <div class="faq_item faq_item_3">ЖЕНСКИЙ<span>Повтор</span></div>
                      <div class="faq_item faq_item_4">МУЖСКОЙ<span>Повтор</span></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="col-sm-6 col-md-6 wizard_templates hidden wow fadeInLeft" id="template">
                  <img src="<?php bloginfo('template_url'); ?>/img/mw.png" alt="" class="wizard_template wizard_template_1 hidden">
                  <img src="<?php bloginfo('template_url'); ?>/img/ww.png" alt="" class="wizard_template wizard_template_2 hidden">
                  <img src="<?php bloginfo('template_url'); ?>/img/mm.png" alt="" class="wizard_template wizard_template_3 hidden">
                  <img src="<?php bloginfo('template_url'); ?>/img/un.png" alt="" class="wizard_template wizard_template_4 hidden">
                  <div class="ring draggable" id="ring">
                    <div class="zone zone_ring hidden"></div>
                  </div>
                  <!-- <div class="zone zone_movable zone_v0 draggable">V0</div> -->
                  <div class="zone zone_movable zone_v1 draggable">V1</div>
                  <div class="zone zone_movable zone_v2 draggable">V2</div>
                  <div class="zone zone_movable zone_v3 draggable">V3</div>
                  <div class="zone zone_movable zone_v4 draggable">V4</div>
                  <div class="zone zone_movable zone_v5 draggable">V5</div>
                  <div class="zone zone_movable zone_v- draggable">V-</div>

                  <div class="zone zone_movable zone_d2 draggable">D2</div>
                  <div class="zone zone_movable zone_d2_ draggable">D2</div>
                  <div class="zone zone_movable zone_d3 draggable">D3</div>
                  <div class="zone zone_movable zone_d4 draggable">D4</div>
                  <div class="zone zone_movable zone_d5 draggable">D5</div>
                  <div class="zone zone_movable zone_d6 draggable">D6</div>

                  <div class="zone zone_movable zone_s2 draggable">S2</div>
                  <div class="zone zone_movable zone_s2_ draggable">S2</div>
                  <div class="zone zone_movable zone_s3 draggable">S3</div>
                  <div class="zone zone_movable zone_s4 draggable">S4</div>
                  <div class="zone zone_movable zone_s5 draggable">S5</div>
                  <div class="zone zone_movable zone_s6 draggable">S6</div>

                  <div class="zone zone_movable zone_alt_v1 draggable">V1</div>
                  <div class="zone zone_movable zone_alt_v2 draggable">V2</div>
                  <div class="zone zone_movable zone_alt_v3 draggable">V3</div>
                  <div class="zone zone_movable zone_alt_v4 draggable">V4</div>
                  <div class="zone zone_movable zone_alt_v5 draggable">V5</div>
                  <div class="zone zone_movable zone_alt_v- draggable">V-</div>

                  <div class="zone zone_movable zone_alt_d2 draggable">D2</div>
                  <div class="zone zone_movable zone_alt_d2_ draggable">D2</div>
                  <div class="zone zone_movable zone_alt_d3 draggable">D3</div>
                  <div class="zone zone_movable zone_alt_d4 draggable">D4</div>
                  <div class="zone zone_movable zone_alt_d5 draggable">D5</div>
                  <div class="zone zone_movable zone_alt_d6 draggable">D6</div>

                  <div class="zone zone_movable zone_alt_s2 draggable">S2</div>
                  <div class="zone zone_movable zone_alt_s2_ draggable">S2</div>
                  <div class="zone zone_movable zone_alt_s3 draggable">S3</div>
                  <div class="zone zone_movable zone_alt_s4 draggable">S4</div>
                  <div class="zone zone_movable zone_alt_s5 draggable">S5</div>
                  <div class="zone zone_movable zone_alt_s6 draggable">S6</div>

                  <div class="zone zone_movable zone_hidden zone_hidden_1 hidden draggable"></div>
                  <div class="zone zone_movable zone_hidden zone_hidden_2 hidden draggable"></div>
                  <div class="zone zone_movable zone_hidden zone_hidden_3 hidden draggable"></div>
                  <div class="zone zone_movable zone_hidden zone_hidden_4 hidden draggable"></div>
                </div>
                <div class="col-sm-12 col-md-12 wizard_way hidden">
                  <div class="row uploaded_pics_wrap wow bounceInUp back-to-top_2">
                    <div class="uploaded_pics_wrapper uploaded_pics_wrapper_1 draggable_photo wow bounceInUp back-to-top_1" data-wow-duration="1.4s" data-wow-delay="0.4s">
                      <img src="" alt="" class="uploaded_pics uploaded_pics_1">
                    </div>
                    <div class="uploaded_pics_wrapper uploaded_pics_wrapper_2 draggable_photo wow bounceInUp back-to-top_2" data-wow-duration="1.4s" data-wow-delay="0.8s">
                      <img src="" alt="" class="uploaded_pics uploaded_pics_2 ">
                    </div>
                  </div>
                  <button class="btn btn-success btn_prot_choice wow bounceInUp back-to-top_2" data-wow-duration="1.4s" data-wow-delay="1.2s">Выбор протокола</button>
                  <button class="btn btn-success btn_reset btn_rotate_left wow bounceInUp" data-wow-duration="1.4s" data-wow-delay="1.2s"><i class="fa fa-undo"></i></button>
                  
                </div>
              </div>
              <div class="row wizard_main_screen hidden">
                <div class="col-sm-6 wizard_zones">
                  <img src="<?php bloginfo('template_url'); ?>/img/template.png" class="wizard_template" alt="wizard_template">

                </div>
                <div class="col-sm-6">
                  <img src="" class="wizard_returned draggable" alt="wizard_returned">
                </div>
                <div class="col-sm-12">
                  <button type="button" class="wizard_starter_alt btn btn-success">
                    START <i class="fa fa-play-circle wizard_start_icon"></i>
                  </button>
                </div>
              </div>
              <div class="row wizard_diag hidden">
                <div class="col-sm-6 wizard_grafic">
                  <div class="row">
                    <img src="<?php bloginfo('template_url'); ?>/img/knife_alt.png" class="marakata" alt="">
                  </div>
                </div>
                <div class="col-sm-6 wizard_grafic_photo">
                  <img src="" class="wizard_returned" alt="wizard_returned">
                </div>
                <div class="col-sm-12" style="text-align: center;">
                  <div class="btn btn-success btn_prot_choice_fromDiag back-to-top_2">Выбрать протокол</div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>
      <!-- #services -->
      <?php } else { ?>
        <!--==========================
          Steps Section
        ============================-->
        <section id="services">
          <div class="container">

            <header class="section-header">
              <h3>Как это работает?</h3>
              <h4 class="center-block" style="text-align: center;">Пошаговое описание алгоритма работы "WizardDuos"</h4>
            </header>

            <div class="row">
              <div class="col-md-12 steps">
                <div class="steps_item steps_item_1 wow bounceInUp" data-wow-duration="1.4s">
                  <!-- <div class="icon"></div> -->
                  <h4 class="title">Загрузка и коррекция фото</h4>
                  <p class="description">Загрузите фотографии клиентов и при необходимости выполните подрезку. Для этого в "WizardDuos" есть встроенные механизмы</p>
                </div>
                <div class="arrow_box arrow_box_1">
                  <svg class="steps_icon" id="Слой_2" data-name="Слой 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><title>arrow_horizontal</title><path d="M5.91,188.77c14.72.16,29.5-.27,44.25-.52,7.39-.11,14.76-.17,22.14-.34l22.15-.23q44.28-.36,88.58.34c29.54.47,59.07,1.24,88.6,2.55s59.08,3,88.52,6.52l-.07,1.43c-14.79.46-29.55.68-44.32,1s-29.53.54-44.3.68q-44.29.39-88.6-.12c-29.53-.36-59.07-1.06-88.6-2.43-7.38-.31-14.77-.74-22.15-1.14s-14.77-1-22.15-1.47c-14.75-1.27-29.53-2.25-44.22-4.89Z"/><path d="M290.91,124.11a155.6,155.6,0,0,0,9,13.31c1.57,2.13,3.26,4.17,5,6.15s3.5,3.92,5.34,5.78a138.47,138.47,0,0,0,24.61,19.52c8.9,5.66,18.4,10.51,28,15.44,4.81,2.47,9.63,5,14.37,7.79s9.42,5.88,13.24,10h0l1.32.77-1.71.49c-10.4,3-20.66,5.84-30.82,8.93s-20.2,6.34-30,10.1a193.5,193.5,0,0,0-28.39,13.34,92.53,92.53,0,0,0-24.35,19.69l-1.2-.78A55.12,55.12,0,0,1,284.6,241a72.74,72.74,0,0,1,5.93-5.85q3.11-2.78,6.47-5.24a131.1,131.1,0,0,1,29-15.73,175.58,175.58,0,0,1,31.41-9.27q8-1.59,16.16-2.39a120.4,120.4,0,0,1,16.34-.57l-.39,1.26h0A85.67,85.67,0,0,0,375.26,196c-4.93-2.08-10-4-15-6a192.6,192.6,0,0,1-29.52-14.58,104,104,0,0,1-25.36-21.65,82,82,0,0,1-15.77-29.07Z"/></svg>
                </div>
                <div class="arrow_box arrow_box_2">
                  <svg class="steps_icon" id="Слой_2" data-name="Слой 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1233 567"><title>arrow_horizontal_split-02</title><path d="M292.44,283.52c-1.22.76-2.42,1.53-3.54,2.39a30.94,30.94,0,0,0-3.17,2.75,36.2,36.2,0,0,0-5.2,6.54c-1.51,2.37-2.79,4.9-4.11,7.45-.65,1.28-1.32,2.56-2.06,3.82A16,16,0,0,1,271.7,310l-.33.32v-.42h0c-1.58-5.52-3.07-10.94-5.06-16.18a52.54,52.54,0,0,0-3.55-7.55,24.85,24.85,0,0,0-5.24-6.48l.21-.32a18,18,0,0,1,6.57,5.78,34.53,34.53,0,0,1,4.19,7.71,47.4,47.4,0,0,1,2.47,8.36,39.1,39.1,0,0,1,.79,8.64h0l-.34-.1a22.47,22.47,0,0,0,1.9-3.8l1.62-4a50.58,50.58,0,0,1,3.87-7.85,27.91,27.91,0,0,1,5.76-6.74,21.77,21.77,0,0,1,7.73-4.2Z"/><path d="M900.18,396.14c-.76-1.2-1.52-2.37-2.37-3.47a29.84,29.84,0,0,0-2.73-3.09,32.88,32.88,0,0,0-6.55-4.93c-2.38-1.41-4.92-2.56-7.48-3.77-1.29-.59-2.58-1.21-3.84-1.91a14.18,14.18,0,0,1-3.51-2.56l-.32-.34h.42c2.78-.81,5.51-1.68,8.21-2.6s5.36-1.92,8-3a58.5,58.5,0,0,0,7.53-3.83,27.5,27.5,0,0,0,6.5-5.4l.32.2a19.08,19.08,0,0,1-5.76,6.71,36.52,36.52,0,0,1-7.66,4.47,48.41,48.41,0,0,1-8.34,2.81,34.69,34.69,0,0,1-8.73,1h0l.11-.33a19.65,19.65,0,0,0,3.76,1.79c1.3.51,2.64.95,4,1.45a49.31,49.31,0,0,1,7.88,3.52,25.73,25.73,0,0,1,6.79,5.51,20.86,20.86,0,0,1,4.18,7.6Z"/><path d="M274.5,294.62c.79-5.22.53-10.52.82-15.88a46.61,46.61,0,0,1,1.05-8,32,32,0,0,1,2.81-7.67,41.89,41.89,0,0,1,10.36-12.47c1-.88,2-1.66,3.14-2.55a22,22,0,0,1,3.6-2.14,43.15,43.15,0,0,1,7.71-2.72,112.18,112.18,0,0,1,15.88-2.73c5.33-.58,10.66-.94,16-1.18,10.67-.47,21.33-.49,32-.34,21.31.34,42.59,1.38,63.84,2.59s42.49,2.66,63.72,4.15c42.45,3,84.88,6.13,127.33,8.84q31.83,2,63.68,3.49c21.23,1,42.47,1.79,63.71,2.2,42.46.73,85,.52,127.24-3.38,21.09-2,42.13-4.83,62.71-9.66A238,238,0,0,0,970.35,238a130.65,130.65,0,0,0,27.88-14.31c8.53-5.95,16.1-13.4,21-22.47a54.56,54.56,0,0,0,6.46-29.8,41,41,0,0,0-3.9-14.76,44.21,44.21,0,0,0-9-12.41c-7.42-7.26-16.84-12.34-26.59-16.2a172.57,172.57,0,0,0-30.48-8.49,297.49,297.49,0,0,0-31.51-4.15c-10.58-.85-21.19-1.28-31.81-1.36s-21.26.17-31.85.92l-.08-1c10.62-.91,21.27-1.32,31.92-1.43s21.32.17,32,.93a298.54,298.54,0,0,1,31.78,3.84,175.44,175.44,0,0,1,31,8.28c10,3.84,19.78,8.94,27.72,16.52a47.17,47.17,0,0,1,9.79,13.16,44.06,44.06,0,0,1,4.38,15.87,58.27,58.27,0,0,1-1,16.36,56.3,56.3,0,0,1-5.57,15.44c-5.18,9.74-13.14,17.67-22,23.94a134.68,134.68,0,0,1-28.63,15,241.45,241.45,0,0,1-30.72,9.56c-20.83,5.08-42,8.16-63.28,10.33s-42.57,3.25-63.88,3.84q-16,.43-32,.47t-32-.3c-21.3-.4-42.59-1.16-63.87-2.16s-42.53-2.32-63.77-3.74c-42.48-2.8-84.9-6.38-127.31-9.79-21.2-1.7-42.4-3.42-63.62-4.89S389,242.5,367.8,241.91c-10.62-.28-21.24-.38-31.83,0-5.3.18-10.59.47-15.85,1a109.52,109.52,0,0,0-15.57,2.48,40.5,40.5,0,0,0-7.35,2.49,19.49,19.49,0,0,0-3.27,1.89c-1,.77-2.09,1.6-3.09,2.43a39.72,39.72,0,0,0-10.13,11.72,30.52,30.52,0,0,0-2.8,7.23,46.43,46.43,0,0,0-1.15,7.75c-.38,5.25-.32,10.64-1.27,15.95Z"/><path d="M1029.23,177.43c.31,5.93.29,11.77.11,17.64s-.52,11.72-1,17.57a317.68,317.68,0,0,1-5.15,34.84,245.8,245.8,0,0,1-9.58,34,189.65,189.65,0,0,1-15,32A143.43,143.43,0,0,1,977,341.67,119,119,0,0,1,948.66,363a125.26,125.26,0,0,1-16.18,7.25c-1.38.52-2.79.94-4.19,1.41l-2.1.7-2.13.59c-1.42.38-2.83.81-4.27,1.16l-4.31,1a142.82,142.82,0,0,1-17.43,2.48,158.25,158.25,0,0,1-17.55.48v-1a161.9,161.9,0,0,0,34.38-4.59,138,138,0,0,0,16.44-5.27,122.8,122.8,0,0,0,15.48-7.52,122.29,122.29,0,0,0,26.84-21.22,145.9,145.9,0,0,0,11-13.14,151.77,151.77,0,0,0,9.39-14.39,192.5,192.5,0,0,0,14.41-31.27,260.5,260.5,0,0,0,9.54-33.22,313.11,313.11,0,0,0,5.63-34.21,316.07,316.07,0,0,0,2.12-34.6Z"/></svg>
                </div>
                <div class="arrow_box arrow_box_3">
                  <svg class="arrow_box" version="1.1" id="Слой_2_1_" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><title>arrow_horizontal</title><path d="M391.6,190.8c-14.7-0.2-29.5,0.3-44.2,0.5c-7.4,0.1-14.8,0.2-22.1,0.3l-22.1,0.2c-29.5,0.2-59,0.1-88.6-0.3
                    c-29.5-0.5-59.1-1.2-88.6-2.6s-59.1-3-88.5-6.5l0.1-1.4c14.8-0.5,29.5-0.7,44.3-1s29.5-0.5,44.3-0.7c29.5-0.3,59.1-0.2,88.6,0.1
                    c29.5,0.4,59.1,1.1,88.6,2.4c7.4,0.3,14.8,0.7,22.2,1.1s14.8,1,22.1,1.5c14.8,1.3,29.5,2.2,44.2,4.9L391.6,190.8z"/><path d="M106.6,255.4c-2.8-4.6-5.8-9-9-13.3c-1.6-2.1-3.3-4.2-5-6.1s-3.5-3.9-5.3-5.8c-7.4-7.4-15.7-14-24.6-19.5
                    c-8.9-5.7-18.4-10.5-28-15.4c-4.8-2.5-9.6-5-14.4-7.8s-9.4-5.9-13.2-10l0,0l-1.3-0.8l1.7-0.5c10.4-3,20.7-5.8,30.8-8.9
                    s20.2-6.3,30-10.1c9.8-3.7,19.3-8.1,28.4-13.3c9.2-5.1,17.4-11.8,24.4-19.7l1.2,0.8c-2.4,5-5.5,9.6-9.3,13.6c-1.9,2.1-3.8,4-5.9,5.9
                    c-2.1,1.9-4.2,3.6-6.5,5.2c-9,6.4-18.7,11.7-29,15.7c-10.2,4.1-20.7,7.2-31.4,9.3c-5.3,1.1-10.7,1.9-16.2,2.4
                    c-5.4,0.6-10.9,0.8-16.3,0.6l0.4-1.3l0,0c4.5,2.8,9.3,5.3,14.3,7.2c4.9,2.1,10,4,15,6c10.2,4,20.1,8.9,29.5,14.6
                    c9.6,5.8,18.1,13.1,25.4,21.6c7.2,8.5,12.5,18.4,15.8,29.1L106.6,255.4z"/>
                  </svg>
                </div>
                <div class="arrow_box arrow_box_4">
                  <svg class="steps_icon" version="1.1" id="Слой_2_1_" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><title>arrow_horizontal</title><path d="M199.8,6.9c-0.2,14.7,0.3,29.5,0.5,44.2c0.1,7.4,0.2,14.8,0.3,22.1l0.2,22.2c0.2,29.5,0.1,59-0.3,88.6
                    c-0.5,29.5-1.2,59.1-2.6,88.6s-3,59.1-6.5,88.5l-1.4-0.1c-0.5-14.8-0.7-29.5-1-44.3s-0.5-29.5-0.7-44.3c-0.3-29.5-0.2-59.1,0.1-88.6
                    c0.4-29.5,1.1-59.1,2.4-88.6c0.3-7.4,0.7-14.8,1.1-22.2s1-14.8,1.5-22.2c1.3-14.8,2.2-29.5,4.9-44.2L199.8,6.9z"/><path d="M264.4,291.9c-4.6,2.8-9,5.8-13.3,9c-2.1,1.6-4.2,3.3-6.1,5s-3.9,3.5-5.8,5.3c-7.4,7.4-14,15.7-19.5,24.6
                    c-5.7,8.9-10.5,18.4-15.4,28c-2.5,4.8-5,9.6-7.8,14.4s-5.9,9.4-10,13.2l0,0l-0.8,1.3l-0.5-1.7c-3-10.4-5.8-20.7-8.9-30.8
                    s-6.3-20.2-10.1-30c-3.7-9.8-8.1-19.3-13.3-28.4c-5.1-9.2-11.8-17.4-19.7-24.4l0.8-1.2c5,2.4,9.6,5.5,13.6,9.3
                    c2.1,1.9,4,3.8,5.9,5.9c1.9,2.1,3.6,4.2,5.2,6.5c6.4,9,11.7,18.7,15.7,29c4.1,10.2,7.2,20.7,9.3,31.4c1.1,5.3,1.9,10.7,2.4,16.2
                    c0.6,5.4,0.8,10.9,0.6,16.3l-1.3-0.4l0,0c2.8-4.5,5.3-9.3,7.2-14.3c2.1-4.9,4-10,6-15c4-10.2,8.9-20.1,14.6-29.5
                    c5.8-9.6,13.1-18.1,21.6-25.4c8.5-7.2,18.4-12.5,29.1-15.8L264.4,291.9z"/>
                  </svg>
                </div>
                <div class="arrow_box arrow_box_5">
                  <svg class="steps_icon" id="Слой_2" data-name="Слой 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><title>arrow_horizontal</title><path d="M5.91,188.77c14.72.16,29.5-.27,44.25-.52,7.39-.11,14.76-.17,22.14-.34l22.15-.23q44.28-.36,88.58.34c29.54.47,59.07,1.24,88.6,2.55s59.08,3,88.52,6.52l-.07,1.43c-14.79.46-29.55.68-44.32,1s-29.53.54-44.3.68q-44.29.39-88.6-.12c-29.53-.36-59.07-1.06-88.6-2.43-7.38-.31-14.77-.74-22.15-1.14s-14.77-1-22.15-1.47c-14.75-1.27-29.53-2.25-44.22-4.89Z"/><path d="M290.91,124.11a155.6,155.6,0,0,0,9,13.31c1.57,2.13,3.26,4.17,5,6.15s3.5,3.92,5.34,5.78a138.47,138.47,0,0,0,24.61,19.52c8.9,5.66,18.4,10.51,28,15.44,4.81,2.47,9.63,5,14.37,7.79s9.42,5.88,13.24,10h0l1.32.77-1.71.49c-10.4,3-20.66,5.84-30.82,8.93s-20.2,6.34-30,10.1a193.5,193.5,0,0,0-28.39,13.34,92.53,92.53,0,0,0-24.35,19.69l-1.2-.78A55.12,55.12,0,0,1,284.6,241a72.74,72.74,0,0,1,5.93-5.85q3.11-2.78,6.47-5.24a131.1,131.1,0,0,1,29-15.73,175.58,175.58,0,0,1,31.41-9.27q8-1.59,16.16-2.39a120.4,120.4,0,0,1,16.34-.57l-.39,1.26h0A85.67,85.67,0,0,0,375.26,196c-4.93-2.08-10-4-15-6a192.6,192.6,0,0,1-29.52-14.58,104,104,0,0,1-25.36-21.65,82,82,0,0,1-15.77-29.07Z"/></svg>
                </div>
                <div class="arrow_box arrow_box_6">
                  <svg class="steps_icon" id="Слой_2" data-name="Слой 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1233 567"><title>arrow_long-02-03</title><path d="M866.18,301.14c-.77-1.22-1.54-2.42-2.39-3.54a32.14,32.14,0,0,0-2.76-3.17,36.15,36.15,0,0,0-6.54-5.19c-2.36-1.51-4.9-2.79-7.45-4.11-1.28-.66-2.56-1.33-3.82-2.07a16.17,16.17,0,0,1-3.52-2.65l-.32-.34h.42c5.52-1.58,11-3.07,16.18-5.05a53.63,53.63,0,0,0,7.55-3.55,25.26,25.26,0,0,0,6.48-5.24l.32.2a18,18,0,0,1-5.78,6.58,34.49,34.49,0,0,1-7.71,4.18,46.57,46.57,0,0,1-8.36,2.47,38.51,38.51,0,0,1-8.64.79h0l.11-.33a24,24,0,0,0,3.79,1.9l4,1.61a50.64,50.64,0,0,1,7.85,3.88,27.47,27.47,0,0,1,6.74,5.75,22,22,0,0,1,4.21,7.73Z"/><path d="M850.93,281.62c6.35-1.28,12.76-2.24,19.19-3.06s12.87-1.45,19.33-1.95c12.91-1,25.86-1.51,38.8-1.81s25.88-.37,38.8-.51,25.84-.28,38.71-.84,25.73-1.42,38.43-3.2,25.27-4.42,37.25-8.73A105.17,105.17,0,0,0,1114.36,243a93.27,93.27,0,0,0,23-29.87,138,138,0,0,0,11.69-36.35c1.18-6.3,2-12.67,2.61-19.07.29-3.2.53-6.4.7-9.61.09-1.61.17-3.22.22-4.82l.08-2.4,0-2.4a151.69,151.69,0,0,0-1.32-19.17,133.53,133.53,0,0,0-3.82-18.82,105.24,105.24,0,0,0-44.45-60.62,144.63,144.63,0,0,0-34.73-16.48A205.44,205.44,0,0,0,1030.64,15c-6.4-.82-12.83-1.36-19.27-1.66a191.92,191.92,0,0,0-19.34,0l-.06-.78a193.8,193.8,0,0,1,19.43-.23c6.48.23,12.94.7,19.38,1.47a200.93,200.93,0,0,1,38,8,146.46,146.46,0,0,1,35.26,16.43,107.35,107.35,0,0,1,45.71,61.47,136.06,136.06,0,0,1,4,19.13,154.9,154.9,0,0,1,1.48,19.49l0,2.44,0,2.45c0,1.63-.09,3.25-.15,4.87-.12,3.25-.32,6.5-.56,9.74-.52,6.48-1.26,13-2.37,19.38a141.39,141.39,0,0,1-11.44,37.44,95.4,95.4,0,0,1-23.65,31.41,97.83,97.83,0,0,1-16.19,11.37,120,120,0,0,1-18,8.14,183.12,183.12,0,0,1-38.15,8.88,384.16,384.16,0,0,1-38.87,2.93c-25.94.72-51.79-.09-77.63,0s-51.67,1-77.21,4.94Z"/></svg>
                </div>
                <div class="steps_item steps_item_2 wow bounceInUp" data-wow-duration="1.4s">
                  <!-- <div class="icon"></div> -->
                  <h4 class="title">Проведите диагностику</h4>
                  <p class="description">Перемещая фото перцептивно определите ее позицию. Второе фото будет перемещаться в ответ, асинхронно</p>
                </div>
                <div class="steps_item steps_item_3 wow bounceInUp" data-wow-duration="1.4s">
                  <!-- <div class="icon"></div> -->
                  <h4 class="title">Если это не первая сессия</h4>
                  <p class="description">После работы по протоколу "Инверсный" вторым этапом идет более глубокая проработка. Выбор протокола зависит от пола</p>
                </div>
                <div class="steps_item steps_item_4 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
                  <!-- <div class="icon"></div> -->
                  <h4 class="title">Если это первая сессия</h4>
                  <p class="description">Начните работу с протокола "Инверсный". Это важно при проработке взаимоотношений. В дальнейшем можно будет работать по другим протоколам</p>
                </div>
                <div class="steps_item steps_item_5 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
                  <!-- <div class="icon"></div> -->
                  <h4 class="title">Подготовка</h4>
                  <p class="description">Поместите калибровочное кольцо так чтобы обе фотографии оказались в нем целиком. Размер кольца можно менять потянув за зеленые точки. Перенесите все зоны с шаблонного изображения на фото клиентов.</p>
                </div>
                <div class="steps_item steps_item_6 wow bounceInUp" data-wow-delay="0.2s" data-wow-duration="1.4s">
                  <!-- <div class="icon"></div> -->
                  <h4 class="title">Выполнение протокола</h4>
                  <p class="description">После переноса зон запустите выполнение протокола. Программа выполняется 25 — 35 мин. В процессе работы нежелательно выключать устройство и переходить на другие вкладки браузера. После выполнения протокола Вам будет доступно два варианта действий</p>
                </div>
                <div class="steps_item steps_item_7 wow bounceInUp" data-wow-delay="0.2s" data-wow-duration="1.4s">
                  <!-- <div class="icon"></div> -->
                  <h4 class="title">Завершение протокола</h4>
                  <p class="description">Когда программа выполнится на экране появится модальное окно с сответствующим сообщением и кнопками "Завершить" и "Продолжить сессию". Клинув на кнопку "Завершить" Вы переместитесь на главную страницу и сможете выйти из программы или начать работу с другими клиентами. Кликнув на кнопку "Продолжить сессию" Вы можете продолжить работу с текущими клиентами по другому протоколу или повторить уже выбранный. При повторе протокола не надо заново проводить каллибровку и расставляться зоны. Вы просто нажимаете "Повтор" и запускаете выполнение программы.</p>
                </div>
              </div>
            </div>

          </div>
        </section><!-- #services -->
      <!--==========================
        Pricing Section
      ============================-->
      <section id="pricing" class="wow fadeInUp section-wh">
        <div class="container">
          <header class="section-header">
            <h3>Сколько стоит "WizardDuos"?</h3>
          </header>
          <div class="row flex-items-xs-middle flex-items-xs-center">
            <!-- Basic Plan  -->
            <div class="col-sm-12 col-lg-2"></div>
            <div class="col-sm-6 col-lg-4">
              <div class="card">
                <div class="card-header">
                  <h3><span class="currency"></span>35 тыс<span class="period">₽/год</span></h3>
                </div>
                <div class="card-block">
                  <h4 class="card-title"> 
                    Первичное подключение
                  </h4>
                  <ul class="list-group">
                    <li class="list-group-item">Если Вы впервые подключаетесь к программе <b>"WizardDuos"</b>.В стоимость включена инструкция в PDF и техническая поддержка специалистом 7 дней в неделю на год.</li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- Regular Plan  -->
            <div class="col-sm-6 col-lg-4">
              <div class="card">
                <div class="card-header">
                  <h3><span class="currency"></span>25 тыс<span class="period">₽/год</span></h3>
                </div>
                <div class="card-block">
                  <h4 class="card-title"> 
                    Продление доступа
                  </h4>
                  <ul class="list-group">
                    <li class="list-group-item">Начиная со второго года пользования <b>"WizardDuos"</b> Вам доступна скидка. Сейчас и далее Вы можете оплатить доступ на 30% дешевле! Даже если Вы некоторое время не продлевали доступ</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-2"></div>
          </div>
        </div>
      </section><!-- #pricing -->

      <?php } ?>
    </main>
  <?php }?>


  <!--==========================
    Footer
  ============================-->
  <footer id="footer" class="section-bg">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-sm-8">
            <div class="row">
              <div class="col-sm-12">
                <div class="footer-info">
                  <h3><span class="acent">W</span>izard<span class="acent">D</span>uos</h3>
                  <p>Проведите диагностику смещая любое фото, а затем выберите протокол!</p><p><b>Инверсный протокол</b>  -  любые позиции фото людей любого пола по отношению друг к другу</p>
                  <p><b>Классический протокол</b>  -  фото мужчины слева , фото женщины справа</p>
                  <p><b>Мужской протокол</b>  -  фото более старшего мужчины над фото более младшего мужчины</p>
                  <p><b>Женский протокол</b>  -  фото более младшей женщины над фото более старше женщины</p>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="col-sm-4">
            <h4>Новости школы</h4>
            <p>Подпишитесь на новости <b>"Школы Доктора Чикурова"</b> и <b>"WizardDuos"</b> в любой из социальных сетей. Будет интересно, гарантируем!</p>
            <div class="social-links">
              <a target="_blank" href="https://www.instagram.com/dr.chikurov/" class="instagram"><i class="fa fa-instagram"></i></a>
              <a target="_blank" href="https://www.youtube.com/user/ThePractik01/" class="youtube"><i class="fa fa-youtube"></i></a>
              <a target="_blank" href="https://www.facebook.com/profile.php?id=100012253260685&pnref" class="facebook"><i class="fa fa-facebook"></i></a>
              <a target="_blank" href="https://vk.com/id139677998" class="vk"><i class="fa fa-vk"></i></a>
              <a target="_blank" href="https://chikurov.com" class="site"><i class="fa fa-globe"></i></a>
            </div>
          </div> -->
          <div class="col-sm-4">
            <div class="form">
              <h4>Есть вопрос? Есть ответ!</h4>
              <p>У Вас остались вопросы? Нужна помощь в освоениии <b>"WizardDuos"</b>? Хотите сказать нам спасибо? Воспользуйтесь формой ниже. Вам оперативно ответят!<br><button class="form_triger" data-toggle="modal" data-target="#mail"><i class="fa fa-envelope"></i></button></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer><!-- #footer -->

  <!-- <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a> -->
  <!-- Uncomment below i you want to use a preloader -->
  <!-- <div id="preloader"></div> -->
  <?php wp_footer(); ?>
  <!-- JavaScript Libraries -->
  <script src="<?php bloginfo('template_url'); ?>/js/jquery-ui.min.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/jquery.ui.touch-punch.min.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/lib/easing/easing.min.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/lib/mobile-nav/mobile-nav.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/lib/wow/wow.min.js"></script>
  <!-- <script src="<?php //bloginfo('template_url'); ?>/lib/waypoints/waypoints.min.js"></script> -->
  <!-- <script src="<?php //bloginfo('template_url'); ?>/lib/counterup/counterup.min.js"></script> -->
  <!-- <script src="<?php //bloginfo('template_url'); ?>/lib/owlcarousel/owl.carousel.min.js"></script> -->
  <script src="<?php bloginfo('template_url'); ?>/lib/isotope/isotope.pkgd.min.js"></script>
  <!-- <script src="<?php //bloginfo('template_url'); ?>/lib/lightbox/js/lightbox.min.js"></script> -->
  <!-- Contact Form JavaScript File -->
  <!-- <script src="<?php //bloginfo('template_url'); ?>/contactform/contactform.js"></script> -->

  <!-- Template Main Javascript File -->
  <script src="<?php bloginfo('template_url'); ?>/js/main.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/JCrop.min.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/exif.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/sweetalert.min.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/vivus.min.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/howler.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/protocols.js"></script>
  <script src="<?php bloginfo('template_url'); ?>/js/script.js"></script>
  
  <div class="modal fade" id="mail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Обратная связь</h4>
        </div>
        <div class="modal-text">
          <?php echo do_shortcode('[contact-form-7 id="262" title="Обратная связь"]')?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
