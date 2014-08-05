<?php
add_filter( 'fitwp_options', 'whisper_options' );

/**
 * Register theme options
 *
 * @return array
 * @since  1.0
 */
function whisper_options()
{
	// Base URL to options framework images
	$url = THEME_URL . 'inc/functions/options/img/';

	$options = array();

	// General
	$options[] = array(
		'icon'   => 'cog-outline',
		'title'  => __( 'General', 'whisper' ),
		'fields' => array(
			array(
				'id'         => 'favicon',
				'label'      => __( 'Favicon', 'whisper' ),
				'type'       => 'image',
				'label_desc' => sprintf( __( 'Specify a <a href="%s" target="_blank">favicon</a> for your site. Accepted formats: .ico, .png, .gif', 'whisper' ), 'http://en.wikipedia.org/wiki/Favicon' ),
			),
			array(
				'id'         => 'touch_icon',
				'label'      => __( 'Touch Icon (152x152 PNG)', 'whisper' ),
				'type'       => 'image',
				'label_desc' => sprintf( __( 'Specify icon for mobile devices and tablets. <a href="%s" target="_blank">Learn more</a>.', 'whisper' ), 'http://mathiasbynens.be/notes/touch-icons' ),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'    => 'page_comment',
				'label' => __( 'Show Comments On Pages', 'whisper' ),
				'type'  => 'switcher',
			),
			array(
				'type' => 'divider',
			),
			array(
				'label'      => __( 'Backup - Restore', 'whisper' ),
				'type'       => 'backup',
				'input_desc' => __( 'You can transfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options" button above', 'whisper' ),
			),
		),
	);

	// Design
	$options[] = array(
		'icon'  => 'device-desktop',
		'title' => __( 'Design', 'whisper' ),
	);

	// Color scheme
	$options[] = array(
		'title'  => __( 'Color Scheme', 'whisper' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'color_scheme',
				'label'   => __( 'Color Scheme', 'whisper' ),
				'type'    => 'color_scheme',
				'options' => array(
					'red',
					'orange',
					'green',
					'blue',
				),
				'default' => 'orange',
			),
		),
	);

	// Background
	$options[] = array(
		'title'  => __( 'Background', 'whisper' ),
		'level'  => 1,
		'fields' => array(
			array(
				'type' => 'box',
				'text' => __( '<b>Warning:</b> Background image is used <b>ONLY</b> in boxed layout.', 'whisper' ),
			),
			array(
				'id'      => 'background_pattern',
				'label'   => __( 'Background Pattern', 'whisper' ),
				'type'    => 'image_toggle',
				'options' => array(
					'patt1' => THEME_URL . 'img/patt1.png',
					'patt2' => THEME_URL . 'img/patt2.png',
					'patt3' => THEME_URL . 'img/patt3.png',
					'patt4' => THEME_URL . 'img/patt4.png',
					'patt5' => THEME_URL . 'img/patt5.png',
				),
				'default' => 'patt4',
			),
			array(
				'id'         => 'background',
				'label'      => __( 'Custom Background', 'whisper' ),
				'type'       => 'background',
				'label_desc' => sprintf( __( 'A lot of background patterns can be found at <a href="%s" target="_blank">Subtle Patterns</a>.', 'whisper' ), 'http://subtlepatterns.com/' ),
			),
		),
	);

	// Layout
	$options[] = array(
		'title'  => __( 'Layout', 'whisper' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'layout_style',
				'label'   => __( 'Layout Style', 'whisper' ),
				'type'    => 'image_toggle',
				'default' => 'wide',
				'options' => array(
					'wide'  => $url . 'layout/wide.png',
					'boxed' => $url . 'layout/box.png',
				)
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'site_layout',
				'label'      => __( 'Site Layout', 'whisper' ),
				'type'       => 'image_toggle',
				'default'    => 'sidebar-right',
				'options'    => array(
					'full-content'  => $url . 'sidebars/empty.png',
					'sidebar-left'  => $url . 'sidebars/single-left.png',
					'sidebar-right' => $url . 'sidebars/single-right.png',
				),
				'label_desc' => __( 'Specify layout for all pages on website, e.g. blog posts, archives, etc.', 'whisper' ),
				'input_desc' => __( 'Single post/page can overwrite this settings in Display Settings meta box when edit.', 'whisper' ),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'page_layout',
				'label'      => __( 'Page Layout', 'whisper' ),
				'type'       => 'image_toggle',
				'default'    => 'sidebar-right',
				'options'    => array(
					'full-content'  => $url . 'sidebars/empty.png',
					'sidebar-left'  => $url . 'sidebars/single-left.png',
					'sidebar-right' => $url . 'sidebars/single-right.png',
				),
				'label_desc' => __( 'Specify layout for pages on website.', 'whisper' ),
				'input_desc' => __( 'Single page can overwrite this settings in Display Settings meta box when edit.', 'whisper' ),
			),
		),
	);

	// Typography
	$options[] = array(
		'title'  => __( 'Typography', 'whisper' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'    => 'body_font',
				'label' => __( 'Body Text', 'whisper' ),
				'type'  => 'font',
				'fonts' => array(
					'arial'     => 'Arial',
					'verdana'   => 'Verdana',
					'times'     => 'Times New Roman',
					'open sans' => 'Open Sans',
				),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'    => 'h1_font',
				'label' => __( 'Heading 1', 'whisper' ),
				'type'  => 'font',
				'fonts' => array(
					'arial'     => 'Arial',
					'verdana'   => 'Verdana',
					'times'     => 'Times New Roman',
					'open sans' => 'Open Sans',
				),
			),
			array(
				'id'    => 'h2_font',
				'label' => __( 'Heading 2', 'whisper' ),
				'type'  => 'font',
				'fonts' => array(
					'arial'     => 'Arial',
					'verdana'   => 'Verdana',
					'times'     => 'Times New Roman',
					'open sans' => 'Open Sans',
				),
			),
			array(
				'id'    => 'h3_font',
				'label' => __( 'Heading 3', 'whisper' ),
				'type'  => 'font',
				'fonts' => array(
					'arial'     => 'Arial',
					'verdana'   => 'Verdana',
					'times'     => 'Times New Roman',
					'open sans' => 'Open Sans',
				),
			),
			array(
				'id'    => 'h4_font',
				'label' => __( 'Heading 4', 'whisper' ),
				'type'  => 'font',
				'fonts' => array(
					'arial'     => 'Arial',
					'verdana'   => 'Verdana',
					'times'     => 'Times New Roman',
					'open sans' => 'Open Sans',
				),
			),
			array(
				'id'    => 'h5_font',
				'label' => __( 'Heading 5', 'whisper' ),
				'type'  => 'font',
				'fonts' => array(
					'arial'     => 'Arial',
					'verdana'   => 'Verdana',
					'times'     => 'Times New Roman',
					'open sans' => 'Open Sans',
				),
			),
			array(
				'id'    => 'h6_font',
				'label' => __( 'Heading 6', 'whisper' ),
				'type'  => 'font',
				'fonts' => array(
					'arial'     => 'Arial',
					'verdana'   => 'Verdana',
					'times'     => 'Times New Roman',
					'open sans' => 'Open Sans',
				),
			),
		),
	);

	// Custom CSS
	$options[] = array(
		'title'  => __( 'Custom CSS', 'whisper' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'         => 'custom_css',
				'label'      => __( 'Custom CSS', 'whisper' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Enter your custom CSS here. This will overwrite theme default CSS.', 'whisper' ),
			),
		),
	);

	// Header
	$options[] = array(
		'icon'   => 'cog-outline',
		'title'  => __( 'Header', 'whisper' ),
		'fields' => array(
			array(
				'id'         => 'logo',
				'label'      => __( 'Logo', 'whisper' ),
				'type'       => 'image',
				'label_desc' => __( 'Specify logo URL or upload, select from Media Library.', 'whisper' ),
			),
			array(
				'label'      => __( 'Logo Size (Optional)', 'whisper' ),
				'label_desc' => __( 'Best size is 201x39', 'whisper' ),
				'type'       => 'group',
				'children'   => array(
					array(
						'id'         => 'logo_width',
						'type'       => 'number',
						'input_desc' => __( 'Width', 'whisper' ),
						'suffix'     => 'px',
					),
					array(
						'id'         => 'logo_height',
						'type'       => 'number',
						'input_desc' => __( 'Height', 'whisper' ),
						'suffix'     => 'px',
					),
				)
			),
			array(
				'label'      => __( 'Logo Margin (Optional)', 'whisper' ),
				'label_desc' => __( 'Use if you want to move logo up or down', 'whisper' ),
				'type'       => 'group',
				'children'   => array(
					array(
						'id'         => 'logo_top',
						'type'       => 'number',
						'input_desc' => __( 'Top', 'whisper' ),
						'suffix'     => 'px',
					),
					array(
						'id'         => 'logo_bottom',
						'type'       => 'number',
						'input_desc' => __( 'Bottom', 'whisper' ),
						'suffix'     => 'px',
					),
					array(
						'id'         => 'logo_left',
						'type'       => 'number',
						'input_desc' => __( 'Left', 'whisper' ),
						'suffix'     => 'px',
					),
					array(
						'id'         => 'logo_right',
						'type'       => 'number',
						'input_desc' => __( 'Right', 'whisper' ),
						'suffix'     => 'px',
					),
				)
			),
			array(
				'type' => 'divider',
			),
			array(
				'label'   => __( 'Header Sidebar Position', 'whisper' ),
				'id'      => 'header_sidebar_position',
				'type'    => 'select',
				'options' => array(
					'above' => __( 'Above Main Navigation', 'whisper' ),
					'below' => __( 'Below Main Navigation', 'whisper' ),
				),
				'size'    => 'medium',
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'header_scripts',
				'label'      => __( 'Header Scripts', 'whisper' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Enter scripts or code you would like output to <code>&lt;head&gt;</code>. It can be custom font link, meta tags, javascript, etc.', 'whisper' ),
			),
		),
	);

	// Featured Title Area
	$options[] = array(
		'icon'   => 'wi-fi-outline',
		'title'  => __( 'Featured Title Area', 'whisper' ),
		'fields' => array(
			array(
				'id'         => 'hide_breadcrumbs',
				'label'      => __( 'Hide breadcrumbs', 'whisper' ),
				'type'       => 'switcher',
				'input_desc' => __( 'Single post/page can overwrite this settings in Display Settings meta box when edit.', 'whisper' ),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'featured_title_background',
				'label'      => __( 'Custom Background', 'whisper' ),
				'type'       => 'image',
				'input_desc' => __( 'Single post/page can overwrite this settings in Display Settings meta box when edit.', 'whisper' ),
			),
		),
	);

	// Blog
	$options[] = array(
		'icon'   => 'cog-outline',
		'title'  => __( 'Blog', 'whisper' ),
		'fields' => array(
			array(
				'id'         => 'blog_display',
				'label'      => __( 'Display', 'whisper' ),
				'type'       => 'select',
				'options'    => array(
					'excerpt' => __( 'Post excerpt', 'whisper' ),
					'content' => __( 'Post content', 'whisper' ),
					'more'    => __( 'Post content before more tag', 'whisper' ),
				),
				'default'    => 'content',
				'label_desc' => __( 'Select type of post content will be displayed in blog page.', 'whisper' ),
			),
			array(
				'id'         => 'blog_content_limit',
				'label'      => __( 'Post Content Limit', 'whisper' ),
				'type'       => 'number',
				'suffix'     => __( 'words', 'whisper' ),
				'default'    => 55,
				'input_desc' => __( '<b>Important:</b> This setting is NOT applied if you select "Post content before more tag" above.', 'whisper' ),
			),
			array(
				'id'      => 'blog_more',
				'label'   => __( 'Readmore text', 'whisper' ),
				'type'    => 'text',
				'size'    => 'large',
				'default' => __( 'Continue reading', 'whisper' ),
			),
		),
	);

	// Footer
	$options[] = array(
		'icon'   => 'cog-outline',
		'title'  => __( 'Footer', 'whisper' ),
		'fields' => array(
			array(
				'id'      => 'footer_columns',
				'label'   => __( 'Footer Columns', 'whisper' ),
				'type'    => 'image_toggle',
				'default' => '4',
				'options' => array(
					'1' => $url . 'footer/one-column.png',
					'2' => $url . 'footer/two-columns.png',
					'3' => $url . 'footer/three-columns.png',
					'4' => $url . 'footer/four-columns.png',
				)
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'footer_copyright',
				'label'      => __( 'Footer Copyright', 'whisper' ),
				'input_desc' => __( 'HTML and Shortcodes are allowed. Available shortcodes: <code>[year]</code>, <code>[bloginfo]</code>, <code>[site_link]</code>', 'whisper' ),
				'type'       => 'text',
				'size'       => 'xxlarge',
				'default'    => sprintf( __( 'Copyright &copy;[year] [site_link]. All rights reserved.', 'whisper' ), 'http://fitwp.com' ),
			),
			array(
				'id'       => 'footer_bottom_info',
				'label'    => __( 'Contact Information', 'whisper' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'footer_info_phone',
						'input_desc' => __( 'Phone Number', 'whisper' ),
						'type'       => 'text',
					),
					array(
						'id'         => 'footer_info_email',
						'input_desc' => __( 'Contact Email', 'whisper' ),
						'type'       => 'email',
					)
				)
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'footer_scripts',
				'label'      => __( 'Footer Scripts', 'whisper' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Enter scripts or code you would like output before <code>&lt;/body&gt;</code>. It can be Google Analytics code or something else.', 'whisper' ),
			),
		),
	);

	return $options;
}

add_filter( 'fitwp_options_meta', 'whisper_options_meta' );

/**
 * Register theme options meta information, like theme links, info, etc.
 *
 * @param array $meta
 *
 * @return array
 * @since  1.0
 */
function whisper_options_meta( $meta )
{
	$meta['links'] = array(
		'http://fitwp.com/section/whisper/'        => __( 'Documentation', 'whisper' ),
		'http://fitwp.com/envato-support/whisper/' => __( 'Support', 'whisper' ),
	);
	return $meta;
}

add_action( 'fitwp_options_enqueue', 'whisper_options_enqueue' );

/**
 * Enqueue custom scripts, styles for theme options
 *
 * @return void
 * @since 1.0
 */
function whisper_options_enqueue()
{
	wp_enqueue_style( 'whisper-options', THEME_URL . 'css/admin/options.css' );
}