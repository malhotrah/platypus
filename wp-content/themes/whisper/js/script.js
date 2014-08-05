jQuery( function ( $ )
{
	// 960gs nested column margins
	(function ()
	{
		$( '*[class*="grid_"] > :last-child' ).not( '.grid_2, .grid_1, .grid_10, .grid_11' ).each( function ()
		{
			var $this = $( this );
			if ( $this.hasClass( 'clear' ) )
			{
				if ( $this.prev( '*[class*="grid_"]' ) )
				{
					$this.parent( '*[class*="grid_"]' ).css( 'margin-bottom', 0 );
				}
			}
			else
				if ( $this.is( '*[class*="grid_"]' ) )
				{
					$this.parent( '*[class*="grid_"]' ).css( 'margin-bottom', 0 );
				}
		} );
	})();


	// Main nav on hover
	$( '#nav li' ).hover( function ()
	{
		$( this ).children( 'ul:first' ).slideDown( 250 );
	}, function ()
	{
		$( this ).children( 'ul:first' ).hide();
	} );

	// Nav dropdown for mobile
	var addedNav = false;

	/**
	 * Show dropdown nav for mobile
	 *
	 * @return void
	 */
	function mobileNav()
	{
		if ( $( window ).width() >= 768 || addedNav )
			return;

		var $select = $( '<select/>' ).insertAfter( '#nav' );
		$( '<option/>', {
			value: '',
			text : Whisper.navDefault
		} ).appendTo( $select );
		$( '#nav a' ).each( function()
		{
			var $el = $( this ),
				atts = {
					value: $el.attr( 'href' ),
					text : $el.text()
				};
			if ( $el.hasClass( 'current-menu-item' ) )
				atts.selected = 'selected';
			$( '<option/>', atts ).appendTo( $select );
		} );
		$select.change( function()
		{
			window.location = $select.find( 'option:selected' ).val();
		} );

		addedNav = true;
	}
	$( window ).on( 'resize', mobileNav ).trigger( 'resize' );

	// Nivoslider in single portfolio page
	if ( jQuery().nivoSlider )
	{
		$( '#slider' ).nivoSlider( {
			directionNav: false
		} );
	}

	// Flexslider
	$( '.flexslider' ).flexslider( {
		animation        : "fade",
		slideDirection   : "horizontal",
		slideshow        : false,
		slideshowSpeed   : 7000,
		animationDuration: 200,
		directionNav     : true,
		controlNav       : true
	} );

	// PrettyPhoto
	$( "a[rel^='prettyPhoto']" ).prettyPhoto( {
		animationSpeed : 'fast',
		slideshow      : 5000,
		theme          : 'pp_default',
		show_title     : false,
		overlay_gallery: false,
		social_tools   : false
	} );

	// Client carousel
	$( '.carousel-li' ).carouFredSel( {
		items : 1,
		prev  : '.clients-nav.prev',
		next  : '.clients-nav.next',
		auto  : false,
		scroll: 1,
		swipe : {
			ontouch: true,
			onMouse: true
		}
	} );

	// jPlayer
	$( '.jp-jplayer' ).each( function ()
	{
		var $this = $( this ),
			url = $this.data( 'audio' ),
			type = url.substr( url.lastIndexOf( '.' ) + 1 ),
			player = '#' + $this.data( 'player' ),
			audio = {};
		audio[type] = url;

		$this.jPlayer( {
			ready              : function ()
			{
				$this.jPlayer( 'setMedia', audio );
			},
			swfPath            : 'jplayer/',
			cssSelectorAncestor: player
		} );
	} );

	// Validate on Contact page
	if ( Whisper.isContactPage )
	{
		$( '.contact-form' ).validate();
	}
} );
