<?php
class Bea_MM_GroupSites_Site {
	private $obj = null;

	public function __construct($site_id = 0) {
		$defaults = array(
			'name' => null,
			'label' => null,
			'sites' => array(
				array('blog_id' => 0, 'language_code' => 'fr_FR', 'language_label' => 'French' )
			)
		);
		$args = wp_parse_args($args, $defaults);
	
	}
	
	public function __get( $key = '' ) {
		return( isset( $this->obj->$key ) ? $this->obj->$key : null );
	}

	public function get_id() {
		return $this->obj -> blog_id;
	}

	public function get_language_code() {
		return $this->obj -> language_code;
	}

	public function get_language_label() {
		return $this->obj -> language_label;
	}

	public function get_home_permalink() {
		return $this->obj -> language_label;
	}

}
