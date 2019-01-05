<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}woobe_history");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}woobe_history_bulk");

//die();
