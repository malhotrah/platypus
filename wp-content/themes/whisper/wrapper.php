<?php get_header(); ?>

<?php get_template_part( 'tpl/parts/featured-title' ); ?>

<?php get_template_part( 'tpl/parts/slider' ); ?>

<div id="main" class="container_12">

	<?php
	$layout = whisper_layout();

	$classes = array( 'hfeed' );
	if ( is_singular() )
		$classes[] = 'blog-post-single';

	if ( !is_singular( 'portfolio' ) && !is_page_template( 'tpl/portfolio.php' ) && !is_page_template( 'tpl/portfolio-hex.php' ) )
		$classes[] = 'full-content' == $layout ? 'grid_12' : 'grid_8';

	if ( is_page_template( 'tpl/blog-boxes.php' ) )
	{
		$classes[] = 'boxes';

		global $whisper;
		$whisper['is_boxed'] = true;
	}

	$classes = implode( ' ', $classes );

	$file = whisper_template_path();
	if ( 'full-content' == $layout )
	{
		echo '<section id="content" class="' . $classes . '" role="main">';
		include $file;
		echo '</section>';
	}
	elseif ( 'sidebar-left' == $layout )
	{
		get_sidebar();

		echo '<section id="content" class="' . $classes . '" role="main">';
		include $file;
		echo '</section>';
	}
	else
	{
		echo '<section id="content" class="' . $classes . '" role="main">';
		include $file;
		echo '</section>';

		get_sidebar();
	}
	?>

</div><!-- #main -->

<?php get_footer(); ?>
