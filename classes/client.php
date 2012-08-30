<?php
class Bea_MM_Client {
	/**
	 * Register hooks
	 */
	public function __construct( ) {
		add_action( 'init', array( __CLASS__, 'init' ) );
		
		add_action( 'deleted_post', array( __CLASS__, 'deleted_post' ) );
		add_action( 'delete_blog', array( __CLASS__, 'delete_blog' ) );
	}

	/**
	 * Register translation groups from DB settings
	 */
	public static function init( ) {
		$db_settings = get_site_option( BEA_MM_OPTION );
		if ( $db_settings != false && is_array( $db_settings ) ) {
			foreach ( $db_settings as $group ) {
				Bea_MM_GroupSites_Factory::register( $group['name'], $group['label'], $group['blogs'] );
			}
		}
	}

	/**
	 * Delete relation row when post is deleted
	 * @param  integer $object_id [description]
	 * @return [type]
	 */
	public static function deleted_post( $object_id = 0 ) {
		global $wpdb;

		$relation = new Bea_MM_Connection_Object( 'post_type', $wpdb->blogid, $object_id );
		return $relation->delete( );
	}

	/**
	 * Delete relations row when blog is deleted
	 * @param  integer $blog_id [description]
	 * @return [type]
	 */
	public static function delete_blog( $blog_id = 0 ) {
		return Bea_MM_Connection_Factory::remove_connections_by_blog( $blog_id );
	}

}
