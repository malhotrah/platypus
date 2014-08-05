<?php
/*
 * Template Name: Portfolio Hexagon
 */
?>
<?php
$paged = max( 1, get_query_var( 'paged' ) );
$query = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => '9', 'paged' => $paged ) );

whisper_portfolio_queried_categories( $query );
?>

<div class="clearfix"></div>

<section class="portfolio-items-container clearfix">

	<ul id="filter-item">
		<?php
		$i = 0;
		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
			$i++;

			$alpha = '';
			$categories = get_the_terms( get_the_ID(), 'portfolio_category' );
			if ( is_array( $categories ) )
			{
				$category = current( $categories );
				$alpha = $category->slug;
			}
			?>
			<li data-id="<?php echo $i; ?>" class="grid_4" data-alpha="<?php echo $alpha; ?>">
				<figure class="portfolio hexagons">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php list( $thumb_full ) = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
						<div class="portfolio-image">
							<div class="hexagon-mask"></div>
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'portfoliohex-thumbs' ); ?>
							</a>
						</div>
					<?php endif; ?>
					<figcaption>
						<div class="caption-title">
							<p class="title">
								<?php the_title(); ?>
							</p>
							<span class="subtitle">
								<?php echo whisper_meta( 'subtitle' ); ?>
							</span>
						</div>

						<div class="caption-content">
							<?php whisper_content_limit( 15, '' ); ?>
						</div>

						<div class="portfolio-hover">
							<ul>
								<li class="portfolio-zoom">
									<a href="<?php echo $thumb_full; ?>" title="<?php the_title_attribute(); ?>" rel="prettyPhoto[pp_gallery]">&nbsp;</a>
								</li>

								<li class="portfolio-single">
									<a href="<?php the_permalink(); ?>">&nbsp;</a>
								</li>
							</ul>
						</div>

						<div class="mask"></div>

					</figcaption>
				</figure>
			</li>

		<?php endwhile; endif; ?>

	</ul>

	<?php if ( !is_singular( 'portfolio' ) ) : ?>
		<div class="grid_12"><?php whisper_numeric_pagination( $query ); ?></div>
	<?php endif; ?>

	<?php wp_reset_postdata(); ?>

</section>
