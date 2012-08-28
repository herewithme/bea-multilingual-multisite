<?php
class Bea_MM_Client {
	/**
	 * Register hooks
	 */
	public function __construct() {
		add_action('deleted_post', array(__CLASS__, 'deleted_post'));
	}
	
	/**
	 * Delete relation row when post is delete
	 * @param  integer $object_id [description]
	 * @return [type]
	 */
	public static function deleted_post( $object_id = 0 ) {
		global $wpdb;
		
		$relation = new Bea_MM_Connection_Object( 'post_type', $wpdb->blogid, $object_id );
		return $relation->delete();
	}
}
