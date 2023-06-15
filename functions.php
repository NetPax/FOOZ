<?php
require_once "ajax-requests.php";

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {

	$parent_style = 'parent-style';

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css',	array( $parent_style ),	wp_get_theme()->get('Version'));

	wp_enqueue_script('child-js-jquery', get_stylesheet_directory_uri() . '/assets/js/jquery.min.js', false, '', true);
	wp_enqueue_script('child-js-jquery-migrate', get_stylesheet_directory_uri() . '/assets/js/jquery-migrate.min.js', false, '', true);
	wp_enqueue_script('child-js', get_stylesheet_directory_uri() . '/assets/js/scripts.js', false, '1.0.0', true);
}

function cptui_register_my_cpts_library() {

	/**
	 * Post Type: Books.
	 */

	$labels = [
		"name" => esc_html__( "Books", "custom-post-type-ui" ),
		"singular_name" => esc_html__( "Book", "custom-post-type-ui" ),
	];

	$args = [
		"label" => esc_html__( "Books", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "library", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"show_in_graphql" => false,
	];

	register_post_type( "library", $args );
}

add_action( 'init', 'cptui_register_my_cpts_library' );

function custom_taxonomy_query( $query ) {
	if ( $query->is_tax() && ! is_admin() && $query->is_main_query() ) {
		$query->set( 'posts_per_page', 5 ); // Set the desired number of posts per page
	}
}
add_action( 'pre_get_posts', 'custom_taxonomy_query' );

// Shortcode: [recent_post_title]
function get_recent_post_title_shortcode() {
	$args = array(
		'post_type'      => 'library',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$recent_posts = get_posts($args);

	if ($recent_posts) {
		$recent_post = $recent_posts[0];
		$post_title = $recent_post->post_title;

		return $post_title; // Return the post title
	}

	return '';
}
add_shortcode('recent_post_title', 'get_recent_post_title_shortcode');

// Shortcode: [taxonomy_posts term_id="3"]
function get_taxonomy_posts_shortcode($atts) {
	$atts = shortcode_atts(array(
		'term_id' => '',
	), $atts, 'taxonomy_posts');

	$args = array(
		'post_type' => 'library',
		'posts_per_page' => 5,
		'tax_query' => array(
			array(
				'taxonomy' => 'book-genre',
				'field' => 'term_id',
				'terms' => $atts['term_id'],
			),
		),
		'orderby' => 'title',
		'order' => 'ASC',
	);

	$query = new WP_Query($args);

	if ($query->have_posts()) {
		$output = '<ul>';
		while ($query->have_posts()) {
			$query->the_post();
			$output .= '<li>' . get_the_title() . '</li>';
		}
		$output .= '</ul>';
		wp_reset_postdata();
		return $output;
	}

	return ''; // Return empty string if no posts found
}
add_shortcode('taxonomy_posts', 'get_taxonomy_posts_shortcode');