<?php

global $wpdb;

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
$collate = '';

if ( $wpdb->has_cap( 'collation' ) ) {
    if ( ! empty( $wpdb->charset ) ) {
        $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
    }
    if ( ! empty( $wpdb->collate ) ) {
        $collate .= " COLLATE $wpdb->collate";
    }
}

/*dbDelta( 
    "CREATE TABLE IF NOT EXISTS ". WP_PREFIX . "wau_accounts (
        account_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        account_name VARCHAR(50) NOT NULL,
        account_desc TEXT NOT NULL,
        account_type VARCHAR(20) NOT NULL,
        account_status TINYINT(1) UNSIGNED NOT NULL,
        is_prolong TINYINT(1) UNSIGNED NOT NULL,
        parent_id BIGINT(20) UNSIGNED NOT NULL,
        PRIMARY KEY  account_id (account_id),
        KEY account_type (account_type),
        KEY account_status (account_status),
        KEY parent_id (parent_id)
    ) $collate;" 
);

dbDelta( 
    "CREATE TABLE IF NOT EXISTS ". WP_PREFIX . "wau_tariffs (
        tariff_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        account_id BIGINT(20) UNSIGNED NOT NULL,
        tariff_name VARCHAR(50) NOT NULL,
        tariff_desc TEXT NOT NULL,
        tariff_price VARCHAR(20) NOT NULL,
        tariff_seq MEDIUMINT(7) UNSIGNED NOT NULL,
        is_best TINYINT(1) UNSIGNED NOT NULL,
        access_time INT(20) UNSIGNED NOT NULL,
        PRIMARY KEY  tariff_id (tariff_id),
        KEY account_id (account_id)
    ) $collate;" 
);

dbDelta( 
    "CREATE TABLE IF NOT EXISTS ". WP_PREFIX . "wau_access (
        access_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        account_id BIGINT(20) UNSIGNED NOT NULL,
        access_time INT(20) UNSIGNED NOT NULL,
        access_date DATETIME NOT NULL,
        PRIMARY KEY  access_id (access_id),
        KEY user_id (user_id),
        KEY account_id (account_id),
        KEY access_date (access_date)
    ) $collate;" 
);

dbDelta( 
    "CREATE TABLE IF NOT EXISTS ". WP_PREFIX . "wau_payments (
        payment_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        account_name VARCHAR(50) NOT NULL,
        tariff_price VARCHAR(20) NOT NULL,
        access_time INT(20) UNSIGNED NOT NULL,
        payment_date DATETIME NOT NULL,
        PRIMARY KEY  payment_id (payment_id),
        KEY user_id (user_id),
        KEY payment_date (payment_date)
    ) $collate;" 
);

dbDelta( 
    "CREATE TABLE IF NOT EXISTS ". WP_PREFIX . "wau_meta (
        meta_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        object_id BIGINT(20) UNSIGNED NOT NULL,
        object_type VARCHAR(75) NOT NULL,
        meta_key VARCHAR(75) NOT NULL,
        meta_value LONGTEXT NOT NULL,
        PRIMARY KEY  meta_id (meta_id),
        KEY object_id (object_id),
        KEY object_type (object_type),
        KEY meta_key (meta_key)
    ) $collate;" 
);*/

