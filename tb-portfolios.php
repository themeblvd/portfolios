<?php
/*
Plugin Name: Theme Blvd Portfolios
Description: Extend the Post Grid system in your Theme Blvd theme to a Portfolio custom post type.
Version: 1.0.0
Author: Theme Blvd
Author URI: http://themeblvd.com
License: GPL2

    Copyright 2013  Theme Blvd

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

define( 'TB_PORTFOLIOS_PLUGIN_VERSION', '1.0.0' );
define( 'TB_PORTFOLIOS_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'TB_PORTFOLIOS_PLUGIN_URI', plugins_url( '' , __FILE__ ) );
define( 'TB_PORTFOLIOS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Setup Portfolios plugin.
 *
 * @since 1.0.0
 */
class Theme_Blvd_Portfolios {

    /**
     * Only instance of object.
     * @var Theme_Blvd_Portfolios
     */
    private static $instance = null;

    /**
     * Creates or returns an instance of this class.
     *
     * @return Theme_Blvd_Portfolios A single instance of this class.
     *
     * @since 1.0.0
     */
    public static function get_instance() {
        if ( self::$instance == null ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Run plugin.
     *
     * @since 1.0.0
     */
    private function __construct() {

    	// Setup CPT & taxonomies
    	add_action( 'init', array( $this, 'register' ) );
    	add_action( 'admin_enqueue_scripts', array( $this, 'menu_icon' ) );

    	// Add elements to Layout Builder plugin
    	// ...

    	// Add shortcodes [portfolio_grid]
    	// ...

    }

    /**
     * Register post type
     *
     * @since 1.0.0
     */
     public function register() {

     	// Add Portfolio Item custom post type
		$labels = apply_filters( 'themeblvd_portfolio_item_cpt_labels', array(
			'name' 					=> __( 'Portfolios', 'themeblvd_portfolios' ),
			'singular_name'			=> __( 'Portfolio Item', 'themeblvd_portfolios' ),
			'add_new'				=> __( 'Add New Item', 'themeblvd_portfolios' ),
			'add_new_item'			=> __( 'Add New Portfolio Item', 'themeblvd_portfolios' ),
			'edit_item'				=> __( 'Edit Portfolio Item', 'themeblvd_portfolios' ),
			'new_item'				=> __( 'New Portfolio Item', 'themeblvd_portfolios' ),
			'all_items'				=> __( 'Portfolio Items', 'themeblvd_portfolios' ),
			'view_item'				=> __( 'View Portfolio Items', 'themeblvd_portfolios' ),
			'search_items'			=> __( 'Search Portfolio Items', 'themeblvd_portfolios' ),
			'not_found'				=> __( 'No portfolio items found', 'themeblvd_portfolios' ),
			'not_found_in_trash'	=> __( 'No portfolio items found in Trash', 'themeblvd_portfolios' ),
			'menu_name'				=> __( 'Portfolios' )
		));

		$args = apply_filters( 'themeblvd_portfolio_item_cpt_args', array(
			'labels'				=> $labels,
			'public'				=> true,
			'publicly_queryable'	=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'query_var'				=> true,
			'rewrite'				=> array( 'slug' => 'item' ),
			'capability_type'		=> 'post',
			'has_archive'			=> true,
			'hierarchical'			=> false,
			'menu_icon'				=> TB_PORTFOLIOS_PLUGIN_URI . '/assets/images/menu-icon.png',
			'menu_position'			=> null,
			'supports'				=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'taxonomies'			=> array( 'tb_portfolio', 'post_tag' )
		));

  		register_post_type( 'portfolio_item', $args );

  		// Add "Portfolio" taxonomy (i.e. Items are grouped into portfolios)
		$labels = apply_filters( 'themeblvd_portfolio_tax_labels', array(
			'name'              => __( 'Portfolios', 'themeblvd_portfolios' ),
			'singular_name'     => __( 'Portfolio', 'themeblvd_portfolios' ),
			'search_items'      => __( 'Search Portfolios', 'themeblvd_portfolios' ),
			'all_items'         => __( 'All Portfolios', 'themeblvd_portfolios' ),
			'parent_item'       => __( 'Parent Portfolio', 'themeblvd_portfolios' ),
			'parent_item_colon' => __( 'Parent Portfolio:', 'themeblvd_portfolios' ),
			'edit_item'         => __( 'Edit Portfolio', 'themeblvd_portfolios' ),
			'update_item'       => __( 'Update Portfolio', 'themeblvd_portfolios' ),
			'add_new_item'      => __( 'Add New Portfolio', 'themeblvd_portfolios' ),
			'new_item_name'     => __( 'New Portfolio Name', 'themeblvd_portfolios' ),
			'menu_name'         => __( 'Portfolios', 'themeblvd_portfolios' )
		));

		$args = apply_filters( 'themeblvd_portfolio_tag_tax_args', array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'portfolio' ),
		));

		register_taxonomy( 'portfolio', array( 'portfolio_item' ), $args );

        // Add "Portfolio Tag" taxonomy
        $labels = apply_filters( 'themeblvd_portfolio_tag_tax_labels', array(
            'name'              => __( 'Portfolio Tags', 'themeblvd_portfolios' ),
            'singular_name'     => __( 'Portfolio Tag', 'themeblvd_portfolios' ),
            'search_items'      => __( 'Search Portfolio Tags', 'themeblvd_portfolios' ),
            'all_items'         => __( 'All Portfolio Tags', 'themeblvd_portfolios' ),
            'edit_item'         => __( 'Edit Portfolio Tag', 'themeblvd_portfolios' ),
            'update_item'       => __( 'Update Portfolio Tag', 'themeblvd_portfolios' ),
            'add_new_item'      => __( 'Add New Portfolio Tag', 'themeblvd_portfolios' ),
            'new_item_name'     => __( 'New Portfolio Tag Name', 'themeblvd_portfolios' ),
            'menu_name'         => __( 'Portfolio Tags', 'themeblvd_portfolios' )
        ));

        $args = apply_filters( 'themeblvd_portfolio_tag_tax_args', array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'portfolio-tag' ),
        ));

        register_taxonomy( 'portfolio_tag', array( 'portfolio_item' ), $args );

		// Better safe than sorry
		register_taxonomy_for_object_type( 'tb_portfolio_tag', 'portfolio_item' );
		register_taxonomy_for_object_type( 'tb_portfolio', 'portfolio_item' );

     }

    /**
     * Add CSS file to all of WP admin for menu icon.
     *
     * @since 1.0.0
     */
     public function menu_icon() {
     	wp_enqueue_style( 'themeblvd_portfolios_icon', TB_PORTFOLIOS_PLUGIN_URI . '/assets/css/icons.css' );
     }

     // And more Theme Blvd integration stuff to come ...
}

/**
 * Run Theme Blvd Portfolios plugin
 *
 * @since 1.0.0
 */
function themeblvd_portfolios_init() {
	Theme_Blvd_Portfolios::get_instance();
}
add_action( 'plugins_loaded', 'themeblvd_portfolios_init' );