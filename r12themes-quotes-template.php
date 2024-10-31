<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'r12themes_get_quotes' ) ) {
	/** 
	 * Get the quotes
	 * @since  1.0.0
	 */
	function r12themes_get_quotes ( $args = '' ) {
		global $r12themes_quotes;
		return $r12themes_quotes->get_quotes( $args );
	} // End r12themes_get_quotes()
}

/**
 * Enable the usage of do_action( 'r12themes_qoutes' ) to display quotes within a theme/plugin.
 * @since  1.0.0
 */
add_action( 'r12themes_quotes', 'r12themes_quotes' );

if ( ! function_exists( 'r12themes_quotes' ) ) {
/**
 * Display or return HTML-formatted quotes.
 * @since  1.0.0
 */
function r12themes_quotes ( $args = '' ) {
	global $post;

	$defaults = array(
		'limit' => 1, 
		'orderby' => 'rand', 
		'order' => 'DESC', 
		'id' => 0, 
		'echo' => true, 
		'title' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Allow child themes/plugins to filter here.
	$args = apply_filters( 'r12themes_quotes_args', $args );
	$html = '';

	do_action( 'r12themes_quotes_before', $args );
		
		// The Query.
		$query = r12themes_get_quotes( $args );

		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
			$html .= '<div class="widget widget_r12themes_quotes">' . "\n";

			if ( '' != $args['title'] ) {
				$html .= '<h3 class="widget-title">' . esc_html( $args['title'] ) . '</h3>' . "\n";
			}
			
			// Begin templating logic.
			$tpl = '<div class="%%CLASS%%"><div class="quote-content">%%CONTENT%%</div><h3 class="quote-title">%%TITLE%%</h3></div>';
			$tpl = apply_filters( 'r12themes_quotes_item_template', $tpl, $args );

			$i = 0;
			foreach ( $query as $post ) {
				$template = $tpl;
				$i++;

				setup_postdata( $post );
				
				$class = 'quote';

				$title = '<i>by - <strong>' . get_the_title() . '</strong></i>';

				$template = str_replace( '%%CLASS%%', $class, $template );
				$template = str_replace( '%%CONTENT%%', get_the_content(), $template );
				$template = str_replace( '%%TITLE%%', $title, $template );

				$html .= $template;

			}

			$html .= '</div><!-- closing the widget class -->' . "\n";

			wp_reset_postdata();
		}
		
		// Allow child themes/plugins to filter here.
		$html = apply_filters( 'r12themes_quotes_html', $html, $query, $args );
		
		if ( $args['echo'] != true ) { return $html; }
		
		echo $html;
		
		do_action( 'r12themes_quotes_after', $args );
	} // End r12themes_quotes()
}

if ( ! function_exists( 'r12themes_quotes_shortcode' ) ) {
function r12themes_quotes_shortcode ( $atts, $content = null ) {
	$args = (array)$atts;

	$defaults = array(
		'limit' => 1, 
		'orderby' => 'rand', 
		'id' => 0, 
		'echo' => true 
	);

	$args = shortcode_atts( $defaults, $atts );

	$args['echo'] = false;

	// Fix integers.
	if ( isset( $args['limit'] ) ) $args['limit'] = intval( $args['limit'] );
	if ( isset( $args['id'] ) ) $args['id'] = intval( $args['id'] );
	if ( isset( $args['size'] ) &&  ( 0 < intval( $args['size'] ) ) ) $args['size'] = intval( $args['size'] );
	if ( isset( $args['per_row'] ) &&  ( 0 < intval( $args['per_row'] ) ) ) $args['per_row'] = intval( $args['per_row'] );

	return r12themes_quotes( $args );
} // End r12themes_quotes_shortcode()
}

add_shortcode( 'r12themes_quotes', 'r12themes_quotes_shortcode' );
?>