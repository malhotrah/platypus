<?php
define( 'HOME_URL', trailingslashit( home_url() ) );
define( 'THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'THEME_URL', trailingslashit( get_template_directory_uri() ) );

// Required plugins
require THEME_DIR . 'inc/functions/class-tgm-plugin-activation.php';
require THEME_DIR . 'inc/plugins.php';

// Theme options
require THEME_DIR . 'inc/functions/options/options.php';
include THEME_DIR . 'inc/options.php';

require THEME_DIR . 'inc/portfolio.php';
require THEME_DIR . 'inc/widgets/tweets.php';
require THEME_DIR . 'inc/widgets/recent-posts.php';
require THEME_DIR . 'inc/widgets/tabs.php';
require THEME_DIR . 'inc/widgets/social-feed.php';
require THEME_DIR . 'inc/widgets/social-media-links.php';

require THEME_DIR . 'inc/functions/shortcodes/shortcodes.php';

if ( is_admin() )
{
	require THEME_DIR . 'inc/admin/meta-boxes.php';
	require THEME_DIR . 'inc/admin/portfolio-columns.php';
}
else
{
	require THEME_DIR . 'inc/functions/theme-wrapper.php';

	require THEME_DIR . 'inc/frontend/frontend.php';
	require THEME_DIR . 'inc/frontend/media.php';
	require THEME_DIR . 'inc/frontend/entry.php';
	require THEME_DIR . 'inc/frontend/seo.php';
	require THEME_DIR . 'inc/frontend/menu.php';
	require THEME_DIR . 'inc/frontend/breadcrumbs.php';
	require THEME_DIR . 'inc/frontend/pagination.php';
	require THEME_DIR . 'inc/frontend/post-formats.php';
	require THEME_DIR . 'inc/frontend/header.php';
	require THEME_DIR . 'inc/frontend/footer.php';
	require THEME_DIR . 'inc/frontend/layout.php';
}

// Sets up the content width
global $content_width;
if ( !isset( $content_width ) )
	$content_width = 620;

add_action( 'init', 'whisper_register_menu' );

/**
 * Register menu
 *
 * @return void
 * @since  1.0
 */
function whisper_register_menu()
{
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'whisper' ),
	) );
}

add_action( 'widgets_init', 'whisper_register_sidebars' );

/**
 * Register sidebars
 *
 * @return void
 */
function whisper_register_sidebars()
{
	register_sidebar( array(
		'name'          => __( 'Header Right', 'whisper' ),
		'id'            => 'header-right',
		'before_widget' => '<div class="widget right %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>'
	) );
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'whisper' ),
		'id'            => 'blog',
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>'
	) );
	register_sidebar( array(
		'name'          => __( 'Page Sidebar', 'whisper' ),
		'id'            => 'page',
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>'
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Sidebar 1', 'whisper' ),
		'id'            => 'footer-1',
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>'
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Sidebar 2', 'whisper' ),
		'id'            => 'footer-2',
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>'
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Sidebar 3', 'whisper' ),
		'id'            => 'footer-3',
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>'
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Sidebar 4', 'whisper' ),
		'id'            => 'footer-4',
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>'
	) );
}

add_action( 'after_setup_theme', 'whisper_setup' );

/**
 * Setup theme support
 *
 * @return void
 * @since  1.0
 */
function whisper_setup()
{
	// Store global configuration
	global $whisper;
	$whisper = array();

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio', 'quote', 'link' ) );

	add_theme_support( 'post-thumbnails' );
	add_image_size( 'big-post-thumbs', 620, 274, true );
	add_image_size( 'boxes-post-thumbs', 300, 133, true );
	add_image_size( 'portfolio-slider', 620, 400, true );
	add_image_size( 'portfolio-simple-slider', 940, 400, true );
	add_image_size( 'portfolio-thumbs', 300, 176, true );
	add_image_size( 'portfoliohex-thumbs', 300, 347, true );
	add_image_size( 'widget-thumb', 60, 60, true );

	load_theme_textdomain( 'whisper', THEME_DIR . 'lang' );
}
