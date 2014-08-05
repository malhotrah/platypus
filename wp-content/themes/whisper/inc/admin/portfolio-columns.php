<?php
add_action( 'load-edit.php', 'whisper_portfolio_columns_load' );

/**
 * Add hooks for post columns
 *
 * @return void
 * @since 1.0
 */
function whisper_portfolio_columns_load()
{
	$screen = get_current_screen();
	if ( 'portfolio' != $screen->post_type )
		return;

	add_action( 'admin_enqueue_scripts', 'whisper_portfolio_columns_enqueue' );

	add_filter( 'manage_portfolio_posts_columns', 'whisper_portfolio_columns_add' );
	add_action( 'manage_portfolio_posts_custom_column', 'whisper_portfolio_columns_show', 10, 2 );
}

/**
 * Enqueue styles for portfolio columns
 *
 * @return void
 * @since 1.0
 */
function whisper_portfolio_columns_enqueue()
{
	wp_enqueue_style( 'whisper-portfolio-columns', THEME_URL . 'css/admin/portfolio-columns.css' );
}

/**
 * Get list of columns
 *
 * @param array $columns Default WordPress columns
 *
 * @return array
 * @since 1.0
 */
function whisper_portfolio_columns_add( $columns )
{
	$columns = array(
		'cb'    => '<input type="checkbox">',
		'image' => __( 'Image', '7listings' ),
		'title' => __( 'Name', '7listings' ),
		'date'  => __( 'Date', '7listings' ),
	);
	return $columns;
}

/**
 * Show column content
 * Must be defined in subclass
 *
 * @param string $column  Column ID
 * @param int    $post_id Post ID
 *
 * @since 1.0
 */
function whisper_portfolio_columns_show( $column, $post_id )
{
	switch ( $column )
	{
		case 'image':
			the_post_thumbnail();
			break;
	}
}
