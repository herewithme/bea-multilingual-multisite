<?php
/**
 * Class for create/get/delete connection between blogs with custom wide table connections
 */
class Bea_MM_Connection_Object {
	/**
	 * Current object
	 */
	private $obj = null;

	/**
	 * Constructor with conditionnal shortcuts for init method
	 * @param string  $object_type [description]
	 * @param integer $blog_id     [description]
	 * @param integer $object_id   [description]
	 * @param boolean $force_add   [description]
	 */
	public function __construct( $object_type = '', $blog_id = 0, $object_id = 0, $force_add = false ) {
		if ( !empty( $object_type ) && (int)$blog_id > 0 && (int)$object_id > 0 ) {
			$this->init( $object_type, $blog_id, $object_id, $force_add );
		}
	}

	/**
	 * Init object, try to get data from DB, optionnaly force insertion on DB
	 * @param  string  $object_type [description]
	 * @param  integer $blog_id     [description]
	 * @param  integer $object_id   [description]
	 * @param  boolean $force_add   [description]
	 * @return [type]
	 */
	public function init( $object_type = '', $blog_id = 0, $object_id = 0, $force_add = false ) {
		global $wpdb;

		// Security cast value, check object type
		$object_type = (!in_array( $object_type, Bea_MM_Plugin::getConnectionTypes( ) )) ? 'post_type' : $object_type;
		$blog_id = (int)$blog_id;
		$object_id = (int)$object_id;

		// Setup object from DB
		$this->obj = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->bea_mm_connections} WHERE object_type = %s AND blog_id = %d AND object_id = %d", $object_type, $blog_id, $object_id ) );
		if ( $this->obj == false ) {
			// Make incomplete object for next usage when not exist on DB
			$this->obj = new stdClass;
			$this->obj->object_type = $object_type;
			$this->obj->blog_id = $blog_id;
			$this->obj->object_id = $object_id;

			// Insert line into table
			if ( $force_add == true ) {
				$this->add( );
			}
		}
	}

	/**
	 * Test if connection exist on table or not
	 * @return boolean
	 */
	public function exists( ) {
		return isset( $this->obj->id );
	}

	/**
	 * Get connection id
	 * @return [type]
	 */
	public function get_id( ) {
		return (int)$this->obj->id;
	}

	/**
	 * Add connection on DB
	 * @return [type]
	 */
	public function add( ) {
		global $wpdb;

		if ( $this->obj !== null && !$this->exists( ) ) {
			$result = $wpdb->insert( $wpdb->bea_mm_connections, array( 'object_type' => $this->obj->object_type, 'blog_id' => $this->obj->blog_id, 'object_id' => $this->obj->object_id, 'group_id' => 0 ), array( '%s', '%d', '%d' ) );
			if ( $result != false ) {
				$this->obj->id = (int)$wpdb->insert_id;
				$this->obj->group_id = 0;
				return true;
			}
		}

		return false;
	}

	/**
	 * Delete connection from DB
	 * @return [type]
	 */
	public function delete( ) {
		global $wpdb;

		if ( $this->exists( ) ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->bea_mm_connections} WHERE id = %d", $this->obj->id ) );
			unset( $this->obj->id, $this->obj->group_id );
			return true;
		}

		return false;
	}

	/**
	 * Set group id field for row
	 * @param integer $group_id [description]
	 */
	public function set_group_id( $group_id = 0 ) {
		global $wpdb;

		$group_id = (int)$group_id;
		// Add line before, set group
		if ( !$this->exists( ) ) {
			$this->add( );
		}

		// Set group if line exist
		if ( $this->exists( ) ) {
			$wpdb->update( $wpdb->bea_mm_connections, array( 'group_id' => $group_id ), array( 'id' => $this->obj->id ), array( '%d' ), array( '%d' ) );
			$this->obj->group_id = $group_id;
			return true;
		}

		return false;
	}

	/**
	 * Return connection object id
	 * @return [type]
	 */
	public function get_object_id( ) {
		return (int)$this->obj->object_id;
	}

	/**
	 * Return connection group id
	 * @return [type]
	 */
	public function get_group_id( ) {
		return (int)$this->obj->group_id;
	}

	/**
	 * Return connection blog id
	 * @return [type]
	 */
	public function get_blog_id( ) {
		return (int)$this->obj->blog_id;
	}

}
