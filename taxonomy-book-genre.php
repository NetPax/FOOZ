<?php get_header(); ?>

	<?php
	global $wp_query;
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	?>

	<?php if (have_posts()): ?>
		<ul>
		<?php while (have_posts()): the_post(); ?>
			<li><?php the_title(); ?></li>
		<?php endwhile; ?>
		</ul>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

	<div class="pager">
		<?php
		echo paginate_links(
			array(
				'current'   => max( 1, $paged ),
				'total' 	=> $wp_query->max_num_pages,
				'prev_text' => __('Poprzednia'),
				'next_text' => __('Następna')
			)
		);
		?>
	</div>

<?php get_footer(); ?>
