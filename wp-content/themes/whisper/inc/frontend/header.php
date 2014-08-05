<?php
add_action( 'wp_enqueue_scripts', 'whisper_enqueue' );
add_action( 'wp_head', 'whisper_favicon' );
add_action( 'wp_head', 'whisper_touch_icon' );
add_action( 'wp_head', 'whisper_design_css' );
add_action( 'wp_head', 'whisper_header_scripts' );

/**
 * Enqueue scripts and styles
 * @return void
 * @since  1.0
 */
function whisper_enqueue()
{
	wp_enqueue_style( 'whisper', THEME_URL . 'css/main.css' );

	// Enqueue style for child theme
	if ( is_child_theme() )
		wp_enqueue_style( 'whisper-child', get_stylesheet_uri() );

	wp_enqueue_style( 'jquery-jplayer' );
	wp_enqueue_script( 'jquery-jplayer' );

	// Portfolio archive and shortcode
	wp_enqueue_style( 'jquery-prettyPhoto' );
	wp_enqueue_script( 'jquery-prettyPhoto' );

	wp_enqueue_script( 'jquery-flexslider' );
	wp_enqueue_script( 'jquery-carouFredSel' );

	wp_enqueue_script( 'whisper' );

	if ( is_page_template( 'tpl/contact.php' ) )
		wp_enqueue_script( 'jquery-validate' );

	if ( is_singular( 'portfolio' ) )
	{
		wp_enqueue_style( 'jquery-nivoslider' );
		wp_enqueue_script( 'jquery-nivoslider' );
	}

	if ( is_singular( 'portfolio' ) || is_page_template( 'tpl/portfolio.php' ) || is_page_template( 'tpl/portfolio-hex.php' ) )
	{
		wp_enqueue_script( 'jquery-quicksand' );
		wp_enqueue_script( 'portfolio' );
	}

	if ( is_singular() && get_option( 'thread_comments' ) && comments_open() )
		wp_enqueue_script( 'comment-reply' );

	$params = array(
		'navDefault' => __( 'Go to...', 'whisper' ),
	);
	if ( is_page_template( 'tpl/contact.php' ) )
		$params['isContactPage'] = true;

	wp_localize_script( 'whisper', 'Whisper', $params );
}

/**
 * Display favicon
 * @return void
 * @since  1.0
 */
function whisper_favicon()
{
	if ( $favicon = fitwp_option( 'favicon' ) )
		echo "<link rel='shortcut icon' href='$favicon'>";
}

/**
 * Display icons for mobile devices and tablets
 * @return void
 * @since  1.0
 */
function whisper_touch_icon()
{
	if ( $icon = fitwp_option( 'touch_icon' ) )
		echo "<link rel='apple-touch-icon-precomposed' href='$icon'>";
}

/**
 * Display custom CSS
 * @return void
 * @since 1.0
 */
function whisper_design_css()
{
	$css = '';

	// Custom background
	if ( fitwp_option( 'layout_style' ) == 'boxed' )
	{
		$bg = fitwp_option( 'background' );
		if ( !empty( $bg ) )
		{
			$bg_css = '.boxed {';
			if ( $bg['color'] )
				$bg_css .= 'background-color: ' . $bg['color'] . ';';
			if ( $bg['image'] )
				$bg_css .= 'background-image: url(' . $bg['image'] . ');';
			if ( $bg['repeat'] )
				$bg_css .= 'background-repeat: ' . $bg['repeat'] . ';';
			if ( $bg['attachment'] )
				$bg_css .= 'background-attachment: ' . $bg['attachment'] . ';';
			if ( $bg['position_x'] )
				$bg_css .= 'background-position-x: ' . $bg['position_x'] . ';';
			if ( $bg['position_y'] )
				$bg_css .= 'background-position-y: ' . $bg['position_y'] . ';';
			$bg_css .= '}';

			$css .= $bg_css;
		}
	}

	// Logo margin
	$logo_css = '';
	$positions = array( 'top', 'bottom', 'left', 'right' );
	foreach ( $positions as $position )
	{
		if ( $margin = fitwp_option( "logo_$position" ) )
			$logo_css .= "margin-$position: {$margin}px;";
	}
	if ( $logo_css )
		$css .= "#logo { $logo_css };";

	// Custom font styles
	$css .= whisper_css_font( 'body', fitwp_option( 'body_font' ) );
	$css .= whisper_css_font( 'h1', fitwp_option( 'h1_font' ) );
	$css .= whisper_css_font( 'h2', fitwp_option( 'h2_font' ) );
	$css .= whisper_css_font( 'h3', fitwp_option( 'h3_font' ) );
	$css .= whisper_css_font( 'h4', fitwp_option( 'h4_font' ) );
	$css .= whisper_css_font( 'h5', fitwp_option( 'h5_font' ) );

	// Custom CSS
	if ( $custom_css = fitwp_option( 'custom_css' ) )
		$css .= $custom_css;

	// Featured title area background
	if ( $bg = fitwp_option( 'featured_title_background' ) )
		$css .= ".featured-title { background: url($bg); }";

	if ( $css )
		echo "<style>$css</style>";
}

/**
 * Display CSS for font
 *
 * @param  string $selector CSS selector
 * @param  array  $font     Font properties
 *
 * @return void|string
 */
function whisper_css_font( $selector, $font )
{
	static $families = array(
		'arial' => 'Arial, Helvetica, sans-serif;',
		'verdana' => 'Verdana, Helvetica, sans-serif;',
		'times' => '"Times New Roman", Times, serif;',
		'open sans' => '"Open Sans", Arial, Helvetica, sans-serif;',
	);

	$font = array_filter( (array) $font );
	if ( empty( $font ) )
		return '';

	$output = "$selector {";
	if ( !empty( $font['size'] ) )
		$output .= "font-size: {$font['size']}px;";
	if ( !empty( $font['font'] ) )
		$output .= 'font-family: ' . $families[$font['font']];
	if ( !empty( $font['line_height'] ) )
		$output .= "line-height: {$font['line_height']}px;";
	if ( !empty( $font['color'] ) )
		$output .= "color: {$font['color']};";
	if ( !empty( $font['styles'] ) )
	{
		if ( in_array( 'italic', $font['styles'] ) )
			$output .= 'font-style: italic;';
		if ( in_array( 'bold', $font['styles'] ) )
			$output .= 'font-weight: bold;';
		if ( in_array( 'underline', $font['styles'] ) )
			$output .= 'text-decoration: underline;';
	}
	$output .= '}';

	return $output;
}

/**
 * Echo header scripts in to wp_header
 * Allow shortcodes
 *
 * @return void
 * @since 1.0
 */
function whisper_header_scripts()
{
	if ( $scripts = fitwp_option( 'header_scripts' ) )
		echo $scripts;
}

add_action( 'template_redirect', 'whisper_header_right_sidebar_hook' );

/**
 * Add hook for header right sidebar: above or below nav
 *
 * @return void
 * @since 1.1
 */
function whisper_header_right_sidebar_hook()
{
	if ( !is_active_sidebar( 'header-right' ) )
		return;

	$position = fitwp_option( 'header_sidebar_position' );
	if ( !$position )
		$position = 'below';
	$hook = 'whisper_' . ( 'above' == $position ? 'before' : 'after' ) . '_nav';
	add_action( $hook, 'whisper_header_right_sidebar' );
}

/**
 * Show header right sidebar
 *
 * @return void
 * @since 1.1
 */
function whisper_header_right_sidebar()
{
	$position = fitwp_option( 'header_sidebar_position' );
	if ( !$position )
		$position = 'below';
	echo "<div id='header-right' class='right $position'>";
	dynamic_sidebar( 'header-right' );
	echo '</div>';
}
