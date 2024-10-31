<?php
if ( ! defined( 'ABSPATH' ) ) exit; // jump out if accessed directly

/**
 * R12Themes Quotes Class
 * @author Razvan Cranganu
 * @since 1.0.0
 */

class R12Themes_Quotes {
	private $dir;
	private $assets_dir;
	private $assets_url;
	private $token;
	public  $version;
	private $file;

	/**
	 * Constructor function.
	 */
	public function __construct( $file ) {
		$this->dir = dirname( $file );
		$this->file = $file;
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( str_replace( WP_PLUGIN_DIR, WP_PLUGIN_URL, $this->assets_dir ) );
		$this->token = 'quote';
		register_activation_hook( $this->file, array( &$this, 'activation' ) );
		add_action( 'init', array( &$this, 'register_post_type' ) );

		if ( is_admin() ) {
			global $pagenow;
			add_filter( 'enter_title_here', array( &$this, 'enter_title_here' ) );
			add_action( 'admin_print_styles', array( &$this, 'enqueue_admin_styles' ), 10 );
			add_filter( 'post_updated_messages', array( &$this, 'updated_messages' ) );
		}

	} // End __construct()

	/**
	 * Register the post type.
	 */
	public function register_post_type () {
		$labels = array(
			'name' => _x( 'Quotes', 'post type general name', 'r12themes-quotes' ),
			'singular_name' => _x( 'Quote', 'post type singular name', 'r12themes-quotes' ),
			'add_new' => _x( 'Add New', 'Quote', 'r12themes-quotes' ),
			'add_new_item' => sprintf( __( 'Add New %s', 'r12themes-quotes' ), __( 'Quote', 'r12themes-quotes' ) ),
			'edit_item' => sprintf( __( 'Edit %s', 'r12themes-quotes' ), __( 'Quote', 'r12themes-quotes' ) ),
			'new_item' => sprintf( __( 'New %s', 'r12themes-quotes' ), __( 'Quote', 'r12themes-quotes' ) ),
			'all_items' => sprintf( __( 'All %s', 'r12themes-quotes' ), __( 'Quotes', 'r12themes-quotes' ) ),
			'view_item' => sprintf( __( 'View %s', 'r12themes-quotes' ), __( 'Qoute', 'r12themes-quotes' ) ),
			'search_items' => sprintf( __( 'Search %a', 'r12themes-quotes' ), __( 'Quotes', 'r12themes-quotes' ) ),
			'not_found' =>  sprintf( __( 'No %s Found', 'r12themes-quotes' ), __( 'Quotes', 'r12themes-quotes' ) ),
			'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'r12themes-quotes' ), __( 'Quotes', 'r12themes-quotes' ) ),
			'parent_item_colon' => '',
			'menu_name' => __( 'Quotes', 'r12themes-quotes' )
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'quote' ),
			'capability_type' => 'post',
			'has_archive' => array( 'slug' => 'quotes' ),
			'hierarchical' => false,
			'supports' => array( 'title', 'editor' ), 
			'menu_position' => 5, 
			'menu_icon' => ''
		);
		register_post_type( $this->token, $args );
	} // End register_post_type()

	/**
	 * Update messages for the post type quote - admin mode.
	 */
	public function updated_messages ( $messages ) {
	  global $post, $post_ID;

	  $messages[$this->token] = array(
	    0 => '', // Unused.
	    1 => sprintf( __( 'Quote updated. %sView Quote%s', 'r12themes-quotes' ), '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    2 => __( 'Custom field updated.', 'r12themes-quotes' ),
	    3 => __( 'Custom field deleted.', 'r12themes-quotes' ),
	    4 => __( 'Quote updated.', 'r12themes-quotes' ),
	    5 => isset($_GET['revision']) ? sprintf( __( 'Quote restored to revision from %s', 'r12themes-quotes' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    6 => sprintf( __( 'Quote published. %sView Quote%s', 'r12themes-quotes' ), '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    7 => __('Quote saved.'),
	    8 => sprintf( __( 'Quote submitted. %sPreview Quote%s', 'r12themes-quotes' ), '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', '</a>' ),
	    9 => sprintf( __( 'Quote scheduled for: %1$s. %2$sPreview Quote%3$s', 'r12themes-quotes' ),
	      '<strong>' . date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) . '</strong>', '<a target="_blank" href="' . esc_url( get_permalink($post_ID) ) . '">', '</a>' ),
	    10 => sprintf( __( 'Quote draft updated. %sPreview Quote%s', 'r12themes-quotes' ), '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', '</a>' ),
	  );

	  return $messages;
	} // End updated_messages()

	/**
	 * Customise the "Enter title here" text.
	 */
	public function enter_title_here ( $title ) {
		if ( get_post_type() == $this->token ) {
			$title = __( 'Enter the quote author here', 'r12themes-quotes' );
		}
		return $title;
	} // End enter_title_here() function - this add the quote author 

	/**
	 * Enqueue post type admin CSS.
	 */
	public function enqueue_admin_styles () {
		wp_register_style( 'r12themes-quotes-admin', $this->assets_url . '/css/admin.css', array(), '1.0.0' );
		wp_enqueue_style( 'r12themes-quotes-admin' );
	} // End enqueue_admin_styles()

	/**
	 * Get quotes.
	 */
	public function get_quotes ( $args = '' ) {
		$defaults = array(
			'limit' => 1, 
			'orderby' => 'rand', 
			'order' => 'DESC', 
			'id' => 0
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		// Allow child themes/plugins to filter here.
		$args = apply_filters( 'r12themes_get_quotes_args', $args );
		
		// The Query Arguments.
		$query_args = array();
		$query_args['post_type'] = 'quote';
		$query_args['numberposts'] = $args['limit'];
		$query_args['orderby'] = $args['orderby'];
		$query_args['order'] = $args['order'];
		
		if ( is_numeric( $args['id'] ) && ( intval( $args['id'] ) > 0 ) ) {
			$query_args['p'] = intval( $args['id'] );
		}
		
		// Whitelist checks.
		if ( ! in_array( $query_args['orderby'], array( 'none', 'ID', 'author', 'title', 'date', 'modified', 'parent', 'rand', 'comment_count', 'menu_order' ) ) ) {
			$query_args['orderby'] = 'date';
		}
		
		if ( ! in_array( $query_args['order'], array( 'ASC', 'DESC' ) ) ) {
			$query_args['order'] = 'DESC';
		}
		
		if ( ! in_array( $query_args['post_type'], get_post_types() ) ) {
			$query_args['post_type'] = 'quote';
		}
		
		// The Query.
		$query = get_posts( $query_args );
		
		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
			foreach ( $query as $k => $v ) {
				$meta = get_post_custom( $v->ID );

				// Get the URL.
				if ( isset( $meta['_url'][0] ) && '' != $meta['_url'][0] ) {
					$query[$k]->url = esc_url( $meta['_url'][0] );
				} else {
					$query[$k]->url = get_permalink( $v->ID );
				}
			}
		} else {
			$query = false;
		}
		
		return $query;
	} // End get_quotes()

	/**
	 * Run on activation.
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * Register the plugin's version.
	 */
	private function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( 'r12themes-quotes' . '-version', $this->version );
		}
	} // End register_plugin_version()

} //End class

?>