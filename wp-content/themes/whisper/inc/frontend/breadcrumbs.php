<?php
/**
 * Display breadcrumbs for posts, pages, archive page with the microdata that search engines understand
 *
 * @see http://support.google.com/webmasters/bin/answer.py?hl=en&answer=185417
 *
 * @param array|string $args
 *
 * @return void
 */
function whisper_breadcrumbs( $args = '' )
{
	$args = wp_parse_args( $args, array(
		'separator'   => '&rsaquo;',
		'home_label'  => __( 'Home', 'whisper' ),
		'home_class'  => 'home',
		'before'      => '<span class="before">' . __( 'You are here: ', 'whisper' ) . '</span>',
		'before_item' => '',
		'after_item'  => '',
		'taxonomy'    => array( 'category' ),
	) );

	$args = apply_filters( 'whisper_breadcrumbs_args', $args );

	$items = array();

	// HTML template for each item
	$item_tpl = $args['before_item'] . '
		<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a href="%s" itemprop="url"><span itemprop="title">%s</span></a>
		</span>
	' . $args['after_item'];
	$item_text_tpl = $args['before_item'] . '
		<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<span itemprop="title">%s</span>
		</span>
	' . $args['after_item'];

	// Home
	if ( !$args['home_class'] )
	{
		$items[] = sprintf( $item_tpl, HOME_URL, $args['home_label'] );
	}
	else
	{
		$items[] = $args['before_item'] . sprintf(
			'<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a class="%s" href="%s" itemprop="url"><span itemprop="title">%s</span></a>
			</span>' . $args['after_item'],
			$args['home_class'],
			HOME_URL,
			$args['home_label']
		);
	}

	if ( is_home() || is_front_page() )
	{
		// Do nothing
	}
	elseif ( is_single() )
	{
		foreach( (array) $args['taxonomy'] as $taxonomy )
		{
			// Terms
			$terms = get_the_terms( get_the_ID(), $taxonomy );
			$count = is_array( $terms ) ? count( $terms ) : 0;

			// Display all terms if there are many
			if ( 2 <= $count )
			{
				foreach ( $terms as $term )
				{
					$items[] = sprintf( $item_tpl, get_term_link( $term, $taxonomy ), $term->name );
				}
			}

			// If there's only one category, display hierarchically
			elseif ( 1 == $count )
			{
				$term = current( $terms );
				$terms = whisper_get_term_parents( $term->term_id, $taxonomy );
				foreach ( $terms as $term_id )
				{
					$term = get_term( $term_id, $taxonomy );
					$items[] = sprintf( $item_tpl, get_term_link( $term, $taxonomy ), $term->name );
				}
			}
		}
	}
	elseif ( is_page() )
	{
		$pages = whisper_get_post_parents( get_queried_object_id() );
		foreach ( $pages as $page )
		{
			$items[] = sprintf( $item_tpl, get_permalink( $page ), get_the_title( $page ) );
		}
	}
	elseif ( is_tax() || is_category() || is_tag() )
	{
		$current_term = get_queried_object();
		$terms = whisper_get_term_parents( get_queried_object_id(), $current_term->taxonomy );
		foreach ( $terms as $term_id )
		{
			$term = get_term( $term_id, $current_term->taxonomy );
			$items[] = sprintf( $item_tpl, get_category_link( $term_id ), $term->name );
		}
	}
	elseif ( is_search() )
	{
		$items[] = sprintf( $item_text_tpl, sprintf( __( 'Search results for &quot;%s&quot;', 'dbt' ), get_search_query() ) );
	}
	elseif ( is_404() )
	{
		$items[] = sprintf( $item_text_tpl, __( 'Not Found', 'whisper' ) );
	}
	elseif ( is_author() )
	{
		// Queue the first post, that way we know what author we're dealing with (if that is the case).
		the_post();
		$items[] = sprintf(
			$item_text_tpl,
			sprintf(
				__( 'Author Archives: %s', 'whisper' ),
				'<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>'
			)
		);
		rewind_posts();
	}
	elseif ( is_day() )
	{
		$items[] = sprintf(
			$item_text_tpl,
			sprintf( __( 'Daily Archives: %s', 'whisper' ), get_the_date() )
		);
	}
	elseif ( is_month() )
	{
		$items[] = sprintf(
			$item_text_tpl,
			sprintf( __( 'Monthly Archives: %s', 'whisper' ), get_the_date( 'F Y' ) )
		);
	}
	elseif ( is_year() )
	{
		$items[] = sprintf(
			$item_text_tpl,
			sprintf( __( 'Yearly Archives: %s', 'whisper' ), get_the_date( 'Y' ) )
		);
	}
	else
	{
		$items[] = sprintf(
			$item_text_tpl,
			sprintf( __( 'Archives', 'whisper' ) )
		);
	}

	echo $args['before'] . implode( $args['separator'], $items );
}

/**
 * Searches for term parents' IDs of hierarchical taxonomies, including current term.
 * This function is similar to the WordPress function get_category_parents() but handles any type of taxonomy.
 * Modified from Hybrid Framework
 *
 * @param int|string    $term_id   The term ID
 * @param object|string $taxonomy  The taxonomy of the term whose parents we want.
 *
 * @return array Array of parent terms' IDs.
 */
function whisper_get_term_parents( $term_id = '', $taxonomy = 'category' )
{
	// Set up some default arrays.
	$list = array();

	// If no term ID or taxonomy is given, return an empty array.
	if ( empty( $term_id ) || empty( $taxonomy ) )
		return $list;

	do
	{
		$list[] = $term_id;

		// Get next parent term
		$term = get_term( $term_id, $taxonomy );
		$term_id = $term->parent;
	}
	while ( $term_id );

	// Reverse the array to put them in the proper order for the trail.
	$list = array_reverse( $list );

	return $list;
}

/**
 * Gets parent posts' IDs of any post type, include current post
 * Modified from Hybrid Framework
 *
 * @param int|string $post_id ID of the post whose parents we want.
 *
 * @return array Array of parent posts' IDs.
 */
function whisper_get_post_parents( $post_id = '' )
{
	// Set up some default array.
	$list = array();

	// If no post ID is given, return an empty array.
	if ( empty( $post_id ) )
		return $list;

	do
	{
		$list[] = $post_id;

		// Get next parent post
		$post = get_post( $post_id );
		$post_id = $post->post_parent;
	}
	while ( $post_id );

	// Reverse the array to put them in the proper order for the trail.
	$list = array_reverse( $list );

	return $list;
}
