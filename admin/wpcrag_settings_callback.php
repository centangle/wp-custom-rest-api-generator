<?php // Custom Fields & Taxanomies in REST API - Settings Callback

// exit if accessed directly

if (!defined('ABSPATH')) {

    exit;

}

// callback: Rest API Section

function wpcrag_callback_section_rest_options() {

    echo '<p>These settings enable you to expose Author Meta, Featured Image, Custom Fields and Taxonomies for all available Post Types in WordPress Rest API.</p>';

}

// callback: checkbox field

function wpcrag_callback_rest_api_fields($callback_args) {

    // Loops through each post type retrived

    $post_types = wpcrag_get_post_types();

    foreach ($post_types as $post_type) {

        global $options;

?>
            <!-- inserts accordion for each post type -->

            <div class="accordion-container">

                <div class="set">

                    <a href="#">

                        <?php

                            // retrives singular name for the post type to display as title

                            $post_type_obj = get_post_type_object($post_type);

                            $post_type_singular_name = $post_type_obj->labels->singular_name;

                            $post_type_capitalized_name = ucwords(strtolower($post_type_singular_name));

                            if ($post_type_capitalized_name == 'Page' || $post_type_capitalized_name == 'Post') {
                            
                                echo '<label style="margin: 1em;"> Default Post Type: ' . $post_type_capitalized_name . '</label>';
                                
                            } else {

                                echo '<label style="margin: 1em;"> Custom Post Type: ' . $post_type_capitalized_name . '</label>';
                            }

                        ?>

                        <i class="fa fa-plus" ></i>

                    </a>

                    <div class="content" style="margin: 0; padding: 1em;">

                        <?php

                            // Author meta Check-box in Plugin UI

                            $post_author_meta = $post_type . '_author_meta';

                            $checked = '';

                            if (is_array($options) && array_key_exists($post_author_meta, $options)) {

                                $checked = 'checked';

                            }

                            echo '<div class="wpcrag_custom_field" style="display: inline-block; margin-top: 1em;"> <input id="wpcrag_options_' . $post_author_meta . '" name="wpcrag_options[' . $post_author_meta . ']" type="checkbox" value="' . $post_author_meta . '"' . $checked . '></div> ';

                            echo '<h4 class="wpcrag_cf_title" style="margin: 0; display: inline-block;"> Author Meta </h4>';
                            
                            // Featured Image Check-box in Plugin UI

                            $post_featured_image = $post_type . '_featured_image';

                            $checked = '';

                            if (is_array($options) && array_key_exists($post_featured_image, $options)) {

                                $checked = 'checked';

                            }

                            echo '<div class="wpcrag_custom_field" style="display: inline-block; margin-top: 1em;"> <input id="wpcrag_options_' . $post_featured_image . '" name="wpcrag_options[' . $post_featured_image . ']" type="checkbox" value="' . $post_featured_image . '"' . $checked . '></div> ';

                            echo '<h4 class="wpcrag_cf_title" style="margin: 0; display: inline-block;"> Featured Image </h4>';

                            echo '<hr class="wpcrag_line_seperator" style="display: block; margin: 1em 0;">';

                            echo '<h4 class="wpcrag_cf_title" style="margin: 0;"> Show Custom Fields </h4>';

                            // retrives each post of a post type currently in the loop
                        
                            $the_query = wpcrag_get_all_posts($post_type);

        ?>
                        <?php if ($the_query->have_posts()): ?>

                            <?php while ($the_query->have_posts()): $the_query->the_post();

                                // retrives custom meta fields for each post of a post type currently in the loop

                                $custom_field_keys = get_post_custom_keys();

                                if (!empty($custom_field_keys) && is_array($custom_field_keys)) {

                                    foreach ($custom_field_keys as $key => $value) {

                                        $valuet = trim($value);

                                        if ('_' == $valuet[0]) {

                                            continue;

                                        }

                                        $post_field_key = $post_type . '_' . $value;

                                        $checked = '';

                                        if (is_array($options) && array_key_exists($post_field_key, $options)) {

                                            $checked = 'checked';

                                        }

                                        echo '<div class="wpcrag_custom_field" style="display: inline-block; margin-top: 1em;"> <input type="checkbox" name="wpcrag_options[' . $post_field_key . ']" value="' . $value . '" ' . $checked . '>';

                                        echo '<label style="display: inline-block; margin: 0 1.5em 0 1em;">' . $value . '</label></div>';

                                    }

                                }

                            endwhile;?>

                        <?php

                            // resets the WP_QUERY to default

                            wp_reset_postdata();

                        endif;

                        ?>

        <?php

        echo '<hr class="wpcrag_line_seperator" style="display: block; margin: 1em 0;">';

        echo '<h4 class="wpcrag_taxonomies_title" style="display: block; margin: 0;"> Show Taxanomies </h4>';

        // retrives taxonomies for each post of a post type currently in the loop

        $taxonomies = get_object_taxonomies($post_type, 'names');

        if ($taxonomies) {

            foreach ($taxonomies as $taxonomy => $taxonomy_value) {

                if ($taxonomy_value == 'post_format') {

                    continue;

                }

                $taxonomies_name = $post_type . '_' . $taxonomy_value;

                $checked = '';

                if (is_array($options) && array_key_exists($taxonomies_name, $options)) {

                    $checked = 'checked';

                }

                // gets the singular name of the taxonomy to display

                $taxonomy_obj = get_taxonomy($taxonomy_value);

                $taxonomy_singular_name = $taxonomy_obj->labels->singular_name;

                echo '<div class="wpcrag_custom_field" style="display: inline-block; margin-top: 1em;"> <input type="checkbox" name="wpcrag_options[' . $taxonomies_name . ']" value="' . $taxonomy_value . '" ' . $checked . '>';

                echo '<label style="display: inline-block;  margin: 0 1.5em 0 1em;">' . $taxonomy_singular_name . '</label></div>';

            }

        }

        ?>

                    </div>

                </div>

            </div>

            <?php

    }
}
