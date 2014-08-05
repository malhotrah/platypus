<?php if ( have_posts() ) : the_post(); ?>
	<section class="grid_12 portfolio-slider-2">
		<div id="slider" class="nivoSlider">
			<?php
			$images = whisper_meta( 'images', 'type=image&size=portfolio-simple-slider' );
			if ( !empty( $images ) && is_array( $images ) )
			{
				foreach ( $images as $image )
				{
					printf(
						'<a href="%s" rel="prettyPhoto[gallery]"><img src="%s" alt="portfoliio-image"></a>',
						$image['full_url'],
						$image['url']
					);
				}
			}
			?>
		</div>
	</section>

	<div class="grid_8 portfolio-description">
		<?php whisper_entry_title(); ?>
		<?php the_content(); ?>

		<?php if ( $url = whisper_meta( 'url' ) ) : ?>
			<a href="<?php echo $url; ?>" class="fitsc-button"><?php _e( 'Visit Web Site', 'whisper' ); ?></a>
		<?php endif; ?>
	</div>

	<div class="grid_4">
		<h3><?php _e( 'Our client\'s words', 'whisper' ); ?></h3>
		<blockquote>
			"<?php echo whisper_meta( 'testimonial' ); ?>" <cite><?php echo whisper_meta( 'testimonial_author' ); ?></cite>
		</blockquote>
	</div>
<?php endif; ?>
