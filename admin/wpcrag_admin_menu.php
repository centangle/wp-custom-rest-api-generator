<?php // Custom Fields & Taxanomies in REST API - Admin Menu

// exit if accessed directly

if (!defined('ABSPATH')) {

    exit;

}

// add sub-level administrative menu

function wpcrag_add_sublevel_menu()
{

    add_submenu_page(

        'options-general.php',

        'WP Custom REST API Generator',

        'WP Custom REST API Generator',

        'manage_options',

        'wpcrag',

        'wpcrag_display_settings_page'

    );

}

add_action('admin_menu', 'wpcrag_add_sublevel_menu');
