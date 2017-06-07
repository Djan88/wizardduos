<?php
/*
 Template Name: profile
*/
?>

<?php get_header(); ?>

<div id="content">

    <div id="inner-content" class="wrap cf">

        <div id="main" class="m-all t-2of3 cf home-page-content" role="main">
        <?php if(is_user_logged_in()){ ?>

            <?php if(current_user_can('contributor') || current_user_can('administrator')) {

                $curr_user = wp_get_current_user();

                if($_POST['profile_sent']){

                    $u = array(
                        'ID'            => htmlspecialchars($curr_user->ID, ENT_QUOTES),
                        'first_name'    => htmlspecialchars($_POST['profile_firstname'], ENT_QUOTES),
                        'last_name'     => htmlspecialchars($_POST['profile_lastname'], ENT_QUOTES),
                        'user_email'    => htmlspecialchars($_POST['profile_email'], ENT_QUOTES)
                    );

                    if($_POST['profile_pass1'] || $_POST['profile_pass2']){
                        if($_POST['profile_pass1'] == $_POST['profile_pass2']){
                            $u['user_pass'] = $_POST['profile_pass1'];
                        }else{
                            $pass_err = true;
                        }
                    }

                    $save_result = wp_update_user($u);

                    update_user_meta( $curr_user->ID, 'phonenum', $_POST['profile_phonenum']);

                    $curr_user = wp_get_current_user();

                    if(is_wp_error($save_result)){
                        $p_save_err = true;
                    }else{
                        $p_saved = true;
                    }

                }

                $phonenum = get_user_option('phonenum',$curr_user->ID);
//                print_r($curr_user);
//                print_r($curr_user->user_lastname);
                
//                wp_update_user(array(
//                    'ID' => $curr_user->ID,
//                    'phonenum' => '123'
//                ));

//                update_user_meta( $curr_user->ID, 'phonenum', '123');


                ?>


            <h1 class="page-title" itemprop="headline" style="text-align: center"><?php the_title(); ?></h1>

            <div class="profile">

                <input type="hidden" name="profile_sent" value="1">

                <form action="<?php bloginfo('url'); echo $_SERVER["REQUEST_URI"]; ?>" method="POST" enctype="multipart/form-data" class="profile__group">
                    <span class="profile__title">Личные данные</span>
                    <label class="profile__field">
                        <span class="profile__field-caption">Имя</span>
                        <input type="text" class="profile__input" name="profile_firstname" value="<?=$curr_user->user_firstname?>">
                    </label>
                    <label class="profile__field">
                        <span class="profile__field-caption">Фамилия</span>
                        <input type="text" class="profile__input" name="profile_lastname" value="<?=$curr_user->user_lastname?>">
                    </label>
                    <label class="profile__field">
                        <span class="profile__field-caption">Телефон</span>
                        <input type="tel" class="profile__input"  name="profile_phonenum" value="<?=$phonenum?>">
                    </label>
                    <label class="profile__field">
                        <span class="profile__field-caption">E-mail</span>
                        <input type="email" required="required" class="profile__input" name="profile_email" value="<?=get_user_option('user_email',$curr_user->ID)?>">
                    </label>

                    <span class="profile__title">Сменить пароль</span>
                    <label class="profile__field">
                        <span class="profile__field-caption">Новый пароль</span>
                        <input type="password" class="profile__input" name="profile_pass1">
                    </label>
                    <?=($pass_err ? '<span style="color:#f55">пароли должны совпадать</span>' : '')?>
                    <label class="profile__field">
                        <span class="profile__field-caption">Новый пароль еще&nbsp;раз</span>
                        <input type="password" class="profile__input" name="profile_pass2">
                    </label>

                    <div class="profile__submit-container">
                        <input type="submit" value="Сохранить изменения" class="profile__submit">
                    </div>
                </form>

                <div class="profile__group">
                    <span class="profile__title">Аватар</span>
                    <?php echo do_shortcode('[basic-user-avatars]'); ?>
                </div>

            </div>
                
            <?php } else { ?>
                <div style="text-align: center">Вы получили это письмо, потому, что зарегистрировались на сайте <a href="http://wizardduos.ru/">wizardmachine</a>. Администрация сайта  доводит до вашего сведения информацию о том, что получение доступа на сайт происходит после предварительного обучения пользователя. Обучение будет проходить в виде очного либо дистантного семинара. По всем вопросам обращаться к Роману <a href="mailto:info@chikurov.com">info@chikurov.com</a></div>
            <?php } ?>
        <?php } else { ?>
            <div class="login__form">
                <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                    <p>
                        <label for="user_login"><?php _e('Username') ?><br />
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
                    <!-- <p class="note_small">Что бы получить доступ </p> -->
                    <p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <?php esc_attr_e('Remember Me'); ?></label></p>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Log In'); ?>" />
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
                </form>
            </div>
        <?php } ?>

        </div>

    </div>

</div>


<?php get_footer(); ?>
