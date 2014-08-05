<?php
add_action( 'wp_footer', 'whisper_footer', 100 ); // 100 = output AFTER other scripts are enqueued

/**
 * Echo footer scripts in to wp_footer
 * Allows shortcodes
 *
 * @return void
 * @since 1.0
 */
function whisper_footer()
{
	if ( $scripts = fitwp_option( 'footer_scripts' ) )
		echo do_shortcode( $scripts );
}