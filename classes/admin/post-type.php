<?php
/**
 * This class to follow post type events (add/edit/delete) and populate relations table
 */
class Bea_MM_Admin_PostType {
	public function __construct( ) {
		// Metabox translations
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
		
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'add_ressources' ), 10 );
		
		add_action( 'wp_ajax_'.'bea_mm_search', array( __CLASS__, 'a_search' ), 10);
	}

	/**
	 * [add_meta_boxes description]
	 */
	public static function add_meta_boxes( ) {
		// This blog have group ?
		if ( Bea_MM_GroupSites_Factory::get_current_group( ) == false )
			return false;

		foreach ( get_post_types(array('show_ui' => true), 'names') as $cpt ) {
			add_meta_box( 'bea-mm', __( 'Translations', 'bea-mm' ), array( __CLASS__, 'metabox' ), $cpt, 'side', 'high', array('post_type' => $cpt) );
		}

		return true;
	}

	/**
	 * [metabox description]
	 * @param  [type] $object [description]
	 * @return [type]
	 */
	public static function metabox( $object, $metabox ) {
		// Always show this nonce field for detect metabox for save
		wp_nonce_field( 'form-bea-mm', 'bea_mm_noncename' );
		
		$output = '';
		
		// Loop on translation
		$translation_factory = new Bea_MM_Translation_Factory('post_type', array('post_id' => $object->ID),  get_current_blog_id());
		if ($translation_factory -> have_translations()) {
			while ($translation_factory -> have_translations()) {
				$translation_factory -> the_translation();
				
				// Skip current translation
				if ( $translation_factory->is_current_translation() ) {
					continue;
				}
				
				// Get translated ID
				if ( $translation_factory->translation_exists() ) {
					$current_id = $translation_factory -> get_translation_id();
				} else {
					$current_id = 0;
				}
				
				$output .= '<div>';
					$output .= '<label for="'.'translations-' . $translation_factory -> get_blog_id().'">'.$translation_factory -> get_language_label( true ).'</label>';
					switch_to_blog( $translation_factory -> get_blog_id() );
					$select = Bea_MM_Plugin::dropdown_post_type_objects( 
							array(
								'post_type' => $object->post_type, 
								'sort_column' => 'menu_order, post_title',
								'post__in' => array( $current_id )
							), 
							array( 
								'attrs' => array(
									'data-blog_id' => $translation_factory -> get_blog_id(),
									'data-post_type' => $object->post_type,
									'data-nonce' => wp_create_nonce( 
										'bea-mm-search-'.$object->post_type.'-'.$translation_factory -> get_blog_id(),
										'bea-mm-search-'.$object->post_type.'-'.$translation_factory -> get_blog_id() )
								),
								'class' => 'widefat bea_chosen_select',
								'echo' => 0, 
								'selected' => $current_id, 
								'name' => 'translations[' . $translation_factory -> get_blog_id() . ']', 
								'show_option_none' => ' ', 
								'option_none_value' => 0, 
								'id' => 'translations-' . $translation_factory -> get_blog_id() 
								)
							) ;
						$output .= empty( $select ) ? '<p>'.__( 'No more elements to associate with for this language', 'bea-mm' ).'</p>' : $select ;
					restore_current_blog();
				$output .= '</div>';
			}
		}
		
		echo $output;
	}

	private static function get_post_type_objects( $args = array() ) {
		return Bea_MM_Plugin::get_post_type_query( $args );
	}
	/**
	 * Ajax function for searching on a remote site
	 * 
	 * @param void
	 * @return void
	 * @author Nicolas Juen
	 * 
	 */
	public static function a_search() {
		
		$post_type = isset( $_POST['post_type'] ) && post_type_exists( $_POST['post_type'] ) ? $_POST['post_type'] : '' ;
		$blog_id = isset( $_POST['blog_id'] ) && (int)$_POST['blog_id'] > 0 ? (int)$_POST['blog_id'] : 0 ;
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '' ;
		$search = !isset( $_POST['s'] ) || empty( $_POST['s'] ) ? '' : $_POST['s'];
		
		if( !wp_verify_nonce( $nonce, 'bea-mm-search-'.$post_type.'-'.$blog_id ) || empty( $search ) ) {
			wp_send_json_error();
		}
		
		switch_to_blog( $blog_id );
			$query = Bea_MM_Plugin::get_post_type_query( 
				array(
					'post_type' => $post_type, 
					'sort_column' => 'menu_order, post_title',
					's' => $search,
				)
			);
		restore_current_blog();
		
		if( !$query->have_posts() ) {
			wp_send_json_error();
		}
		
		$out = array();
		while( $query->have_posts() ) {
			$query->the_post();
			$out[] = array(
				'value' => get_the_ID(),
				'text' => get_the_title()
			);
		}
		
		wp_send_json_success( $out );
	}

	/**
	 * [save_post description]
	 * @param  integer $object_id [description]
	 * @param  [type]  $object    [description]
	 * @return [type]
	 */
	public static function save_post( $object_id = 0, $object = null ) {
		if ( (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) || wp_is_post_revision( $object_id ) || !isset( $_POST['bea_mm_noncename'] ) || !wp_verify_nonce( $_POST['bea_mm_noncename'], 'form-bea-mm' ) ||  !isset($_POST['translations'] ) )
			return false;
		
		// Init current factory and remove group
		$connection_factory = new Bea_MM_Connection_Factory();
		$connection_factory->load_by_object( 'post_type', get_current_blog_id(), $object_id);
		$connection_factory->ungroup();
		
		// Cast values, expected integer
		$_POST['translations'] = array_map('intval', $_POST['translations']);
		
		// Convert array format for class usage
		$translations_to_load = array();
		foreach( $_POST['translations'] as $translation_blog_id => $translation_obj_id ) {
			if ( $translation_obj_id == 0 ) {
				continue;
			}
			
			$translations_to_load[] = array( 'blog_id' => $translation_blog_id, 'object_id' => $translation_obj_id );
		}
		
		// Add current object/blog
		$translations_to_load[] = array( 'blog_id' => get_current_blog_id(), 'object_id' => $object_id );
		
		// Init new factory and set group !
		$connection_factory = new Bea_MM_Connection_Factory();
		$connection_factory->load( 'post_type', $translations_to_load );
		
		// Group if more than one translations !
		if ( count($translations_to_load) > 1 ) {
			$connection_factory->group();
		}
	}

	public static function add_ressources() {
		wp_enqueue_script( 'bea-mm-admin-scripts' );
	}
}
