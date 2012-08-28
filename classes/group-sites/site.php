<?php
/**
 * Class that represente site
 */
class Bea_MM_GroupSites_Site {
	/**
	 * Site object
	 * @var stdClass
	 */
	private $obj = null;

	/**
	 * Constructor, setup class object
	 *
	 * @param integer $blog_id       [description]
	 * @param string  $language_code [description]
	 * @param string  $public_label  [description]
	 * @param string  $admin_label   [description]
	 */
	public function __construct( $blog_id = 0, $language_code = '', $public_label = '', $admin_label = '' ) {
		// All parameters are required
		if ( $blog_id == 0 || empty( $language_code ) || empty( $public_label ) || empty( $admin_label ) ) {
			return false;
		}

		// Blog exist ?
		$blog = get_blog_details( $blog_id, false );
		if ( $blog == false )
			return false;

		$this->obj = new stdClass;
		$this->obj->blog_id = $blog_id;
		$this->obj->language_code = $language_code;
		$this->obj->public_label = $public_label;
		$this->obj->admin_label = $admin_label;

		return true;
	}

	/**
	 * Test if blog exist and valid
	 * @return boolean
	 */
	public function exist( ) {
		return is_null( $this->obj );
	}

	/**
	 * Get blog ID
	 * @return integer
	 */
	public function get_id( ) {
		return $this->obj->blog_id;
	}

	/**
	 * Get language code, ex: fr_FR or de_DE
	 * @return string
	 */
	public function get_language_code( ) {
		return $this->obj->language_code;
	}

	/**
	 * Get public or admin language label, ex: French or Français
	 * @param  boolean $admin [description]
	 * @return string
	 */
	public function get_language_label( $admin = false ) {
		if ( $admin == true )
			return $this->obj->admin_label;

		return $this->obj->public_label;
	}

	/**
	 * Get blog home url
	 * @param  string $path   (optional) Path relative to the home url.
	 * @param  string $scheme (optional) Scheme to give the home url context. Currently 'http', 'https', or 'relative'.
	 * @return string
	 */
	public function get_permalink( $path = '', $scheme = null ) {
		return get_home_url( $this->get_id( ), $path, $scheme );
	}

	/**
	 * Key or null
	 * @param  string $key [description]
	 * @return mixed
	 */
	public function __get( $key = '' ) {
		return (isset( $this->obj->$key ) ? $this->obj->$key : null);
	}

}
