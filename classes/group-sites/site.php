<?php
/**
 * Class that represente site
 */
class Bea_MM_GroupSites_Site {
	private $obj = null;

	/**
	 * Constructor
	 */
	public function __construct($blog_id = 0, $language_code = '', $public_label = '', $admin_label = '') {
		if ($blog_id == 0 || empty($language_code) || empty($public_label) || empty($admin_label)) {
			return false;
		}
		
		$this -> obj = new stdClass;
		$this -> obj -> blog_id = $blog_id;
		$this -> obj -> language_code = $language_code;
		$this -> obj -> public_label = $public_label;
		$this -> obj -> admin_label = $admin_label;
		return true;
	}

	public function get_id() {
		return $this -> obj -> blog_id;
	}

	public function get_language_code() {
		return $this -> obj -> language_code;
	}

	
	public function get_language_label( $admin = false ) {
		if ( $admin == true )
			return $this -> obj -> admin_label;
		
		return $this -> obj -> public_label;
	}

	public function get_home_permalink() {
		return $this -> obj -> language_label;
	}

	/**
	 * Key or null
	 */
	public function __get($key = '') {
		return (isset($this -> obj -> $key) ? $this -> obj -> $key : null);
	}

}
