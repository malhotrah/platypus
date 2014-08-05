<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php get_template_part( 'tpl/parts/content' ); ?>
		</article>

	<?php endwhile; ?>

	<?php whisper_numeric_pagination(); ?>

<?php else : ?>

	<?php get_template_part( '404' ); ?>

<?php endif; ?>
