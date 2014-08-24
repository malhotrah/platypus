<?php
class Whisper_Shortcodes_Frontend
{
	/**
	 * JavaScript code that will be outputted in the footer for shortcode
	 *
	 * @var string
	 */
	public $js = '';

	/**
	 * Constructor
	 *
	 * @return Whisper_Shortcodes_Frontend
	 */
	function __construct()
	{
		// Register shortcodes
		$shortcodes = array(
			'dropcap',
			'divider',
			'column',

			'icon',
			'team_member',
			'icon_box',
			'note',
			'clients',
			'client',
			'portfolios',
			'posts',
			'post_portfolio_tab',
			'social',
			'social_feed',

			'year',
			'bloginfo',
			'site_link',
		);
		foreach ( $shortcodes as $shortcode )
		{
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}

		add_action( 'wp_footer', array( $this, 'footer_script' ), 1000 );
	}

	/**
	 * Output JavaScript code for shortcodes
	 *
	 * @return void
	 */
	function footer_script()
	{
		if ( !$this->js )
			return;
		echo '<script>jQuery(function($){' . $this->js . '});</script>';
	}

	/**
	 * Show dropcap shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function dropcap( $atts, $content )
	{
		extract( shortcode_atts( array(
			'type' => '',
		), $atts ) );
		return '<span class="dropcap' . ( $type ? " dropcap-$type" : '' ) . '">' . $content . '</span>';
	}

	/**
	 * Show divider shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function divider( $atts, $content )
	{
		extract( shortcode_atts( array(
			'type' => '',
		), $atts ) );
		if ( !$type )
			return '<div class="divider"></div>';
		return sprintf(
			'<div class="divider"><div class="divider-icon"><img src="%s" alt="%s"></div></div>',
			THEME_URL . 'img/divider.png', __( 'divider', 'whisper' )
		);
	}

	/**
	 * Show column shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function column( $atts, $content )
	{
		static $container = 0;
		static $is_first = true;

		extract( shortcode_atts( array(
			'span'  => '',
			'class' => '',
			'total' => 12,
		), $atts ) );
		$classes = "grid_$span";
		if ( $class )
			$classes .= " $class";

		if ( $is_first )
		{
			$classes .= ' alpha';
			$is_first = false;
		}

		$after = '';

		$container += $span;
		if ( $container >= $total )
		{
			$after .= '<div class="clear"></div>';
			$classes .= ' omega';
			$container = 0;
			$is_first = true;
		}

		return "<div class='$classes'>" . apply_filters( 'fitsc_content', $content ) . "</div>$after";
	}

	/**
	 * Show icon shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function icon( $atts, $content )
	{
		extract( shortcode_atts( array(
			'class' => '',
			'size'  => 24,
		), $atts ) );
		$size .= 'px';
		return "<i class='$class' style='font-size:$size'></i>";
	}

	/**
	 * Show icon_box shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function icon_box( $atts, $content )
	{
		extract( shortcode_atts( array(
			'type'  => '',
			'title' => '',
			'icon'  => '',
			'url'   => '',
		), $atts ) );

		if ( $type == 'big' )
		{
			return sprintf(
				'<div class="service-box">
					<i class="%s"></i>
					<h5>%s</h5>
					<p>%s</p>
					%s
				</div>',
				$icon, $title, do_shortcode( $content ),
				$url ? "<a href='$url'></a>" : ''
			);
		}
		if ( $type == 'small' )
		{
			return sprintf(
				'<div class="icon-box icon-box-small">
					<div class="service-simple-title clearfix">
						<i class="%s"></i>
						<h5>%s</h5>
					</div>
					<p>%s</p>
				</div>',
				$icon, $title, do_shortcode( $content )
			);
		}
		if ( $type == 'hex' )
		{
			return sprintf(
				'<div class="services-hexagonal clearfix">
					<div class="service-hex-icon">
							<i class="%s"></i>
						</div>
						<div class="service-box-hex">
							<h5>%s</h5>
							<p>%s</p>
							%s
						</div>
				</div>',
				$icon, $title, do_shortcode( $content ),
				$url ? "<a href='$url'></a>" : ''
			);
		}
		if ( $type == 'simple' )
		{
			return sprintf(
				'<div class="icon-box icon-box-simple">
					<i class="%s"></i>
					<span class="text">%s</span>
					%s
				</div>',
				$icon, do_shortcode( $content ),
				$url ? "<a href='$url'></a>" : ''
			);
		}
	}

	/**
	 * Show team_member shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function team_member( $atts, $content )
	{
		extract( shortcode_atts( array(
			'name'     => '',
			'position' => '',
			'photo'    => '',
			'phone'    => '',
			'email'    => '',
		), $atts ) );

        //todo: updated the code for about us page issue related to email hide
        $team_name=explode(' ',rtrim($atts['name'],","));
        $team_final_name=$team_name[0];
		return sprintf( '
			<div class="team clearfix">
				<img src="%s" alt="%s">
				<div class="team-info">
					<div class="name-position">
						<h6>%s</h6>
						<span>%s</span>
					</div>
					<div class="team-description">
						%s
						<ul>
							<li class="phone">%s</li>
							<li class="mail"><a href="mailto:%s">Contact '.$team_final_name.'</a></li>
						</ul>
					</div>
				</div>
			</div>',
			$photo, __( 'team member', 'whisper' ),
			$name, $position, do_shortcode( $content ),
			$phone, antispambot( $email ), antispambot( $email )
		);
	}

	/**
	 * Show note shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function note( $atts, $content )
	{
		extract( shortcode_atts( array(
			'type' => '',
		), $atts ) );

		if ( $type == 'icon' )
		{
			return sprintf(
				'<article class="note">
					<div class="note-content">%s</div>
				</article>',
				apply_filters( 'fitsc_content', $content )
			);
		}
		else
		{
			return sprintf(
				'<article class="intro-note">%s</article>',
				apply_filters( 'fitsc_content', $content )
			);
		}
	}

	/**
	 * Show clients shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function clients( $atts, $content )
	{
		$atts = shortcode_atts( array(
			'title' => '',
		), $atts );
		return sprintf(
			'<div class="clients clearfix">
				<div class="section-title">
					<h4>%s</h4>
					<ul class="clients-navigation">
						<li><a class="clients-nav prev" href="#"></a></li>
						<li><a class="clients-nav next" href="#"></a></li>
					</ul>
				</div>
				<ul class="carousel-li">%s</ul>
			</div>',
			$atts['title'],
			do_shortcode( $content )
		);
	}

	/**
	 * Show client shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function client( $atts, $content )
	{
		extract( shortcode_atts( array(
			'name'  => '',
			'image' => '',
			'url'   => '',
		), $atts ) );
		$short_url = $url;
		if ( 0 === strpos( $url, 'http' ) )
			$short_url = str_replace( array( 'https://', 'http://' ), '', $url );
		else
			$url = "http://$url";
		return sprintf(
			'<li>
				<div class="client-logo">
					<img src="%s" alt="%s">
					<div class="client-name">
						<h6>%s</h6>
						<a href="%s">%s</a>
					</div>
				</div>
				<div class="client-text">%s</div>
			</li>',
			$image, __( 'client image', 'whisper' ),
			$name,
			$url, $short_url,
			do_shortcode( $content )
		);
	}

	/**
	 * Show portfolios shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function portfolios( $atts, $content )
	{
		extract( shortcode_atts( array(
			'title_wrap'    => 1,
			'title'         => '',
			'format_icon'   => 1,
			'total_columns' => 12,
			'number'        => 3,
		), $atts ) );

		$args = array(
			'posts_per_page' => $number,
			'post_type'      => 'portfolio',
		);
		$query = new WP_Query( $args );
		if ( !$query->have_posts() )
			return '';

		$html = '<div class="shortcode-portfolios">';
		if ( $title_wrap )
		{
			$html .= sprintf( '
				<div class="clearfix">
					<section class="section-title">
						<h4>%s</h4>
						<a class="more-link" href="%s">%s</a>
					</section>',
				$title,
				get_post_type_archive_link( 'portfolio' ),
				__( 'View all projects <span>&#62;</span>', 'whisper' )
			);
		}

		$i = 1;
		while ( $query->have_posts() ):
			$query->the_post();

			$link = get_permalink();
			list( $thumb_full ) = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			$html .= sprintf(
				'<section class="grid_%s%s">
					<figure class="portfolio clearfix">
						<div class="portfolio-image">
							<a href="%s">%s</a>

							<div class="portfolio-hover">
								<div class="mask"></div>
								<ul>
									<li class="portfolio-zoom">
										<a href="%s" rel="prettyPhoto[pp_gallery]" title="%s">&nbsp;</a>
									</li>
									<li class="portfolio-single">
										<a href="%s">&nbsp;</a>
									</li>
								</ul>
							</div>
						</div>

						<figcaption>
							%s
							<div class="caption-title">
								<p class="title">%s</p>
								<span class="subtitle">%s</span>
							</div>
						</figcaption>
					</figure>
				</section>',
				$total_columns / $number,
				$i == 1 ? ' alpha' : ( $i == $number ? ' omega' : '' ),
				$link,
				get_the_post_thumbnail( null, 'portfolio-thumbs' ),
				$thumb_full,
				the_title_attribute( 'echo=0' ),
				$link,
				$format_icon ? whisper_format_icon( false, false ) : '',
				get_the_title(),
				whisper_meta( 'subtitle' )
			);

			$i = $i == $number ? 1 : ( $i + 1 );
		endwhile;
		wp_reset_postdata();

		if ( $title_wrap )
			$html .= '</div>';

		$html .= '</div>';

		return $html;
	}

	/**
	 * Show posts shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function posts( $atts, $content )
	{
		global $whisper;
		extract( shortcode_atts( array(
			'title_wrap'    => 1,
			'title'         => '',
			'content_limit' => 25,
			'more'          => __( 'Continue reading', 'whisper' ),
			'total_columns' => 12,
			'number'        => 3,

		), $atts ) );

		$args = array(
			'posts_per_page'      => $number,
			'post_type'           => 'post',
			'ignore_sticky_posts' => 1,
		);
		$query = new WP_Query( $args );
		if ( !$query->have_posts() )
			return '';

		$html = '<div class="shortcode-posts">';

		if ( $title_wrap )
			$html .= sprintf( '<section class="section-title"><h4>%s</h4></section>', $title );

		// Setup global variable for displaying
		$backup = $whisper;
		$whisper['entry_meta_info'] = array( 'date', 'author', 'comment' );
		$whisper['is_boxed'] = true;
		$whisper['blog_display'] = 'content';
		$whisper['blog_content_limit'] = $content_limit;
		$whisper['blog_more'] = $more;
		$whisper['is_single'] = false;

		$i = 1;
		while ( $query->have_posts() )
		{
			$query->the_post();

			$html .= sprintf(
				'<div class="grid_%s %s %s">',
				$total_columns / $number,
				implode( ' ', get_post_class() ),
				$i == 1 ? ' alpha' : ( $i == $number ? ' omega' : '' )
			);

			ob_start();
			get_template_part( 'tpl/parts/content' );
			$html .= ob_get_clean();

			$html .= '</div>';

			$i = $i == $number ? 1 : ( $i + 1 );
		}
		wp_reset_postdata();

		// Restore global configuration
		$whisper = $backup;

		$html .= '</div>';

		return $html;
	}

	/**
	 * Show post_portfolio_tab shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function post_portfolio_tab( $atts, $content )
	{
		$html = '<div class="portfolio-blog">';
		$html .= '
			<div class="blog-portfolio-tabs">
				<div class="buttons">
					<a href="#portfolio-tab" class="fitsc-button fitsc-medium fitsc-background-white portfolio-blog-button">
						<span>' . __( 'View portfolio', 'whisper' ) . '</span>
					</a>

					<span class="label">or</span>

					<a href="#blog-tab" class="fitsc-button fitsc-medium fitsc-background-black portfolio-blog-button">
						<span>' . __( 'Read our blog', 'whisper' ) . '</span>
					</a>
				</div>
			</div>
		';

		$html .= '<div id="portfolio-tab" class="blog-portfolio-content">';
		$html .= do_shortcode( '[portfolios title_wrap="0"]' );
		$html .= '</div>';

		$html .= '<div id="blog-tab" class="blog-portfolio-content active">';
		$html .= do_shortcode( '[posts title_wrap="0" number="3"]' );
		$html .= '</div>';

		$html .= '</div>';

		$html .= '
			<script>
			jQuery( function( $ )
			{
				$( ".blog-portfolio-tabs .portfolio-blog-button" ).on( "click", function( e )
				{
					e.preventDefault();
					var tab = $( this ).attr( "href" );
					$( ".blog-portfolio-content" ).removeClass( "active" ).hide();
					$( tab ).addClass( "active" ).fadeIn();
				} );
			} );
			</script>
		';

		return $html;
	}

	/**
	 * Show social shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function social( $atts, $content )
	{
		extract( shortcode_atts( array(
			'class' => '',
			'url'   => '',
			'title' => '',
		), $atts ) );

		return "<a href='$url' class='$class social-link' rel='nofollow' title='$title'></a>";
	}

	/**
	 * Show social_feed shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function social_feed( $atts, $content )
	{
		$atts = shortcode_atts( array(
			'network'  => '',
			'username' => '',
			'limit'    => 16,
		), $atts );
		$atts['socialnetwork'] = $atts['network'];
		unset( $atts['network'] );

		$html = '<div class="social-feed" data-params="' . esc_attr( json_encode( $atts ) ) . '"></div>';

		// Required script
		wp_enqueue_script( 'socialstream' );
		$this->js = '
			$( ".social-feed" ).each( function()
			{
				var $this = $( this ),
					params = $this.data( "params" );
				$this.socialstream( params );
			} );
		';

		return $html;
	}

	/**
	 * Shortcode [year]
	 *
	 * @param array  $atts    Shortcode attributes
	 * @param string $content Shortcode content
	 *
	 * @return string
	 */
	function year( $atts, $content = null )
	{
		return date( 'Y' );
	}

	/**
	 * Shortcode [bloginfo]
	 * A wrapper short code of get_bloginfo() function
	 *
	 * @param array  $atts    Shortcode attributes
	 * @param string $content Shortcode content
	 *
	 * @return string
	 */
	function bloginfo( $atts, $content = null )
	{
		extract( shortcode_atts( array(
			'name' => 'name',
		), $atts ) );

		return get_bloginfo( $name );
	}

	/**
	 * Shortcode to display a link back to the site.
	 *
	 * @param array $atts Shortcode attributes
	 *
	 * @return string
	 */
	function site_link( $atts )
	{
		$name = get_bloginfo( 'name' );
		return '<a class="site-link" href="' . HOME_URL . '" title="' . esc_attr( $name ) . '" rel="home">' . $name . '</a>';
	}

}

new Whisper_Shortcodes_Frontend;