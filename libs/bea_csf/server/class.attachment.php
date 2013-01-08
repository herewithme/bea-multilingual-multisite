<?php
class BEA_CSF_Server_Attachment {
	/**
	 * Constructor
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public function __construct() {}
	
	public static function get_attachment_data( $media_id = 0 ) {
		$attachment 					= get_post( $media_id, ARRAY_A, 'display' );
		$attachment['meta'] 			= get_post_custom($media_id);
		$attachment['attachment_url'] 	= get_permalink($media_id);
		$attachment['attachment_dir'] 	= get_attached_file($media_id);
		
		return $attachment;
	}
}