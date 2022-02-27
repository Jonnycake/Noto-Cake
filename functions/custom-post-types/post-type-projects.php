<?php
function register_projects_post_type()
{
	/**
	 * Post Type: Projects.
	 */

	$labels = [
		"name" => __( "Projects", "custom-post-type-ui" ),
		"singular_name" => __( "Project", "custom-post-type-ui" ),
	];

	$args = [
		"label" => __( "Projects", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "These are projects that should be displayed on my portfolio page",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"rewrite" => [ "slug" => "projects", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-editor-code",
		"supports" => [ "title", "thumbnail", "custom-fields" ],
		"show_in_graphql" => false,
	];

	register_post_type( "projects", $args );
}
add_action( 'init', 'register_projects_post_type' );
