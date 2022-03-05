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

    wp_enqueue_script(
        'jc-event-crud',
        get_stylesheet_directory_uri() . '/assets/js/jc-event-crud.js',
        array(),
        filemtime(__DIR__ . '/../assets/js/jc-event-crud.js')
    );

    wp_localize_script( 'jc-event-crud', 'my_restapi_details', array(
        'rest_url' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' )
    ) );

    wp_enqueue_script('bootstrap-js', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js');

    wp_enqueue_script('jquery-validate', '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js');
}
add_action( 'wp_enqueue_scripts', 'notocake_enqueue_styles' );
