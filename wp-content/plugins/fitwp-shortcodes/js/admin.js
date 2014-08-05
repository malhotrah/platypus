// Common jQuery action
jQuery( function ( $ )
{
	var $wrap = $( 'body' ),
		$menu = $( '#fitsc-menu' ),
		$modal = $( '#fitsc-modal' );

	// Set menu width
	var $cols = $menu.find( '.fitsc-cols' ),
		width = parseInt( $cols.children().first().css( 'width' ).replace( 'px', '' ) ),
		num = $cols.children().length;
	$cols.width( num * width );

	// Toggle menu
	$wrap.on( 'click', function ( e )
	{
		if ( $( e.target ).is( '#fitsc-button' ) )
		{
			$menu.toggle();
			return false;
		}
		$menu.hide();
	} );

	// Execute commands
	$menu.on( 'click', 'li', function ()
	{
		var ed = tinyMCE.activeEditor,
			$this = $( this ),
			data;

		// Store current editor for further reference
		FITSC.editor = ed;

		if ( data = $this.data( 'command' ) )
		{
			ed.execCommand( data, false );
		}
		else if ( data = $this.data( 'wrap' ) )
		{
			window.send_to_editor( $this.data( 'before' ) + getSelectedText( ed ) + $this.data( 'after' ) );
		}
		else if ( data = $this.data( 'modal' ) )
		{
			$modal.show();
			window.fitscModal( data );
		}
	} );

	// Close modal
	$( '#fitsc-close' ).click( function ()
	{
		$modal.hide();
		return false;
	} );

	// Insert shortcode to editor
	$( '#fitsc-insert' ).click( function ( e )
	{
		e.preventDefault();
		var code = $modal.find( '.fitsc-preview-shortcode .fitsc-preview-content' ).html();

		code = code.replace(/ng-[a-z]+/gi, '' )             // Angular attributes
			.replace( /[a-z0-9-_]+:;/gi, '' )               // Empty inline styles
			.replace( / [a-z0-9-_]+=['"]['"]/gi, '' )       // Empty attributes
			.replace( /^\s+|\s+$/g, '' )                    // Trim spaces
			.replace( /<\/?div.*?>/g, '' )                  // All <div>
			.replace( /<!--.*?-->/g, '' )                   // Comments
			.replace( /&lt;/g, '<' )                        // Convert back HTML entities
			.replace( /&gt;/g, '>' )

			// Then replace selection placeholder with real content
			.replace( '%SELECTION%', getSelectedText( FITSC.editor ) );

		window.send_to_editor( code );
		$modal.hide();
	} );

	// Add .fitsc-active to label when its input is checked
	$wrap.on( 'change', 'label input', function ()
	{
		$( this ).parent().addClass( 'fitsc-active' ).siblings().removeClass( 'fitsc-active' );
	} );

	/**
	 * Get selected text
	 *
	 * @param TinyMCE ed
	 *
	 * @return string
	 */
	function getSelectedText( ed )
	{
		// If editor is active
		if ( ed && !ed.isHidden() )
			return ed.selection.getContent();

		// Else get content from selected text
		// @see http://stackoverflow.com/a/16036818
		var value = '',
			content = document.getElementById( 'content' );

		// IE
		if ( document.selection )
		{
			// For browsers like Internet Explorer
			content.focus();
			var selection = document.selection.createRange();
			value = selection.text;
		}
		// Firefox, Chrome, Opera
		else if ( content.selectionStart || content.selectionStart == '0')
		{
			var start = content.selectionStart,
				end = content.selectionEnd;
			value = content.value.substring( start, end );
		}
		return value;
	}
} );
