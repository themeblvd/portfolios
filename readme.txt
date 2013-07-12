=== Theme Blvd Portfolios ===
Author URI: http://www.themeblvd.com
Contributors: themeblvd
Tags: bundle, Theme Blvd, themeblvd, Jason Bobich, portfolios
Stable Tag: 1.0.0
Tested up to: 3.6

Extend the Post Grid system in your Theme Blvd theme to a Portfolio Item custom post type.

== Description ==

@TODO

== Installation ==

1. Upload `portfolios` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

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

= 1.0.0 =

* This is the first release.