<?php
class Bea_MM_Translation_View_Home implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array(
			'blog_id' => 0
		);
		$this->$obj = (object) wp_parse_args($args, $defaults);
	}

	public function get_site_id() {
	}

	public function get_type() {
	}

	public function get_id() {
		return 0;
	}

	public function get_permalink() {
	}

	public function get_title() {
	}

	public function get_classes() {
	}

	public function is_available() {
		return true;
	}
	
	public function __get( $key = '' ) {
		return( isset( $this->obj->$key ) ? $this->obj->$key : null );
	}
}