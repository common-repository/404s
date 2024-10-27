<?php
if (!defined('ABSPATH'))
{
	exit;
}


function add_404s_post_type() {
	
	global $wp_rewrite;
	
	$labels = array(
			'name' => __('404s', 'wordpress-404s'),
			'singular_name' => __('404', 'wordpress-404s'),
			'add_new' => __('Add New', 'wordpress-404s'),
			'add_new_item' => __('Add New 404', 'wordpress-404s'),
			'edit_item' => __('Edit 404', 'wordpress-404s'),
			'new_item' => __('New 404', 'wordpress-404s'),
			'all_items' => __('All 404s', 'wordpress-404s'),
			'view_item' => __('View 404', 'wordpress-404s'),
			'search_items' => __('Search 404', 'wordpress-404s'),
			'not_found' =>  __('No 404 found', 'wordpress-404s'),
			'not_found_in_trash' => __('No 404 found in Trash', 'wordpress-404s'),
			'menu_name' => __('404s', 'wordpress-404s')
	);

	$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'_builtin' =>  false,
			'query_var' => "404s",
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title', 'editor','author','custom-fields','thumbnail' )
	);

	register_post_type('404s', $args);
	$wp_rewrite->flush_rules();
}
add_action( 'init', 'add_404s_post_type' );


