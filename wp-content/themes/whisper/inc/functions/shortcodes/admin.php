<?php
class Whisper_Shortcodes_Admin
{
	/**
	 * Constructor
	 *
	 * @return Whisper_Shortcodes_Admin
	 */
	function __construct()
	{
		add_action( 'fitsc_admin_enqueue', array( $this, 'enqueue' ) );

		add_action( 'fitsc_menu_col1', array( $this, 'add_col_text' ) );
		add_action( 'fitsc_menu_col2', array( $this, 'add_col_elements' ) );
		add_action( 'fitsc_menu_cols', array( $this, 'add_col' ) );
		add_action( 'fitsc_get_modal', array( $this, 'get_modal' ), 10, 1 );

		add_filter( 'fitsc_icon_font_all', array( $this, 'icon_font_all' ) );
		add_filter( 'fitsc_icon_font_socials', array( $this, 'icon_font_socials' ) );
	}

	/**
	 * Enqueue script for admin
	 * @return void
	 */
	function enqueue()
	{
		wp_enqueue_style( 'whisper-icons', THEME_URL . 'css/admin/shortcodes.css' );
		wp_enqueue_script( 'whisper-shortcodes', THEME_URL . 'inc/functions/shortcodes/js/app.js', array( 'fitsc-app' ), '', true );
	}

	/**
	 * Add column to menu
	 *
	 * @return void
	 */
	function add_col_text()
	{
		?>
		<li data-modal="dropcap"><i class="fa fa-font"></i><?php _e( 'Drop cap', 'whisper' ); ?></li>
		<li data-modal="divider"><i class="fa fa-ellipsis-h"></i><?php _e( 'Divider', 'whisper' ); ?></li>
		<?php
	}

	/**
	 * Add column to menu
	 *
	 * @return void
	 */
	function add_col_elements()
	{
		?>
		<li data-modal="column"><i class="fa fa-columns"></i><?php _e( 'Column', 'whisper' ); ?></li>
		<?php
	}

	/**
	 * Add column to menu
	 *
	 * @return void
	 */
	function add_col()
	{
		include THEME_DIR . "inc/functions/shortcodes/tpl/menu.php";
	}

	/**
	 * Get shortcode modals
	 *
	 * @param string $shortcode
	 *
	 * @return void
	 */
	function get_modal( $shortcode )
	{
		$file = str_replace( '_', '-', $shortcode );
		$file = THEME_DIR . "inc/functions/shortcodes/modals/$file.php";
		if ( file_exists( $file ) )
			include $file;
	}

	/**
	 * Get all icons
	 *
	 * @return array
	 */
	function icon_font_all()
	{
		$icons = file_get_contents( THEME_DIR . 'inc/functions/shortcodes/tpl/icons.php' );
		$icons = array_filter( array_map( 'trim', explode( "\n", $icons ) ) );
		return $icons;
	}

	/**
	 * Get all social icons
	 *
	 * @return array
	 */
	function icon_font_socials()
	{
		$icons = file_get_contents( THEME_DIR . 'inc/functions/shortcodes/tpl/socials.php' );
		$icons = array_filter( array_map( 'trim', explode( "\n", $icons ) ) );
		return $icons;
	}
}

new Whisper_Shortcodes_Admin;