<?php
/**
 *  Class for make groups sites factory, allow to get
 *  This class used a global for save group, as WP for CPT or Taxonomies
 */
class Bea_MM_GroupSites_Factory {
	/**
	 * Constructor, do nothing
	 */
	public function __construct( ) {
	}

	/**
	 * Get group of current blog
	 * @return array|false
	 */
	public function get_current_group( ) {
		global $wpdb;
		return self::get_group_by_blog_id( $wpdb->blogid );
	}
	
	/**
	 * Get group for a blog id specified
	 * @param  integer $blog_id [description]
	 * @return array|false
	 */
	public static function get_group_by_blog_id( $blog_id = 0 ) {
		global $groupsites_factory;
		
		foreach( $groupsites_factory as $name => $blogs ) {
			foreach( $blogs as $id => $blog ) {
				if ( $blog_id == $id ) {
					return $groupsites_factory[$name];
				}
			}
		}
		
		return false;
	}
	
	/**
	 * [get_group description]
	 * @param  string $name [description]
	 * @return array|false
	 */
	public static function get_group( $name = '' ) {
		global $groupsites_factory;
		
		if ( empty( $name ) || !isset($groupsites_factory[$name]) ) {
			return false;
		}
		
		return $groupsites_factory[$name];
	}

	/**
	 * Get all group sites registered for this plugin
	 * @return array
	 */
	public static function get_all_groups() {
		global $groupsites_factory;
		return $groupsites_factory;
	}

	/**
	 * Register new group into factory
	 * @param  string $name  [description]
	 * @param  string $label [description]
	 * @param  array  $blogs [description]
	 * @return boolean
	 */
	public static function register( $name = '', $label = '', $blogs = array() ) {
		global $groupsites_factory;

		if ( empty( $name ) || empty( $label ) || empty( $blogs ) || !is_array( $blogs ) ) {
			return false;
		}

		// Add group into factory
		$groupsites_factory[$name] = array( );
		$groupsites_factory[$name]['label'] = $label;
		$groupsites_factory[$name]['blogs'] = array( );

		// Add blogs object
		foreach ( $blogs as $blog ) {
			self::append( $name, $blog );
		}
		
		return true;
	}
	
	/**
	 * Allow to add a blog object into factory
	 * @param  string $name [description]
	 * @param  array  $blog [description]
	 * @return boolean
	 */
	public static function append( $name = '', $blog = array() ) {
		global $groupsites_factory;
		
		if ( empty( $name ) || empty( $blogs ) || !is_array( $blogs ) || !isset($groupsites_factory[$name]) ) {
			return false;
		}
		
		// Sample: array( 'blog_id' => 3, 'language_code' => 'de_DE', 'public_label' => 'German',   'admin_label' => 'German' ),
		$site = new Bea_MM_GroupSites_Site( $blog['blog_id'], $blog['language_code'], $blog['public_label'], $blog['admin_label'] );
		if ( $site->exist( ) ) {
			$groupsites_factory[$name]['blogs'][$site->get_id()] = $site;
			return true;
		}
		
		return false;
	}

	/**
	 * Remove group site from factory
	 * @param  string $name [description]
	 * @return boolean
	 */
	public static function deregister( $name = '' ) {
		global $groupsites_factory;

		if ( isset( $groupsites_factory[$name] ) ) {
			unset( $groupsites_factory[$name] );
			return true;
		}

		return false;
	}

}
