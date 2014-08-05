<?php if ( have_posts() ) : the_post(); ?>

	<article <?php post_class(); ?>>
		<?php get_template_part( 'tpl/parts/content' ); ?>
	</article>

	<?php
	if ( comments_open() || get_comments_number() )
		comments_template( '', true );
	?>

<?php else : ?>

	<?php get_template_part( '404' ); ?>

<?php endif; ?>