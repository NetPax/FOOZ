<?php
add_action( "wp_ajax_get_books", "get_books" );
add_action( "wp_ajax_nopriv_get_books", "get_books" );

function get_books() {
	$args = array(
		'post_type' => 'library',
		'posts_per_page' => 20,
	);

	$books = get_posts($args);
	$booksJSON = json_encode($books, JSON_PRETTY_PRINT);

	echo $booksJSON;
	die();
}