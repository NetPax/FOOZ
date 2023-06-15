<?php get_header(); ?>
<h1><?php echo get_the_title(); ?></h1>
<?php echo get_the_post_thumbnail(); ?>
<?php $book_genres = get_the_terms(get_the_ID(), 'book-genre'); ?>
<?php if ($book_genres): ?>
	<p>Genres:</p>
	<ul>
	<?php foreach ($book_genres as $genre): ?>
		<li><?php echo $genre->name; ?></li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
<p><?php echo get_the_date(); ?></p>
<?php get_footer(); ?>