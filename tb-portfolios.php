<?php
/*
Plugin Name: Portfolios
Description: Extend the Post Grid system in your Theme Blvd theme to a Portfolio custom post type.
Version: 1.1.6
Author: Theme Blvd
Author URI: http://themeblvd.com
License: GPL2
*/

define( 'TB_PORTFOLIOS_PLUGIN_VERSION', '1.1.6' );
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

		// Localization
		add_action( 'init', array( $this, 'textdomain' ) );

		// Setup CPT & taxonomies
		add_action( 'init', array( $this, 'register' ) );

		// Theme Blvd Integration
		add_action( 'after_setup_theme', array( $this, 'themeblvd_init' ) );

	}

	/*--------------------------------------------*/
	/* Setup the post type and taxonomies
	/*--------------------------------------------*/

	/**
	 * Plugin text domain for localization.
	 * GlotPress-ready.
	 *
	 * @since 1.1.0
	 */
	public function textdomain() {
		load_plugin_textdomain('portfolios' );
	}

	/**
	 * Register post type
	 *
	 * @since 1.0.0
	 */
	public function register() {

		// Add Portfolio Item custom post type
		$labels = apply_filters( 'themeblvd_portfolio_item_cpt_labels', array(
			'name'               => __( 'Portfolio Items', 'portfolios' ),
			'singular_name'      => __( 'Portfolio Item', 'portfolios' ),
			'add_new'            => __( 'Add New Item', 'portfolios' ),
			'add_new_item'       => __( 'Add New Portfolio Item', 'portfolios' ),
			'edit_item'          => __( 'Edit Item', 'portfolios' ),
			'new_item'           => __( 'New Portfolio Item', 'portfolios' ),
			'all_items'          => __( 'Portfolio Items', 'portfolios' ),
			'view_item'          => __( 'View Item', 'portfolios' ),
			'search_items'       => __( 'Search Portfolio Items', 'portfolios' ),
			'not_found'          => __( 'No portfolio items found', 'portfolios' ),
			'not_found_in_trash' => __( 'No portfolio items found in Trash', 'portfolios' ),
			'menu_name'          => __( 'Portfolios' ),
		));

		$args = apply_filters( 'themeblvd_portfolio_item_cpt_args', array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'item' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_icon'          => 'dashicons-portfolio', // overridden with CSS if using Theme Blvd theme
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'taxonomies'         => array( 'portfolio', 'portfolio_tag' ),
			'show_in_rest'       => true,
		));

  		register_post_type( 'portfolio_item', $args );

  		// Add "Portfolio" taxonomy (i.e. Items are grouped into portfolios)
		$labels = apply_filters( 'themeblvd_portfolio_tax_labels', array(
			'name'              => __( 'Portfolios', 'portfolios' ),
			'singular_name'     => __( 'Portfolio', 'portfolios' ),
			'search_items'      => __( 'Search Portfolios', 'portfolios' ),
			'all_items'         => __( 'All Portfolios', 'portfolios' ),
			'parent_item'       => __( 'Parent Portfolio', 'portfolios' ),
			'parent_item_colon' => __( 'Parent Portfolio:', 'portfolios' ),
			'edit_item'         => __( 'Edit Portfolio', 'portfolios' ),
			'update_item'       => __( 'Update Portfolio', 'portfolios' ),
			'add_new_item'      => __( 'Add New Portfolio', 'portfolios' ),
			'new_item_name'     => __( 'New Portfolio Name', 'portfolios' ),
			'menu_name'         => __( 'Portfolios', 'portfolios' ),
		));

		$args = apply_filters( 'themeblvd_portfolio_tax_args', array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'items' ),
			'show_in_rest'      => true,
		));

		register_taxonomy( 'portfolio', array( 'portfolio_item' ), $args );

		// Add "Portfolio Tag" taxonomy
		$labels = apply_filters( 'themeblvd_portfolio_tag_tax_labels', array(
			'name'              => __( 'Portfolio Tags', 'portfolios' ),
			'singular_name'     => __( 'Portfolio Tag', 'portfolios' ),
			'search_items'      => __( 'Search Portfolio Tags', 'portfolios' ),
			'all_items'         => __( 'All Portfolio Tags', 'portfolios' ),
			'edit_item'         => __( 'Edit Portfolio Tag', 'portfolios' ),
			'update_item'       => __( 'Update Portfolio Tag', 'portfolios' ),
			'add_new_item'      => __( 'Add New Portfolio Tag', 'portfolios' ),
			'new_item_name'     => __( 'New Portfolio Tag Name', 'portfolios' ),
			'menu_name'         => __( 'Portfolio Tags', 'portfolios' ),
		));

		$args = apply_filters( 'themeblvd_portfolio_tag_tax_args', array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'item-tag' ),
			'show_in_rest'      => true,
		));

		register_taxonomy( 'portfolio_tag', array( 'portfolio_item' ), $args );

		register_taxonomy_for_object_type( 'portfolio_tag', 'portfolio_item' );

		register_taxonomy_for_object_type( 'portfolio', 'portfolio_item' );

	}

	/*--------------------------------------------*/
	/* Initiate Theme Blvd integration
	/*--------------------------------------------*/

	/**
	 * Apply Theme Blvd specific actions and filters.
	 *
	 * @since 1.0.1
	 */
	public function themeblvd_init() {

		if ( ! defined('TB_FRAMEWORK_VERSION') ) {
			return;
		}

		add_filter( 'themeblvd_elements', array( $this, 'builder_options' ) );
		add_filter( 'themeblvd_portfolio_module_options', array( $this, 'portfolio_module_options' ) );

		add_filter( 'themeblvd_posts_args', array( $this, 'query_args' ), 9, 2 );
		add_filter( 'themeblvd_post_slider_args', array( $this, 'query_args' ), 9, 2 );
		add_filter( 'themeblvd_slider_auto_args', array( $this, 'query_args' ), 9, 2 );
		add_filter( 'themeblvd_portfolio_module_args', array( $this, 'query_args' ), 9, 2 );

		add_filter( 'themeblvd_template_list_query', array( $this, 'page_template_query' ), 10, 3 );
		add_filter( 'themeblvd_template_grid_query', array( $this, 'page_template_query' ), 10, 3 );

		add_filter( 'themeblvd_post_meta', array( $this, 'post_meta' ) );
		add_filter( 'themeblvd_banner_meta', array( $this, 'post_meta' ) ); // only in framework 2.5+
		add_filter( 'themeblvd_meta_options_tb_post_options', array( $this, 'post_meta_options' ) );
		add_filter( 'themeblvd_pto_options', array( $this, 'pto_options' ) );

		if ( version_compare(TB_FRAMEWORK_VERSION, '2.5.0', '>=') ) { // requires framework 2.5+
			add_filter( 'themeblvd_formatted_options', array( $this, 'theme_options' ) );
			add_filter( 'themeblvd_theme_mode_override', array( $this, 'archive_mode' ), 10, 2 );
			add_action( 'themeblvd_archive_info', array( $this, 'archive_info' ) );
			add_filter( 'themeblvd_sidebar_layout', array( $this, 'sidebar_layout' ) );
		}

		// add_filter( 'themeblvd_locals', array( $this, 'locals' ) );
		add_filter( 'themeblvd_pre_breadcrumb_parts', array( $this, 'breadcrumbs' ), 10, 2 );
		add_filter( 'the_tags', array( $this, 'tags' ), 10, 4 );

		// @deprecated
		if ( version_compare(TB_FRAMEWORK_VERSION, '2.5.0', '<') ) {
			add_filter( 'themeblvd_template_parts', array( $this, 'template_parts' ) );
			add_filter( 'themeblvd_meta', array( $this, 'meta' ), 10, 6 );
		}

	}

	/*--------------------------------------------*/
	/* Theme Blvd Layout Builder Integration
	/*--------------------------------------------*/

	/**
	 * Modify options for posts lists and grids in
	 * the Theme Blvd Layout Builder.
	 *
	 * @since 1.0.0
	 */
	public function builder_options( $elements ) {

		// The keys for the elements that we're modifying
		// the options for.
		$items = $this->get_builder_items();

		// Loop through the items we're going to modify
		// and edit them within the overall $elements array.
		foreach ( $items as $item ) {

		if ( ! isset( $elements[$item]['options'] ) ) {
			continue;
		}

		$options = $elements[$item]['options'];

		// Add additional sources the user can pull
		// posts from.
		if ( isset( $options['source']['options'] ) ) {
			$options['source']['options'] = $this->set_sorce( $options['source']['options'] );
		}

		// Add additional filering options
		if ( isset( $options['filter']['options'] ) ) {
			$options['filter']['options']['portfolio'] = __( 'Filtered by portfolio', 'portfolios' );
			$options['filter']['options']['portfolio_tag'] = __( 'Filtered by portfolio tag', 'portfolios' );
		}

		// Set triggers on other options so they
		// appear when the user selects the source.
		$options = $this->set_triggers( $options );

		// Add options
		$options = $this->set_options( $options );

		// Finalize and put back options
		$elements[$item]['options'] = $options;

		}

		return $elements;
	}

	/**
	 * Add portfolio options to Portfolio Module,
	 * only exists in some themes, like Arcadian.
	 *
	 * @since 1.0.0
	 */
	public function portfolio_module_options( $options ) {

		// Add additional sources the user can pull
		// posts from.
		if ( isset( $options['source']['options'] ) ) {
			$options['source']['options'] = $this->set_sorce( $options['source']['options'] );
		}

		// Set triggers on other options so they
		// appear when the user selects the source.
		$options = $this->set_triggers( $options );

		// Add options
		$options = $this->set_options( $options );

		return $options;

	}

	/**
	 * Take the selections for the source of a Builder
	 * elements and add in the Portfolios sources.
	 *
	 * @since 1.0.0
	 */
	public function get_builder_items() {

		$items = array(
			'mini_post_list',
			'mini_post_grid',
			'post_grid',
			'post_list',
			'post_showcase',
			'post_slider',
			'post_slider_popout',

			// @deprecated
			'post_grid_paginated',
			'post_grid_slider',
			'post_list_paginated',
			'post_list_slider',
		);

		return apply_filters( 'themeblvd_portfolios_builder_items', $items );
	}

	/**
	 * Take the selections for the source of a Builder
	 * elements and add in the Portfolios sources.
	 *
	 * @since 1.0.0
	 */
	public function set_sorce( $selections ) {

		if ( ! is_array( $selections ) || count( $selections ) < 0 ) {
			return array();
		}

		$new_selections = array();

		foreach ( $selections as $key => $value ) {

			$new_selections[$key] = $value;

			if ( $key == 'tag' ) {
				$new_selections['portfolio'] = __( 'Portfolio', 'portfolios' );
				$new_selections['portfolio-tag'] = __( 'Portfolio Tag', 'portfolios' );
			}

		}

		return $new_selections;
	}

	/**
	 * Take the selections for the source of a Builder
	 * elements and add in the Portfolios sources.
	 *
	 * @since 1.0.0
	 */
	public function set_triggers( $options ) {

		foreach ( $options as $key => $option ) {

			if( ! isset( $option['class'] ) ) {
				continue;
			}

			if( strpos( $option['class'], 'receiver-category receiver-tag' ) === false ) {
				continue;
			}

			$options[$key]['class'] .= ' receiver-portfolio receiver-portfolio-tag';

		}

		return $options;
	}

	/**
	 * Add options to select portfolios and insert
	 * portfolio tag.
	 *
	 * @since 1.0.0
	 */
	public function set_options( $options ) {

		$new_options = array();

		foreach ( $options as $key => $option ) {

			$new_options[ $key ] = $option;

			// Insert new options after the "Tag" option
			if ( $key == 'tag' ) {

				// Add option to select portfolios
				$new_options['portfolio'] = array(
					'id'    => 'portfolio',
					'name'  => __( 'Portfolio', 'portfolios' ),
					'desc'  => __( 'Enter a portfolio slug, or a comma separated list of portfolio slugs, to pull posts from. Leave blank to pull all portfolio items.', 'portfolios' ),
					'type'  => 'text',
					'class' => 'hide receiver receiver-portfolio',
				);

				// Add option to input portfolio tag
				$new_options['portfolio_tag'] = array(
					'id'    => 'portfolio_tag',
					'name'  => __( 'Portfolio Tag', 'portfolios' ),
					'desc'  => __( 'Enter a single portfolio tag, or a comma separated list of portfolio tags, to pull posts from.', 'portfolios' ),
					'type'  => 'text',
					'class' => 'hide receiver receiver-portfolio-tag',
				);

			}

		}

		return $new_options;

	}

	/*--------------------------------------------*/
	/* Theme Blvd "Post Options" Integration
	/*--------------------------------------------*/

	/**
	 * Add "Post Options" to Portfolio Item custom
	 * post type.
	 *
	 * @since 1.0.0
	 */
	public function post_meta( $setup ) {
		$setup['config']['page'][] = 'portfolio_item';
		return $setup;
	}

	/**
	 * Adjustments to options in "Post Options" meta box.
	 *
	 * @since 1.0.0
	 */
	public function post_meta_options( $options ) {

		// Meta data hidden by default on single posts
		$options['tb_meta']['std'] = 'hide';

		return $options;

	}

	/**
	 * Adjustments to options in Theme Blvd "Post Template
	 * Options" plugin.
	 *
	 * @since 1.0.0
	 */
	public function pto_options( $options ) {

		$new_options = array();

		foreach ( $options as $key => $option ) {

			$new_options[ $key ] = $option;

			// Add our custom options after tag
			if ( $key == 'tag' ) {

				$new_options['portfolio'] = array(
					'id'   => 'portfolio',
					'name' => __( 'portfolio', 'portfolios' ),
					'desc' => __( 'Portfolio slugs to include.<br>Ex: my-portfolio<br>Ex: my-portfolio-1, my-portfolio-2', 'portfolios' ),
					'type' => 'text',
				);

				$new_options['portfolio_tag'] = array(
					'id'   => 'portfolio_tag',
					'name' => __( 'portfolio_tag', 'portfolios' ),
					'desc' => __( 'Portfolio tags to include.<br>Ex: my-tag<br>Ex: my-tag-1, my-tag-2', 'portfolios' ),
					'type' => 'text',
				);

			}

		}

		return $new_options;

	}

	/*--------------------------------------------*/
	/* Theme Blvd "Theme Options" Integration
	/*--------------------------------------------*/

	/**
	 * Adjustments to Theme Options page. Add "Portfolio"
	 * section just after "Archives" section. Only applies
	 * to framework 2.5+ themes.
	 *
	 * @since 1.1.0
	 */
	public function theme_options( $options ) {

		$new_options = array();

		foreach ( $options as $key => $option ) {

			$new_options[$key] = $option;

			if ( $key == 'end_section_archives' ) {

				$new_options['section_start_portfolios'] = array(
					'name' => __( 'Portfolios', 'portfolios' ),
					'type' => 'section_start',
				);

				$new_options['portfolio_mode'] = array(
					'name'    => __( 'Post Display', 'portfolios' ),
					'desc'    => __( 'When viewing a portfolio item, portfolio, or portfolio tag archive, how do you want the posts displayed?', 'portfolios' ),
					'id'      => 'portfolio_mode',
					'std'     => 'showcase',
					'type'    => 'select',
					'options' => array(
						'blog'     => __( 'Blog', 'portfolios' ),
						'list'     => __( 'List', 'portfolios' ),
						'grid'     => __( 'Grid', 'portfolios' ),
						'showcase' => __( 'Showcase', 'portfolios' ),
					),
				);

				$new_options['portfolio_info'] = array(
					'name'    => __( 'Portfolio Info Boxes', 'portfolios' ),
					'desc'    => __( 'When viewing a portfolio archive, would you like to show an info box at the top that contains the title and description of the current portfolio?', 'portfolios' ),
					'id'      => 'portfolio_info',
					'std'     => 'show',
					'type'    => 'select',
					'options' => array(
						'show' => __( 'Yes, show info boxes', 'portfolios' ),
						'hide' => __( 'No, hide info boxes', 'portfolios' ),
					),
				);

				$new_options['portfolio_tag_info'] = array(
					'name'    => __( 'Portfolio Tag Info Boxes', 'portfolios' ),
					'desc'    => __( 'When viewing a portfolio tag archive, would you like to show an info box at the top that contains the title and description of the current portfolio?', 'portfolios' ),
					'id'      => 'portfolio_tag_info',
					'std'     => 'hide',
					'type'    => 'select',
					'options' => array(
						'show' => __( 'Yes, show info boxes', 'portfolios' ),
						'hide' => __( 'No, hide info boxes', 'portfolios' ),
					),
				);

				$new_options['section_end_portfolios'] = array(
					'type' => 'section_end',
				);

			} else if ( $key == 'archive_sidebar_layout' ) {

				$new_options['portfolio_sidebar_layout'] = array(
					'name'      => __( 'Portfolios', 'themeblvd' ),
					'desc'      => __( 'When viewing a portfolio or portfolio tag archive of posts, what do you want to use for the sidebar layout?', 'themeblvd' ),
					'id'        => 'portfolio_sidebar_layout',
					'std'       => 'full_width',
					'type'      => 'images',
					'options'   => $new_options[$key]['options'],
					'img_width' => '45',
				);

			}

		}

		return $new_options;
	}

	/**
	 * Change post display mode for portfolio and portfolio
	 * tag archive pages, based on theme options additions.
	 *
	 * @since 1.1.0
	 */
	public function archive_mode( $mode, $q ) {

		if ( $q->is_post_type_archive('portfolio_item') || $q->is_tax('portfolio') || $q->is_tax('portfolio_tag') ) {

			$mode = themeblvd_get_option('portfolio_mode', null, 'showcase');

			if ( ! $mode || $mode == 'default' ) {
				$mode = themeblvd_get_option('archive_mode', null, 'blog');
			}

		}

		return $mode;
	}

	/**
	 * Display info box at the top of portfolio and portfolio
	 * tag archive pages, based on theme options additions.
	 *
	 * @since 1.1.0
	 */
	public function archive_info() {

		if ( ( is_tax('portfolio') && themeblvd_get_option('portfolio_info') == 'show' ) || ( is_tax('portfolio_tag') && themeblvd_get_option('portfolio_tag_info') == 'show' ) ) {
			themeblvd_tax_info();
		}

	}

	/**
	 * Display info box at the top of portfolio and portfolio
	 * tag archive pages, based on theme options additions.
	 *
	 * @since 1.1.2
	 */
	public function sidebar_layout( $layout ) {

		if ( is_tax('portfolio') || is_tax('portfolio_tag') ) {

			$layout = themeblvd_get_option('portfolio_sidebar_layout', null, 'full_width');

			if ( $layout == 'default' ) {
				$layout = themeblvd_get_option('sidebar_layout', null, apply_filters( 'themeblvd_default_sidebar_layout', 'sidebar_right') );
			}
		}

		return $layout;
	}

	/*--------------------------------------------*/
	/* Theme Blvd Query Modfications
	/*--------------------------------------------*/

	/**
	 * Allow "portfolio" and "portfolio_tag" custom
	 * fields with Post List and Post Grid page templates.
	 *
	 * @since 1.0.0
	 */
	public function page_template_query( $query, $custom, $post_id ) {

		if ( ! $custom ) {

			$portfolio = get_post_meta( $post_id, 'portfolio', true );
			$portfolio_tag = get_post_meta( $post_id, 'portfolio_tag', true );

			if ( $portfolio || $portfolio_tag ) {

				unset( $query['categories'], $query['cat'], $query['category_name'] );
				unset( $query['tag'] );

				$query['post_type'] = 'portfolio_item';

				if ( $portfolio ) {

					$portfolio = str_replace(' ', '', $portfolio );
					$portfolio = explode( ',', $portfolio );

					$query['tax_query'][] = array(
						'taxonomy' => 'portfolio',
						'field'    => 'slug',
						'terms'    => $portfolio,
					);
				}

				if ( $portfolio_tag ) {

					$portfolio_tag = str_replace(' ', '', $portfolio_tag );
					$portfolio_tag = explode( ',', $portfolio_tag );

					$query['tax_query'][] = array(
						'taxonomy' => 'portfolio_tag',
						'field'    => 'slug',
						'terms'    => $portfolio_tag,
					);
				}
			}
		}

		return $query;

	}

	/**
	 * On the frontend of the site, filter the query
	 * args for items that query posts, for our portfolio
	 * options.
	 *
	 * @since 1.0.0
	 */
	public function query_args( $query, $args ) {

		$source = '';

		if ( ! empty( $args['source'] ) ) {
			$source = $args['source'];
		}

		if ( $source == 'portfolio' || $source == 'portfolio-tag' || ! $source ) {

			// Portfolios
			if ( $source == 'portfolio' || ( ! $source && ! empty( $args['portfolio'] ) ) ) {

				$query['post_type'] = 'portfolio_item';

				if ( ! empty( $args['portfolio'] ) ) {

					$portfolios = str_replace(' ', '', $args['portfolio'] );
					$portfolios = explode( ',', $portfolios );

					$query['tax_query'][] = array(
						'taxonomy' => 'portfolio',
						'field'    => 'slug',
						'terms'    => $portfolios,
					);

				}

				// Remove standard post taxomonies
				unset( $query['categories'], $query['cat'], $query['category_name'] );
				unset( $query['tag'] );

			}

			// Portfolio Tags
			if ( $source == 'portfolio-tag' || ( ! $source && ! empty( $args['portfolio_tag'] ) ) ) {

				$query['post_type'] = 'portfolio_item';

				if ( ! empty( $args['portfolio_tag'] ) ) {

					$tags = str_replace(' ', '', $args['portfolio_tag'] );
					$tags = explode( ',', $tags );

					$query['tax_query'][] = array(
						'taxonomy'  => 'portfolio_tag',
						'field'     => 'slug',
						'terms'     => $tags,
					);

				}

				// Remove standard post taxomonies
				unset( $query['categories'], $query['cat'], $query['category_name'] );
				unset( $query['tag'] );
			}

		}

		return $query;
	}

	/*--------------------------------------------*/
	/* Theme Blvd Frontend Integration
	/*--------------------------------------------*/

	/**
	 * Frontend text strings
	 *
	 * @since 1.0.2
	 */
	public function locals( $locals ) {
		// ... @TODO Maybe do later if things get more complicated
		return $locals;
	}

	/**
 	* Breadcrumbs
 	*
 	* @since 1.0.0
 	*/
	public function breadcrumbs( $parts, $atts ) {

		global $wp_query;

		// Single Portfolio Items
		if ( is_single() && 'portfolio_item' == get_post_type() ) {

			$parts = array(); // reset it

			// Portfolio taxonomy tree
			$portfolio = get_the_terms( get_the_id(), 'portfolio' );

			if ( $portfolio ) {
				$portfolio = reset( $portfolio );
				$parents = themeblvd_get_term_parents( $portfolio->term_id, 'portfolio' );
				$parts = array_merge( $parts, $parents );
			}

			// Single post title
			$parts[] = array(
				'link' => '',
				'text' => get_the_title(),
				'type' => 'single',
			);

		}

		// Portfolios
		if ( is_tax( 'portfolio' ) ) {

			// Parent portfolios
			$portfolio_obj = $wp_query->get_queried_object();
			$current_portfolio = $portfolio_obj->term_id;
			$current_portfolio = get_term( $current_portfolio, 'portfolio' );

			if ( $current_portfolio->parent && ( $current_portfolio->parent != $current_portfolio->term_id ) ) {
				$parents = themeblvd_get_term_parents( $current_portfolio->parent, 'portfolio' );
				$parts = array_merge( $parts, $parents );
			}

			// Add current portfolio
			$parts[] = array(
				'link' => '',
				'text' => $current_portfolio->name,
				'type' => 'category',
			);


		}

		// Portfolio Tags
		if ( is_tax( 'portfolio_tag' ) ) {
			$parts[] = array(
				'link'  => '',
				'text'  => single_term_title( '', false ),
				'type'  => 'tag',
			);
		}

		return $parts;
	}

	/**
	 * Tags
	 *
	 * @since 1.0.0
	 */
	public function tags( $tags, $before, $sep, $after ) {

		if ( 'portfolio_item' == get_post_type() ) {
			$tags = get_the_term_list( get_the_id(), 'portfolio_tag', $before, $sep, $after );
		}

		return $tags;
	}

	/**
	 * Sub post meta
	 *
	 * @since 1.1.0
	 */
	public function sub_meta() {

		$taxos = apply_filters( 'themeblvd_portfolios_sub_meta_taxos', array( 'portfolio', 'portfolio_tag' ) );

		foreach ( $taxos as $tax ) {

			$terms = get_the_terms( get_the_ID(), $tax );

			if ( $terms ) {

				$tax_obj = get_taxonomy($tax);

				printf( '<div class="tb-%1$s %1$s">', $tax );
				printf( '<span class="title">%s:</span>', $tax_obj->labels->name );

				$count = count($terms);
				$i = 1;

				foreach ( $terms as $term ) {
					printf( '<a href="%1$s" title="%2$s">%2$s</a>', get_term_link( $term->term_id, $tax ), $term->name );
					if ( $i < $count ) {
						echo ', ';
					}
					$i++;
				}

				echo '</div>';
			}
		}

	}

	/**
	 * Post Meta
	 *
	 * @since 1.0.0
	 */
	public function meta( $output, $time, $author, $category, $comments, $sep ) {

		if ( get_post_type() == 'portfolio_item' ) {

			if ( $category ) {
				$output = str_replace( $category, '', $output );
			}

			$portfolio = get_the_term_list( get_the_id(), 'portfolio', '<span class="category"><i class="icon-reorder fa fa-bars"></i> ', ', ', '</span>' ); // "icon-reorder" class for older themes

			if ( $portfolio ) {
				$output = str_replace( $author, $author.$sep.$portfolio, $output );
			}

		}

		return $output;
	}

	/**
 	 * Adjust the template part used for archives
 	 * of portfolios and portfolio tags.
 	 *
 	 * @since 1.0.0
 	 */
	public function template_parts( $parts ) {

		// Point theme to content-grid.php and
		// trigger "grid" mode in framework 2.3+
		if ( is_tax( 'portfolio' ) || is_tax( 'portfolio_tag' ) ) {
			$parts['archive'] = 'grid';
		}

		return $parts;

	}

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

/**
 * Plugin activation
 *
 * @since 1.0.0
 */
function themeblvd_portfolios_activate() {

	// Register CPT
	$portfolios = Theme_Blvd_Portfolios::get_instance();
	$portfolios->register();

	// Flush re-write rules
	flush_rewrite_rules();

}
register_activation_hook( __FILE__, 'themeblvd_portfolios_activate' );
