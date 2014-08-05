<?php if ( have_posts() ) : the_post(); ?>

	<article <?php post_class( 'page-content' ); ?>>
		<div class="post-body entry-content">
			<?php the_content(); ?>
			<?php
			wp_link_pages( array(
				'before'      => '<p class="pages">' . __( 'Pages:', 'whisper' ),
				'after'       => '</p>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
			?>
		</div>
		<div style="display:none">
			<?php whisper_entry_title(); ?>
			<?php whisper_entry_info(); ?>
		</div>
	</article>

	<?php
	if ( fitwp_option( 'page_comment' ) )
		comments_template( '', true );
	?>

<?php else : ?>

	<?php get_template_part( '404' ); ?>

<?php endif; ?>
