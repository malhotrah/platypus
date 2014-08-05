<?php
// Allow shortcodes in text widgets
add_filter( 'widget_text', 'do_shortcode' );

// Use custom style for shortcodes
add_filter( 'fitsc_custom_style', '__return_true' );

// Add CSS classes to body
add_filter( 'body_class', 'whisper_body_class' );

/**
 * Add CSS classes to body
 *
 * @param array $classes
 *
 * @return array
 */
function whisper_body_class( $classes )
{
	$classes[] = fitwp_option( 'color_scheme' );

	if ( fitwp_option( 'layout_style' ) == 'boxed' )
	{
		$bg = fitwp_option( 'background' );
		if ( empty( $bg ) )
			$classes[] = fitwp_option( 'background_pattern' );
	}

	$layout_style = fitwp_option( 'layout_style' );
	if ( is_singular() && whisper_meta( 'custom_layout' ) && whisper_meta( 'layout_style' ) )
		$layout_style = whisper_meta( 'layout_style' );
	$classes[] = $layout_style;

	// Class for header right sidebar
	if ( is_active_sidebar( 'header-right' ) )
	{
		$position = fitwp_option( 'header_sidebar_position' );
		if ( !$position )
			$position = 'below';
		$classes[] = "header-right-sidebar header-right-sidebar-$position";
	}

	$classes = array_unique( array_filter( $classes ) );
	return $classes;
}

add_filter( 'post_class', 'whisper_post_class' );

/**
 * Add class to posts
 *
 * @param  array $classes
 *
 * @return array
 * @since  1.0
 */
function whisper_post_class( $classes )
{
	static $col = 1;

	$classes[] = 'clearfix';

	if ( is_page_template( 'tpl/blog-boxes.php' ) )
	{
		$classes[] = 'grid_4';
		$classes[] = $col == 1 ? 'alpha' : 'omega';
		$col = 3 - $col;
	}

	return $classes;
}

/**
 * Callback function to show comment
 *
 * @param object $comment
 * @param array  $args
 * @param int    $depth
 *
 * @return void
 * @since 1.0
 */
function whisper_comment( $comment, $args, $depth )
{
	$GLOBALS['comment'] = $comment;
	?>
<li <?php comment_class(); ?> itemscope itemtype="http://schema.org/Comment">
	<article id="comment-<?php comment_ID(); ?>">
		<?php echo get_avatar( $comment, 60 ); ?>

		<ul class="comment-meta">
			<li itemprop="author">
				<?php comment_author_link(); ?>
			</li>
			<li>
				<span itemprop="datePublished"><?php printf( __( '%1$s at %2$s', 'whisper' ), get_comment_date(), get_comment_time() ) ?></span>
				<?php edit_comment_link( __( '(Edit)', 'whisper' ), '  ', '' ) ?>
			</li>
		</ul>

		<div class="comment-body">
			<div itemprop="text"><?php comment_text() ?></div>
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?>
		</div>

		<?php if ( $comment->comment_approved == '0' ): ?>
			<div class="comment-waiting"><?php _e( '&nbsp;&nbsp;Your comment is awaiting moderation.', 'whisper' ) ?></div>
		<?php endif; ?>

	</article>
<?php
}

/**
 * Get post meta, using rwmb_meta() function from Meta Box class
 *
 * @param string   $key     Meta key. Required.
 * @param int|null $post_id Post ID. null for current post. Optional
 * @param array    $args    Array of arguments. Optional.
 *
 * @return mixed
 */
function whisper_meta( $key, $args = array(), $post_id = null )
{
	if ( !function_exists( 'rwmb_meta' ) )
		return false;
	return rwmb_meta( $key, $args, $post_id );
}
