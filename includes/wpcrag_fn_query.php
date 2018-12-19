<?php
/* Commonly accessed function */

function wpcrag_get_post_types() {
    // Get all post types

    $args = array(

        'public' => true, // only get publically accessable post types

        '_builtin' => false, // remove builtin post types

    );

    // generate post type list

    $post_types = get_post_types($args, 'names');

    // add built-in 'post and page' post type

    $post_types['post'] = 'post';

    $post_types['page'] = 'page';

    return $post_types;

}

// default plugin options

function wpcrag_options_default() {

    return array(

        'check_post_types' => false,

    );

}

// get options from database

$options = get_option('wpcrag_options', wpcrag_options_default());


// get all posts of every post type

function wpcrag_get_all_posts($post_type) {

    $args = array(

        'post_type' => $post_type,

        'posts_per_page' => -1,

    );

    // retrives each post of a post type currently in the loop

    $the_query = new WP_Query($args);

    return $the_query;

}