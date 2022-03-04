<?php
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
    update_field('start_date_time', $event_details['start_date_time'], $post_id);
    update_field('end_date_time', $event_details['end_date_time'], $post_id);

    if (isset($event_details['is_all_day']) && $event_details['is_all_day']) {
        update_field('is_all_day', 1, $post_id);
    }

    return array('id' => $post_id);
}

function jc_event_crud(WP_REST_Request $request)
{
    $editor_or_admin = current_user_can('editor');
    $editor_or_admin |= current_user_can('administrator');

    switch(strtolower($request->get_method())) {
        case 'get':
            break;

        case 'post':
            if (!$editor_or_admin) return false;

            return jc_create_event($request->get_json_params());

        case 'delete':
            if (!$editor_or_admin) return false;

            break;
    }
}
add_action('rest_api_init', function() {
    register_rest_route('jc/v1', '/events', array(
        'methods'   => array(
            WP_REST_Server::READABLE,
            WP_REST_Server::CREATABLE,
            WP_REST_Server::DELETABLE,
        ),
        'callback' => 'jc_event_crud',
    ));
});
