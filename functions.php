<?php
$includes = array(
    // Vendor Files
    __DIR__ . '/vendor/autoload.php',

    // Basic Theme Edits
    __DIR__ . '/functions/scripts-styles.php',

    // Useful ACF Usage
    __DIR__ . '/functions/acf/custom-css-js.php',

    // WP-FullCalendar Customizations
    __DIR__ . '/functions/wpfc.php',

    // Custom Post Types
    __DIR__ . '/functions/custom-post-types/post-type-events.php',
    __DIR__ . '/functions/custom-post-types/post-type-projects.php',

    // REST API
    __DIR__ . '/functions/wp-json/jc-event-crud.php',
);

foreach ($includes as $include) {
    require_once($include);
}

register_nav_menus( array(
	'menu-1-logged-in' => esc_html__( 'Primary (Logged In Users)', 'noto-simple' ),
) );

add_filter('wp_nav_menu_args', function($args) {
    if (!isset($args['theme_location'])) return $args;
    if ($args['theme_location'] !== 'menu-1') return $args;

    $modifier = "";
    if (is_user_logged_in()) $modifier = '-logged-in';

    $args['theme_location'] = $args['theme_location'] . $modifier;

    return $args;
});
