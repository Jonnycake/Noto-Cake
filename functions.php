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
);

foreach ($includes as $include) {
    require_once($include);
}
