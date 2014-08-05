<?php
/**
 * Display numeric pagination
 *
 * @param object $query Custom query object. Used when needed pagination for non-main query loop
 *
 * @return void
 */
function whisper_numeric_pagination( $query = null )
{
	global $wp_query;

	if ( null == $query )
		$query = $wp_query;

	// Don't print empty markup in archives if there's only one page.
	if ( $query->max_num_pages < 2 )
		return;
	?>
	<nav class="pagination">
		<?php
		$big = 9999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => __( '&laquo;', 'whisper' ),
			'next_text' => __( '&raquo;', 'whisper' ),
			'type'      => 'plain',
		);
		$args = apply_filters( __FUNCTION__, $args );

		echo paginate_links( $args );
		?>
	</nav>
	<?php
}

/**
 * Display navigation to next/previous pages when applicable
 *
 * @return void
 */
function whisper_single_pagination()
{
	global $wp_query, $post;

	$args = apply_filters(
		'whisper_single_pagination_args',
		array(
			'prev' => _x( '&larr;', 'Previous post link', 'whisper' ),
			'next' => _x( '&rarr;', 'Next post link', 'whisper' ),
		)
	);

	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next = get_adjacent_post( false, '', false );

	if ( !$next && !$previous )
		return;
	?>
	<nav class="pagination">

		<?php previous_post_link( '%link', $args['prev'] . ' %title' ); ?>
		<?php next_post_link( '%link', '%title ' . $args['next'] ); ?>

	</nav>
	<?php
}
