<?php
/*
Plugin Name: TVS REST Support for Custom Post Types
Plugin URI: https://hub.tesvalley.hants.sch.uk/
Version: 1.0
Author: Mr P Upfold
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


/* Copyright (C) 2016-2018 Test Valley School.


    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License version 2
    as published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

class TVS_REST_Support_for_Custom_Post_Types {


	/*
	 * Will hold the slugs of all the custom post types for which this
	 * plugin will handle adding REST support.
	 */
	protected $post_types = null;

	/**
	 * An stdClass() instance that holds information about each post type
	 * and the CFS custom fields attached to that post type. The stdClass
	 * has a property named for each post type slug and that property is
	 * an array with all the field names associated with it.
	 */
	protected $field_info = null;


	/**
	 * Wrapper to call register_post_type and add the post type
	 * to our internal list of post types that we handle.
	 */
	protected function register( $post_type, $args ) {
		$this->post_types[] = $post_type;
		register_post_type( $post_type, $args );
	}

	/**
	 * Set up the object, adding actions to WP and setting up post type
	 * information.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_new_post_types' ), 5 );
		add_action( 'init', array( $this, 'register_new_taxonomies' ), 6 );
		if ( function_exists( 'CFS' ) ) {
			add_action( 'init', array( $this, 'assign_custom_posts_controller' ), 999 );
			add_action( 'rest_api_init', array( $this, 'register_all_cpt_cfs_fields' ), 999 );
		}

		$this->field_info = new stdClass();

		add_action( 'wp_ajax_nopriv_sanitize_title', array( $this, 'sanitize_title' ), 99, 1 );
		add_action( 'wp_ajax_sanitize_title', array( $this, 'sanitize_title' ), 99, 1 );
	}

	/**
	 * Called during the init action, add our custom post types to WordPress.
	 */
	public function register_new_post_types() {
		require_once( dirname( __FILE__ ) . '/post-types.php' );
	}

	/**
	 * Register the new taxonomies that we use.
	 */
	public function register_new_taxonomies() {
		require_once( dirname( __FILE__ ) . '/taxonomies.php' );
	}

	/**
	 * Register our custom posts controller that fixes permissions.
	 */
	public function assign_custom_posts_controller() {

		require_once( dirname( __FILE__ ) . '/lib/endpoints/class-wp-rest-posts-controller-fix-private-access-check.php' );

		$registered_post_types = get_post_types( array( '_builtin' => false ), 'objects');

		foreach( $registered_post_types as $pt ) {
			if ( in_array( $pt->name, $this->post_types ) ) {
				// reregister with rest controller class override
				register_post_type( $pt->name,
					array(
						'label'                   => $pt->label,
						'labels'                  => $pt->labels,
						'description'             => $pt->description,
						'public'                  => $pt->public,
						'hierarchical'            => $pt->hierarchical,
						'exclude_from_search'     => $pt->exclude_from_search,
						'publicly_queryable'      => $pt->publicly_queryable,
						'show_ui'                 => $pt->show_ui,
						'show_in_menu'            => $pt->show_in_menu,
						'show_in_nav_menus'       => $pt->show_in_nav_menus,
						'show_in_admin_bar'       => $pt->show_in_admin_bar,
						'menu_icon'               => $pt->menu_icon,
						'capability_type'         => $pt->capability_type,
						'map_meta_cap'            => $pt->map_meta_cap,
						'taxonomies'              => $pt->taxonomies,
						'has_archive'             => $pt->has_archive,
						'query_var'               => $pt->query_var,
						'can_export'              => $pt->can_export,
						'show_in_rest'            => true,
						'rest_controller_class'   => 'WP_REST_Posts_Controller_Fix_Private_Access_Check'
					)
				);

				// add the filter to enable querying by meta -- the '2' argument below is accepted_args, so that we receive the $request object
				add_filter( 'rest_' . $pt->name . '_query', array( $this, 'rest_meta_query' ), 10, 2 );

				// add the filter to enable us to order by custom meta fields
				add_filter( 'rest_'. $pt->name . '_collection_params', array( $this, 'rest_meta_collection_params' ), 10, 2 );

				// add the filter to enable querying by taxonomy -- the '2' argument below is accepted_args, so that we receive the $request object
				add_filter( 'rest_' . $pt->name . '_query', array( $this, 'rest_taxonomy_query' ), 10, 2 );


			}
		}

	}


	/**
 	 * Allow a REST query to our custom post type(s) to support custom
	 * 'order' and 'orderby' parameters.
	 */
	public function rest_meta_collection_params( $params, $post_type_obj ) {
		
		if ( count ( get_object_vars( $this->field_info ) ) < 1 ) {
			$this->determine_cfs_fields();
		}

		$params['order'] = array(
			'description'        => __( 'Order sort attribute ascending or descending.' ),
			'type'               => 'string',
			'default'            => 'desc',
			'enum'               => array( 'asc', 'desc', 'ASC', 'DESC' ),
		);

		// depending upon the post type, we should enable different orderby enums

		if ( $post_type_obj != null && property_exists( $post_type_obj, 'name' ) ) {
			$post_type_name = $post_type_obj->name;
			// get all meta keys for this post type to add to orderby params
			$meta_keys = $this->field_info->$post_type_name;

			$param_keys = array_merge( $meta_keys, array(
				'date',
				'id',
				'title',
				'slug',
				'date am_pm', // hack to allow multiple level sorting -- do we need to generalise this? TODO
			) );

			if ( is_array( $meta_keys ) && count( $meta_keys ) > 0 ) {
				$params['orderby'] = array(
					'description' => __( 'Sort collection by object attribute.' ),
					'type'        => 'string',
					'default'     => 'date',
					'enum'        => $param_keys
				);
			}
		}


		return $params;
	}

	/**
	 * Filter the query arguments for our custom post types, so that our
	 * additional arguments make it to the WP_Query. This enables the querying
	 * of the filtered post type by any passed meta_query.
	 */
	public function rest_meta_query( $args, $request ) {
		if ( $request instanceof WP_REST_Request ) {
			
			$params = $request->get_query_params();

			if ( is_array( $params ) && array_key_exists( 'meta_query', $params ) ) {
				$args['meta_query'] = $params['meta_query'];
			}
		}

		// note that everything that uses this interface must now do so as follows:
		// /wp-json/v2/post-type?status=private&meta_query[0][key]=username&meta_query[0][value]=15test&meta_query[0][compare]=%3D

		// (before it was [filter][meta_query][0][meta_key] ... )

		return $args;
	}

	/**
	 * Filter the query arguments for our custom post types, so that our additional taxonomy
	 * queries make it to the WP_Query.
	 */
	public function rest_taxonomy_query( $args, $request ) {
		if ( $request instanceof WP_REST_Request ) {
			$params = $request->get_query_params();

			if ( is_array( $params ) && array_key_exists( 'tax_query', $params ) ) {
				$args['tax_query'] = $params['tax_query'];
			}
		}

		return $args;
	}


	/**
	 * This is a callback method that facilitates the querying of arbitrary post meta field values
	 * from the WP REST API.
	 *
	 * To use, have an appropriate function (running at the rest_api_init action) call register_rest_field
	 * and pass in this callback method. The second argument to register_rest_field() is the $field_name.
	 */
	public function rest_get_post_meta( $object, $field_name, $request ) {
		return get_post_meta( $object['id'], $field_name, true );
	}

	/**
	 * A callback method to facilitate updating arbitrary post meta field values from the WP REST API.
	 * To use, have an appropriate function call register_rest_field and pass in this callback method.
	 *
	 * Note that this sets postmeta values, but does not work completely with Custom Field Suite fields.
	 * Use another function below.
	 */
	public function rest_update_post_meta( $value, $object, $field_name ) {
		if ( ! isset( $value ) || ! is_string( $value ) || strlen( $value ) < 1 ) {
			return;
		}

		return update_post_meta( $object->ID, $field_name, wp_strip_all_tags( $value ) );
	}

	/**
	 * A callback method to facilitate updating arbitrary Custom Field Suite custom fields from the WP
	 * REST API. To use, have an appropriate function call register_rest_field and pass in this callback
	 * method.
	 */
	public function rest_update_cfs_field( $value, $object, $field_name ) {
		if ( ! isset( $value ) || ! is_string( $value ) || strlen( $value ) < 1 ) {
			return;
		}

		if ( 	'date' == $field_name ||
			'mark_date' == $field_name ||
			'incident_date' == $field_name) {
			// parse date for storage in CFS preferred format
			$date = strtotime( $value );
			$value = date( 'Y-m-d', $date );
		}

		$field_data = array(
			$field_name          => wp_strip_all_tags( $value )
		);

		$post_data = array(
			'ID'                 => $object->ID
		);

		return CFS()->save(
			$field_data,
			$post_data
		);

	}

	/**
	 * Wrapper for the WordPress sanitize_title() function so we can determine a valid slug for a given friendly name. This is a helper
	 * method for the Get-TermID cmdlet in the PowerShell components for Parent Progress View marksheet imports, but may also be used
	 * later by other components.
	 */
	public function sanitize_title() {
		if ( ! array_key_exists( 'input', $_REQUEST ) ) {
			wp_die();
		}
		wp_send_json( array( 'output' => sanitize_title( $_REQUEST['input'] ) ) );
		wp_die();
	}

	/**
	 * Determine all Custom Post UI posts, get all the Custom Field Suite fields associated with them
	 * and store the information in an instance variable -- $field_info.
	 */
	protected function determine_cfs_fields() {
		// get all field groups posts, which we will loop over and register those fields for rest on the
		// attached post type
		
		$field_groups = get_posts(
			array(
				'post_type'                     =>  'cfs',
				'posts_per_page'		=> -1
			)
		);

		if ( is_array( $field_groups ) && count( $field_groups ) > 0 ) {

			foreach( $field_groups as $field_group ) {
				// find fields for this Field Group
				$fields = CFS()->find_fields(
					array(
						'group_id'              => $field_group->ID
					)
				);

				// determine the post type attached to this Field Group for registering fields for REST
				$attached_cfs_rules = get_post_meta( $field_group->ID, 'cfs_rules', true );
				$attached_post_types = array();

				// parse the CFS rules for ['post_types']['values'][0] which should contain the post type
				if ( is_array( $attached_cfs_rules ) && array_key_exists( 'post_types', $attached_cfs_rules ) ) {
					if ( array_key_exists( 'values', $attached_cfs_rules['post_types'] ) ) {
						if ( is_array( $attached_cfs_rules['post_types']['values'] ) && count( $attached_cfs_rules['post_types']['values'] ) > 0 ) {
							foreach( $attached_cfs_rules['post_types']['values'] as $value ) {
								$attached_post_types[] = $value;
							}
						}
					}
				}

				if ( is_array( $fields ) && count( $fields ) > 0 ) {
					foreach( $fields as $field ) {
						foreach( $attached_post_types as $attached_post_type) {
							// if field is in the current loop's field group, add to the list

							if ( ! property_exists( $this->field_info, $attached_post_type ) ) {
								$this->field_info->$attached_post_type = array();
							}

							$this->field_info->$attached_post_type = array_merge( $this->field_info->$attached_post_type, array( $field['name'] ) );
						}
					}
				}
			}
		}
	}

	/**
	 * Query all the Custom Post Type UI posts, get all CFS fields associated with them, and register them to be
	 * exposed through the WP REST API. Normal post permissions determine actual access through the API.
	 */
	public function register_all_cpt_cfs_fields() {

		if ( count ( get_object_vars( $this->field_info ) ) < 1 ) {
			$this->determine_cfs_fields();
		}

		$reflection = new ReflectionObject( $this->field_info );

		foreach( $this->field_info as $post_type => $field_set ) {
			
			$post_type_name = (string) $reflection->getProperty( $post_type )->name;

			if ( is_array( $field_set ) && count( $field_set ) > 0 ) {
				foreach( $field_set as $field ) {
					register_rest_field(
						$post_type_name,
						$field,
						array(
							'get_callback'                        => array( $this, 'rest_get_post_meta' ),
							'update_callback'                     => array( $this, 'rest_update_cfs_field' ),
							'schema'                              => null
						)
					);
				}
			}

		}
	}

};

if ( function_exists( 'add_action' ) ) {
	$TVS_REST_Support_for_Custom_Post_Types = new TVS_REST_Support_for_Custom_Post_Types();
}

