<?php
class Whisper_Widget_Recent_Posts extends WP_Widget
{
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor
	 *
	 * @return Whisper_Widget_Recent_Posts
	 */
	function __construct()
	{
		$this->defaults = array(
			'title'         => '',
			'limit'         => 5,
			'excerpt'       => 0,
			'length'        => 10,
			'thumb'         => 1,
			'thumb_default' => 'http://placehold.it/60x60',
			'cat'           => '',
			'date'          => 1,
			'comments'      => 0,
			'date_format'   => 'd/m/Y',
			'readmore'      => 0,
			'readmore_text' => __( 'Read More &raquo;', 'whisper' )
		);

		parent::__construct(
			'whisper-recent-posts',
			__( 'Whisper - Recent Posts', 'whisper' ),
			array(
				'classname'   => 'whisper-recent-posts',
				'description' => __( 'Advanced recent posts widget.', 'whisper' )
			),
			array( 'width'  => 600, 'height' => 350 )
		);
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->defaults );
		extract( $instance );

		$query_args = array(
			'posts_per_page'      => $limit,
			'post_status'         => 'publish',
			'post_type'           => 'post',
			'ignore_sticky_posts' => true,
		);
		if ( !empty( $cat ) && is_array( $cat ) )
			$query_args['category__in'] = $cat;

		$query = new WP_Query( $query_args );

		if ( !$query->have_posts() )
			return;

		extract( $args );

		echo $before_widget;

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) )
			echo $before_title . $title . $after_title;

		while ( $query->have_posts() ) : $query->the_post();
			$class = $thumb ? '' : ' list';
			?>
			<article class="recent-post<?php echo $class; ?>">
				<?php
				if ( $thumb )
				{
					$src = whisper_post_thumbnail_src( 'widget-thumb' );
					if ( !$src )
						$src = $thumb_default;
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
					if ( $date )
						echo '<time class="date">' . esc_html( get_the_time( $date_format ) ) . '</time>';

					if ( $comments )
						echo '<span class="comments">' . sprintf( __( '%s Comments', 'whisper' ), get_comments_number() ) . '</span>';

					if ( $excerpt )
					{
						$more = $readmore ? $readmore_text : '';
						echo '<div class="excerpt">' . whisper_content_limit( $length, $more, false ) . '</div>';
					}
					?>
				</div>
			</article>
		<?php
		endwhile;
		wp_reset_postdata();

		echo $after_widget;

	}

	/**
	 * Update widget
	 */
	function update( $new_instance, $old_instance )
	{
		$instance                  = $old_instance;
		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['limit']         = (int)( $new_instance['limit'] );
		$instance['cat']           = array_filter( $new_instance['cat'] );
		$instance['comments']      = $new_instance['comments'] ? 1 : 0;
		$instance['thumb']         = $new_instance['thumb'] ? 1 : 0;
		$instance['thumb_default'] = $new_instance['thumb_default'];

		$instance['date']          = $new_instance['date'] ? 1 : 0;
		$instance['date_format']   = strip_tags( $new_instance['date_format'] );
		$instance['excerpt']       = $new_instance['excerpt'] ? 1 : 0;
		$instance['length']        = (int)( $new_instance['length'] );
		$instance['readmore']      = $new_instance['readmore'] ? 1 : 0;
		$instance['readmore_text'] = strip_tags( $new_instance['readmore_text'] );

		return $instance;
	}

	/**
	 * Widget setting
	 */
	function form( $instance )
	{
		$instance = wp_parse_args( $instance, $this->defaults );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'whisper' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $instance['title']; ?>">
		</p>

		<div style="width: 280px; float: left; margin-right: 10px;">
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" size="2" value="<?php echo $instance['limit']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Number Of Posts', 'whisper' ); ?></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"><?php _e( 'Select Category: ', 'whisper' ); ?></label>
			   	<select class="widefat" multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cat' ) ); ?>[]">
			   		<option value=""<?php selected( empty( $instance['cat'] ) ); ?>><?php _e( 'All', 'whisper' ); ?></option>
					<?php
					$categories = get_terms( 'category' );
					foreach ( $categories as $category )
					{
						printf(
							'<option value="%s"%s>%s</option>',
							$category->term_id,
							selected( in_array( $category->term_id, (array)$instance['cat'] ) ),
							$category->name
						);
					}
					?>
   			    </select>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'comments' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['comments'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'comments' ) ); ?>"><?php _e( 'Show Comment Number', 'whisper' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['thumb'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>"><?php _e( 'Show Thumbnail', 'whisper' ); ?></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'thumb_default' ) ); ?>"><?php _e( 'Default Thumbnail', 'whisper' ); ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'thumb_default' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_default' ) ); ?>" value="<?php echo $instance['thumb_default']; ?>">

			</p>
		</div>

		<div style="width: 280px; float: left; margin-right: 10px;">

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['date'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php _e( 'Show Date', 'whisper' ); ?></label>
			</p>
			<p>
				<input size="6" id="<?php echo esc_attr( $this->get_field_id( 'date_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date_format' ) ); ?>" type="text" value="<?php echo $instance['date_format']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'date_format' ) ); ?>"><?php _e( 'Date Format', 'whisper' ); ?></label>
				<a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">[?]</a>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['excerpt'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>"><?php _e( 'Show Excerpt', 'whisper' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'length' ) ); ?>" type="text" size="2" value="<?php echo $instance['length']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>"><?php _e( 'Excerpt Length (words)', 'whisper' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readmore' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['readmore'] ); ?>>&nbsp;
				<label for="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>"><?php _e( 'Show Readmore Text', 'whisper' ); ?></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'readmore_text' ) ); ?>"><?php _e( 'Readmore Text:', 'whisper' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'readmore_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readmore_text' ) ); ?>" type="text" value="<?php echo $instance['readmore_text']; ?>">
			</p>
		</div>

		<div style="clear: both;"></div>
	<?php
	}

}

add_action( 'widgets_init', 'whisper_register_widget_recent_posts' );

/**
 * Register widget
 *
 * @return void
 * @since 1.0
 */
function whisper_register_widget_recent_posts()
{
	register_widget( 'Whisper_Widget_Recent_Posts' );
}
