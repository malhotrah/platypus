<?php
/**
 * Plugin Name: FitWP Shortcodes
 * Plugin URI: http://fitwp.com
 * Description: A collections of shortcodes to be used in your theme
 * Author: The FitWP Team
 * Author URI: http://fitwp.com
 * Version: 0.2
 */

define( 'FITSC_URL', plugin_dir_url( __FILE__ ) );
define( 'FITSC_INC', trailingslashit( plugin_dir_path( __FILE__ ) . 'inc' ) );

if ( !is_admin() )
	require_once FITSC_INC . 'frontend.php';
elseif ( !defined( 'DOING_AJAX' ) )
	require_once FITSC_INC . 'admin.php';
else
	require_once FITSC_INC . 'ajax.php';