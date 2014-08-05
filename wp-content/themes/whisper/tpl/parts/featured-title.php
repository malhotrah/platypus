<?php
$title = __( 'Archives', 'whisper' );
$subtitle = '';

if ( is_home() )
{
	$title = __( 'Home', 'whisper' );
}
elseif ( is_singular() )
{
	// Check if singular post/page is enabled the title area
	if ( whisper_meta( 'hide_title' ) )
		return;

	if ( !( $title = whisper_meta( 'custom_title' ) ) )
		$title = get_the_title();

	$subtitle = whisper_meta( 'subtitle' );
}
elseif ( is_search() )
{
	$title = sprintf( __( 'Search results for &quot;%s&quot;', 'whisper' ), get_search_query() );
}
elseif ( is_404() )
{
	$title = __( 'Not Found', 'whisper' );
}
elseif ( is_author() )
{
	the_post();
	$title = sprintf( __( 'Author Archives: %s', 'whisper' ), get_the_author() );
	rewind_posts();
}
elseif ( is_day() )
{
	$title = sprintf( __( 'Daily Archives: %s', 'whisper' ), get_the_date() );
}
elseif ( is_month() )
{
	$title = sprintf( __( 'Monthly Archives: %s', 'whisper' ), get_the_date( 'F Y' ) );
}
elseif ( is_year() )
{
	$title = sprintf( __( 'Yearly Archives: %s', 'whisper' ), get_the_date( 'Y' ) );
}
elseif ( is_tax() || is_category() || is_tag() )
{
	$title = single_term_title( '', false );
}
?>
<section class="featured-title"<?php
if ( is_singular() && $background = whisper_meta( 'featured_title_background' ) )
	echo " style='background:url($background)'";
?>>
	<div class="container_12">
		<div class="grid_12">
			<div class="title left">
				<?php
				echo "<h1>$title</h1>";
				if ( $subtitle )
					echo "<h3 class='subtitle'>$subtitle</h3>";
				?>
			</div>

			<?php
			$hide_breadcrumbs = fitwp_option( 'hide_breadcrumbs' );
			if ( is_singular() && whisper_meta( 'hide_breadcrumbs' ) )
				$hide_breadcrumbs = true;
			if ( !$hide_breadcrumbs )
			{
				echo '<ul class="breadcrumbs right">';
				whisper_breadcrumbs( array(
					'separator'   => '<li>/</li>',
					'before'      => '',
					'before_item' => '<li>',
					'after_item'  => '</li>',
				) );
				echo '</ul>';
			}
			?>
		</div>
	</div>
</section>