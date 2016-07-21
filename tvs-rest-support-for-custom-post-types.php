<?php
/*
Plugin Name: TVS REST Support for Custom Post Types
Plugin URI: https://hub.tesvalley.hants.sch.uk/
Version: 1.0
Author: Mr P Upfold
*/

class TVS_REST_Support_for_Custom_Post_Types {

	public function add_support() {
		global $wp_post_types;

		// provide slugs of post types to add REST support for
		$post_types[] = 'lunch_menus';
		$post_types[] = 'lunch_weeks';

		foreach( $post_types as $pt ) {
			if ( array_key_exists( $pt, $wp_post_types ) ) {
				$wp_post_types[$pt]->show_in_rest = true;
				$wp_post_types[$pt]->rest_base = $pt;
				$wp_post_types[$ot]->rest_controller_class = 'WP_REST_Posts_Controller';
			}
		}

	}

};

if ( function_exists( 'add_action' ) ) {
	$TVS_REST_Support_for_Custom_Post_Types = new TVS_REST_Support_for_Custom_Post_Types();
	add_action( 'init', array( $TVS_REST_Support_for_Custom_Post_Types, 'add_support' ), 25 );
}


