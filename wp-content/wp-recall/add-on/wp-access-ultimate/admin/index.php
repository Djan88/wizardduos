<?php

require_once 'classes/class-wau-history.php';
require_once 'classes/class-wau-access-list.php';
require_once 'classes/class-wau-manager.php';
require_once 'classes/class-wau-user-accounts-settings.php';
require_once 'classes/class-wau-post-accounts-settings.php';
require_once 'classes/class-wau-term-accounts-settings.php';

require_once 'admin-pages.php';
require_once 'admin-actions.php';
require_once 'user-actions.php';
require_once 'post-actions.php';
require_once 'term-actions.php';

add_action('admin_init','wau_admin_scripts',10);
function wau_admin_scripts(){
    wp_enqueue_style('wau-admin-style', rcl_addon_url('admin/assets/css/style.css', __FILE__));
    wp_enqueue_script('wau-admin-script', rcl_addon_url('admin/assets/js/scripts.js', __FILE__));
}
