<?php
add_filter( 'the_content', 'whisper_post_formats_content' );

/**
 * Remove images in post content if it has post format 'image'
 *
 * @param string $content
 *
 * @return string
 * @since 1.0
 */
function whisper_post_formats_content( $content )
{
	if ( has_post_format( 'image' ) )
		$content = preg_replace( '|<img[^>]*>|i', '', $content );
	if ( has_post_format( 'link' ) )
	{
		$url = whisper_meta( 'url' );
		$text = whisper_meta( 'text' );
		if ( $url && $text )
			$content = "<p><a class='link' href='$url'>$text</a></p>";
	}
	if ( has_post_format( 'quote' ) )
	{
		$quote = whisper_meta( 'quote' );
		$author = whisper_meta( 'author' );
		$author_url = whisper_meta( 'author_url' );
		if ( $author_url )
			$author = "<a href='$author_url'>$author</a>";
		if ( $quote && $author )
			$content = "<blockquote>$quote<cite>$author</cite></blockquote>";
	}

	return $content;
}

add_action( 'whisper_entry_top', 'whisper_post_formats' );

/**
 * Show entry format images, video, gallery, audio, etc.
 * @return void
 */
function whisper_post_formats()
{
	$html = '';
	switch ( get_post_format() )
	{
		case 'image':
			global $whisper;
			$size = 'big-post-thumbs';
			if ( isset( $whisper['is_boxed'] ) )
				$size = 'boxes-post-thumbs';
			$image = whisper_get_image( array(
				'size'     => $size,
				'format'   => 'src',
				'meta_key' => 'image',
				'echo'     => false,
			) );
			if ( !$image )
				break;

			$html = sprintf(
				'<a class="post-image" href="%1$s" title="%2$s"><img src="%3$s" alt="%2$s"></a>',
				get_permalink(),
				the_title_attribute( 'echo=0' ),
				$image
			);
			break;
		case 'gallery':
			global $whisper;
			$size = 'big-post-thumbs';
			if ( isset( $whisper['is_boxed'] ) )
				$size = 'boxes-post-thumbs';
			$images = whisper_meta( 'images', "type=image&size=$size" );

			if ( empty( $images ) )
				break;

			$html .= '<div class="flexslider">';
			$html .= '<ul class="slides">';
			foreach ( $images as $image )
			{
				$html .= sprintf(
					'<li><a href="%s" rel="prettyPhoto[gallery]" class="hover-gradient"><img src="%s" alt="gallery"></a></li>',
					$image['full_url'],
					$image['url']
				);
			}
			$html .= '</ul>';
			$html .= '</div>';
			break;
		case 'audio':
			$audio = whisper_meta( 'audio' );
			if ( !$audio )
				break;

			// If URL: show oEmbed HTML or jPlayer
			if ( filter_var( $audio, FILTER_VALIDATE_URL ) )
			{
				// Try oEmbed first
				if ( $oembed = @wp_oembed_get( $audio ) )
				{
					$html .= $oembed;
				}
				// Use jPlayer
				else
				{
					$id = uniqid();
					$html .= "<div data-player='$id' class='jp-jplayer' data-audio='$audio'></div>";
					$html .= whisper_jplayer( $id );
				}
			}
			// If embed code: just display
			else
			{
				$html .= $audio;
			}
			break;
		case 'video':
			$video = whisper_meta( 'video' );
			if ( !$video )
				break;

			// If URL: show oEmbed HTML
			if ( filter_var( $video, FILTER_VALIDATE_URL ) )
			{
				if ( $oembed = @wp_oembed_get( $video ) )
					$html .= $oembed;
			}
			// If embed code: just display
			else
			{
				$html .= $video;
			}
			break;
		default:
			global $whisper;
			$size = 'big-post-thumbs';
			if ( isset( $whisper['is_boxed'] ) )
				$size = 'boxes-post-thumbs';

			$thumb = get_the_post_thumbnail( get_the_ID(), $size );
			if ( empty( $thumb ) )
				return;

			$html .= '<a class="post-image" href="' . get_permalink() . '">';
			$html .= get_the_post_thumbnail( get_the_ID(), $size );
			$html .= '</a>';
	}

	if ( $html )
		echo "<div class='post-formats-wrapper'>$html</div>";
}

/**
 * Display jPlayer container HTML for audio player
 *
 * @param string $id Player ID
 *
 * @return string
 */
function whisper_jplayer( $id = 'jp_container_1' )
{
	ob_start();
	?>
	<div id="<?php echo $id; ?>" class="jp-audio">
		<div class="jp-type-playlist">
			<div class="jp-gui jp-interface">
				<ul class="jp-controls">
					<li><a href="javascript:;" class="jp-previous" tabindex="1"><?php _e( 'previous', 'whisper' ); ?></a></li>
					<li><a href="javascript:;" class="jp-play" tabindex="1"><?php _e( 'play', 'whisper' ); ?></a></li>
					<li><a href="javascript:;" class="jp-pause" tabindex="1"><?php _e( 'pause', 'whisper' ); ?></a></li>
					<li><a href="javascript:;" class="jp-next" tabindex="1"><?php _e( 'next', 'whisper' ); ?></a></li>
					<li><a href="javascript:;" class="jp-stop" tabindex="1"><?php _e( 'stop', 'whisper' ); ?></a></li>
					<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute"><?php _e( 'mute', 'whisper' ); ?></a></li>
					<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute"><?php _e( 'unmute', 'whisper' ); ?></a></li>
					<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume"><?php _e( 'max volume', 'whisper' ); ?></a></li>
				</ul>
				<div class="jp-progress">
					<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
					</div>
				</div>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
				<div class="jp-time-holder">
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>
				</div>
				<ul class="jp-toggles">
					<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle"><?php _e( 'shuffle', 'whisper' ); ?></a></li>
					<li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle off"><?php _e( 'shuffle off', 'whisper' ); ?></a>
					</li>
					<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat"><?php _e( 'repeat', 'whisper' ); ?></a></li>
					<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off"><?php _e( 'repeat off', 'whisper' ); ?></a></li>
				</ul>
			</div>
			<div class="jp-no-solution">
				<?php printf( __( '<span>Update Required</span> To play the media you will need to either update your browser to a recent version or update your <a href="%s" target="_blank">Flash plugin</a>.', 'whisper' ), 'http://get.adobe.com/flashplayer/' ); ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}


/**
 * Display post formats icon
 *
 * @param bool $link Link icon to post format archive?
 * @param bool $echo Display or return value
 *
 * @return void|string
 *
 * @since 1.0
 */
function whisper_format_icon( $link = false, $echo = true )
{
	$icons = array(
		'standard' => 'write',
		'audio'    => 'speaker',
		'video'    => 'clapboard',
		'image'    => 'camera',
		'gallery'  => 'image',
		'link'     => 'link',
		'quote'    => 'admin',
	);
	$format = get_post_format();
	$icon = isset( $icons[$format] ) ? $icons[$format] : 'write';
	if ( $format == 'standard' && has_post_thumbnail() )
		$icon = 'camera';
	$icon = "<i class='serviceicon-$icon'></i>";
	$class = ( get_post_type() == 'portfolio' ? 'portfolio' : 'format' ) . '-icon';
	if ( $link )
		$icon = "<a class='$class' href='" . get_post_format_link( $format ) . "'>$icon</a>";
	else
		$icon = "<div class='$class'>$icon</div>";
	if ( $echo )
		echo $icon;
	else
		return $icon;
}

/**
 * Get post thumbnail src based on post formats
 * @return void
 */
function whisper_post_thumbnail_src( $size )
{
	$src = '';
	switch ( get_post_format() )
	{
		case 'gallery':
			$images = whisper_meta( 'images', "type=image&size=$size" );

			if ( empty( $images ) )
				break;

			$image = current( $images );
			$src = $image['url'];
			break;
		default:
			$src = whisper_get_image( array(
				'size'     => $size,
				'format'   => 'src',
				'meta_key' => 'image',
				'echo'     => false,
			) );
			break;
	}

	return $src;
}