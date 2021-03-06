<?php
/**
 * Class for make groups for connections between blogs with custom wide table connections
 */
class Bea_MM_Connection_Factory {
	/**
	 * @var array Collection of Bea_MM_Connection_Object
	 */
	private $objects = array( );

	/**
	 * @var integer current group_id of factory
	 */
	private $group_id = 0;

	/**
	 * Constructor, do nothing
	 */
	public function __construct( ) {
	}

	/**
	 * Load objects from a group id
	 * @param  integer $group_id [description]
	 * @return void
	 */
	public function load_by_group_id( $group_id = 0 ) {
		global $wpdb;
		
		// Group ID egal 0 are not valid group !
		if( $group_id == 0 ) {
			return false;
		}
		
		$this->group_id = $group_id;
		$objects = $wpdb->get_results( $wpdb->prepare( "SELECT blog_id, object_id, object_type FROM {$wpdb->bea_mm_connections} WHERE group_id = %d", $group_id ), ARRAY_A );
		if ( $objects == false ) {
			return false;
		}

		foreach ( $objects as $object ) {
			$this->append( $object['object_type'], $object );
		}

		return true;
	}

	/**
	 * Load objects from a object
	 * @param  integer $group_id [description]
	 * @return void
	 */
	public function load_by_object( $object_type = '', $blog_id = 0, $object_id = 0 ) {
		$object = new Bea_MM_Connection_Object( $object_type, $blog_id, $object_id, false );
		if ( $object->exists( ) ) {
			return $this->load_by_group_id( $object->get_group_id( ) );
		}

		return false;
	}

	/**
	 * Load objects manually
	 * @param  string $object_type [description]
	 * @param  array  $objects     [description]
	 * @return [type]
	 */
	public function load( $object_type = '', $objects = array() ) {
		if ( empty( $objects ) || empty( $object_type ) || !is_array( $objects ) ) {
			return false;
		}

		foreach ( $objects as $object ) {
			$this->append( $object_type, $object );
		}

		return true;
	}

	/**
	 * Add elements into private var objects array
	 * @param  string $object_type [description]
	 * @param  array  $object      [description]
	 * @return [type]
	 */
	public function append( $object_type = '', $object = array() ) {
		if ( isset( $object['blog_id'] ) && isset( $object['object_id'] ) ) {
			$_object = new Bea_MM_Connection_Object( $object_type, $object['blog_id'], $object['object_id'], true );
			$this->objects[$_object->get_blog_id( )] = $_object;
		}
	}

	/**
	 * Get all connections
	 * @return array [description]
	 */
	function get_all( ) {
		return $this->objects;
	}

	/**
	 * Get the connection for the specified blog id
	 * @param  integer $blog_id [description]
	 * @return [type]           [description]
	 */
	function get_by_blog_id( $blog_id = 0 ) {
		if ( isset( $this->objects[$blog_id] ) ) {
			return $this->objects[$blog_id];
		}

		return false;
	}

	/**
	 * Group objects
	 * @param  integer $group_id [description]
	 * @return [type]
	 */
	public function group( $group_id = 0 ) {
		// First step, ungroup existing
		$this->ungroup( );

		// Get new group ID if nothing specified
		$group_id = (int)$group_id;
		if ( $group_id == 0 ) {
			$group_id = $this->get_new_group_id( );
		}

		// Set new group
		foreach ( $this -> objects as $object ) {
			$object->set_group_id( $group_id );
		}

		// Update factory obj
		$this->group_id = $group_id;
	}

	/**
	 * Ungroup all objects, set 0 as group id
	 * @return [type]
	 */
	public function ungroup( ) {
		foreach ( $this -> objects as $object ) {
			$object->set_group_id( 0 );
		}
	}

	/**
	 * Ungroup one blog object, set 0 as group id
	 * @param  integer $blog_id [description]
	 * @return [type]
	 */
	public function ungroup_blog( $blog_id = 0 ) {
		$blog_id = (int)$blog_id;
		if ( isset( $this->objects[$blog_id] ) ) {
			$this->objects[$blog_id]->set_group_id( 0 );
		}
	}

	/**
	 * With MySQL, we try to find the first available ID, and if the index is perfect, we increment the maximum with 1!
	 * @return (integer)
	 */
	public function get_new_group_id( ) {
		global $wpdb;

		// Try to find first available ID
		$group_id = $wpdb->get_var( "SELECT (a.group_id+ 1) AS group_id FROM {$wpdb->bea_mm_connections} AS a WHERE NOT EXISTS (SELECT 1 FROM {$wpdb->bea_mm_connections} AS b WHERE b.group_id = (a.group_id+1) ) AND a.group_id NOT IN (select max(c.group_id) FROM {$wpdb->bea_mm_connections} AS c)" );
		if ( $group_id !== NULL ) {
			return (int)$group_id;
		}

		$group_id = (int)$wpdb->get_var( "SELECT MAX(group_id) + 1 FROM {$wpdb->bea_mm_connections}" );
		if ( $group_id == 0 ) {// Failback if table is empty
			$group_id = 1;
		}

		return $group_id;
	}

	/**
	 * Static method for remove all connection for a blog
	 * @return integer number of rows deleted
	 */
	public static function remove_connections_by_blog( $blog_id = 0 ) {
		global $wpdb;
		return $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->bea_mm_connections} WHERE blog_id = %d", (int)$blog_id ) );
	}

}
