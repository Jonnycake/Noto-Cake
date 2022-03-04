<?php
// Prevents WPFC from modifying the posts query to add post_date condition
add_action('wpfc_before_wp_query', function() {
    remove_filter('posts_where', 'wpfc_temp_filter_where');
});

// Adds our meta query parameters (to use the start/end time of the event as a filter)
add_filter('wpfc_fullcalendar_args', function($args) {
    $args['meta_query'] = array(
        array(
            'key' => 'start_date_time',
            'compare' => '>',
            'value' => $_REQUEST['start'],
        ),
        array(
            'key' => 'end_date_time',
            'compare' => '<',
            'value' => $_REQUEST['end'],
        ),
    );

    return $args;
});


// This is where we can modify individual event fields
add_filter('wpfc_ajax_post', function($item, $post) {
    $start_date = get_field('start_date_time', $post->ID);
    $end_date = get_field('end_date_time', $post->ID);

    $start_date_dt = new DateTime($start_date);
    $end_date_dt = new DateTime($end_date);

    $item['start'] = $start_date_dt->format('Y-m-d\TH:i:s');
    $item['end']   = $end_date_dt->format('Y-m-d\TH:i:s');
    $item['editable'] = get_the_author_ID($post->ID) == get_current_user_id();
    $item['allDay'] = get_field('is_all_day', $post->ID);

    $color = "#aaa";

    $colors = array();

    foreach (get_the_terms($post->ID, 'event_category') as $term) {
        $term_id = (int) $term->term_id;
        $term_color = get_field('color', "event_category_{$term_id}");

        if ($term_color) $colors[] = $term_color;
    }

    if (count($colors)) {
        $color = $colors[0];
    }

    $item['color'] = $color;

    return $item;
}, 10, 2);

// This gives us access to add/remove events from the list before JSON'ifying it
add_filter('wpfc_ajax', function($items) {
    $new_items = array_values(array_filter($items, function($item) {
        if (!isset($item['post_id'])) return false;
        return user_should_access_event($item['post_id']);
    }));

    return $new_items;
});

// Since we don't use the standard content field - we need to update the tooltip
add_filter('wpfc_qtip_content', function($content) {
    if (empty($_REQUEST['post_id'])) return $content;

    $post_id = (int) $_REQUEST['post_id'];

    $content .= get_field('description', $post_id);

    return $content;
});

add_action('wp_ajax_WP_FullCalendar', function() {
    add_filter('option_wpfc_limit', function() {
        return PHP_INT_MAX;
    });
}, 1);
