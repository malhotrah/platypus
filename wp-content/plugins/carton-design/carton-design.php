<?php
/*
Plugin Name: Carton Design Download Manager
Plugin URI: #
Description: Download Carton Design and filling form enteries
Author: Hitanshu (hitanshumalhotra@gmail.com, +919958205181)
Author URI: http://hitanshumalhotra.com
Version: 1.0.0
Text Domain: cartondesign
License: GPLv2
*/

define('CDURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );

define('CDPATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );

function ajaxcontact_enqueuescripts()

{

    wp_enqueue_script('ajaxcontact', CDURL.'/js/ajaxcd.js', array('jquery'));

    wp_localize_script( 'ajaxcontact', 'ajaxcontactajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}
