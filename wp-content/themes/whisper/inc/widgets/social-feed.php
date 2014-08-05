<?php
class Whisper_Widget_Social_Feed extends WP_Widget
{
	/**
	 * Constructor
	 *
	 * @return Whisper_Widget_Social_Feed
	 */
	function __construct()
	{
		parent::__construct(
			'whisper-social-feed',
			__( 'Whisper - Social Feed', 'whisper' ),
			array(
				'classname'   => 'whisper-social-feed',
				'description' => __( 'Shows items from the feed of social networks.', 'whisper' ),
			)
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
		$instance = wp_parse_args( $instance, array(
			'title'    => '',
			'network'  => '',
			'username' => '',
			'limit'    => '',

		) );

		if ( !$instance['network'] || !$instance['username']  )
			return;

		extract( $args );
		echo $before_widget;

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) )
			echo $before_title . $title . $after_title;

		$shortcode = sprintf( '[social_feed network="%s" username="%s" limit="%s"]', $instance['network'], $instance['username'], $instance['limit']  );
		echo do_shortcode( $shortcode );
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
		$instance = array();
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['network']  = strip_tags( $new_instance['network'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['limit']    = (int)( $new_instance['limit'] );

		return $instance;
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
		$instance = wp_parse_args( $instance, array(
			'title'    => '',
			'network'  => '',
			'username' => '',
			'limit'    => '',
		) );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'whisper' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $instance['title'] ?>" style="width:99%;"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'network' ) ); ?>"><?php _e( 'Choose Network', 'whisper' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'network' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'network' ) ); ?>">
				<option value="pinterest" <?php selected( 'pinterest', $instance['network'] ); ?>><?php _e( 'Pinterest', 'whisper' ) ?></option>
				<option value="devian_art" <?php selected( 'devian_art', $instance['network'] ); ?>><?php _e( 'Devian Art', 'whisper' ) ?></option>
				<option value="flickr" <?php selected( 'flickr', $instance['network'] ); ?>><?php _e( 'Flickr', 'whisper' ) ?></option>
				<option value="dribbble" <?php selected( 'dribbble', $instance['network'] ); ?>><?php _e( 'Dribbble', 'whisper' ) ?></option>
				<option value="youtube" <?php selected( 'youtube', $instance['network'] ); ?>><?php _e( 'Youtube', 'whisper' ) ?></option>
				<option value="newsfeed" <?php selected( 'newsfeed', $instance['network'] ); ?>><?php _e( 'Newsfeed', 'whisper' ) ?></option>
				<option value="instagram" <?php selected( 'instagram', $instance['network'] ); ?>><?php _e( 'Instagram', 'whisper' ) ?></option>
				<option value="picasa" <?php selected( 'picasa', $instance['network'] ); ?>><?php _e( 'Picasa', 'whisper' ) ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php _e( 'Username', 'whisper' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" value="<?php echo $instance['username'] ?>" size="10">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Number Of Items', 'whisper' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" value="<?php echo $instance['limit'] ?>" size="3">
		</p>
	<?php
	}
}

add_action( 'widgets_init', 'whisper_register_widget_social_feed' );

/**
 * Register widget tweets
 *
 * @return void
 * @since 1.0
 */
function whisper_register_widget_social_feed()
{
	register_widget( 'Whisper_Widget_Social_Feed' );
}