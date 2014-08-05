<?php
class FITSC_Frontend
{
	/**
	 * Hold all custom js code
	 *
	 * @var array
	 */
	public $js = array();

	/**
	 * Store the active status of current tab
	 *
	 * @var bool
	 */
	static $tab_active;

	/**
	 * Constructor
	 *
	 * @return FITSC_Frontend
	 */
	function __construct()
	{
		// Enqueue shortcodes scripts and styles
		// High priority = enqueue before theme styles = theme can overwrite styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 1 );
		add_action( 'wp_footer', array( $this, 'footer' ), 1000 );

		// Apply filters to shortcode content
		add_filter( 'fitsc_content', 'wpautop' );
		add_filter( 'fitsc_content', 'shortcode_unautop' );
		add_filter( 'fitsc_content', 'do_shortcode' );
		add_filter( 'fitsc_content', array( $this, 'cleanup' ) );

		// Register shortcodes
		$shortcodes = array(
			// 'dropcap',
			'highlight',
			// 'divider',
			'button',
			'box',
			'toggles',
			'toggle',
			'accordions',
			'accordion',
			'tabs',
			'tab',
			'tooltip',
			'progress_bar',
			// 'socials',
			// 'person',
			'promo_box',
			'testimonial',
			// 'column',
			// 'icon_box',
			'map',
			'widget_area',
		);
		foreach ( $shortcodes as $shortcode )
		{
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}
		add_shortcode( 'list', array( $this, 'custom_list' ) );
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * Allow themes to overwrite shortcode script/style
	 * - false: use plugin (default) script/style
	 * - true: no js/css file is enqueued
	 * - string: URL of custom js/css file, which will be enqueued
	 *
	 * @return void
	 */
	function enqueue()
	{
		$script = apply_filters( 'fitsc_custom_script', false );
		if ( false === $script )
			$script = FITSC_URL . 'js/frontend.js';
		if ( is_string( $script ) )
			wp_enqueue_script( 'fitsc', $script, array( 'jquery' ), '', true );

		$style = apply_filters( 'fitsc_custom_style', false );
		if ( false === $style )
			$style = FITSC_URL . 'css/frontend.css';
		if ( is_string( $style ) )
			wp_enqueue_style( 'fitsc', $style );
	}

	/**
	 * Display custom js code
	 *
	 * @return void
	 */
	function footer()
	{
		// Load Google maps only when needed
		echo '<script>if ( typeof google !== "object" || typeof google.maps !== "object" )
				document.write(\'<script src="//maps.google.com/maps/api/js?sensor=false"><\/script>\')</script>';
		echo '<script>jQuery(function($){' . implode( '', $this->js ) . '} )</script>';
	}

	/**
	 * Remove empty <br>, <p> tags
	 * @param  string $text
	 * @return string
	 */
	function cleanup( $text )
	{
		return str_replace( array( '<br>', '<br />', '<p></p>' ), '', $text );
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
		return '<span class="fitsc-dropcap' . ( $type ? " fitsc-$type" : '' ) . '">' . $content . '</span>';
	}

	/**
	 * Show highlight shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function highlight( $atts, $content )
	{
		extract( shortcode_atts( array(
			'background' => '',
			'custom_background' => '',
		), $atts ) );

		return sprintf(
			'<span class="fitsc-highlight%s"%s>' . do_shortcode( $content ) . '</span>',
			$background && !$custom_background ? " fitsc-background-$background" : '',
			$custom_background ? " style=\"background:$custom_background\"" : ''
		);
	}

	/**
	 * Show list shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function custom_list( $atts, $content )
	{
		extract( shortcode_atts( array(
			'icon'       => '',
			'icon_color' => '',
		), $atts ) );
		$content = apply_filters( 'fitsc_content', $content );
		$content = str_replace( '<li>', '<li><i class="' . $icon . '"></i>', $content );
		$content = str_replace( '<ul', "<ul class='fitsc-list fitsc-$icon_color'", $content );
		return $content;
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
		return '<hr class="fitsc-divider' . ( $type ? " fitsc-$type" : '' ) . '">';
	}

	/**
	 * Show button shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function button( $atts, $content )
	{
		extract( shortcode_atts( array(
			'link'          => '#',
			'color'         => '',
			'size'          => '',
			'icon'          => '',
			'icon_position' => '',

			'id'            => '',
			'nofollow'      => '',
			'background'    => '',
			'text_color'    => '',
			'target'        => '',
			'align'         => '',
			'full'          => '',
			'class'         => '',
		), $atts ) );

		$classes = array( 'fitsc-button' );
		if ( $full )
			$classes[] = 'fitsc-full';
		if ( $class )
			$classes[] = $class;
		if ( 'right' == $icon_position )
			$classes[] = 'fitsc-icon-right';
		if ( $color )
			$classes[] = "fitsc-background-$color";
		if ( $align )
			$classes[] = "fitsc-align-$align";
		if ( $size )
			$classes[] = "fitsc-$size";
		$classes = implode( ' ', $classes );
		$style = '';
		if ( $background )
			$style .= "background:$background;";
		if ( $text_color )
			$style .= "color:$text_color;";

		$html = "<a href='$link' class='$classes'" .
			( $id ? " id='$id'" : '' ) .
			( $nofollow ? " rel='nofollow'" : '' ) .
			( $target ? " target='$target'" : '' ) .
			( $style ? " style='$style'" : '' ) .
			'>';
		$content = apply_filters( 'fitsc', $content );
		if ( $icon )
		{
			$icon = '<i class="' . $icon . '"></i>';
			$content = $icon_position == 'right' ? ( $content . $icon ) : ( $icon . $content );
		}
		$html .= $content . '</a>';
		if ( $align == 'center' )
			$html = '<div style="text-align:center">' . $html . '</div>';
		return $html;
	}

	/**
	 * Show styled boxes shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function box( $atts, $content )
	{
		extract( shortcode_atts( array(
			'type'  => '',
			'close' => '',
		), $atts ) );
		$classes = array( 'fitsc-box' );
		if ( $type )
			$classes[] = "fitsc-$type";
		if ( $close )
		{
			$classes[] = "fitsc-close";
			$close = '<div class="fitsc-close">✕</div>';
		}
		return '<div class="' . implode( ' ', $classes ) . '">' . $close . '<div class="fitsc-text">' . apply_filters( 'fitsc_content', $content ) . '</div></div>';
	}

	/**
	 * Show toggles shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function toggles( $atts, $content )
	{
		// Get all toggle titles
		preg_match_all( '#\[toggle [^\]]*?title=[\'"]?(.*?)[\'"]#', $content, $matches );

		if ( empty( $matches[1] ) )
			return '';

		return sprintf(
			'<div class="fitsc-toggles">%s</div>',
			do_shortcode( $content )
		);
	}

	/**
	 * Show toggle shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function toggle( $atts, $content )
	{
		extract( shortcode_atts( array(
			'title' => '',
		), $atts ) );
		if ( !$title || !$content )
			return '';

		return sprintf( '
			<div class="fitsc-toggle">
				<div class="fitsc-title">%s</div>
				<div class="fitsc-content">%s</div>
			</div>',
			$title,
			apply_filters( 'fitsc_content', $content )
		);
	}

	/**
	 * Show tabs shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function tabs( $atts, $content )
	{
		extract( shortcode_atts( array(
			'type' => '',
		), $atts ) );

		// Get all tab titles
		preg_match_all( '#\[tab [^\]]*?\]#', $content, $matches );

		if ( empty( $matches ) )
			return '';

		$tpl = '<li%s><a href="#">%s%s</a></li>';
		$lis = '';
		foreach ( $matches[0] as $k => $match )
		{
			$tab_atts = shortcode_parse_atts( substr( $match, 1, -1 ) );
			$tab_atts = shortcode_atts( array(
				'title' => '',
				'icon'  => '',
			), $tab_atts );
			$lis .= sprintf(
				$tpl,
				$k ? '' : ' class="fitsc-active"',
				$tab_atts['icon'] ? '<i class="' . $tab_atts['icon'] . '"></i>' : '',
				$tab_atts['title']
			);
		}

		self::$tab_active = true;
		return sprintf(
			'<div class="fitsc-tabs%s">
				<ul class="fitsc-nav">%s</ul>
				<div class="fitsc-content">%s</div>
			</div>',
			$type ? " fitsc-$type" : '',
			$lis,
			do_shortcode( $content )
		);
	}

	/**
	 * Show tab shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function tab( $atts, $content )
	{
		$class = 'fitsc-tab' . ( self::$tab_active ? ' fitsc-active' : '' );
		self::$tab_active = false;
		return sprintf(
			'<div class="%s">%s</div>',
			$class,
			apply_filters( 'fitsc_content', $content )
		);
	}

	/**
	 * Show accordions shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function accordions( $atts, $content )
	{
		// Get all toggle titles
		preg_match_all( '#\[accordion [^\]]*?title=[\'"]?(.*?)[\'"]#', $content, $matches );

		if ( empty( $matches[1] ) )
			return '';

		return sprintf(
			'<div class="fitsc-accordions">%s</div>',
			do_shortcode( $content )
		);
	}

	/**
	 * Show accordion shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function accordion( $atts, $content )
	{
		extract( shortcode_atts( array(
			'title' => '',
		), $atts ) );
		if ( !$title || !$content )
			return '';

		return sprintf( '
			<div class="fitsc-accordion">
				<div class="fitsc-title">%s</div>
				<div class="fitsc-content">%s</div>
			</div>',
			$title,
			apply_filters( 'fitsc_content', $content )
		);
	}

	/**
	 * Show tooltip shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function tooltip( $atts, $content )
	{
		$atts = shortcode_atts( array(
			'content' => '',
			'link'    => '#',
		), $atts );
		return sprintf( '<a class="fitsc-tooltip" href="%s" title="%s">%s</a>', $atts['link'], $atts['content'], apply_filters( 'fitsc_content', $content ) );
	}

	/**
	 * Show progress bar shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function progress_bar( $atts, $content )
	{
		extract( shortcode_atts( array(
			'text'    => '',
			'percent' => 100,
			'type'    => '',
		), $atts ) );

		return sprintf( '
			<div class="fitsc-progress-bar%s">
				<div class="fitsc-title">%s</div>
				<div class="fitsc-percent-wrapper"><div class="fitsc-percent fitsc-percent-%s" data-percentage="%s"></div></div>
			</div>',
			$type ? " fitsc-$type" : '',
			$text,
			$percent,
			$percent
		);
	}

	/**
	 * Show promo box shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function promo_box( $atts, $content )
	{
		extract( shortcode_atts( array(
			'type'             => '',
			'heading'          => '',
			'text'             => '',
			'button1_text'     => '',
			'button1_link'     => '',
			'button1_color'    => '',
			'button1_target'   => '',
			'button1_nofollow' => '',
			'button2_text'     => '',
			'button2_link'     => '',
			'button2_color'    => '',
			'button2_target'   => '',
			'button2_nofollow' => '',
		), $atts ) );

		$button1 = "<a href='$button1_link'" .
			( $button1_color ? " class='fitsc-button fitsc-large fitsc-background-$button1_color'" : '' ) .
			( $button1_nofollow ? " rel='nofollow'" : '' ) .
			( $button1_target ? " target='$button1_target'" : '' ) .
			">$button1_text</a>";

		$button2 = '';
		if ( $type == 'two-buttons' )
		{
			$button2 = "<a href='$button2_link'" .
				( $button2_color ? " class='fitsc-button fitsc-large fitsc-background-$button2_color'" : '' ) .
				( $button2_nofollow ? " rel='nofollow'" : '' ) .
				( $button2_target ? " target='$button2_target'" : '' ) .
				">$button2_text</a>";
		}

		$content = sprintf( '
			<div class="fitsc-content">
				<h3 class="fitsc-heading">%s</h3>
				<p class="fitsc-text">%s</p>
			</div>',
			$heading,
			$text
		);
		$buttons = sprintf( '
			<div class="fitsc-buttons">%s %s</div>',
			$button1,
			$button2
		);

		$html = sprintf( '
			<div class="fitsc-promo-box-wrap">
				<div class="fitsc-promo-box%s">%s</div>
			</div>',
			$type ? " fitsc-$type" : '',
			$type ? $content . $buttons : $buttons . $content
		);
		return $html;
	}

	/**
	 * Show socials shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function socials( $atts, $content )
	{
		$html = '<ul class="fitsc-socials">';
		foreach ( $atts as $k => $v )
		{
			$class = str_replace( '_', '-', $k );
			$html .= sprintf(
				'<li>
					<a href="%1$s" class="fitsc-%2$s"><i class="%2$s"></i></a>
				</li>',
				$v,
				$class
			);
		}
		$html .= '</ul>';
		return $html;
	}

	/**
	 * Show person shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function person( $atts, $content )
	{
		$atts = array_merge( array(
			'name'     => '',
			'position' => '',
			'photo'    => '',
		), $atts );
		$meta = sprintf( '
			<div class="fitsc-meta">
				<div class="fitsc-name">%s</div>
				<div class="fitsc-position">%s</div>
			</div>',
			$atts['name'],
			$atts['position']
		);
		unset( $atts['name'], $atts['position'] );

		$html = '<div class="fitsc-person">';
		$html .= '<div class="fitsc-photo">';
		$html .= '<img src="' . $atts['photo'] . '">';
		unset( $atts['photo'] );
		$html .= '<ul class="fitsc-socials">';
		foreach ( $atts as $k => $v )
		{
			$class = str_replace( '_', '-', $k );
			$html .= sprintf(
				'<li>
					<a href="%1$s" class="fitsc-%2$s"><i class="%2$s"></i></a>
				</li>',
				$v,
				$class
			);
		}
		$html .= '</ul>';
		$html .= '</div>';
		$html .= $meta;
		$html .= '</div>';

		return $html;
	}

	/**
	 * Show testimonial shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function testimonial( $atts, $content )
	{
		extract( shortcode_atts( array(
			'name'  => '',
			'info'  => '',
			'photo' => '',
		), $atts ) );

		$html = sprintf( '
			<div class="fitsc-testimonial">
				<img src="%s" class="fitsc-photo">
				<div class="fitsc-text">
					%s
					<div class="fitsc-name">%s</div>
					<div class="fitsc-info">%s</div>
				</div>
			</div>',
			$photo,
			apply_filters( 'fitsc_content', $content ),
			$name,
			$info
		);

		return $html;
	}

	/**
	 * Show column shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 * @see    https://github.com/justintadlock/grid-columns/blob/master/grid-columns.php
	 */
	function column( $atts, $content = null)
	{
		// Allowed grids can be 2, 3, 4, 5, or 12 columns
		static $allowed_grids = array( 2, 3, 4, 5, 12 );

		static $is_first_column = true;
		static $is_last_column = false;

		// The current grid
		static $grid = 4;

		// The current total number of columns in the grid
		static $span = 0;

		if ( $content === null )
			return '';

		$atts = shortcode_atts( array(
			'grid'  => 4,
			'span'  => 1,
			'push'  => 0,
			'class' => ''
		), $atts );

		// Make sure the grid is in the allowed grids array
		if ( $is_first_column && in_array( $atts['grid'], $allowed_grids ) )
			$grid = absint( $atts['grid'] );

		$atts['span'] = $grid >= $atts['span'] ? absint( $atts['span'] ) : 1;
		$atts['push'] = $grid >= $atts['push'] + $atts['span'] + $span ? absint( $atts['push'] ) : 0;

		// Add to the total $span
		$span = $span + $atts['span'] + $atts['push'];

		// Column classes
		$column_classes = array( 'fitsc-column', "fitsc-span-{$atts['span']}" );
		if ( $atts['push'] )
			$column_classes[] = "fitsc-push-{$atts['push']}";

		// Add user-input custom class(es)
		if ( !empty( $atts['class'] ) )
		{
			if ( !is_array( $atts['class'] ) )
				$atts['class'] = preg_split( '#\s+#', $atts['class'] );
			$column_classes = array_merge( $column_classes, $atts['class'] );
		}

		if ( $is_first_column )
			$column_classes[] = 'fitsc-first';

		// If the $span property is greater than (shouldn't be) or equal to the $grid property
		if ( $span >= $grid )
		{
			$column_classes[] = 'fitsc-last';
			$is_last_column = true;
		}

		// Sanitize and join all classes
		$column_class = implode( ' ', array_map( 'sanitize_html_class', array_unique( $column_classes ) ) );

		$html = '';

		// If this is the first column
		if ( $is_first_column )
		{
			$html .= "<div class='fitsc-grid fitsc-grid-$grid'>";

			// Set the $is_first_column property back to false
			$is_first_column = false;
		}

		// Add the current column to the output
		$html .= '<div class="' . $column_class . '">' . apply_filters( 'fitsc_content', $content ) . '</div>';

		// If this is the last column
		if ( $is_last_column )
		{
			$html .= '</div>';

			// Reset
			$is_first_column = true;
			$is_last_column = false;
			$grid = 4;
			$span = 0;
		}

		return $html;
	}

	/**
	 * Show icon box shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function icon_box( $atts, $content )
	{
		extract( shortcode_atts( array(
			'type'          => 'basic',
			'icon'          => '',
			'icon_position' => 'top',
			'image'         => '',
			'title'         => '',
			'link'          => '',
			'more_text'     => '',
			'more_link'     => '',
		), $atts ) );

		$icon = $icon ? "<i class='$icon'></i>" : '';
		$more = '';
		if ( $more_text )
		{
			$more = $more_text;
			if ( $more_link )
				$more = "<a href='$more_link' class='fitsc-more'>$more</a>";
		}
		$content = apply_filters( 'fitsc_content', $content );
		if ( $type != 'image' )
		{
			if ( $link )
				$title = "<a href='$link'>$title</a>";
			$title = $type != 'basic' ? "<h4>$title</h4>" : "<h4>$icon $title</h4>";
		}
		else
		{
			$title = "<h4>$title</h4>";
		}

		$classes = array( 'fitsc-icon-box', "fitsc-$type" );
		switch ( $type )
		{
			case 'basic':
				$html = $title . $content . $more;
				break;
			case 'no-border':
				$classes[] = "fitsc-$icon_position";
				$html = "$icon<div class='fitsc-text'>{$title}{$content}{$more}</div>";
				break;
			case 'border':
				$classes[] = "fitsc-$icon_position";
				$html = $icon . $title . $content . $more;
				break;
			case 'middle':
				$html = $title . $icon . $content;
				break;
			case 'image':
				$html = "
					<div class='fitsc-icon'>$icon</div>
					<div class='fitsc-image'><img src='$image'></div>
					<div class='fitsc-text'>{$title}{$content}</div>
				";
				if ( $link )
					$html = "<a href='$link'>$html</a>";
				break;
		}

		return '<div class="' . implode( ' ', $classes ) . '">' . $html . '</div>';
	}

	/**
	 * Show map shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function map( $atts, $content )
	{
		static $counter = 0;
		$counter++;

		extract( shortcode_atts( array(
			'type'         => '',
			'address'      => '',
			'latitude'     => '',
			'longtitude'   => '',
			'map_type'     => '',
			'marker_title' => '',
			'info_window'  => '',
			'zoom'         => 8,
			'width'        => '100%',
			'height'       => '400px',
			'scrollwheel'  => '',
			'controls'     => '',
		), $atts ) );

		$width = intval( $width ) ? $width : '100%';
		$height = intval( $height ) ? $height : '400px';

		$html = sprintf( '<div style="width:%s;height:%s" id="sls-map-%s"></div>', $width, $height, $counter );
		$js = '( function() {';

		switch ( $map_type )
		{
			case 'satellite':
				$map_type = 'google.maps.MapTypeId.SATELLITE';
				break;
			case 'hybrid':
				$map_type = 'google.maps.MapTypeId.HYBRID';
				break;
			case 'terrain':
				$map_type = 'google.maps.MapTypeId.TERRAIN';
				break;
			default:
				$map_type = 'google.maps.MapTypeId.ROADMAP';
		}

		$controls = array_filter( explode( ',', $controls . ',' ) );
		$js .= '
			var latLng = new google.maps.LatLng( -34.397, 150.644 );
			var map = new google.maps.Map( document.getElementById( "sls-map-' . $counter . '" ), {
				zoom: ' . $zoom . ',
				center: latLng,
				mapTypeId: ' . $map_type . ',
				panControl: ' . ( in_array( 'pan', $controls ) ? 'true' : 'false' ) . ',
				zoomControl: ' . ( in_array( 'zoom', $controls ) ? 'true' : 'false' ) . ',
				mapTypeControl: ' . ( in_array( 'map_type', $controls ) ? 'true' : 'false' ) . ',
				scaleControl: ' . ( in_array( 'scale', $controls ) ? 'true' : 'false' ) . ',
				streetViewControl: ' . ( in_array( 'street_view', $controls ) ? 'true' : 'false' ) . ',
				rotateControl: ' . ( in_array( 'rotate', $controls ) ? 'true' : 'false' ) . ',
				overviewMapControl: ' . ( in_array( 'overview', $controls ) ? 'true' : 'false' ) . ',
				scrollwheel: ' . ( $scrollwheel ? 'true' : 'false' ) . '
			} );
			var marker = new google.maps.Marker( {
				position: latLng,
				map: map
			} );
		';

		if ( $marker_title )
		{
			$js .= '
				marker.setTitle( "' . $marker_title . '" );
			';
		}

		if ( $info_window )
		{
			$js .= '
				var infoWindow = new google.maps.InfoWindow( {
					content: "' . $info_window . '"
				} );

				google.maps.event.addListener( marker, "click", function()
				{
					infoWindow.open( map, marker );
				} );
			';
		}

		if ( 'latlng' == $type && $latitude && $longtitude )
		{
			$js .= '
				latLng = new google.maps.LatLng( ' . $latitude . ', ' . $longtitude . ' );
				map.setCenter( latLng );
				marker.setPosition( latLng );
			';
		}
		elseif ( $address )
		{
			$js .= '
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode( {
					address: "' . $address . '"
				}, function( results )
				{
					var loc = results[0].geometry.location;
					latLng = new google.maps.LatLng( loc.lat(), loc.lng() );
					map.setCenter( latLng );
					marker.setPosition( latLng );
				} );
			';
		}

		$js .= '} )();';

		$this->js[] = $js;
		return $html;
	}

	/**
	 * Show widget_area shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function widget_area( $atts, $content )
	{
		extract( shortcode_atts( array(
			'id' => '',
		), $atts ) );
		if ( !$id )
			return '';

		ob_start();
		dynamic_sidebar( $id );
		return ob_get_clean();
	}
}

new FITSC_Frontend;