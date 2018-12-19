<?php // Custom Fields & Taxanomies in REST API - Register Settings

// exit if accessed directly

if (!defined('ABSPATH')) {

    exit;

}

// register settings & add plugin section and fields

function wpcrag_register_settings()
{

    register_setting(

        'wpcrag_options',

        'wpcrag_options',

        'wpcrag_callback_validate_options'

    );

    add_settings_section(

        'wpcrag_section_rest_options',

        '',

        'wpcrag_callback_section_rest_options',

        'wpcrag'

    );

    add_settings_field(

        'check_post_types',

        '',

        'wpcrag_callback_rest_api_fields',

        'wpcrag',

        'wpcrag_section_rest_options',

        ['id' => 'check_post_types']

    );

}

add_action('admin_init', 'wpcrag_register_settings');
