<?php
class Bea_Multilingual_Multisite_Widget extends WP_Widget {
	/*--------------------------------------------------*/
	/* Constructor, PHP5
	/*--------------------------------------------------*/
	
	/**
	 * The widget constructor. Specifies the classname and description, instantiates
	 * the widget, loads localization files, and includes necessary scripts and
	 * styles.
	 */
	public function __construct() {
		// TODO: update classname and description
		// TODO: replace 'widget-name-locale' to be named more plugin specific. other instances exist throughout the code, too.
		parent::__construct(
			'widget-name-id',
			__( 'Widget Name', 'widget-name-locale' ),
			array(
				'classname'	=>	'widget-name-class',
				'description'	=>	__( 'Short description of the widget goes here.', 'widget-name-locale' )
			)
		);
	} // end constructor

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/
	
	/**
	 * Outputs the content of the widget.
	 *
	 * @args			The array of form elements
	 * @instance		The current instance of the widget
	 */
	public function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		
		echo $before_widget;
		
		// TODO: This is where you retrieve the widget values
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Widget Name', 'widget-name-locale' ) : $instance['title'], $instance, $this->id_base);

		// Display the widget, allow take template from child or parent theme
		if ( is_file(STYLESHEETPATH .'/widget-views/widget-name.php') ) { // Use custom template from child theme
			include( STYLESHEETPATH .'/widget-views/widget-name.php' );
		} elseif ( is_file(TEMPLATEPATH .'/widget-views/widget-name.php') ) { // Use custom template from parent theme
			include( TEMPLATEPATH .'/widget-views/widget-name.php' );
		} else { // Use builtin temlate
			include( BEA_MM_DIR . '/widgets/widget.php' );
		}
		
		echo $after_widget;
		
	} // end widget
	
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @new_instance	The previous instance of values before the update.
	 * @old_instance	The new instance of values to be generated via the update.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		// TODO Update the widget with the new values
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
		
	} // end widget
	
	/**
	 * Generates the administration form for the widget.
	 *
	 * @instance	The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		// TODO define default values for your variables
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'	=>	__( 'Widget Name', 'widget-name-locale' ),
			)
		);
	
		// TODO store the values of widget in a variable
		
		// Display the admin form
		include( BEA_MM_DIR . '/widgets/admin.php' );
	} // end form
}