=== Portfolios ===
Author URI: http://www.themeblvd.com
Contributors: themeblvd
Tags: bundle, Theme Blvd, themeblvd, Jason Bobich, portfolios
Stable Tag: 1.1.2

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

Yup, but it just won't do a whole lot. You'll essentially end up with a "Portfolio Item" custom post type and associated "Portfolio" taxonomy with a cool retina icon.

= How can change the number of columns and rows in portfolio archive grids? =

`
function my_grid_columns() {
	return 3; // Number of columns (1-5)
}
add_filter( 'themeblvd_default_grid_columns', 'my_grid_columns' );

function my_grid_rows() {
	return 4; // Number of rows per page
}
add_filter( 'themeblvd_default_grid_rows', 'my_grid_rows' );
`

= How can disable portfolio archives from displaying in a grid? =

`
function my_portfolio_mods() {

	$portfolios = Theme_Blvd_Portfolios::get_instance();

	remove_filter( 'themeblvd_theme_mode_override', array( $portfolios, 'theme_mode' ) );
	remove_filter( 'themeblvd_template_parts', array( $portfolios, 'template_parts' ) );

}
add_action( 'after_setup_theme', 'my_portfolio_mods' );
`

== Changelog ==

= 1.1.2 =

* Fixed filters applied to registering the taxonomies.

= 1.1.1 =

* Fixed Theme Blvd integration hook, from last update.
* Fixed post meta integration (for framework 2.4 themes).

= 1.1.0 =

* List associated portfolios at the bottom of the portfolio item post (for framework 2.5+ themes).
* Added portfolio archive options at *Appearance > Theme Options > Content > Portfolios* (for framework 2.5+ themes).
* Fixes for Layout Builder 2.0+ integration.
* Added support for filtering with Post Grid, Post List, and Post Showcase elements of Layout Builder 2.0+ (for framework 2.5+ themes).
* GlotPress compatibility (for 2015 wordpress.org release).

= 1.0.1 =

* Fixed PHP warning on Portfolio Items breadcrumb trail when no portfolio is selected.

= 1.0.0 =

* This is the first release.