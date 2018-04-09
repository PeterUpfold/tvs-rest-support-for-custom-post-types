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

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'add_action' ) ) {
        header('HTTP/1.1 403 Forbidden');
        die();
}

/**
 * Taxonomy: Result Sets.
 */

$labels = array(
	"name" => __( "Result Sets", "" ),
	"singular_name" => __( "Result Set", "" ),
);

$args = array(
	"label" => __( "Result Sets", "" ),
	"labels" => $labels,
	"public" => false,
	"hierarchical" => false,
	"label" => "Result Sets",
	"show_ui" => true,
	"show_in_menu" => true,
	"show_in_nav_menus" => true,
	"query_var" => true,
	"rewrite" => array( 'slug' => 'result_sets', 'with_front' => true, ),
	"show_admin_column" => false,
	"show_in_rest" => true,
	"rest_base" => "",
	"show_in_quick_edit" => false,
);
register_taxonomy( "result_sets", array( "results" ), $args );

/**
 * Taxonomy: Aspects.
 */

$labels = array(
	"name" => __( "Aspects", "" ),
	"singular_name" => __( "Aspect", "" ),
);

$args = array(
	"label" => __( "Aspects", "" ),
	"labels" => $labels,
	"public" => false,
	"hierarchical" => false,
	"label" => "Aspects",
	"show_ui" => true,
	"show_in_menu" => true,
	"show_in_nav_menus" => true,
	"query_var" => true,
	"rewrite" => array( 'slug' => 'aspects', 'with_front' => true, ),
	"show_admin_column" => false,
	"show_in_rest" => true,
	"rest_base" => "",
	"show_in_quick_edit" => true,
);
register_taxonomy( "aspects", array( "results" ), $args );

/**
 * Taxonomy: Admissions Numbers.
 */

$labels = array(
	"name" => __( "Admissions Numbers", "" ),
	"singular_name" => __( "Admissions Number", "" ),
);

$args = array(
	"label" => __( "Admissions Numbers", "" ),
	"labels" => $labels,
	"public" => false,
	"hierarchical" => false,
	"label" => "Admissions Numbers",
	"show_ui" => true,
	"show_in_menu" => true,
	"show_in_nav_menus" => true,
	"query_var" => true,
	"rewrite" => array( 'slug' => 'adnos', 'with_front' => true, ),
	"show_admin_column" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"show_in_quick_edit" => false,
	'capabilities'    => array(
		'manage_terms'   => 'manage_adnos',
		'edit_terms'     => 'edit_adnos',
		'delete_terms'   => 'delete_adnos',
		'assign_terms'   => 'edit_posts'
	)
);
register_taxonomy( "adnos", array( "achievements", "attendance-marks", "behaviour-incidents", "terms", "results" ), $args );

/**
 * Taxonomy: Pupil Usernames.
 */

$labels = array(
	"name" => __( "Pupil Usernames", "" ),
	"singular_name" => __( "Pupil Username", "" ),
);

$args = array(
	"label" => __( "Pupil Usernames", "" ),
	"labels" => $labels,
	"public" => false,
	"hierarchical" => false,
	"label" => "Pupil Usernames",
	"show_ui" => true,
	"show_in_menu" => true,
	"show_in_nav_menus" => true,
	"query_var" => true,
	"rewrite" => array( 'slug' => 'pupil_usernames', 'with_front' => true, ),
	"show_admin_column" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"show_in_quick_edit" => true,
);
register_taxonomy( "pupil_usernames", array( "achievements", "attendance-marks", "behaviour-incidents", "results" ), $args );

/**
 * Taxonomy: Person IDs.
 */

$labels = array(
	"name" => __( "Person IDs", "" ),
	"singular_name" => __( "Person ID", "" ),
);

$args = array(
	"label" => __( "Person IDs", "" ),
	"labels" => $labels,
	"public" => false,
	"hierarchical" => false,
	"label" => "Person IDs",
	"show_ui" => true,
	"show_in_menu" => true,
	"show_in_nav_menus" => true,
	"query_var" => true,
	"rewrite" => array( 'slug' => 'person_ids', 'with_front' => true, ),
	"show_admin_column" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"show_in_quick_edit" => false,
);
register_taxonomy( "person_ids", array( "achievements", "attendance-marks", "behaviour-incidents", "terms", "results" ), $args );

