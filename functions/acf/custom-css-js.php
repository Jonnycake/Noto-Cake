<?php
function jcake_include_custom_css()
{
    if (get_field('jcake_custom_css')) {
        ?>
        <style type="text/css">
        <?= get_field('jcake_custom_css') ?>
        </style>
        <?php
    }
}
add_action('wp_head', 'jcake_include_custom_css');

function jcake_include_custom_javascript()
{
    if (get_field('jcake_custom_js')) {
        ?>
        <script type="text/javascript">
        <?= get_field('jcake_custom_js') ?>
        </script>
        <?php
    }
}
add_action('wp_footer', 'jcake_include_custom_javascript');
