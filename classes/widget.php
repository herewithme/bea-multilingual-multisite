<?php
class Bea_MM_Widget extends WP_Widget {
	/**
	 * The widget constructor. Specifies the classname and description, instantiates
	 * the widget, loads localization files, and includes necessary scripts and
	 * styles.
	 */
	public function __construct( ) {
		parent::__construct( 'bea-multilingual-multisite', __( 'Language switcher', 'bea-mm' ), array( 'classname' => 'bea-multilingual-multisite language-switcher', 'description' => __( 'Display avalaible translation for current view.', 'bea-mm' ) ) );
	}

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
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Widget Name', 'bea-mm' ) : $instance['title'], $instance, $this->id_base );
		
		$langs = Bea_MM_Client::get_langs();
		
		// Display the widget, allow take template from child or parent theme
		if ( is_file( STYLESHEETPATH . '/widget-views/bea-widget-mm-related.php' ) ) {// Use custom template from child theme
			include (STYLESHEETPATH . '/widget-views/bea-widget-mm-related.php');
		} elseif ( is_file( TEMPLATEPATH . '/widget-views/bea-widget-mm-related.php' ) ) {// Use custom template from parent theme
			include (TEMPLATEPATH . '/widget-views/bea-widget-mm-related.php');
		} else {// Use builtin temlate
			include (BEA_MM_DIR . '/widgets/widget.php');
		}

		echo $after_widget;

	}

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @new_instance	The previous instance of values before the update.
	 * @old_instance	The new instance of values to be generated via the update.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// TODO Update the widget with the new values
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;

	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @instance	The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		// TODO define default values for your variables
		$instance = wp_parse_args( (array)$instance, array( 'title' => __( 'Widget Name', 'bea-mm' ), ) );

		// Display the admin form
		include (BEA_MM_DIR . '/widgets/admin.php');
	}

}
