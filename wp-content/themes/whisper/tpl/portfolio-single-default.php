<article class="clearfix grid_12">
	<?php if ( have_posts() ) : the_post(); ?>
		<section class="portfolio-slider-1">
			<div id="slider" class="nivoSlider">
				<?php
				$images = whisper_meta( 'images', 'type=image&size=portfolio-slider' );
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

		<section class="portfolio-default-description">
			<div class="title">
				<?php whisper_entry_title(); ?>
				<span class="subtitle">
					<?php echo strip_tags( get_the_term_list( get_the_ID(), 'portfolio_category' ) ); ?>
				</span>
			</div>

			<?php the_content(); ?>

			<?php if ( $url = whisper_meta( 'url' ) ) : ?>
				<a href="<?php echo $url; ?>" class="fitsc-button fitsc-background-white"><?php _e( 'Visit Web Site', 'whisper' ); ?></a>
			<?php endif; ?>
		</section>
	<?php endif; ?>
</article>

<?php get_template_part( 'tpl/portfolio', 'items' ); ?>