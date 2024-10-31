<?php
/**
 * Plugin Name: Quotes
 * Plugin URI: http://www.r12themes.com
 * Description: Hi, I`m an wordpress plugin that show your favorite quotes on your blog using our shortcode, widget sau template tag.
 * Author: R12Themes
 * Version: 1.0.2
 * Author URI: http://www.r12themes.com
 *
 * @package WordPress
 * @subpackage R12Themes_Quotes
 * @author Razvan Cranganu
 * @since 1.0.0
 */

require_once( 'classes/class-r12themes-quotes.php' );
require_once( 'r12themes-quotes-template.php' );
require_once( 'classes/class-r12themes-widget-quotes.php' );

global $r12themes_quotes;
$r12themes_quotes = new R12Themes_Quotes( __FILE__ );

?>