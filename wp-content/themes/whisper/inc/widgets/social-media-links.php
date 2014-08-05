<?php
class Whisper_Widget_Social_Links extends WP_Widget
{
	protected $default;
	protected $socials;

	/**
	 * Constructor
	 *
	 * @return Whisper_Widget_Social_Links
	 */
	function __construct()
	{
		$this->socials = array(
			'facebook-1'  => __( 'Facebook', 'whisper' ),
			'twitter-1'   => __( 'Twitter', 'whisper' ),
			'linkedin'    => __( 'Linkedin', 'whisper' ),
			'google_plus' => __( 'Google Plus', 'whisper' ),
			'pinterest'   => __( 'Pinterest', 'whisper' ),
			'tumblr'      => __( 'Tumblr', 'whisper' ),
			'flickr'      => __( 'Flickr', 'whisper' ),
			'myspace'     => __( 'MySpace', 'whisper' ),
			'instagram'   => __( 'Instagram', 'whisper' ),
			'deviantart'  => __( 'DeviantArt', 'whisper' ),
			'youtube'     => __( 'Youtube', 'whisper' ),
			'stumbleupon' => __( 'StumbleUpon', 'whisper' ),
			'foursquare'  => __( 'FourSquare', 'whisper' ),
			'github-2'    => __( 'Github', 'whisper' ),
			'dribbble'    => __( 'Dribbble', 'whisper' ),
			'rss'         => __( 'RSS', 'whisper' ),
		);
		$this->default = array(
			'title' => '',
		);
		foreach ( $this->socials as $k => $v )
		{
			$this->default["{$k}_title"] = '';
			$this->default["{$k}_url"] = '';
		}

		parent::__construct(
			'whisper-social-links',
			__( 'Whisper - Social Links', 'whisper' ),
			array(
				'classname'   => 'whisper-social-links',
				'description' => __( 'Display links to social media networks.', 'whisper' ),
			),
			array( 'width'  => 600, 'height' => 350 )
		);
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 *
	 * @return void Echoes it's output
	 */
	function widget( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->default );

		extract( $args );
		echo $before_widget;

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) )
			echo $before_title . $title . $after_title;

		foreach ( $this->socials as $social => $label )
		{
			if ( !empty( $instance[$social . '_title'] ) || !empty( $instance[$social . '_url'] ) )
			{
				$shortcode = sprintf( '[social class="pixons-%s" url="%s" title="%s"]', $social, $instance[$social . '_url'], $instance[$social . '_title'] );
				echo do_shortcode ( $shortcode );
			}
		}

		echo $after_widget;
	}


	/**
	 * Deals with the settings when they are saved by the admin.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance )
	{
		return $new_instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance
	 *
	 * @return array
	 */
	function form( $instance )
	{
		$instance = wp_parse_args( $instance, $this->default );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'whisper' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<?php
		foreach ( $this->socials as $social => $label )
		{
			printf(
				'<div style="width: 280px; float: left; margin-right: 10px;">
					<label>%s</label>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
				</div>',
				$label,
				$this->get_field_name( $social . '_title' ),
				__( 'Title', 'whisper' ),
				$instance[$social . '_title'],
				$this->get_field_name( $social . '_url' ),
				__( 'URL', 'whisper' ),
				$instance[$social . '_url']
			);
		}
	}
}

add_action( 'widgets_init', 'whisper_register_widget_social_links' );

/**
 * Register widget tweets
 *
 * @return void
 * @since 1.0
 */
function whisper_register_widget_social_links()
{
	register_widget( 'Whisper_Widget_Social_Links' );
}