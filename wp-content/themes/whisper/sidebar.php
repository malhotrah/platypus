<aside class="grid_4" id="sidebar">
	<?php
	$sidebar = 'blog';
	if ( is_page() && !is_page_template( 'tpl/blog.php' ) && !is_page_template( 'tpl/blog-boxes.php' ) )
		$sidebar = 'page';
	if ( !dynamic_sidebar( $sidebar ) )
	{
		echo '<h5>' . __( 'Widget areas', 'whisper' ) . '</h5>' .
			sprintf( __( 'This is a sidebar (widget area). Please go to <a href="%s">Appearance &rarr; Widgets</a> to add widgets to this area', 'whisper' ), admin_url( 'widgets.php' ) );
	}
	?>
</aside>