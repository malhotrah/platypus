jQuery( function ( $ )
{
	var $holder = $( '#filter-item' ),
		$data = $holder.clone().children();

	// portfolio single filter
	$( '#portfolio-filter' ).on( 'click', 'a', function ( e )
	{
		e.preventDefault();

		// Set active class
		$( this ).parent().addClass( 'active' ).siblings().removeClass( 'active' );

		var type = $( this ).attr( 'class' ).replace( /^\s+|\s+/g, '' ),
			$filteredData = type === 'all' ? $data : $data.filter( '[data-alpha*=' + type + ']' );

		// Quicksand
		$holder.quicksand( $filteredData, {
			duration: 800,
			easing  : 'swing'
		}, function ()
		{
			$( "a[rel^='prettyPhoto']" ).prettyPhoto( {
				animationSpeed : 'fast',
				slideshow      : 5000,
				theme          : 'pp_default',
				show_title     : false,
				overlay_gallery: false,
				social_tools   : false
			} );
		} );
	} );
} );
