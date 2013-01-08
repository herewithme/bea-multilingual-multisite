<?php
class BEA_CSF_Client_PostType {
	/**
	 * Constructor
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public function __construct() {}
	
	/**
	 * Add post on DB
	 */
	public static function new_post( $datas ) {
		// Clean values
		if ( $datas == false || !is_array($datas) ) {
			return new WP_Error('invalid_datas', 'Error - Datas is invalid.' );
		}
		
		// Clone datas for post insertion
		$datas_for_post = $datas;
		unset($datas_for_post['medias'], $datas_for_post['terms'], $datas_for_post['tags_input'], $datas_for_post['post_category']);
		
		$datas_for_post['import_id'] = $datas_for_post['ID'];
		unset($datas_for_post['ID']);
		$new_post_id = wp_insert_post( $datas_for_post );
		
		// Post on DB ?
		if ( (int) $new_post_id === 0 ) {
			return new WP_Error('post_insertion', 'Error during the post insertion ' . $new_post_id->get_error_message() );
		}
		
		// Remove old thimb
		delete_post_meta( $new_post_id, '_thumbnail_id' );
		
		// Medias array
		$search_replace = array();
		
		// Medias exist ?
		if ( is_array($datas['medias']) && !empty($datas['medias']) ) {
			// Loop for medias
			foreach( $datas['medias'] as $media ) {
				// Insert with WP media public static function
				$new_media_id = BEA_CSF_Client_Attachment::media_sideload_image( $media['attachment_dir'], $new_post_id, null );
				if ( !is_wp_error($new_media_id) && $new_media_id > 0 ) {
					// Stock main fields from server
					$updated_datas = array();
					$updated_datas['ID'] = $new_media_id;
					$updated_datas['post_title'] = $media['post_title'];
					$updated_datas['post_content'] = $media['post_content'];
					$updated_datas['post_excerpt'] = $media['post_excerpt'];
					$current_media_id = wp_update_post($updated_datas);
				} else {
					continue;
				}
				
				// Get size array
				$thumbs = maybe_unserialize($media['meta']['_wp_attachment_metadata'][0]);
				$base_url = esc_url( trailingslashit($datas['upload_url']) . trailingslashit(dirname($media['meta']['_wp_attached_file'][0])) );
				
				// Try to replace old link by new (for thumbs)
				foreach ( $thumbs['sizes'] as $key => $size ) {
					$img = wp_get_attachment_image_src($current_media_id, $key);
					$search_replace[$base_url.$size['file']] = $img[0];
				}
				
				// Add url attachment link to replace
				$search_replace[$media['attachment_url']] = get_permalink($current_media_id);
			}
			
			// Update links on content
			if ( !empty($search_replace) ) {
				$post = get_post($new_post_id, ARRAY_A);
				$post['post_content'] = strtr( $post['post_content'], $search_replace );
				wp_update_post($post);
			}
		}
		
		// Restore post thumb
		if ( $datas['_thumbnail'] != false ) {
			$media_id = BEA_CSF_Client_Attachment::merge_attachment( $datas['_thumbnail'] );
			if ( $media_id > 0 ) {
				update_post_meta( $new_post_id, '_thumbnail_id', $media_id );
			}
		}
		return $new_post_id;
	}
}