<?php

/*

Plugin Name:  WP Custom REST API Generator

Plugin URI:  https://developer.wordpress.org/plugins/wp-custom-rest-api-generator/

Description:  Admin panel for enabling Author Meta, Featured Image, Custom Fields and Taxonomies for all available Post Types in WordPress Rest API

Version:      1.0.5

Author:       Centangle Interactive

Author URI:   https://www.centangle.com/

Text Domain:  wpcrag

Domain Path:  /languages

License:      GPL2

WP Custom REST API Generator is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Custom REST API Generator is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Custom REST API Generator. If not, see License URI:
https://www.gnu.org/licenses/gpl-2.0.html

 */

// exit if accessed directly

if (!defined('ABSPATH')) {

    exit;

}

// import required assets using WordPress Enqueue functions

add_action('admin_enqueue_scripts', 'wpcrag_enqueue_scripts');

function wpcrag_enqueue_scripts()
{

    wp_enqueue_style('wpcrag_fa', plugin_dir_url(__FILE__) . 'includes/css/fontawesome-free-5.6.1-web/css/all.css', array(), '5.6.1');

    wp_enqueue_style('wpcrag_css', plugin_dir_url(__FILE__) . 'includes/css/style.css', array(), '1.0');

    wp_enqueue_script('wpcrag_js', plugin_dir_url(__FILE__) . 'includes/js/main.js', array('jquery'), '1.0', true);

}

require_once ABSPATH . "wp-includes/pluggable.php";

require_once plugin_dir_path(__FILE__) . 'includes/wpcrag_fn_query.php';

if (is_admin()) {

    // include php dependencies

    require_once plugin_dir_path(__FILE__) . 'admin/wpcrag_admin_menu.php';

    require_once plugin_dir_path(__FILE__) . 'admin/wpcrag_settings_page.php';

    require_once plugin_dir_path(__FILE__) . 'admin/wpcrag_settings_register.php';

    require_once plugin_dir_path(__FILE__) . 'admin/wpcrag_settings_callback.php';

}

require_once plugin_dir_path(__FILE__) . 'admin/wpcrag_settings_api_function.php';
