<?php // Custom Fields & Taxanomies in REST API - API Call Function

/********************************
 * wpcrag_settings_api_function.php
 ********************************/

// callback function for register_rest_api for author (meta)

function wpcrag_get_author_meta()
{

    $user_info = get_user_meta(get_the_author_meta('ID'));

    if(! empty($user_info)) {
        $author_meta = (object) ['author_name' => implode($user_info['nickname']),
    
            'author_description' => implode($user_info['description']),
    
            'author_user_level' => (int) implode($user_info['wp_user_level']),
    
            'author_avatar' => get_avatar_url(get_the_author_meta('ID')),
    
        ];
    
        return $author_meta;
    }
}

// callback function for register_rest_api for featured_image

function wpcrag_get_featured_image()
{
    global $post;

    $featured_image = (object) ['size_thumbnail' => get_the_post_thumbnail_url($post->ID, 'thumbnail'),

        'size_medium' => get_the_post_thumbnail_url($post->ID, 'medium'),

        'size_large' => get_the_post_thumbnail_url($post->ID, 'large'),

        'size_full' => get_the_post_thumbnail_url($post->ID, 'full'),

    ];

    return $featured_image ? $featured_image : '';

}

add_action('rest_api_init', function () {

    global $options;

    $post_types = wpcrag_get_post_types();

    // Loops through each post type retrived

    foreach ($post_types as $post_type) {

        // API register for featured image

        $post_author_meta = $post_type . '_author_meta';

        if (is_array($options) && array_key_exists($post_author_meta, $options)) {

            register_rest_field($post_type,

                'author',

                array(

                    'get_callback' => 'wpcrag_get_author_meta',

                    'update_callback' => null,

                    'schema' => null,

                )

            );

        }

        // API register for author meta

        $post_featured_image = $post_type . '_featured_image';

        if (is_array($options) && array_key_exists($post_featured_image, $options)) {

            register_rest_field($post_type,

                'featured_image',

                array(

                    'get_callback' => 'wpcrag_get_featured_image',

                    'update_callback' => null,

                    'schema' => null,

                )

            );

        }

        // retrives each post of a post type currently in the loop

        $the_query = wpcrag_get_all_posts($post_type);

        $posts = $the_query->posts;

        foreach ($posts as $post) {

            $post_id = $post->ID;

            // retrives custom meta fields for each post of a post type currently in the loop

            $custom_field_keys = get_post_custom_keys($post_id);
            
            if ($custom_field_keys) {
                foreach ($custom_field_keys as $key => $field_name) {

                    $valuet = trim($field_name);

                    if ('_' == $valuet[0]) {

                        continue;

                    }

                    // matches value in the db, if found then registers to Rest API

                    $post_field_key = $post_type . '_' . $field_name;

                    if (is_array($options) && array_key_exists($post_field_key, $options)) {

                        register_rest_field($post_type,

                            $field_name,

                            array(

                                'get_callback' => 'wpcrag_get_post_meta',

                                'update_callback' => null,

                                'schema' => null,

                            )

                        );

                    }

                }
            }

        }

        $taxonomies = get_object_taxonomies($post_type, 'names');

        if ($taxonomies) {

            foreach ($taxonomies as $taxonomy => $taxonomy_value) {

                // matches value in the db, if found then registers to Rest API

                $taxonomies_name = $post_type . '_' . $taxonomy_value;

                if (is_array($options) && array_key_exists($taxonomies_name, $options)) {

                    register_rest_field($post_type,

                        $taxonomy_value,

                        array(

                            'get_callback' => 'wpcrag_get_taxonomies',

                            'update_callback' => null,

                            'schema' => null,

                        )

                    );

                }

            }

        }

    }

});

// callback for register_rest_api for custom fields, returns custom fields for each post of a post type

function wpcrag_get_post_meta($object, $field_name, $request) {

    $post_id = $object['id'];

    $custom_field_value = get_post_custom_values($field_name, $post_id);

    return $custom_field_value;

}

// callback for register_rest_api for taxonomies, returns taxnomoies for each post of a post type

function wpcrag_get_taxonomies($object, $taxonomy_value, $request) {

    $post_id = $object['id'];

    if (has_term('', $taxonomy_value, $post_id)) {

        $termsArr = get_terms(array(

            'taxonomy' => $taxonomy_value,

            'hide_empty' => false,

            'object_ids' => $post_id,

        ));

        $array = array();

        $z = 0;

        // loop again to the array to skip the un-wanted attributes from the array

        foreach ($termsArr as $data) {

            // push the slug and name only into the newly created array

            $array[$z]['id'] = $data->term_id;

            $array[$z]['slug'] = $data->slug;

            $array[$z]['name'] = $data->name;
            
            $z++;

        }

        // return the newly cerated array with skipped values

        return $array;

    } else {

        return null;

    }

}