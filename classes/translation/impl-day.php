<?php
class Bea_MM_Translation_View_Day implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0, 'year' => 0, 'monthnum' => 0, 'day' => 0 );
		$this->obj = (object) wp_parse_args( $args, $defaults );
	}

	public function get_blog_id( ) {
		return $this->obj->blog_id;
	}

	public function get_type( ) {
		return 'day';
	}

	public function get_id( ) {
		return 0;
	}

	public function get_permalink( ) {
		switch_to_blog( $this->obj->blog_id );
		$return_value = get_day_link( $this->obj->year, $this->obj->monthnum, $this->obj->day );
		restore_current_blog();
		
		return $return_value;
	}

	public function get_title( ) {
		$date = sprintf( '%1$d-%2$02d-%3$02d 00:00:00', $this->obj->year, $this->obj->monthnum, $this->obj->day );
		
		switch_to_blog( $this->obj->blog_id );
		$return_value =  mysql2date( get_option( 'date_format' ), $date );
		restore_current_blog();
		
		return $return_value;
	}

	public function get_classes( ) {
		return 'day';
	}

	public function is_available( ) {
		global $wpdb;
		
		switch_to_blog( $this->obj->blog_id );
		$return_value = $wpdb->get_var( $wpdb->prepare( "SELECT count(ID) FROM {$wpdb->posts} WHERE DATE(post_date) = '%d-%02d-%02d' AND post_status = 'publish'", $this->obj->year, $this->obj->monthnum, $this->obj->day ) );
		restore_current_blog();
		
		return $return_value;
	}

}
