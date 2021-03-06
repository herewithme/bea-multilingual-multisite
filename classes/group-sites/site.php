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
	 * @param string  $user_language   [description]
	 */
	public function __construct( $blog_id = 0, $language_code = '', $public_label = '', $admin_label = '', $user_language = '' ) {
		// All parameters are required
		if ( $blog_id == 0 || empty( $language_code ) || empty( $public_label ) ) {
			return false;
		}
		
		// Optionnal fields
		$admin_label = ( empty($admin_label) ) ? $public_label : $admin_label;

		// Blog exists ?
		$blog = get_blog_details( $blog_id, false );
		if ( $blog == false )
			return false;

		$this->obj = new stdClass;
		$this->obj->blog_id = $blog_id;
		$this->obj->language_code = $language_code;
		$this->obj->public_label = $public_label;
		$this->obj->admin_label = $admin_label;
		$this->obj->user_language = $user_language;

		return true;
	}

	/**
	 * Test if blog exist and valid
	 * @return boolean
	 */
	public function exists( ) {
		return !is_null( $this->obj );
	}

	/**
	 * Get blog ID
	 * @return integer
	 */
	public function get_id( ) {
		if ( !$this->exists( ) )
			return null;

		return $this->obj->blog_id;
	}

	/**
	 * Get language code, ex: fr_FR or de_DE
	 * @return string
	 */
	public function get_language_code( ) {
		if ( !$this->exists( ) )
			return null;

		return $this->obj->language_code;
	}

	/**
	 * Get public or admin language label, ex: French or Français
	 * @param  boolean $admin [description]
	 * @return string
	 */
	public function get_language_label( $admin = false ) {
		if ( !$this->exists( ) )
			return null;

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
		if ( !$this->exists( ) )
			return null;

		return get_home_url( $this->get_id( ), $path, $scheme );
	}
	

	/**
	 * Get user_language of language
	 * @return string
	 */
	public function get_user_language( ) {
		if ( !$this->exists( ) )
			return null;

		return $this->obj->user_language;
	}

}
