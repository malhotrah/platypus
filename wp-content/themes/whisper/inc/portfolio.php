<?php
add_action( 'init', 'whisper_register_portfolio' );

/**
 * Register portfolio post type
 *
 * @return void
 * @since  1.0
 */
function whisper_register_portfolio()
{
	$labels = array(
		'name'               => _x( 'Portfolio', 'Post Type General Name', 'whisper' ),
		'singular_name'      => _x( 'Portfolio', 'Post Type Singular Name', 'whisper' ),
		'menu_name'          => __( 'Portfolio', 'whisper' ),
		'parent_item_colon'  => __( 'Parent Portfolio:', 'whisper' ),
		'all_items'          => __( 'All Portfolios', 'whisper' ),
		'view_item'          => __( 'View Portfolio', 'whisper' ),
		'add_new_item'       => __( 'Add New Portfolio', 'whisper' ),
		'add_new'            => __( 'New Portfolio', 'whisper' ),
		'edit_item'          => __( 'Edit Portfolio', 'whisper' ),
		'update_item'        => __( 'Update Portfolio', 'whisper' ),
		'search_items'       => __( 'Search portfolios', 'whisper' ),
		'not_found'          => __( 'No portfolios found', 'whisper' ),
		'not_found_in_trash' => __( 'No portfolios found in Trash', 'whisper' ),
	);
	$args = array(
		'labels'      => $labels,
		'supports'    => array( 'title', 'editor', 'thumbnail', ),
		'public'      => true,
		'has_archive' => true,
	);
	register_post_type( 'portfolio', $args );
}

add_filter( 'post_updated_messages', 'whisper_portfolio_updated_messages' );

/**
 * Change updated messages
 *
 * @param  array $messages
 *
 * @return array
 * @since  1.0
 */
function whisper_portfolio_updated_messages( $messages )
{
	global $post, $post_ID;
	$messages['portfolio'] = array(
		0  => '',
		1  => sprintf( __( 'Portfolio updated. <a href="%s">View Portfolio</a>', 'whisper' ), esc_url( get_permalink( $post_ID ) ) ),
		2  => __( 'Custom field updated.', 'whisper' ),
		3  => __( 'Custom field deleted.', 'whisper' ),
		4  => __( 'Portfolio updated.', 'whisper' ),
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Portfolio restored to revision from %s', 'whisper' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
		6  => sprintf( __( 'Portfolio published. <a href="%s">View Portfolio</a>', 'whisper' ), esc_url( get_permalink( $post_ID ) ) ),
		7  => __( 'Portfolio saved.', 'whisper' ),
		8  => sprintf( __( 'Portfolio submitted. <a target="_blank" href="%s">Preview Portfolio</a>', 'whisper' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		9  => sprintf( __( 'Portfolio scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Portfolio</a>', 'whisper' ), date_i18n( __( 'M j, Y @ G:i', 'whisper' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Portfolio draft updated. <a target="_blank" href="%s">Preview Portfolio</a>', 'whisper' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);
	return $messages;
}

add_action( 'init', 'whisper_register_portfolio_taxonomy' );

/**
 * Register portfolio taxonomy
 *
 * @return void
 * @since  1.0
 */
function whisper_register_portfolio_taxonomy()
{
	// Add post format support for portfolio
	add_post_type_support( 'portfolio', 'post-formats' );

	// Portfolio Categories
	$labels = array(
		'name'                       => _x( 'Portfolio Categories', 'Taxonomy General Name', 'whisper' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'whisper' ),
		'menu_name'                  => __( 'Categories', 'whisper' ),
		'all_items'                  => __( 'All Categories', 'whisper' ),
		'parent_item'                => __( 'Parent Category', 'whisper' ),
		'parent_item_colon'          => __( 'Parent Category:', 'whisper' ),
		'new_item_name'              => __( 'New Category Name', 'whisper' ),
		'add_new_item'               => __( 'Add New Category', 'whisper' ),
		'edit_item'                  => __( 'Edit Category', 'whisper' ),
		'update_item'                => __( 'Update Category', 'whisper' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'whisper' ),
		'search_items'               => __( 'Search categories', 'whisper' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'whisper' ),
		'choose_from_most_used'      => __( 'Choose from the most used categories', 'whisper' ),
	);
	$args = array(
		'labels'       => $labels,
		'hierarchical' => true,
	);
	register_taxonomy( 'portfolio_category', 'portfolio', $args );
}

add_filter( 'whisper_breadcrumbs_args', 'whisper_portfolio_breadcrumbs_args' );

/**
 * Change parameter for portfolio breadcrumbs
 *
 * @param $args
 *
 * @return mixed
 */
function whisper_portfolio_breadcrumbs_args( $args )
{
	if ( is_singular( 'portfolio' ) )
		$args['taxonomy'] = array( 'portfolio_category' );
	return $args;
}

/**
 * Get portfolio categories of queried posts
 *
 * @param WP_Query $query
 * @param string   $sep
 *
 * @return void
 */
function whisper_portfolio_queried_categories( $query, $sep = '/' )
{
	$post_ids = wp_list_pluck( $query->posts, 'ID' );
	$terms = wp_get_object_terms( $post_ids, 'portfolio_category' );
	if ( !is_array( $terms ) || empty( $terms ) )
		return;
	$slugs = array_unique( wp_list_pluck( $terms, 'slug' ) );
	$names = array_unique( wp_list_pluck( $terms, 'name' ) );
	$categories = array_combine( $slugs, $names );
	?>
	<section class="portfolio-filter-container grid_12">

		<ul id="portfolio-filter" class="clearfix">
			<li class="active"><a class="all" href="#"><?php _e( 'All', 'whisper' ); ?></a>/</li>
			<?php
			$i = count( $categories );
			foreach ( $categories as $slug => $name )
			{
				$i--;
				printf( '<li><a class="%s" href="#">%s</a>%s</li>', $slug, $name, $i ? $sep : '' );
			}
			?>
		</ul>

	</section>
<?php
}