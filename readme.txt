=== R12Themes Quotes===
Contributors: rzvc
Donate link: http://r12themes.com/
Tags: quotes, widgets, sidebar, shortcodes
Requires at least: 3.0.1
Tested up to: 3.4.2
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
It displays random qoutes on your sidebar or on your page depending where you want to be shown.

== Usage == 
You can add the quote on your sidebar by using the "Quote" widget that allows you to set the title of your block, number of quotes that you want to be displayed or one specific quote.

You can display the quotes via template by using `<?php do_action( 'r12themes_quotes' ); ?>`.

This sintax can be customized by using the following arguments:

* 'limit' => 1 (the default value - e.g `2`  will display two random quotes )
* 'id' => 0 ( display random post - e.g. `19`  will display the quote that have id = 19 )

You can add this in your post by using [r12themes_quotes], the arguments above apply to this one to.

== Installation ==
For installing R12Themes Quotes can be done by following this steps:

1. Download the plugin from WordPress.org;
1. Upload the ZIP file through the "Plugins > Add New > Upload" screen in your WordPress dashboad; 
1. Activate your plugin through the "Plugins" menu in WordPress;
1. Start using your new plugin by using the widget, shortcode or place `<?php do_action( 'r12themes_quotes' ); ?>` in your template files.

== Frequently Asked Questions ==

= The plugin looks grotesque =
It looks that way because it comes with no CSS styling for the front-end. This gives you the posibility to customize it according to your theme.

= Can I contribute to this plugin =
Yes, of course everyone is encourage to contribute to R12Thems Quotes. You can do this for following the <a href="https://github.com/r12themes/quotes" target="_blank">GitHub Account of R12themes</a>.

== Screenshots ==

1. The management screen in your WordPress admin panel

== Upgrade Notice == 

= 1.0.2 =
* Tweak - Update display Quotes icon in Admin panel.

= 1.0.1 =
* Tweak - FAQ 

= 1.0.0 =
* First release. Go planet!

== Changelog ==

= 1.0.2 =
* 2012-11-22
* Tweak - Update display Quotes icon in Admin panel.

= 1.0.1 =
* 2012-11-22
* Tweak - Faq - How to contribute to plugin development.

= 1.0.0 =
* 2012-11-19
* First release.