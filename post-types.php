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
 * Post Type: Lunch Weeks.
 */

$labels = array(
	"name" => __( "Lunch Weeks", "" ),
	"singular_name" => __( "Lunch Week", "" ),
);

$args = array(
	"label" => __( "Lunch Weeks", "" ),
	"labels" => $labels,
	"description" => "",
	"public" => true,
	"publicly_queryable" => false,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => false,
	"capability_type" => "post",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "lunch-weeks", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-calendar",
	"supports" => array( "title", "editor", "thumbnail" ),
);

register_post_type( "lunch-weeks", $args );

/**
 * Post Type: Lunch Menus.
 */

$labels = array(
	"name" => __( "Lunch Menus", "" ),
	"singular_name" => __( "Lunch Menu", "" ),
);

$args = array(
	"label" => __( "Lunch Menus", "" ),
	"labels" => $labels,
	"description" => "",
	"public" => true,
	"publicly_queryable" => false,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => false,
	"capability_type" => "post",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "lunch-menus", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-store",
	"supports" => array( "title", "editor", "thumbnail" ),
);

$this->register( "lunch-menus", $args );

/**
 * Post Type: Achievements.
 */

$labels = array(
	"name" => __( "Achievements", "" ),
	"singular_name" => __( "Achievement", "" ),
);

$args = array(
	"label" => __( "Achievements", "" ),
	"labels" => $labels,
	"description" => "",
	"public" => true,
	"publicly_queryable" => true,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => true,
	"capability_type" => "achievement",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "achievements", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-star-filled",
	"supports" => array( "title", "custom-fields" ),
);

$this->register( "achievements", $args );

/**
 * Post Type: Attendance Marks.
 */

$labels = array(
	"name" => __( "Attendance Marks", "" ),
	"singular_name" => __( "Attendance Mark", "" ),
);

$args = array(
	"label" => __( "Attendance Marks", "" ),
	"labels" => $labels,
	"description" => "",
	"public" => true,
	"publicly_queryable" => true,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => true,
	"capability_type" => "attendance-mark",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "attendance-marks", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-yes",
	"supports" => array( "title", "custom-fields" ),
);

$this->register( "attendance-marks", $args );

/**
 * Post Type: Behaviour Incidents.
 */

$labels = array(
	"name" => __( "Behaviour Incidents", "" ),
	"singular_name" => __( "Behaviour Incident", "" ),
);

$args = array(
	"label" => __( "Behaviour Incidents", "" ),
	"labels" => $labels,
	"description" => "",
	"public" => true,
	"publicly_queryable" => true,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => true,
	"capability_type" => "behaviour-incident",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "behaviour-incidents", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-clipboard",
	"supports" => array( "title", "editor", "thumbnail" ),
);

$this->register( "behaviour-incidents", $args );

/**
 * Post Type: Terms.
 */

$labels = array(
	"name" => __( "Terms", "" ),
	"singular_name" => __( "Term", "" ),
);

$args = array(
	"label" => __( "Terms", "" ),
	"labels" => $labels,
	"description" => "",
	"public" => true,
	"publicly_queryable" => false,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => false,
	"capability_type" => "post",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "terms", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-admin-multisite",
	"supports" => array( "title" ),
);

$this->register( "terms", $args );

/**
 * Post Type: Attendance Summaries.
 */

$labels = array(
	"name" => __( "Attendance Summaries", "" ),
	"singular_name" => __( "Attendance Summary", "" ),
);

$args = array(
	"label" => __( "Attendance Summaries", "" ),
	"labels" => $labels,
	"description" => "Holds summarised information about a pupil\'s attendance in a given academic year.",
	"public" => false,
	"publicly_queryable" => true,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => false,
	"capability_type" => "attendance-mark",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "attendance_summaries", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-chart-bar",
	"supports" => array( "title" ),
);

$this->register( "attendance_summaries", $args );

/**
 * Post Type: Results.
 */

$labels = array(
	"name" => __( "Results", "" ),
	"singular_name" => __( "Result", "" ),
);

$args = array(
	"label" => __( "Results", "" ),
	"labels" => $labels,
	"description" => "Allows us to store individual result entries extracted from the MIS. The results are linked to an Aspect, and possibly a Result Set.",
	"public" => true,
	"publicly_queryable" => false,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => false,
	"capability_type" => "achievement",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "results", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-yes",
	"supports" => array( "title", "editor", "thumbnail", "custom-fields" ),
	"taxonomies" => array( "category" ),
);

$this->register( "results", $args );

/**
 * Post Type: Achievement Totals.
 */

$labels = array(
	"name" => __( "Achievement Totals", "" ),
	"singular_name" => __( "Achievement Total", "" ),
);

$args = array(
	"label" => __( "Achievement Totals", "" ),
	"labels" => $labels,
	"description" => "Total achievement points, behaviour points, and other summary information relating to conduct.",
	"public" => false,
	"publicly_queryable" => false,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => false,
	"capability_type" => "achievement",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "achievement_totals", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-chart-bar",
	"supports" => array( "title", "thumbnail", "custom-fields" ),
);

$this->register( "achievement_totals", $args );

/**
 * Post Type: Tutor Groups.
 */

$labels = array(
	"name" => __( "Tutor Groups", "" ),
	"singular_name" => __( "Tutor Group", "" ),
);

$args = array(
	"label" => __( "Tutor Groups", "" ),
	"labels" => $labels,
	"description" => "Stores public information and statistics relating to tutor groups, i.e. achievement totals.",
	"public" => true,
	"publicly_queryable" => true,
	"show_ui" => true,
	"show_in_rest" => true,
	"rest_base" => "",
	"has_archive" => false,
	"show_in_menu" => true,
	"exclude_from_search" => false,
	"capability_type" => "achievement",
	"map_meta_cap" => true,
	"hierarchical" => false,
	"rewrite" => array( "slug" => "tutor_groups", "with_front" => true ),
	"query_var" => true,
	"menu_icon" => "dashicons-admin-multisite",
	"supports" => array( "title", "editor", "thumbnail", "custom-fields" ),
);

$this->register( "tutor_groups", $args );
