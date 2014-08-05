<?php
add_filter( 'rwmb_meta_boxes', 'whisper_register_meta_boxes' );

/**
 * Registering meta boxes
 * Using Meta Box plugin: http://www.deluxeblogtips.com/meta-box/
 *
 * @see http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 */
function whisper_register_meta_boxes( $meta_boxes )
{
	// Post Formats
	$meta_boxes[] = array(
		'title'  => __( 'Post Format: Image', 'whisper' ),
		'id'     => 'whisper-meta-box-post-format-image',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name'             => __( 'Image', 'whisper' ),
				'id'               => 'image',
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
			),
		),
	);
	$meta_boxes[] = array(
		'title'  => __( 'Post Format: Gallery', 'whisper' ),
		'id'     => 'whisper-meta-box-post-format-gallery',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'Images', 'whisper' ),
				'id'   => 'images',
				'type' => 'image_advanced',
			),
		),
	);
	$meta_boxes[] = array(
		'title'  => __( 'Post Format: Video', 'whisper' ),
		'id'     => 'whisper-meta-box-post-format-video',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'Video URL or Embeded Code', 'whisper' ),
				'id'   => 'video',
				'type' => 'textarea',
			),
		)
	);
	$meta_boxes[] = array(
		'title'  => __( 'Post Format: Audio', 'whisper' ),
		'id'     => 'whisper-meta-box-post-format-audio',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'Audio URL or Embeded Code', 'whisper' ),
				'id'   => 'audio',
				'type' => 'textarea',
			),
		)
	);
	$meta_boxes[] = array(
		'title'  => __( 'Post Format: Quote', 'whisper' ),
		'id'     => 'whisper-meta-box-post-format-quote',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'Quote', 'whisper' ),
				'id'   => 'quote',
				'type' => 'textarea',
			),
			array(
				'name' => __( 'Author', 'whisper' ),
				'id'   => 'author',
				'type' => 'text',
			),
			array(
				'name' => __( 'Author URL', 'whisper' ),
				'id'   => 'author_url',
				'type' => 'url',
			),
		)
	);
	$meta_boxes[] = array(
		'title'  => __( 'Post Format: Link', 'whisper' ),
		'id'     => 'whisper-meta-box-post-format-link',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'URL', 'whisper' ),
				'id'   => 'url',
				'type' => 'url',
			),
			array(
				'name' => __( 'Text', 'whisper' ),
				'id'   => 'text',
				'type' => 'text',
			),
		)
	);

	// Display Settings
	$meta_boxes[] = array(
		'title'  => __( 'Display Settings', 'whisper' ),
		'pages'  => get_post_types(), // All custom post types
		'fields' => array(
			array(
				'name' => __( 'Featured Title Area?', 'whisper' ),
				'id'   => 'heading_title',
				'type' => 'heading',
			),
			array(
				'name' => __( 'Hide Featured Title Area?', 'whisper' ),
				'id'   => 'hide_title',
				'type' => 'checkbox',
				'class'  => 'checkbox-toggle reverse',
			),
			array(
				'name'   => __( 'Custom Title', 'whisper' ),
				'id'     => 'custom_title',
				'type'   => 'text',
				'desc'   => __( 'Leave empty to use post title', 'whisper' ),
				'before' => '<div>',
			),
			array(
				'name' => __( 'Subtitle', 'whisper' ),
				'id'   => 'subtitle',
				'type' => 'text',
			),
			array(
				'name' => __( 'Custom Background', 'whisper' ),
				'id'   => 'featured_title_background',
				'type' => 'file_input',
				'desc'  => sprintf( __( 'This will <b>overwrite</b> breadcrumbs settings in <a href="%s" target="_blank">Theme Options</a>.', 'whisper' ), admin_url( 'themes.php?page=theme-options' ) ),
			),
			array(
				'name'  => __( 'Hide Breadcrumbs?', 'whisper' ),
				'id'    => 'hide_breadcrumbs',
				'type'  => 'checkbox',
				'desc'  => sprintf( __( 'This will <b>overwrite</b> breadcrumbs settings in <a href="%s" target="_blank">Theme Options</a>.', 'whisper' ), admin_url( 'themes.php?page=theme-options' ) ),
				'after' => '</div>',
			),
			array(
				'name' => __( 'Slider', 'whisper' ),
				'id'   => 'heading_slider',
				'type' => 'heading',
			),
			array(
				'name' => __( 'Slider', 'whisper' ),
				'id'   => 'slider',
				'type' => 'text',
				'desc' => __( 'Enter <b>slider shortcode</b> here to display it under title area.', 'whisper' )
			),
			array(
				'name' => __( 'Custom Layout', 'whisper' ),
				'id'   => 'heading_layout',
				'type' => 'heading',
			),
			array(
				'name'  => __( 'Use Custom Layout?', 'whisper' ),
				'id'    => 'custom_layout',
				'type'  => 'checkbox',
				'class' => 'checkbox-toggle',
				'desc'  => sprintf( __( 'This will <b>overwrite</b> page layout settings in <a href="%s" target="_blank">Theme Options</a>.', 'whisper' ), admin_url( 'themes.php?page=theme-options' ) ),
			),
			array(
				'name'    => __( 'Layout Style', 'whisper' ),
				'id'      => 'layout_style',
				'type'    => 'image_select',
				'options' => array(
					'wide'  => THEME_URL . 'inc/functions/options/img/layout/wide.png',
					'boxed' => THEME_URL . 'inc/functions/options/img/layout/box.png',
				),
				'before'  => '<div>',
			),
			array(
				'name'    => __( 'Select Layout', 'whisper' ),
				'id'      => 'layout',
				'type'    => 'image_select',
				'options' => array(
					'full-content'  => THEME_URL . 'inc/functions/options/img/sidebars/empty.png',
					'sidebar-left'  => THEME_URL . 'inc/functions/options/img/sidebars/single-left.png',
					'sidebar-right' => THEME_URL . 'inc/functions/options/img/sidebars/single-right.png',
				),
				'after'   => '</div>',
			),
		)
	);

	// Contact
	$meta_boxes[] = array(
		'title'  => __( 'Contact Info', 'whisper' ),
		'id'     => 'whisper-meta-box-contact',
		'pages'  => array( 'page' ),
		'fields' => array(
			array(
				'name' => __( 'Contact Form Shortcode', 'whisper' ),
				'id'   => 'form',
				'type' => 'text',
				'desc' => __( 'Enter shortcode for contact form if you use a plugin like Contact Form 7, Gravity Forms. Leave empty to use default form.', 'whisper' ),
			),
			array(
				'name' => __( 'Email', 'whisper' ),
				'id'   => 'email',
				'type' => 'email',
			),
			array(
				'name' => __( 'Phone', 'whisper' ),
				'id'   => 'phone',
				'type' => 'text',
			),
			array(
				'name' => __( 'Address', 'whisper' ),
				'id'   => 'address',
				'type' => 'text',
			),
			array(
				'name'          => __( 'Location', 'whisper' ),
				'id'            => 'location',
				'type'          => 'map',
				'std'           => '-6.233406,-35.049906,15',
				'style'         => 'width:100%;height:400px',
				'address_field' => 'address',
			),
		)
	);

	// Portfolio
	$meta_boxes[] = array(
		'title'  => __( 'Portfolio Settings', 'whisper' ),
		'pages'  => array( 'portfolio' ),
		'fields' => array(
			array(
				'name'    => __( 'Display', 'whisper' ),
				'id'      => 'display',
				'type'    => 'radio',
				'options' => array(
					'default' => __( 'Default', 'whisper' ),
					'simple'  => __( 'Simple', 'whisper' ),
				),
				'std'     => 'default',
			),
			array(
				'name' => __( 'Project Images', 'whisper' ),
				'id'   => 'images',
				'type' => 'image_advanced',
			),
			array(
				'name' => __( 'Project URL', 'whisper' ),
				'id'   => 'url',
				'type' => 'url',
			),
			array(
				'name'   => __( 'Testimonial', 'whisper' ),
				'id'     => 'testimonial',
				'type'   => 'textarea',
				'before' => '<div id="portfolio-testimonial">',
			),
			array(
				'name'  => __( 'Testimonial Author', 'whisper' ),
				'id'    => 'testimonial_author',
				'type'  => 'text',
				'after' => '</div>',
			),
		)
	);
	return $meta_boxes;
}

add_action( 'admin_enqueue_scripts', 'whisper_admin_script_meta_box' );

/**
 * Enqueue script for handling actions with meta boxes
 *
 * @return void
 * @since 1.0
 */
function whisper_admin_script_meta_box()
{
	$screen = get_current_screen();
	if ( !in_array( $screen->post_type, array( 'post', 'page', 'portfolio' ) ) )
		return;

	wp_enqueue_script( 'whisper-meta-box', THEME_URL . 'js/admin/meta-boxes.js', array( 'jquery' ), '', true );
}
