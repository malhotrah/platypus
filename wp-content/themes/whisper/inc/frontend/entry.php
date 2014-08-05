<?php
add_filter( 'excerpt_length', 'whisper_content_length' );
add_filter( 'excerpt_more', 'whisper_more_text' );

/**
 * Show entry title
 * Wrap in H1 tag for singular page
 * Wrap in H2 tag and a link for non singular page
 *
 * @return void
 */
function whisper_entry_title()
{
	if ( !( $title = get_the_title() ) )
		return;

	// Check on singular pages
	$is_single = is_singular() && !is_page_template( 'tpl/blog.php' ) && !is_page_template( 'tpl/blog-boxes.php' );

	// Allow to config via global variable
	if ( isset( $whisper['is_single'] ) )
		$is_single = $whisper['is_single'];

	$tag = $is_single ? 'h1' : 'h2';
	$title = sprintf( '<%1$s class="entry-title"><a href="%2$s" title="%3$s" rel="bookmark">%4$s</a></%1$s>', $tag, get_permalink(), the_title_attribute( 'echo=0' ), $title );
	echo apply_filters( __FUNCTION__, $title );
}

/**
 * Show entry info after entry title
 *
 * @return void
 */
function whisper_entry_info()
{
	global $whisper;

	// Default info will be shown
	$info = array( 'date', 'author', 'comment' );
	if ( !isset( $whisper['is_boxed'] ) )
		$info[] = 'categories';
	if ( isset( $whisper['entry_meta_info'] ) )
		$info = $whisper['entry_meta_info'];

	$info = array_filter( $info );
	if ( empty( $info ) )
		return;
	?>
	<ul class="post-meta clearfix">
		<?php if ( in_array( 'date', $info ) ) : ?>
			<li>
				<time class="date updated" datetime="<?php the_time( 'c' ); ?>" pubdate><?php the_time( get_option( 'date_format' ) ); ?></time>
			</li>
		<?php endif; ?>
		<?php if ( in_array( 'author', $info ) ) : ?>
			<li>
				<?php
				printf(
					'<span class="author vcard"><a class="url fn n" href="%s" title="%s" rel="author">%s</a></span>',
					get_author_posts_url( get_the_author_meta( 'ID' ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'peace' ), get_the_author() ) ),
					get_the_author()
				);
				?>
			</li>
		<?php endif; ?>
		<?php if ( in_array( 'comment', $info ) && ( comments_open() || get_comments_number() ) ) : ?>
			<li>
				<?php comments_popup_link( __( '0 comments', 'whisper' ), __( '1 comments', 'whisper' ), __( '% comments', 'whisper' ), 'comments-link' ); ?>
			</li>
		<?php endif; ?>
		<?php if ( in_array( 'categories', $info ) ) : ?>
			<li>
				<span><?php _e( 'Posted In: ', 'whisper' ); ?></span><?php the_category( ', ' ); ?>
			</li>
		<?php endif; ?>
	</ul>
<?php
}

/**
 * Show entry info after entry content
 * Show only in 'real' single page
 *
 * @return void
 */
function whisper_entry_meta()
{
	global $whisper;

	// Check on singular pages
	$is_single = is_singular() && !is_page_template( 'tpl/blog.php' ) && !is_page_template( 'tpl/blog-boxes.php' );

	// Allow to config via global variable
	if ( isset( $whisper['is_single'] ) )
		$is_single = $whisper['is_single'];

	if ( !$is_single )
		return;
	?>
	<p class="post-tags">
		<?php the_tags( __( '<span>Tags: </span>', 'whisper' ), ' ' ); ?>
	</p>
	<?php
}

/**
 * Get content limit length
 *
 * @param  integer $length
 *
 * @return integer
 */
function whisper_content_length( $length = 55 )
{
	global $whisper;

	$length = fitwp_option( 'blog_content_limit' );

	// Allow to config via global variable
	if ( isset( $whisper['blog_content_limit'] ) )
		$length = $whisper['blog_content_limit'];

	if ( !$length )
		$length = 55;

	return $length;
}

/**
 * Get read more text
 *
 * @param  string $more
 *
 * @return string
 */
function whisper_more_text( $more = '' )
{
	global $whisper;

	$more = fitwp_option( 'blog_more' );

	// Allow to config via global variable
	if ( isset( $whisper['blog_more'] ) )
		$more = $whisper['blog_more'];

	if ( !$more )
		$more = __( 'Continue reading', 'whisper' );

	$more .= '<span>&gt;</span>';

	if ( 'excerpt_more' == current_filter() )
		$more = '<a class="more-link" href="' . get_permalink() . '" title="' . sprintf( __( 'Continue reading &quot;%s&quot;', 'whisper' ), the_title_attribute( 'echo=0' ) ) . '">' . $more . '</a>';

	return $more;
}

/**
 * Display entry content or summary
 *
 * @return void
 */
function whisper_entry_content()
{
	global $whisper;

	// Check on singular pages
	$is_single = is_singular() && !is_page_template( 'tpl/blog.php' ) && !is_page_template( 'tpl/blog-boxes.php' );

	// Allow to config via global variable
	if ( isset( $whisper['is_single'] ) )
		$is_single = $whisper['is_single'];

	if ( $is_single )
	{
		echo '<div class="entry-content">';
		the_content();
		wp_link_pages( array(
			'before'      => '<p class="pages">' . __( 'Pages:', 'whisper' ),
			'after'       => '</p>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );
		echo '</div>';
		return;
	}

	// Archives & Blog pages

	// Display type
	$display = fitwp_option( 'blog_display' );

	// Allow to config via global variable
	if ( isset( $whisper['blog_display'] ) )
		$display = $whisper['blog_display'];

	if ( !$display )
		$display = 'content';

	echo '<div class="entry-summary">';

	// Excerpt
	if ( 'excerpt' == $display )
	{
		the_excerpt();
		return;
	}

	$more_text = whisper_more_text();

	// Post content before more tag
	if ( 'more' == $display )
	{
		if ( is_page_template( 'tpl/blog.php' ) || is_page_template( 'tpl/blog-boxes.php' ) )
		{
			global $more;
			$more = false;
		}

		the_content( $more_text );
		wp_link_pages( array(
			'before'      => '<p class="pages">' . __( 'Pages:', 'whisper' ),
			'after'       => '</p>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );
	}
	else
	{
		whisper_content_limit( whisper_content_length(), $more_text );
	}

	echo '</div>'; // .entry-summary
}

/**
 * Display limited post content
 *
 * Strips out tags and shortcodes, limits the content to `$num_words` words and appends more link to the end.
 *
 * @param integer $num_words The maximum number of words
 * @param string  $more      More link. Default is "...". Optional.
 * @param bool    $echo      Echo or return output
 *
 * @return string Limited content.
 */
function whisper_content_limit( $num_words, $more = '...', $echo = true )
{
	$content = get_the_content();

	// Strip tags and shortcodes so the content truncation count is done correctly
	$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'whisper_content_limit_allowed_tags', '<script>,<style>' ) );

	// Remove inline styles / scripts
	$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

	// Truncate $content to $max_char
	$content = wp_trim_words( $content, $num_words );

	if ( $more )
	{
		$output = sprintf(
			'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
			$content,
			get_permalink(),
			sprintf( __( 'Continue reading &quot;%s&quot;', 'whisper' ), the_title_attribute( 'echo=0' ) ),
			$more
		);
	}
	else
	{
		$output = sprintf( '<p>%s</p>', $content );
	}

	// Still display post formats differently
	$output = whisper_post_formats_content( $output );

	if ( $echo )
		echo $output;
	else
		return $output;
}

/**
 * Display single navigation
 *
 * @return void
 */
function whisper_single_navigation()
{
	$display = apply_filters( __FUNCTION__, is_single() );
	if ( $display )
		whisper_single_pagination();
}
