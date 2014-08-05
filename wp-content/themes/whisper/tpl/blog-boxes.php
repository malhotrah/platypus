<?php
/*
 * Template Name: Blog Boxes
 */
?>

<?php
$paged = max( 1, get_query_var( 'paged' ) );
$query = new WP_Query( array(
	'post_type'   => 'post',
	'post_status' => 'publish',
	'paged'       => $paged,
) );
?>
<?php if ( $query->have_posts() ) : ?>

	<?php while ( $query->have_posts() ) : $query->the_post(); ?>

		<article <?php post_class(); ?>>
			<?php get_template_part( 'tpl/parts/content' ); ?>
		</article>

	<?php endwhile; ?>

	<?php whisper_numeric_pagination( $query ); ?>

	<?php wp_reset_postdata(); ?>

<?php else : ?>

	<?php get_template_part( '404' ); ?>

<?php endif; ?>
