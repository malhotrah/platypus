<?php
/**
 * Tabs Widget Class
 */
class Whisper_Widget_Tabs extends WP_Widget
{
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Class constructor
	 * Set up the widget
	 *
	 * @return Whisper_Widget_Tabs
	 */
	function __construct()
	{
		$this->defaults = array(
			'popular_show'          => 1,
			'popular_title'         => __( 'Popular', 'whisper' ),
			'popular_limit'         => 5,
			'popular_thumb'         => 1,
			'popular_thumb_default' => 'http://placehold.it/60x60',
			'popular_comments'      => 0,
			'popular_date'          => 1,

			'popular_date_format'   => 'd/m/Y',

			'recent_show'           => 1,
			'recent_title'          => __( 'Recent', 'whisper' ),
			'recent_limit'          => 5,
			'recent_thumb'          => 1,
			'recent_thumb_default'  => 'http://placehold.it/60x60',
			'recent_comments'       => 0,
			'recent_date'           => 1,
			'recent_date_format'    => 'd/m/Y',

			'comments_show'         => 1,
			'comments_title'        => __( 'Comments', 'whisper' ),
			'comments_limit'        => 5,
		);

		$this->WP_Widget(
			'whisper-tabs',
			__( 'Whisper - Tabs', 'whisper' ),
				array(
				'classname'   => 'widget-tabs whisper-recent-posts',
				'description' => __( 'Display most popular posts, recent posts, recent comments in tabbed widget.', 'whisper' ),
			),
			array( 'width' => 780, 'height' => 350 )
		);

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments
	 * @param array $instance Saved values from database
	 *
	 * @return void
	 */
	function widget( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->defaults );
		extract( $instance );

		extract( $args );
		echo $before_widget;

		echo '<div class="fitsc-tabs">';
		echo '<ul class="fitsc-nav">';
		if ( $popular_show )
			echo "<li><a href='#'>$popular_title</a></li>";

		if ( $recent_show )
			echo "<li><a href='#'>$recent_title</a></li>";

		if ( $comments_show )
			echo "<li><a href='#'>$comments_title</a></li>";
		echo '</ul>';
		?>
		<div class="fitsc-content">
			<?php if ( $popular_show ) : ?>
				<div class="fitsc-tab fitsc-active">
					<?php
					$popular_posts = new WP_Query( array(
						'posts_per_page' => $popular_limit,
						'orderby'   => 'comment_count',
						'order'     => 'DESC'
					) );
					while ( $popular_posts->have_posts() ): $popular_posts->the_post();
						$class = $popular_thumb ? '' : ' list';
						?>
						<article class="popular-post<?php echo $class; ?>">
							<?php
							if ( $popular_thumb )
							{
								$src = whisper_post_thumbnail_src( 'widget-thumb' );
								if ( !$src )
									$src = $popular_thumb_default;
								if ( $src )
								{
									printf(
										'<a class="thumb" href="%s" title="%s"><img src="%s" alt="%s"></a>',
										get_permalink(),
										the_title_attribute( 'echo=0' ),
										$src,
										the_title_attribute( 'echo=0' )
									);
								}
							}
							?>
							<div class="text">
								<a class="title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'whisper' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
								<?php
								if ( $popular_date )
									echo '<time class="date">' . esc_html( get_the_time( $popular_date_format ) ) . '</time>';

								if ( $popular_comments )
									echo '<span class="comments">' . sprintf( __( '%s Comments', 'whisper' ), get_comments_number() ) . '</span>';
								?>
							</div>
						</article>
					<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>

			<?php if ( $recent_show ) : ?>
				<div class="fitsc-tab">
					<?php
					the_widget(
						'Whisper_Widget_Recent_Posts',
						array(
							'limit'         => $recent_limit,
							'thumb'         => $recent_thumb,
							'thumb_default' => $recent_thumb_default,
							'date'          => $recent_date,
							'comments'      => $recent_comments,
							'date_format'   => $recent_date_format,
						),
						array(
							'before_widget' => '',
							'after_widget'  => '',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<?php
			if ( $comments_show )
			{
				echo '<div class="fitsc-tab comment-tab">';
				$comments = get_comments( array(
					'status' => 'approve',
					'number' => $comments_limit,
				) );

				foreach ( $comments as $comment )
				{
					echo sprintf(
						'<div class="comment">
							<p class="comment-summary">%s <span class="author-comment">%s %s</span></p>
							<span class="post-comment">%s <a href="%s" title="%s">%s</a></span>
						</div>',
						wp_trim_words( strip_tags( $comment->comment_content ), 10 ),
						__( 'by', 'whisper' ),
						$comment->comment_author,
						__( 'on', 'whisper' ),
						get_comments_link( $comment->comment_post_ID ),
						get_the_title( $comment->comment_post_ID ),
						wp_trim_words( strip_tags( get_the_title( $comment->comment_post_ID ) ), 7 )
					);
				}

				echo '</div>';
			}
			?>
		</div>
		<?php
		echo '<div>';
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array Updated safe values to be saved
	 */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['popular_show']          = $new_instance['popular_show'] ? 1 : 0;
		$instance['popular_title']         = strip_tags( $new_instance['popular_title'] );
		$instance['popular_limit']         = (int)( $new_instance['popular_limit'] );
		$instance['popular_comments']      = $new_instance['popular_comments'] ? 1 : 0;
		$instance['popular_thumb']         = $new_instance['popular_thumb'] ? 1 : 0;
		$instance['popular_thumb_default'] = $new_instance['popular_thumb_default'];
		$instance['popular_date']          = $new_instance['popular_date'] ? 1 : 0;
		$instance['popular_date_format']   = strip_tags( $new_instance['popular_date_format'] );

		$instance['recent_show']           = $new_instance['recent_show'] ? 1 : 0;
		$instance['recent_title']          = strip_tags( $new_instance['recent_title'] );
		$instance['recent_limit']          = (int)( $new_instance['recent_limit'] );
		$instance['recent_comments']       = $new_instance['recent_comments'] ? 1 : 0;
		$instance['recent_thumb']          = $new_instance['recent_thumb'] ? 1 : 0;
		$instance['recent_thumb_default']  = $new_instance['recent_thumb_default'];
		$instance['recent_date']           = $new_instance['recent_date'] ? 1 : 0;
		$instance['recent_date_format']    = strip_tags( $new_instance['recent_date_format'] );

		$instance['comments_show']         = $new_instance['comments_show'] ? 1 : 0;
		$instance['comments_title']        = strip_tags( $new_instance['comments_title'] );
		$instance['comments_limit']        = (int)( $new_instance['comments_limit'] );

		return $instance;
	}

	/**
	 * Displays the widget options
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	function form( $instance )
	{
		// Merge with defaults
		$instance = wp_parse_args( $instance, $this->defaults );

		?>
		<div style="width: 250px; float: left; margin-right: 10px;">
			<p><strong><?php _e( 'Popular Posts', 'whisper' ); ?></strong></p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'popular_show' ); ?>" name="<?php echo $this->get_field_name( 'popular_show' ); ?>" value="1" <?php checked( 1, $instance['popular_show'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'popular_show' ); ?>"><?php _e( 'Show Popular Tab', 'whisper' ); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'popular_title' ); ?>"><?php _e( 'Title', 'whisper' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'popular_title' ); ?>" name="<?php echo $this->get_field_name( 'popular_title' ); ?>" value="<?php echo esc_attr( $instance['popular_title'] ); ?>" />
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_limit' ) ); ?>" type="text" size="2" value="<?php echo $instance['popular_limit']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_limit' ) ); ?>"><?php _e( 'Number Of Posts', 'whisper' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_comments' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_comments'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_comments' ) ); ?>"><?php _e( 'Show Comment Number', 'whisper' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_thumb' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_thumb'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_thumb' ) ); ?>"><?php _e( 'Show Thumbnail', 'whisper' ); ?></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_thumb_default' ) ); ?>"><?php _e( 'Default Thumbnail', 'whisper' ); ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'popular_thumb_default' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_thumb_default' ) ); ?>" value="<?php echo $instance['popular_thumb_default']; ?>">

			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_date' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_date'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_date' ) ); ?>"><?php _e( 'Show Date', 'whisper' ); ?></label>
			</p>
			<p>
				<input size="6" id="<?php echo esc_attr( $this->get_field_id( 'popular_date_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_date_format' ) ); ?>" type="text" value="<?php echo $instance['popular_date_format']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_date_format' ) ); ?>"><?php _e( 'Date Format', 'whisper' ); ?></label>
				<a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">[?]</a>
			</p>
		</div>
		<div style="width: 250px; float: left; margin-right: 10px;">
			<p><strong><?php _e( 'Recent Posts', 'whisper' ); ?></strong></p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'recent_show' ); ?>" name="<?php echo $this->get_field_name( 'recent_show' ); ?>" value="1" <?php checked( 1, $instance['recent_show'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'recent_show' ); ?>"><?php _e( 'Show Recent Posts Tab', 'whisper' ); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'recent_title' ); ?>"><?php _e( 'Title', 'whisper' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'recent_title' ); ?>" name="<?php echo $this->get_field_name( 'recent_title' ); ?>" value="<?php echo esc_attr( $instance['recent_title'] ); ?>" />
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_limit' ) ); ?>" type="text" size="2" value="<?php echo $instance['recent_limit']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_limit' ) ); ?>"><?php _e( 'Number Of Posts', 'whisper' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_comments' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_comments'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_comments' ) ); ?>"><?php _e( 'Show Comment Number', 'whisper' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_thumb' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_thumb'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_thumb' ) ); ?>"><?php _e( 'Show Thumbnail', 'whisper' ); ?></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_thumb_default' ) ); ?>"><?php _e( 'Default Thumbnail', 'whisper' ); ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'recent_thumb_default' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_thumb_default' ) ); ?>" value="<?php echo $instance['recent_thumb_default']; ?>">

			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_date' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_date'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_date' ) ); ?>"><?php _e( 'Show Date', 'whisper' ); ?></label>
			</p>
			<p>
				<input size="6" id="<?php echo esc_attr( $this->get_field_id( 'recent_date_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_date_format' ) ); ?>" type="text" value="<?php echo $instance['recent_date_format']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_date_format' ) ); ?>"><?php _e( 'Date Format', 'whisper' ); ?></label>
				<a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">[?]</a>
			</p>
		</div>
		<div style="width: 250px;float:left;">
			<p><strong><?php _e( 'Recent Comments', 'whisper' ); ?></strong></p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'comments_show' ); ?>" name="<?php echo $this->get_field_name( 'comments_show' ); ?>" value="1" <?php checked( 1, $instance['comments_show'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'comments_show' ); ?>"><?php _e( 'Show Recent Posts Tab', 'whisper' ); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'comments_title' ); ?>"><?php _e( 'Title', 'whisper' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'comments_title' ); ?>" name="<?php echo $this->get_field_name( 'comments_title' ); ?>" value="<?php echo esc_attr( $instance['comments_title'] ); ?>" />
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'comments_limit' ); ?>" name="<?php echo $this->get_field_name( 'comments_limit' ); ?>" type="text" value="<?php echo $instance['comments_limit']; ?>" size="3">
				<label for="<?php echo $this->get_field_id( 'comments_limit' ); ?>"><?php _e( 'Number of comments to show', 'whisper' ); ?></label>
			</p>
		</div>
		<div class="clear"></div>
	<?php
	}
}

// Register widget
add_action( 'widgets_init', 'whisper_register_widget_tabs' );

/**
 * Register widget
 *
 * @return void
 */
function whisper_register_widget_tabs()
{
	register_widget( 'Whisper_Widget_Tabs' );
}

/**
 * Show limit words when input a content and number word limit
 *
 * @param string $string     String word
 * @param int    $word_limit Number word cut
 * @param string $more
 *
 * @return string
 */
function whisper_limit_words( $string, $word_limit, $more = '' )
{
	$words = explode( ' ', $string, ( $word_limit + 1 ) );

	if ( count( $words ) > $word_limit )
	{
		array_pop( $words );
	}

	return implode( ' ', $words ) . $more;
}