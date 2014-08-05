<?php
class Whisper_Widget_Tweets extends WP_Widget
{
	/**
	 * Constructor
	 *
	 * @return Whisper_Widget_Tweets
	 */
	function __construct()
	{
		parent::__construct(
			'whisper-tweets',
			__( 'Whisper - Tweets', '7listings' ),
			array(
				'description' => __( 'Display latest tweets', '7listings' ),
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
			'title'               => '',
			'consumer_key'        => '',
			'consumer_secret'     => '',
			'access_token'        => '',
			'access_token_secret' => '',
			'cache_time'          => 3600,
			'username'            => '',
			'number'              => 2
		) );

		if ( !$instance['consumer_key'] || !$instance['consumer_secret'] || !$instance['access_token'] || !$instance['access_token_secret'] || !$instance['cache_time'] || !$instance['username'] )
			return;

		extract( $args );
		echo $before_widget;

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) )
			echo $before_title . $title . $after_title;

		$transient_key = 'whisper_tweets_' . md5( serialize( $instance ) );
		if ( false === ( $tweets = get_transient( $transient_key ) ) )
		{
			require_once THEME_DIR . 'inc/widgets/twitter-api-php.php';

			$settings = array(
				'oauth_access_token'        => $instance['access_token'],
				'oauth_access_token_secret' => $instance['access_token_secret'],
				'consumer_key'              => $instance['consumer_key'],
				'consumer_secret'           => $instance['consumer_secret'],
			);

			$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
			$fields = "?screen_name={$instance['username']}&count={$instance['number']}";
			$method = 'GET';

			$twitter = new TwitterAPIExchange( $settings );
			$tweets = $twitter->setGetfield( $fields )->buildOauth( $url, $method )->performRequest();
			$tweets = @json_decode( $tweets );

			if ( empty( $tweets ) )
			{
				_e( 'Cannot retrieve tweets.', 'whisper' );
				echo $after_widget;
				return;
			}

			// Save our new transient.
			set_transient( $transient_key, $tweets, $instance['cache_time'] );
		}

		echo '<ul class="tweet-list">';
		foreach ( $tweets as $tweet )
		{
			printf( '<li>%s</li>', $this->convert_links( $tweet->text ) );
		}
		echo '</ul>';
		echo $after_widget;
	}

	/**
	 * Replace link tweet
	 *
	 * @param $text
	 *
	 * @return string
	 */
	function convert_links( $text )
	{
		$text = preg_replace( '#https?://[a-z0-9._/-]+#i', '<a rel="nofollow" target="_blank" href="$0">$0</a>', $text );
		$text = preg_replace( '#@([a-z0-9_]+)#i', '@<a rel="nofollow" target="_blank" href="http://twitter.com/$1">$1</a>', $text );
		$text = preg_replace( '# \#([a-z0-9_-]+)#i', ' #<a rel="nofollow" target="_blank" href="http://twitter.com/search?q=%23$1">$1</a>', $text );
		return $text;
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
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['consumer_key'] = strip_tags( $new_instance['consumer_key'] );
		$instance['consumer_secret'] = strip_tags( $new_instance['consumer_secret'] );
		$instance['access_token'] = strip_tags( $new_instance['access_token'] );
		$instance['access_token_secret'] = strip_tags( $new_instance['access_token_secret'] );
		$instance['cache_time'] = strip_tags( $new_instance['cache_time'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['number'] = strip_tags( $new_instance['number'] );

		if ( $old_instance['username'] != $new_instance['username'] )
			delete_option( 'tp_twitter_plugin_last_cache_time' );

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
			'title'               => '',
			'consumer_key'        => '',
			'consumer_secret'     => '',
			'access_token'        => '',
			'access_token_secret' => '',
			'cache_time'          => 3600,
			'username'            => '',
			'number'              => 2
		) );
		$fields = array(
			'title'               => __( 'Title', 'whisper' ),
			'consumer_key'        => __( 'Consumer Key', 'whisper' ),
			'consumer_secret'     => __( 'Consumer Secret', 'whisper' ),
			'access_token'        => __( 'Access Token', 'whisper' ),
			'access_token_secret' => __( 'Access Token Secret', 'whisper' ),
			'cache_time'          => __( 'Cache Time (seconds)', 'whisper' ),
			'username'            => __( 'Twitter Username', 'whisper' ),
			'number'              => __( 'Number Of Tweets', 'whisper' ),
		);
		foreach ( $fields as $k => $v )
		{
			printf(
				'<p>
					<label for="%s">%s</label>
					<input type="text" class="widefat" id="%s" name="%s" value="%s">
				</p>',
				$this->get_field_id( $k ),
				$v,
				$this->get_field_id( $k ),
				$this->get_field_name( $k ),
				$instance[$k]
			);
		}
	}
}

add_action( 'widgets_init', 'whisper_register_widget_tweets' );

/**
 * Register widget tweets
 *
 * @return void
 * @since 1.0
 */
function whisper_register_widget_tweets()
{
	register_widget( 'Whisper_Widget_Tweets' );
}