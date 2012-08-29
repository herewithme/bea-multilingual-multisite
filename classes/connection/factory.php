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
	 * @return [type]
	 */
	public function load_by_group_id( $group_id = 0 ) {
		global $wpdb;

		$this->group_id = $group_id;
		$objects = $wpdb->get_results( $wpdb->prepare( "SELECT blog_id, object_id, object_type FROM {$wpdb->bea_mm_connections} WHERE group_id = %d", $group_id ), ARRAY_A );
		foreach ( $objects as $object ) {
			$this->append( $object['object_type'], $object );
		}
	}

	/**
	 * Load objects manually
	 * @param  string $object_type [description]
	 * @param  array  $objects     [description]
	 * @return [type]
	 */
	public function load( $object_type = '', $objects = array() ) {
		foreach ( $objects as $object ) {
			$this->append( $object_type, $object );
		}
	}

	/**
	 * Add elements into private var objects array
	 * @param  string $object_type [description]
	 * @param  array  $object      [description]
	 * @return [type]
	 */
	public function append( $object_type = '', $object = array() ) {
		if ( isset( $object['blog_id'] ) && isset( $object['object_id'] ) ) {
			$object = new Bea_MM_Connection_Object( $object_type, $object['blog_id'], $object['object_id'], true );
			$this->objects[$object->get_blog_id( )] = $object;
		}
	}
	
	function get_all() {
		return $this->objects;
	}
	
	function get_by_blog_id( $blog_id = 0 ) {
		if ( isset($this->objects[$blog_id]) ) {
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
		$group_id = (int)$group_id;
		if ( $group_id == 0 ) {
			$group_id = $this->get_new_group_id( );
		}
		
		foreach ( $this -> objects as $object ) {
			$object->set_group_id( $group_id );
		}

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
	 * Get with MySQL the max group id used, increment to one
	 * @return [type]
	 */
	public function get_new_group_id( ) {
		global $wpdb;

		$group_id = (int)$wpdb->get_var( "SELECT MAX(group_id) + 1 FROM {$wpdb->bea_mm_connections}" );
		if ( $group_id == 0 ) {// Failback if table is empty
			$group_id = 1;
		}

		return $group_id;
	}

}
