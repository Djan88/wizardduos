            <?php if(!is_front_page()){ ?>
                <footer class="footer" role="contentinfo">

                    <div id="inner-footer" class="wrap cf">

                        <nav role="navigation">
                            <?php wp_nav_menu(array(
                            'container' => '',                              // remove nav container
                            'container_class' => 'footer-links cf',         // class of container (should you choose to use it)
                            'menu' => __( 'Footer Links', 'bonestheme' ),   // nav name
                            'menu_class' => 'nav footer-nav cf',            // adding custom nav class
                            'theme_location' => 'footer-links',             // where it's located in the theme
                            'before' => '',                                 // before the menu
                        'after' => '',                                  // after the menu
                        'link_before' => '',                            // before each link
                        'link_after' => '',                             // after each link
                        'depth' => 0,                                   // limit the depth of the nav
                            'fallback_cb' => 'bones_footer_links_fallback'  // fallback function
                            )); ?>
                        </nav>

                        <p class="source-org copyright">&copy; <?php echo date('Y'); ?> <a href="/" class="homelink"><?php bloginfo( 'name' ); ?></a>. Версия 2.3 <span class="supportlink">Поддержка пользователей: <a href="mailto:wizardmachine@yandex.ru">wizardmachine@yandex.ru</a></span></p>

                    </div>

                </footer>
            <?php } ?>

        </div>

        <?php // all js scripts are loaded in library/bones.php ?>
        <?php wp_footer(); ?>
        <script src="<?php bloginfo('template_url'); ?>/library/js/jquery-ui.min.js"></script>
        <script src="<?php bloginfo('template_url'); ?>/library/js/jquery.ui.touch-punch.min.js"></script>
        <script src="<?php bloginfo('template_url'); ?>/library/js/jquery.Jcrop.min.js"></script>
        <script src="<?php bloginfo('template_url'); ?>/library/js/exif.js"></script>
        <script src="<?php bloginfo('template_url'); ?>/library/js/vivus.min.js"></script>
        <script src='<?php bloginfo('template_url'); ?>/library/js/sweet-alert.min.js'></script>
        <script src='<?php bloginfo('template_url'); ?>/library/js/buzz.min.js'></script>
        <script src='<?php bloginfo('template_url'); ?>/library/js/protocols.js'></script>
        <script src='<?php bloginfo('template_url'); ?>/library/js/script-magic.js'></script>
        <?php if(is_front_page()) { ?>
            <script>
                var window_height = jQuery(window).height();
                jQuery('#main').css('height', window_height+'px');
                
            </script>
        <?php } ?>

    </body>

</html> <!-- end of site. what a ride! -->
