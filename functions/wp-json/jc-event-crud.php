<?php
function jc_event_crud(WP_REST_Request $request)
{
    $editor_or_admin = current_user_can('editor');
    $editor_or_admin |= current_user_can('administrator');

    switch(strtolower($request->get_method())) {
        case 'get':
            return jc_get_events();

        case 'post':
            if (!$editor_or_admin) return false;

            return jc_create_event($request->get_json_params());

        case 'put':
        case 'patch':
            if (!$editor_or_admin) return false;

            return jc_edit_event($request->get_json_params());

        case 'delete':
            if (!$editor_or_admin) return false;

            break;

        default:
            return false;
    }
}

function jc_get_events()
{
    $events = array();

    $start_date = new DateTime($_REQUEST['start']);
    $end_date = new DateTime($_REQUEST['end']);

    $args = array(
        'post_type' => 'events',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'start_date_time',
                'compare' => '>=',
                'value' => $start_date->format('Y-m-d H:i:s'),
            ),
            array(
                'key' => 'end_date_time',
                'compare' => '<=',
                'value' => $end_date->format('Y-m-d H:i:s'),
            ),
        ),
        'posts_per_page' => -1,
    );

    if (!get_current_user_id()) {
        $args['meta_query'][] = array(
            'key' => 'is_public',
            'value' => 1,
        );
    }

    $tz = new DateTimeZone(wp_timezone_string());

    $q = new WP_Query($args);
    while($q->have_posts()) {
        $q->the_post();

        $start_dt = new DateTime(get_field('start_date_time'), $tz);
        $end_dt = new DateTime(get_field('end_date_time'), $tz);

        $user_has_access = get_field('is_public');
        $user_has_access |= get_the_author_ID() == get_current_user_id();
        if (get_field('allowed_users')) {
            $user_has_access |= in_array(get_current_user_id(), get_field('allowed_users'));
        }

        if (!$user_has_access) continue;

        $editable = get_the_author_ID() == get_current_user_id();

        if (get_field('editors')) {
            $editable |= in_array(get_current_user_id(), get_field('editors'));
        }

        $editable &= (bool) get_current_user_id();

        $item = array(
            'id' => get_the_ID(),
            'title' => get_the_title(),
            'start' => $start_dt->format('c'),
            'end' => $end_dt->format('c'),
            'allDay' => get_field('is_all_day'),
            'editable' => $editable,
            'url' => get_the_permalink(),
        );

        $events[] = $item;
    }

    return $events;
}

function jc_create_event($event_details)
{
    if (!isset($event_details['title'])) return false;
    if (!isset($event_details['start_date_time'])) return false;
    if (!isset($event_details['end_date_time'])) return false;
    if (!isset($event_details['description'])) return false;

    $postarr = array(
        'post_title' => $event_details['title'],
        'post_type' => 'events',
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post($postarr);
    update_field('description', $event_details['description'], $post_id);

    $start_dt = new DateTime($event_details['start_date_time']);
    $end_dt = new DateTime($event_details['end_date_time']);

    update_field('start_date_time', $start_dt->format('Y-m-d H:i:s'), $post_id);
    update_field('end_date_time', $end_dt->format('Y-m-d H:i:s'), $post_id);

    if (isset($event_details['is_all_day']) && $event_details['is_all_day']) {
        update_field('is_all_day', 1, $post_id);
    }

    if (isset($event_details['is_public']) && $event_details['is_public']) {
        update_field('is_public', 1, $post_id);
    }

    return array('id' => $post_id);
}

function jc_edit_event($event_details)
{
    if (!isset($event_details['id'])) return false;

    $post_id = (int) $event_details['id'];

    if (!isset($event_details['title'])) {
        $postarr = array(
            'ID' => $post_id,
            'post_title' => $event_details['title'],
        );
    }

    $tz = new DateTime(wp_timezone_string());
    if (isset($event_details['start_date_time'])) {
        $start_dt = new DateTime($event_details['start_date_time']);
        $start_dt->setTimezone($tz);
        update_field('start_date_time', $start_dt->format('Y-m-d H:i:s'), $post_id);
    }

    if (isset($event_details['end_date_time'])) {
        $end_dt = new DateTime($event_details['end_date_time']);
        $start_dt->setTimezone($tz);
        update_field('end_date_time', $end_dt->format('Y-m-d H:i:s'), $post_id);
    }

    if (isset($event_details['description'])) {
        update_field('start_date_time', $event_details['start_date_time'], $post_id);
    }

    if (isset($event_details['is_all_day'])) {
        update_field('is_all_day', (int) $event_details['is_all_day'], $post_id);
    }

    return true;
}

add_action('rest_api_init', function() {
    register_rest_route('jc/v1', '/events', array(
        'methods'   => WP_REST_Server::ALLMETHODS,
        'callback' => 'jc_event_crud',
    ));
});
