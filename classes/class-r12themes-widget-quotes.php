<?php
if ( ! defined( 'ABSPATH' ) || ! function_exists( 'r12themes_quotes' ) ) exit; // Exit if accessed directly.

/**
 * R12Themes Quotes Widget
 * @subpackage R12Themes_Quotes
 * @category Widgets
 * @author Razvan Cranganu
 * @since 1.0.0
 *
 */
class R12Themes_Widget_Quotes extends WP_Widget {
	protected $r12themes_widget_cssclass;
	protected $r12themes_widget_description;
	protected $r12themes_widget_idbase;
	protected $r12themes_widget_title;

	/**
	 * Constructor function.
	 */
	public function __construct() {
		/* Widget variable settings. */
		$this->r12themes_widget_cssclass = 'widget_r12themes_quotes';
		$this->r12themes_widget_description = __( 'Add a random or a specific quote on your sidebar.', 'r12themes-quotes' );
		$this->r12themes_widget_idbase = 'r12themes_quotes';
		$this->r12themes_widget_title = __( 'R12Themes Quotes', 'r12themes-quotes' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->r12themes_widget_cssclass, 'description' => $this->r12themes_widget_description );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => $this->r12themes_widget_idbase );

		/* Create the widget. */
		$this->WP_Widget( $this->r12themes_widget_idbase, $this->r12themes_widget_title, $widget_ops, $control_ops );	
	} // End __construct()

	/**
	 * Display the widget on the frontend.
	 */
	public function widget( $args, $instance ) {  
		extract( $args, EXTR_SKIP );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );
			
		/* Before widget (defined by themes). */
		echo $before_widget;

		$args = array();

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title ) { $args['title'] = $title; }
		
		/* Widget content. */
		// Add actions for plugins/themes to hook onto.
		do_action( $this->r12themes_widget_cssclass . '_top' );

		// Integer values.
		if ( isset( $instance['limit'] ) && ( 0 < count( $instance['limit'] ) ) ) { $args['limit'] = intval( $instance['limit'] ); }
		if ( isset( $instance['specific_id'] ) && ( 0 < count( $instance['specific_id'] ) ) ) { $args['id'] = intval( $instance['specific_id'] ); }
		if ( isset( $instance['size'] ) && ( 0 < count( $instance['size'] ) ) ) { $args['size'] = intval( $instance['size'] ); }
		if ( isset( $instance['per_row'] ) && ( 0 < count( $instance['per_row'] ) ) ) { $args['per_row'] = intval( $instance['per_row'] ); }

		// Display the quotes.
		r12themes_quotes( $args );

		// Add actions for plugins/themes to hook onto.
		do_action( $this->r12themes_widget_cssclass . '_bottom' );

		/* After widget (defined by themes). */
		echo $after_widget;
	} // End widget()

	/**
	 * Update the settings from the form() method.
	 */
	public function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* Make sure the integer values are definitely integers. */
		$instance['limit'] = intval( $new_instance['limit'] );
		$instance['specific_id'] = intval( $new_instance['specific_id'] );
		$instance['size'] = intval( $new_instance['size'] );
		$instance['per_row'] = intval( $new_instance['per_row'] );

		/* The select box is returning a text value, so we escape it. */
		$instance['orderby'] = esc_attr( $new_instance['orderby'] );
		$instance['order'] = esc_attr( $new_instance['order'] );

		return $instance;
	} // End update()

	/**
	 * The form on the widget control in the widget administration area.
	 * @since  1.0.0
	 */
    public function form( $instance ) {       
   
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => '', 
			'limit' => 1, 
			'orderby' => 'rand', 
			'specific_id' => '', 
			'per_row' => 1
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'r12themes-quotes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<!-- Widget Limit: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit:', 'r12themes-quotes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>"  value="<?php echo $instance['limit']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'specific_id' ); ?>"><?php _e( 'Specific ID (optional):', 'r12themes-quotes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'specific_id' ); ?>"  value="<?php echo $instance['specific_id']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'specific_id' ); ?>" />
		</p>
		<p><small><?php _e( 'Display a specific quote, rather than a list.', 'r12themes-quotes' ); ?></small></p>
<?php
	} // End form()

} // End Class

/* Register the widget. */
add_action( 'widgets_init', create_function( '', 'return register_widget("R12Themes_Widget_Quotes");' ), 1 ); 
?>