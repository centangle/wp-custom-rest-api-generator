<?php // Custom Fields & Taxanomies in REST API - Settings Page

// exit if accessed directly

if (!defined('ABSPATH')) {

    exit;

}

// a callback to add_submenu_page fn to display the plugin settings section and fields

function wpcrag_display_settings_page() {

    // check if user is allowed access

    if (!current_user_can('manage_options')) {

        return;

    }

?>

	<div class="wrap">

		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

		<form action="options.php" method="post">

			<?php

                // output security fields

                settings_fields('wpcrag_options');

                // output setting sections

                do_settings_sections('wpcrag');

                // display submit button

                submit_button();

            ?>

		</form>

	</div>

	<?php

}