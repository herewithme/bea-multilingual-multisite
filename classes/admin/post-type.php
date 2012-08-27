<?php
/**
 * This class to follow post type events (add/edit/delete) and populate relations table
 */
class Bea_MM_Admin_PostType {
	public function __construct() {
		// Metabox translations
		add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
		add_action('save_post', array(__CLASS__, 'save_post'), 10, 2);
	}
	
	public static function add_meta_boxes() {
		foreach (get_post_types(array('show_ui' => true), 'names') as $cpt) {
			add_meta_box('bea-mm', __('Translations', 'bea-mm'), array(__CLASS__, 'metabox'), $cpt, 'side', 'high');
		}
	}
	
	public static function metabox( $object ) {
		// Always show this nonce field for detect metabox for save
		wp_nonce_field('form-bea-mm', 'bea_mm_noncename');
		
		
		$output = wp_dropdown_pages(array('post_type' => $object->post_type, 'selected' => $mydata -> $language, 'name' => 'translations[' . $language . ']', 'show_option_none' => ' ', 'sort_column' => 'menu_order, post_title' ));
	}
	
	public static function save_post($object_id = 0, $object = null) {
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($object_id) || !isset($_POST['bea_mm_noncename']) || !wp_verify_nonce($_POST['bea_mm_noncename'], 'form-bea-mm'))
			return false;
		
		
	}
}
