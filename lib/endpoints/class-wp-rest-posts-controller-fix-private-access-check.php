<?php

/* Copyright (C) 2016-2017 Test Valley School.


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

class WP_REST_Posts_Controller_Fix_Private_Access_Check extends WP_REST_Posts_Controller {


	/**
	 * Validate whether the user can query private statuses
	 *
	 * @param  mixed $value
	 * @param  WP_REST_Request $request
	 * @param  string $parameter
	 * @return WP_Error|boolean
	 */
	public function validate_user_can_query_private_statuses( $value, $request, $parameter ) {
	
		// support for basic auth
		if ( wp_get_current_user()->ID == 0 ) {
			if ( array_key_exists( 'PHP_AUTH_USER', $_SERVER ) && array_key_exists( 'PHP_AUTH_PW', $_SERVER ) ) {
				wp_authenticate( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] );
			}
		}
	
		if ( 'publish' === $value ) {
			return true;
		}
		$post_type_obj = get_post_type_object( $this->post_type );
		if ( current_user_can( $post_type_obj->cap->read_private_posts ) ) {
			return true;
		}

		return new WP_Error( 'rest_forbidden_status', __( 'Status is forbidden' ), array( 'status' => rest_authorization_required_code() ) );
	}

	/**
	 * Sanitizes and validates the list of post statuses, including whether the
	 * user can query private statuses.
	 *
	 * @param string|array      $statuses    One or more post statuses
	 * @param WP_REST_Request   $request     Full details about the request.
	 * @param string            $parameter   Additional parameter to pass to validation.
	 * @return array|WP_Error   A list of valid statuses, otherwise a WP_Error object.
	 */
	public function sanitize_post_statuses( $statuses, $request, $parameter ) {
		
	}

};
