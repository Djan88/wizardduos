<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/style/images/favicon.ico">
<title><?php wp_title(''); ?></title>
<!-- Bootstrap core CSS -->
<link href="<?php bloginfo('template_url'); ?>/style/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php bloginfo('template_url'); ?>/style/css/plugins.css" rel="stylesheet">
<link href="<?php bloginfo('template_url'); ?>/style/css/prettify.css" rel="stylesheet">
<link href="<?php bloginfo('template_url'); ?>/style.css" rel="stylesheet">
<link href="<?php bloginfo('template_url'); ?>/style/css/color/orange.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">
<link href="<?php bloginfo('template_url'); ?>/style/type/fontello.css" rel="stylesheet">
<link href="<?php bloginfo('template_url'); ?>/style/type/budicons.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="style/js/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
<?php wp_head(); ?> 
</head>
<body class="full-layout">
<div id="preloader"><div id="status"><div class="spinner"></div></div></div>
<div class="body-wrapper">
  <nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header"> <a class="btn responsive-menu" data-toggle="collapse" data-target=".navbar-collapse"><i></i></a>
      <!-- <div class="navbar-brand text-center"> <a href="index.html"><img src="<?php //bloginfo('template_url'); ?>/style/images/logo.png" alt="" data-src="<?php //bloginfo('template_url'); ?>/style/images/logo.png" data-ret="<?php //bloginfo('template_url'); ?>/style/images/logo@2x.png" class="retina" /></a> </div> -->
      <!-- /.navbar-brand --> 
    </div>
    <!-- /.navbar-header -->
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="current"><a href="#home" class="hint--right" data-hint="К Началу"><i class="budicon-home-1"></i><span>К Началу</span></a></li>
        <?php if(is_user_logged_in()){ ?>
          <li><a href="#nine" class="hint--right nine_link" data-hint="Оцифратор">9 <span> Оцифратор</span></a></li>
        <?php } else { ?>
          <li><a href="#login" class="hint--right" data-hint="Войти"><i class="budicon-lock"></i><span>Войти</span></a></li>
        <?php } ?>
        <?php if(is_user_logged_in()){ ?>
          <?php if(is_user_role('administrator')) { ?>
            <li><a href="/wp-admin" class="hint--right" data-hint="Панель"><i class="budicon-setting"></i><span>Панель</span></a></li>
          <?php } ?>
        <?php } ?>
        <li><a href="#about" class="hint--right" data-hint="Что это такое?"><i class="budicon-book-1"></i><span>Что это такое?</span></a></li>
        <li><a href="#author" class="hint--right" data-hint="Кто это придумал?"><i class="budicon-author"></i><span>Кто это придумал?</span></a></li>
        <li><a href="#contact" class="hint--right" data-hint="Обратная связь"><i class="budicon-mail"></i><span>Обратная связь</span></a></li>
        <?php if(is_user_logged_in()){ ?>
          <li><a href="/wp-login.php?action=logout&_wpnonce=e93c4401c3" class="hint--right" data-hint="Выйти"><i class="budicon-power"></i><span>Выйти</span></a></li>
        <?php } ?>
        <!-- <li><a href="#elsewhere" class="hint--right fancybox-inline" data-hint="Elsewhere" data-fancybox-width="325" data-fancybox-height="220"><i class="icon-heart-empty-1"></i><span>Elsewhere</span></a></li> -->
      </ul>
      <!-- /.navbar-nav --> 
    </div>
    <!-- /.navbar-collapse -->
    <!-- <div id="elsewhere" style="display:none;">
      <h1>Me, Elsewhere</h1>
      <div class="divide20"></div>
      <ul class="social">
        <li><a href="#"><i class="icon-s-twitter"></i></a></li>
        <li><a href="#"><i class="icon-s-facebook"></i></a></li>
        <li><a href="#"><i class="icon-s-instagram"></i></a></li>
        <li><a href="#"><i class="icon-s-flickr"></i></a></li>
        <li><a href="#"><i class="icon-s-pinterest"></i></a></li>
        <li><a href="#"><i class="icon-s-linkedin"></i></a></li>
      </ul>
    </div> -->
    <!-- /#elsewhere --> 
  </nav>
  <!-- /.navbar -->
  
  <section id="home" class="naked">
    <div class="fullscreenbanner-container revolution">
      <div class="fullscreenbanner">
        <ul>
          <li data-transition="fade"> <img src="<?php bloginfo('template_url'); ?>/style/images/dummy.png" alt="slidebg1" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="repeat">
            <h1 class="tp-caption caption large sfb" data-x="center" data-y="center" data-voffset="-25" data-speed="900" data-start="1000" data-endspeed="100" data-easing="Sine.easeOut">"Девяточка"</h1>
            <div class="tp-caption small tp-fade fadeout tp-resizeme" data-x="center" data-y="center" data-voffset="25" data-speed="100"
			data-start="1500"
			data-easing="Power4.easeOut"
			data-splitin="chars"
			data-splitout="chars"
			data-elementdelay="0.03"
			data-endelementdelay="0"
			data-endspeed="100"
			data-endeasing="Power1.easeOut"
			style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;">Психодинамическая трансформационная игра</div>
            <div class="arrow smooth">
              <?php if(is_user_logged_in()){ ?>
                <a href="#nine"><i class="icon-down-open-big"></i></a>
              <?php } else { ?>
                <a href="#login"><i class="icon-down-open-big"></i></a>
              <?php } ?>
            </div>
          </li>
        </ul>
        <div class="tp-bannertimer"></div>
      </div>
      <!-- /.fullscreenbanner --> 
    </div>
    <!-- /.revolution --> 
  </section>
  <!-- /#home -->
  
  <div class="container">
    <?php if(is_user_logged_in()){ ?>
      <section class="nine">
        <div class="box">
          <h2 class="section-title">Оцифратор</h2>
          <div class="row section-description text-center no_padding_wrap">
            <div id="nine" class="col-md-12 no_padding_wrap content">
              <div class="row">
                <h2 class="col-md-12 marakata_heading">
                  <span class="game_numbers">
                  </span>
                  <button type="button" class="btn btn-submit bm0 btn_reset">Сброс</button>
                </h2>
                <div class="col-md-4 col-sm-6 col-xs-9 marakata_sim-wrap marakata_game">
                  <div class="marakata_sim marakata_sim_g marakata_sim-0 marakata_sim_game"></div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row" style="text-align: center;">
                  <button type="button" class="btn btn-submit bm0 btn_game">Начать с начала</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box --> 
      </section>
      <!-- /#login -->
    <?php } else { ?>
      <section id="login" class="login">
        <div class="box">
          <h2 class="section-title pull-left">Введите Ваши логин и пароль</h2>
          <div class="login__form">
            <div>
              <div class="row">
                <div class="">
                  <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                    <p>
                        <label for="user_login">Логин<br />
                        <input type="text" name="log" id="user_login" class="input" value="<?php echo esc_attr($user_login); ?>" size="20" /></label>
                    </p>
                    <p>
                        <label for="user_pass"><?php _e('Password') ?><br />
                        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" /></label>
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
                        <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-submit bm0" value="<?php esc_attr_e('Log In'); ?>" />
                <?php   if ( $interim_login ) { ?>
                        <input type="hidden" name="interim-login" value="1" />
                <?php   } else { ?>
                        <input type="hidden" name="redirect_to" value="/" />
                <?php   } ?>
                <?php   if ( $customize_login ) : ?>
                        <input type="hidden" name="customize-login" value="1" />
                <?php   endif; ?>
                        <input type="hidden" name="testcookie" value="1" />
                    </p>
                    <p class="note_small">У Вас нет доступа к игре <b>"Девяточка"</b>? Заполните <a class="bablosadres" href="#contact">эту форму</a> и мы расскажем как его получить.</p>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box --> 
      </section>
      <!-- /#login -->
    <?php } ?>
    <section id="about">
      <div class="box">
        <h2 class="section-title">Что такое "Девяточка"?</h2>
        <div class="row">
	        <div class="col-md-5 col-md-7 col-sm-12 pull-right">
            <figure class="frame"><img src="<?php bloginfo('template_url'); ?>/style/images/art/nine.jpg" alt="" /></figure>
            <figure class="frame"><img src="<?php bloginfo('template_url'); ?>/style/images/art/nine_patent.jpeg" alt="" /></figure>
          </div>
          <!-- /column -->
          <div>
            <p class="lead">Психодинамическая трансформационная игра для освобождения телесного подсознания</p>
            <p>Подсознание управляет нашими мыслями, желаниями и поступками. Напряжения подсознания могут наследоваться, приобретатьcя в детстве, а затем углубляться на протяжение всей жизни человека, делая эту жизнь, порой, невыносимой и создавая ощущение фатальности событий.</p>
            <p>Полностью избавиться от этих напряжений подсознания невозможно - мы так устроены природой!</p>
            <p>Но мы можем управлять своим подсознанием с помощью технологий, одной из которых и является <b>"Девяточка"</b>.</p>
            <p>Мы можем определить тип, причину появления, локализацию в телесном подсознании и закономерности влияния на нас этих напряжений. Мы можем на какое-то время уменьшить влияние напряжений подсознания на нас при однократной сессии, а при регулярном использовании <b>"Девяточки"</b> - практически нивелировать эти патологические схемы реагирования и получить реальное освобождение от нашего внутреннего рабства.</p>
            <p>Люди осознанные, берущие ответственность за свою жизнь и здоровье на себя, оценят <b>"Девяточку"</b> по достоинству.</p>
            <p>Методика противопоказана тем, кто ждёт чудесного "исцелителя - Айболита", который возьмёт на себя ответственность и разгребет завалы ваших проблем, так как подобные люди не знают - что делать с полученной свободой, так как не привыкли отвечать за свою жизнь, и потом истошно вопят: "... Верните все назад!".</p>
            <p>Мы называем <b>"Девяточку"</b> игрой, так как это снимает барьерное напряжение пользователей, на самом деле <b>"Девяточка"</b> -  есть технология для диагностики и управления напряжениями телесного подсознания человека.</p>
            <h3>Как это работает?</h3>
            <p><b>1.</b> Нейрофизиологический феномен <b>Р300</b> использован в оцифраторе напряжений подсознания. Последовательные серии ритмичного движения пальцев левой руки (правое полушария головного мозга) позволяют оцифровать и алгоритмизировать напряжения телесного подсознания по зонам <b>DSV</b> с учётом динамики текущего состояния клиента.</p>
            <p><b>2.</b> Игровые карты (или игровые поля) представлены динамикой смещения циклической  активности зон <b>DSV</b> уровней 2-3-4.</p>
            <p><b>3.</b> Игровые фишки представлены стандартизированными по размеру органическими носителями Флюэда напряжений телесного подсознания с нанесенными формулами удержания контента.</p>
            <h3>Как играть?</h3>
            <p>Ведущий игры должен пройти специальное обучение у Автора метода Ю.Чикурова либо у лицензированного ученика.</p>
            <p>Участником игры может быть любой желающий, не имеющий противопоказаний (психические заболевания, психопатии и акцентуации личности, состояния алкогольного и наркотического опьянения, случаи тяжёлых соматических заболеваний, лица с явным когнитивным дефицитом).</p>
          </div>
          <!-- /column -->
        </div>
        <!-- /.row -->
        <div class="clearfix"></div>
      </div>
      <!-- /.box --> 
    </section>
    <!-- /#about -->
        <section id="author">
          <div class="box">
            <h2 class="section-title">Кто создал игру "Девяточка"?</h2>
            <div class="row">
              <div class="col-md-7 col-md-push-5 col-sm-12">
                <p class="lead">Доктор Ю. Чикуров, кандидат медицинских наук, специализации в области неврологии, остеопатии и психологии</p>
                <p>Автор книг: "Краниосакральная терапия", "Мягкие мануальные техники", "Висцеральная остеопатия", "Остеопатическое лечение внутрикостных дисфункций", "Эстетическое мануальное моделирование лица и тела", "Биологическое центрирование".</p>
                <p>Основоположник новых прогрессивных направлений — "Биологическое центрирование" и "Практическая психосоматика", автор практических семинаров и онлайн—курсов. Руководитель "Школы доктора Чикурова"</p>
              </div>
              <!-- /column -->
              <div class="col-md-5 col-md-pull-7 col-sm-12">
                <figure class="frame"><img src="<?php bloginfo('template_url'); ?>/style/images/art/chikurov_.jpeg" alt="" /></figure>
              </div>
              <!-- /column -->
            </div>
            <!-- /.row -->
            <div class="clearfix"></div>
          </div>
          <!-- /.box --> 
        </section>
        <!-- /#author -->
    
    <section id="contact">
      <div class="box">
        <h2 class="section-title">Обратная связь</h2>
        <p class="howto">Хотите узнать как получить персональный доступ к <b>"Девяточке"</b>? Воспользуйтесь формой ниже!<br>Тут можно задать вопрос о проекте, узнать где и когда <b>можно поиграть</b> или просто оставить отзыв!</p>
        <div class="divide20"></div>
        <!-- /.services-2 -->
        <div class="divide30"></div>
        <div class="form-container">
          <div class="response alert alert-success"></div>
          <?php echo do_shortcode('[contact-form-7 id="141" title="Без названия"]'); ?>
          <!-- <form class="forms" action="contact/form-handler.php" method="post">
            <fieldset>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-row text-input-row name-field">
                    <label>Имя</label>
                    <input type="text" name="name" class="text-input defaultText required"/>
                  </div>
                  <div class="form-row text-input-row email-field">
                    <label>Email</label>
                    <input type="text" name="email" class="text-input defaultText required email"/>
                  </div>
                  <div class="form-row text-input-row subject-field">
                    <label>Тема</label>
                    <input type="text" name="subject" class="text-input defaultText"/>
                  </div>
                </div>
                <div class="col-sm-6 lp5">
                  <div class="form-row text-area-row">
                    <label>Сообщение</label>
                    <textarea name="message" class="text-area required"></textarea>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="button-row">
                    <input type="submit" value="Отправить сообщение" name="submit" class="btn btn-submit bm0" />
                  </div>
                </div>
              </div>
            </fieldset>
          </form> -->

        </div>
        <!-- /.form-container --> 
        
      </div>
      <!-- /.box --> 
    </section>
    <!-- /#contact -->
    
    <footer class="footer box">
      <p class="pull-left">© 2019 "Девяточка". <a href="http://chikurov.com">Школа Доктора Чикурова</a>.</p>
      <ul class="social pull-right">
        <!-- <li><a href="#"><i class="icon-s-rss"></i></a></li> -->
        <!-- <li><a href="#"><i class="icon-s-twitter"></i></a></li> -->
        <li><a target="_blank" href="https://www.facebook.com/profile.php?id=100012253260685&pnref"><i class="icon-s-facebook"></i></a></li>
        <li><a target="_blank" href="https://www.instagram.com/dr.chikurov/"><i class="icon-s-instagram"></i></a></li>
        <li><a target="_blank" href="https://www.youtube.com/user/ThePractik01/"><i class="icon-s-youtube"></i></a></li>
      </ul>
      <div class="clearfix"></div>
    </footer>
    <!-- /footer --> 
    
  </div>
  <!-- /.container --> 
</div>
<!-- /.body-wrapper --> 
<?php wp_footer(); ?>
<script src="<?php bloginfo('template_url'); ?>/style/js/bootstrap.min.js"></script> 
<script src="<?php bloginfo('template_url'); ?>/style/js/jquery.themepunch.tools.min.js"></script> 
<script src="<?php bloginfo('template_url'); ?>/style/js/classie.js"></script> 
<script src="<?php bloginfo('template_url'); ?>/style/js/plugins.js"></script> 
<script src="<?php bloginfo('template_url'); ?>/style/js/scripts.js"></script>  
<script>
	jQuery.backstretch(["<?php bloginfo('template_url'); ?>/style/images/art/bg3_1.png"]);
</script>
</body>
</html>
