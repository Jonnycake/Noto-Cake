<?php
function notocake_enqueue_styles() {
    wp_enqueue_style('bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');

    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/child-style.css',
        array('parent-style'),
        filemtime(__DIR__ . '/../child-style.css')
    );

    wp_enqueue_style(
        'portfolio-style',
        get_stylesheet_directory_uri() . '/assets/css/portfolio.css',
        array('child-style'),
        filemtime(__DIR__ . '/../assets/css/portfolio.css')
    );

    wp_enqueue_style(
        'events-style',
        get_stylesheet_directory_uri() . '/assets/css/events.css',
        array('child-style'),
        filemtime(__DIR__ . '/../assets/css/events.css')
    );


    wp_enqueue_script('bootstra-js', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js');
}
add_action( 'wp_enqueue_scripts', 'notocake_enqueue_styles' );
