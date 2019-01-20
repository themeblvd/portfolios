=== Portfolios ===
Author URI: http://www.themeblvd.com
Contributors: themeblvd
Tags: bundle, Theme Blvd, themeblvd, Jason Bobich, portfolios
Stable Tag: 1.1.6
Tested up to: 5.0

Adds a "Portfolio Item" custom post type with associated "Portfolio" and "Porfolio Tag" taxonomies.

== Description ==

This plugin adds a "Portfolio Item" custom post type with associated "Portfolio" and "Porfolio Tag" taxonomies.

Any instances of `the_tags()` by your theme when on a portfolio item post are filtered to use Portfolio Tags.

= Theme Blvd Integration =

If you're using a theme with [Theme Blvd](http://www.themeblvd.com) framework v2.3+, this plugin has some cool integration features.

* Breadcrumb integration for Portfolio Items and associated taxonomy archives.
* Portfolio and Portfolio Tag WordPress can display in grid mode.
* Post List and Post Grid page templates can accept "portfolio" and "portfolio_tag" custom fields to filter posts.
* Standard "Post Options" meta box is integrated into the portfolio item custom post type.
* With our [Layout Builder](http://wordpress.org/plugins/theme-blvd-layout-builder) plugin, options to pull posts by Portfolio or Portfolio Tag are added to verious elements.
* With our [Shortcodes](http://wordpress.org/plugins/theme-blvd-shortcodes/) plugin, you can use "portfolio" and "portfolio_tag" parameters for `[post_list]` and `[post_grid]` shortcodes.
* With our [Sliders](http://wordpress.org/plugins/theme-blvd-sliders/) plugin, you can use "portfolio" and "portfolio_tag" parameters with `[post_slider]` shortcode.

== Installation ==

1. Upload `portfolios` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Will this plugin work if I'm not using a Theme Blvd theme? =

Yup, but it just won't do a whole lot. You'll essentially end up with a "Portfolio Item" custom post type and associated "Portfolio" and "Portfolio Tag" taxonomies.

= How can change the number of columns and rows in portfolio archive grids? =

`
function my_grid_columns() {
	return 3; // Number of columns (1-5)
}
add_filter('themeblvd_default_grid_columns', 'my_grid_columns');
`

`
function my_grid_rows() {
	return 4; // Number of rows per page
}
add_filter('themeblvd_default_grid_rows', 'my_grid_rows');
`

= How can disable portfolio archives from displaying in a grid? =

`
function my_portfolio_mods() {

	$portfolios = Theme_Blvd_Portfolios::get_instance();

	remove_filter( 'themeblvd_theme_mode_override', array( $portfolios, 'theme_mode' ) );
	remove_filter( 'themeblvd_template_parts', array( $portfolios, 'template_parts' ) );

}
add_action('after_setup_theme', 'my_portfolio_mods');
`

= How can I change the sidebar layout of Portfolio and Portfolio Tag archives? =

If you're using a theme with Theme Blvd framework 2.5+, there's a user option for this at *Appearance > Theme Options > Layout > Sidebar Layout > Portfolios*. And if not, you can use the following code.

`
function my_sidebar_layout( $layout ) {

	if ( is_tax('portfolio') || is_tax('portfolio_tag') ) {
		$layout = 'full_width';
	}

	return $layout;
}
add_filter('themeblvd_sidebar_layout', 'my_sidebar_layout');
`

More Info: [Customizing Sidebar Layouts](http://dev.themeblvd.com/tutorial/sidebar-layouts/)

= How can I change the URL slug of Portfolio and Portfolio Tag archives? =

`
function my_portfolio_tax_args( $args ) {
	$args['rewrite'] = array('slug' => 'my-slug');
	return $args;
}
add_filter('themeblvd_portfolio_tax_args', 'my_portfolio_tax_args');
`

`
function my_portfolio_tag_tax_args( $args ) {
	$args['rewrite'] = array('slug' => 'my-other-slug');
	return $args;
}
add_filter('themeblvd_portfolio_tag_tax_args', 'my_portfolio_tag_tax_args');
`

Note: Remember to flush your re-write rules! In other words, after you make this change, go to *Settings > Permalinks* in your WordPress admin, and re-save the page.

== Changelog ==

= 1.1.6 - 01/20/2019 =
* Allow portfolio taxonomies to be available through REST API and applied with Gutenberg.

= 1.1.5 - 09/07/2018 =
* Allow portfolio items to be available through REST API and editable with Gutenberg.

= 1.1.4 - 09/11/2016 =
* Apply archive post display option to portfolio item archives, opposed to just portfolio and portfolio tag taxonomy archives.

= 1.1.3 - 02/10/2015 =
* Changed default value for "Portfolio Info Boxes" option.
* Added Banner functionality to portfolio items (for framework 2.5+ themes).

= 1.1.2 - 12/16/2014 =
* Fixed filters applied to registering the taxonomies.
* Added option for sidebar layout on portfolio archives (for framework 2.5+ themes).
* Added more code examples to [FAQ](https://wordpress.org/plugins/portfolios/faq/) on using these filters.

= 1.1.1 - 12/15/2014 =
* Fixed Theme Blvd integration hook, from last update.
* Fixed post meta integration (for framework 2.4 themes).

= 1.1.0 - 12/13/2014 =
* List associated portfolios at the bottom of the portfolio item post (for framework 2.5+ themes).
* Added portfolio archive options at *Appearance > Theme Options > Content > Portfolios* (for framework 2.5+ themes).
* Fixes for Layout Builder 2.0+ integration.
* Added support for filtering with Post Grid, Post List, and Post Showcase elements of Layout Builder 2.0+ (for framework 2.5+ themes).
* GlotPress compatibility (for 2015 wordpress.org release).

= 1.0.1 - 07/21/2014 =
* Fixed PHP warning on Portfolio Items breadcrumb trail when no portfolio is selected.

= 1.0.0 - 08/01/2013 =
* This is the first release.
