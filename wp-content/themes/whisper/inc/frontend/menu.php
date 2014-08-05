<?php
add_filter( 'wp_nav_menu_objects', 'whisper_add_parent_menu_class' );

/**
 * Add classname to parent menu item
 * @param  array $objs menu objects
 * @return array
 * @since  1.0
 */
function whisper_add_parent_menu_class( $objs )
{
	$parents = wp_list_pluck( $objs, 'menu_item_parent' );
	foreach ( $objs as $obj )
	{
		if ( in_array( $obj->ID, $parents ) )
			$obj->classes[] = 'has-sub';
	}
	return $objs;
}

add_filter( 'wp_page_menu', 'whisper_wp_page_menu' );

/**
 * Cleanup output HTML of wp_page_menu() function
 * The function wp_page_menu() is used in fallback if there's no menu for wp_nav_menu()
 *
 * @param  string $menu
 * @return string
 * @since  1.0
 */
function whisper_wp_page_menu( $menu )
{
	// No wrapper div
	$menu = preg_replace( '|</?div[^>]*?>|', '', $menu );

	// Class for dropdown
	$menu = str_replace( 'page_item_has_children', 'has-sub', $menu );

	return $menu;
}