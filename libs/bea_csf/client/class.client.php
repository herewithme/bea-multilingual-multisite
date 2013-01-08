<?php
class BEA_CSF_Client_Base {
	/**
	 * Constructor
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public function __construct( ) {
	}

	/**
	 * Get post ID from post meta with meta_key and meta_value
	 */
	public static function get_post_id_from_meta( $key, $value ) {
		global $wpdb;
		return (int)$wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s", $key, $value ) );
	}

}
