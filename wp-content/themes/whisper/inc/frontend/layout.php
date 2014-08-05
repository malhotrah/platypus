<?php
/**
 * Get page layout
 *
 * @param string $default Default layout
 *
 * @return string
 */
function whisper_layout( $default = 'sidebar-right' )
{
	// Default layout
	$layout = $default;

	// Site layout
	if ( $site_layout = fitwp_option( 'site_layout' ) )
		$layout = $site_layout;

	// Page layout
	if ( is_page() )
	{
		if ( $page_layout = fitwp_option( 'page_layout' ) )
			$layout = $page_layout;
	}

	// Singular page can have custom layout
	if ( is_singular() && whisper_meta( 'custom_layout' ) )
	{
		if ( $custom_layout = whisper_meta( 'layout' ) )
			$layout = $custom_layout;
	}

	// Contact: full content
	if ( is_page_template( 'tpl/contact.php' ) )
		$layout = 'full-content';

	// Portfolio: full content
	if ( is_singular( 'portfolio' ) )
		$layout = 'full-content';

	// Allow to filter
	$layout = apply_filters( __FUNCTION__, $layout );

	return $layout;
}