<?php
require_once(__DIR__ . '/classes/NC_Event.php');

function register_events_post_type()
{
	/**
	 * Post Type: Events.
	 */

	$labels = [
		"name" => __( "Events", "custom-post-type-ui" ),
		"singular_name" => __( "Event", "custom-post-type-ui" ),
	];

	$args = [
		"label" => __( "Events", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "These are events that should be displayed on the calendar",
		"public" => false,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => false,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"rewrite" => [ "slug" => "events", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-editor-code",
		"supports" => [ "title", "thumbnail", "custom-fields", ],
		"show_in_graphql" => false,
	];

	register_post_type( "events", $args );

    $args = array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x( 'Event Categories', 'taxonomy general name' ),
            'singular_name' => _x( 'Event Category', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Event Categories' ),
            'all_items' => __( 'All Event Categories' ),
            'parent_item' => __( 'Parent Category' ),
            'parent_item_colon' => __( 'Parent Category:' ),
            'edit_item' => __( 'Edit Category' ),
            'update_item' => __( 'Update Category' ),
            'add_new_item' => __( 'Add New Event Category' ),
            'new_item_name' => __( 'New Event Category' ),
            'menu_name' => __( 'Event Categories' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'event_categories', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => false, // This will allow URL's like "/locations/boston/cambridge/"
        ),
    );
    register_taxonomy('event_category', 'events', $args);
}
add_action( 'init', 'register_events_post_type' );

function jcake_event_guard() {
    if (!is_singular('events')) return;
    if (user_should_access_event()) return;

    // Return a 404 instead
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
}
add_action( 'wp', 'jcake_event_guard' );

// Utility functions
function event_is_public($event_id)
{
    return get_field('is_public', $event_id);
}

function event_allows_user_explicitly($event_id, $user_id)
{
    $allowed_users = get_field('allowed_users', $event_id);

    if (!$allowed_users) return false;
    if (!$user_id) return false;

    return in_array($user_id, $allowed_users);
}

function user_should_access_event($event_id=null)
{
    if (is_null($event_id)) {
        $event_id = get_the_ID();
    }

    if (event_is_public($event_id)) return true;
    if (!is_user_logged_in()) return false;

    $post = get_post($event_id);
    if (!$post) return false;

    $user_id = get_current_user_id();
    if (current_user_can('administrator')) return true;
    if (event_allows_user_explicitly($event_id, $user_id)) return true;
    if ($user_id == $post->post_author) return true;

    return false;
}
