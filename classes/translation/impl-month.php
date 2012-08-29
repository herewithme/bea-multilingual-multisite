<?php
class Bea_MM_Translation_View_Month implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0, 'year' => 0, 'monthnum' => 0, );
		$this->obj = (object) wp_parse_args( $args, $defaults );
	}

	public function get_blog_id( ) {
		return $this->obj->blog_id;
	}

	public function get_type( ) {
		return 'month';
	}

	public function get_id( ) {
		return 0;
	}

	public function get_permalink( ) {
		switch_to_blog( $this->obj->blog_id );
		$return_value = get_month_link( $this->obj->year, $this->obj->monthnum );
		restore_current_blog();
		
		return $return_value;
	}

	public function get_title( ) {
		global $wp_locale;
		
		switch_to_blog( $this->obj->blog_id );
		$return_value = sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $this->obj->monthnum ), $this->obj->year );
		restore_current_blog();
		
		return $return_value;
	}

	public function get_classes( ) {
		return 'month';
	}

	public function is_available( ) {
		global $wpdb;
		
		switch_to_blog( $this->obj->blog_id );
		$return_value = $wpdb->get_var( $wpdb->prepare( "SELECT count(ID) FROM {$wpdb->posts} WHERE YEAR(post_date) = %d AND MONTH(post_date) = %d AND post_status = 'publish'", $this->obj->year, $this->obj->monthnum ) );
		restore_current_blog();
		
		return $return_value;
	}

}
