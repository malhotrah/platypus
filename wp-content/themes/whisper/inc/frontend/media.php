<?php
add_action( 'init', 'whisper_register_media' );

/**
 * Register scripts and styles
 * @return void
 * @since  1.0
 */
function whisper_register_media()
{
	$url = THEME_URL . 'js/';

	wp_register_style( 'jquery-nivoslider', $url . 'nivoslider/nivo-slider.css' );
	wp_register_style( 'jquery-prettyPhoto', $url . 'prettyPhoto/prettyPhoto.css' );
	wp_register_style( 'jquery-jplayer', $url . 'jplayer/skin/pixel-industry/pixel-industry.css' );

	wp_register_script( 'jquery-prettyPhoto', $url . 'prettyPhoto/jquery.prettyPhoto.js', array( 'jquery' ), '3.1.5', true );
	wp_register_script( 'jquery-flexslider', $url . 'jquery.flexslider.js', array( 'jquery' ), '', true );
	wp_register_script( 'jquery-quicksand', $url . 'quicksand.js', array( 'jquery' ), '1.4', true );
	wp_register_script( 'jquery-nivoslider', $url . 'nivoslider/jquery.nivo.slider.pack.js', array( 'jquery' ), '3.2', true );
	wp_register_script( 'jquery-carouFredSel', $url . 'jquery.carouFredSel-6.0.0-packed.js', array( 'jquery' ), '6.0.0', true );

	wp_register_script( 'jquery-jplayer', $url . 'jplayer/jquery.jplayer.min.js', array( 'jquery' ), '2.2.0', true );
	wp_register_script( 'jquery-validate', $url . 'jquery.validate.min.js', array( 'jquery' ), '1.11.1', false );

	wp_register_script( 'portfolio', $url . 'portfolio.js', array( 'jquery' ), '', true );
	wp_register_script( 'socialstream', $url . 'socialstream.jquery.js', array( 'jquery' ), '', true );
	wp_register_script( 'whisper', $url . 'script.js', array( 'jquery' ), '', true );
}

/**
 * Display or get post image
 *
 * @param array $args
 *
 * @return void|string
 */
function whisper_get_image( $args = array() )
{
	$default = apply_filters(
		'whisper_get_image_default_args',
		array(
			'post_id'  => get_the_ID(),
			'size'     => 'thumbnail',
			'format'   => 'html', // html or src
			'attr'     => '',
			'meta_key' => '',
			'scan'     => true,
			'default'  => '',
			'echo'     => true,
		)
	);

	$args = wp_parse_args( $args, $default );

	if ( !$args['post_id'] )
		$args['post_id'] = get_the_ID();

	// Get image from cache
	$key = md5( serialize( $args ) );
	$image_cache = wp_cache_get( $args['post_id'], 'whisper_get_image' );

	if ( !is_array( $image_cache ) )
		$image_cache = array();

	if ( empty( $image_cache[$key] ) )
	{
		// Get post thumbnail
		if ( has_post_thumbnail( $args['post_id'] ) )
		{
			$id = get_post_thumbnail_id();
			$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
			list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
		}

		// Get the first image in the custom field
		if ( !isset( $html, $src ) && $args['meta_key'] )
		{
			$id = get_post_meta( $args['post_id'], $args['meta_key'], true );

			// Check if this post has attached images
			if ( $id )
			{
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}
		}

		// Get the first attached image
		if ( !isset( $html, $src ) )
		{
			$image_ids = array_keys( get_children( array(
				'post_parent'    => $args['post_id'],
				'post_type'	     => 'attachment',
				'post_mime_type' => 'image',
				'orderby'        => 'menu_order',
				'order'	         => 'ASC',
			) ) );

			// Check if this post has attached images
			if ( !empty( $image_ids ) )
			{
				$id = $image_ids[0];
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}
		}

		// Get the first image in the post content
		if ( !isset( $html, $src ) && ( $args['scan'] ) )
		{
			preg_match( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );

			if ( !empty( $matches ) )
			{
				$html = $matches[0];
				$src = $matches[1];
			}
		}

		// Use default when nothing found
		if ( !isset( $html, $src ) && !empty( $args['default'] ) )
		{
			if ( is_array( $args['default'] ) )
			{
				$html = @$args['html'];
				$src = @$args['src'];
			}
			else
			{
				$html = $src = $args['default'];
			}
		}

		// Still no images found?
		if ( !isset( $html, $src ) )
			return false;

		$output = 'html' === strtolower( $args['format'] ) ? $html : $src;

		$image_cache[$key] = $output;
		wp_cache_set( $args['post_id'], $image_cache, 'whisper_get_image' );
	}
	// If image already cached
	else
	{
		$output = $image_cache[$key];
	}

	$output = apply_filters( 'whisper_get_image', $output, $args );

	if ( !$args['echo'] )
		return $output;

	echo $output;
}
