<?php
add_action( 'template_redirect', 'whisper_seo_check' );

/**
 * Check if SEO plugins are enabled and add needed hooks
 *
 * @return void
 * @since  1.0
 */
function whisper_seo_check()
{
	$enabled = defined( 'WPSEO_VERSION' );
	$classes = array(
		'All_in_One_SEO_Pack',
		'All_in_One_SEO_Pack_p',
		'HeadSpace_Plugin',
		'Platinum_SEO_Pack',
		'wpSEO',
	);
	foreach ( $classes as $class )
	{
		if ( class_exists( $class ) )
		{
			$enabled = true;
			break;
		}
	}
	$enabled = apply_filters( 'whisper_detect_seo_plugins', $enabled );
	if ( $enabled )
		return;

	add_filter( 'wp_title', 'whisper_wp_title', 10, 3 );
	add_action( 'wp_head', 'whisper_meta_description' );
	add_action( 'wp_head', 'whisper_meta_keywords' );
}

/**
 * Get meta <title> tag
 *
 * @param string $title
 * @param string $sep
 * @param string $sep_location
 *
 * @return string
 */
function whisper_wp_title( $title, $sep, $sep_location )
{
	global $page, $paged;

	// Don't affect in feeds.
	if ( is_feed() )
		return $title;

	// Add the blog name
	if ( 'right' == $sep_location )
		$title .= get_bloginfo( 'name' );
	else
		$title = get_bloginfo( 'name' ) . $title;

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " {$sep} {$site_description}";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " {$sep} " . sprintf( __( 'Page %s', 'whisper' ), max( $paged, $page ) );

	return $title;
}

/**
 * Generates the meta description
 *
 * @return void
 */
function whisper_meta_description()
{
	global $post;

	$description = '';

	// If we're on the home page
	if ( is_front_page() )
		$description = get_bloginfo( 'description' );

	//	If we're on a single post / page / attachment
	if ( is_singular() )
		$description = whisper_content_limit( 20, '', false );

	if ( is_category() || is_tag() )
	{
		$term = get_queried_object();
		$description = !empty( $term->meta['description'] ) ? $term->meta['description'] : '';
	}

	if ( is_tax() )
	{
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$description = !empty( $term->meta['description'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['description'] ) ) : '';
	}

	if ( is_author() )
	{
		$user_description = get_the_author_meta( 'meta_description', (int) get_query_var( 'author' ) );
		$description = $user_description ? $user_description : '';
	}

	// Add the description if one exists
	if ( $description )
		echo '<meta name="description" content="' . esc_attr( $description ) . '">';
}

/**
 * Generates the meta keywords
 *
 * @return void
 */
function whisper_meta_keywords()
{
	global $post;

	$keywords = '';

	// If we're on the home page
	if ( is_front_page() )
		$keywords = '';

	if ( is_single() )
	{
		$keywords = implode( ',', wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) ) );
	}

	if ( is_category() || is_tag() )
	{
		$term = get_queried_object();
		$keywords = !empty( $term->meta['keywords'] ) ? $term->meta['keywords'] : '';
	}

	if ( is_tax() )
	{
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$keywords = !empty( $term->meta['keywords'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['keywords'] ) ) : '';
	}

	if ( is_author() )
	{
		$user_keywords = get_the_author_meta( 'meta_keywords', (int) get_query_var( 'author' ) );
		$keywords = $user_keywords ? $user_keywords : '';
	}

	// Add the keywords if they exist
	if ( $keywords )
		echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '">';
}
