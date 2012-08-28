<?php
class Bea_MM_Translation_View_Day implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array(
			'blog_id' => 0,
			'year' => 0,
			'monthnum' => 0,
			'day' => 0
		);
		$this->obj = (object) wp_parse_args($args, $defaults);
	}

	public function get_site_id() {
		return $this->obj->blog_id;
	}

	public function get_type() {
		return 'day';
	}

	public function get_id() {
		return 0;
	}

	public function get_permalink() {
		return get_day_link( $this->obj->year, $this->obj->monthnum, $this->obj->day );
	}

	public function get_title() {
		$date = sprintf('%1$d-%2$02d-%3$02d 00:00:00', $this->obj->year, $this->obj->monthnum, $this->obj->day);
		return mysql2date(get_option('date_format'), $date);
	}

	public function get_classes() {
		return 'day';
	}

	public function is_available() {
		global $wpdb;
        return $wpdb->get_var( $wpdb->prepare("SELECT count(ID) FROM {$wpdb->posts} WHERE DATE(post_date) = '%d-%02d-%02d' AND post_status = 'publish'", $this->obj->year, $this->obj->monthnum, $this->obj->day) );
	}
	
	public function __get( $key = '' ) {
		return( isset( $this->obj->$key ) ? $this->obj->$key : null );
	}
}