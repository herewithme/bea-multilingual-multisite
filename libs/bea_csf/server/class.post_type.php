<?php
class BEA_CSF_Server_PostType {
	/**
	 * Constructor, register hooks
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public function __construct() {}
	
	public static function wp_insert_post( $post_ID, $post = null, $blog_id = 0 ) {
		global $wpdb;
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false ;
		}
		
		// Get post
		$object = get_post( $post_ID, ARRAY_A );
		if ( $object == false || is_wp_error($object) ) {
			return false;
		}
		
		// Get post metas
		$object['_thumbnail_id'] = (int) get_post_meta( $object['ID'], '_thumbnail_id', true );
		if ( $object['_thumbnail_id'] > 0 ) {
			$object['_thumbnail'] = BEA_CSF_Server_Attachment::get_attachment_data($object['_thumbnail_id']);
		} else {
			$object['_thumbnail'] = false;
		}
		
		// Init medias children
		$object['medias'] = array();
		
		// Get medias attachment
		$attachments = & get_children( array('post_parent' => $object['ID'], 'post_type' => 'attachment' ), ARRAY_A );
		foreach( $attachments as $attachment ) {
			$attachment['meta'] = get_post_custom($attachment['ID']);
			$attachment['attachment_url'] = get_permalink($attachment['ID']);
			$attachment['attachment_dir'] = get_attached_file($attachment['ID']);
			$object['medias'][] = $attachment;
		}
		
		// Add Server URL
		$object['server_url'] = home_url('/');
		$uploads = wp_upload_dir();
		$object['upload_url'] =  $uploads['baseurl'];
		
		// Change to draft
		$object['post_status'] = 'draft';
		
		return BEA_CSF_Server_Client::send_to_clients( 'BEA_CSF_Client_PostType', 'new_post', $object, $blog_id );
	}
}