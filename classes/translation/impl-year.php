<?php
class Bea_MM_Translation_View_Year implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0, 'year' => 0, );
		$this->obj = (object) wp_parse_args( $args, $defaults );
	}

	public function get_blog_id( ) {
		return $this->obj->blog_id;
	}

	public function get_type( ) {
		return 'year';
	}

	public function get_id( ) {
		return 0;
	}

	public function get_permalink( ) {
		switch_to_blog( $this->obj->blog_id );
		$return_value = get_year_link( $this->obj->year );
		restore_current_blog();
		
		return $return_value;
	}

	public function get_title( ) {
		return sprintf( '%d', $this->obj->year );
	}

	public function get_classes( ) {
		return 'year';
	}

	public function is_available( ) {
		global $wpdb;
		
		switch_to_blog( $this->obj->blog_id );
		$return_value = $wpdb->get_var( $wpdb->prepare( "SELECT count(ID) FROM {$wpdb->posts} WHERE YEAR(post_date) = %d AND post_status = 'publish'", $this->obj->year ) );
		restore_current_blog();
		
		return $return_value;
	}
}