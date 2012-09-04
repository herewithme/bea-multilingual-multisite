<?php
/**
 * This class to follow term taxonomy events (add/edit/delete) and populate relations table
 */
class Bea_MM_Admin_Taxonomy {
	public function __construct( ) {
		foreach( get_taxonomies() as $taxonomy ) {
			add_action( "{$taxonomy}_edit_form_fields", array( __CLASS__, 'form_edit' ), 10, 2 );
			add_action( "{$taxonomy}_add_form_fields", array( __CLASS__, 'form_add' ), 10, 1 );
		}
		
		add_action( "edited_term",  array( __CLASS__, 'save' ), 10, 3 );
		add_action( "created_term", array( __CLASS__, 'save' ), 10, 3 );
	}
	
	public static function form_add( $taxonomy = '' ) {
		self::form_edit( null, $taxonomy );
	}

	public static function form_edit( $term = null, $taxonomy = '' ) {
		// Check term values
		if ( $term == null ) {
			$term = new stdClass;
			$term->term_taxonomy_id = 0;
			$term->taxonomy = $taxonomy;
			$term->term_id = 0;
		}
		// Always show this nonce field for detect fields for save
		wp_nonce_field( 'form-bea-mm', 'bea_mm_noncename' );
		
		$output = '';
		
		// Loop on translation
		$translation_factory = new Bea_MM_Translation_Factory('term_taxonomy', array('term_taxonomy_id' => $term->term_taxonomy_id),  get_current_blog_id());
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
				
				$output .= '<tr class="form-field">';
					$output .= '<th scope="row" valign="top"><label for="'.'translations-' . $translation_factory -> get_blog_id().'">'.$translation_factory -> get_language_label( true ).'</label></th>';
				
					switch_to_blog( $translation_factory -> get_blog_id() );
						$output .= '<td>'.wp_dropdown_categories( array('taxonomy' => $term->taxonomy, 'selected' => $current_id, 'show_option_none' => false, 'show_option_all' => ' ', 'echo' => 0, 'class' => 'widefat', 'name' => 'translations[' . $translation_factory -> get_blog_id() . ']', 'id' => 'translations-' . $translation_factory -> get_blog_id() ) ).'</td>';
					restore_current_blog();
				$output .= '</tr>';
			}
		}
		echo $output;
	}

	public static function save( $term_id = 0, $tt_id = 0, $taxonomy = '' ) {
		if ( !isset( $_POST['bea_mm_noncename'] ) || !wp_verify_nonce( $_POST['bea_mm_noncename'], 'form-bea-mm' ) || !isset($_POST['translations'] ) )
			return false;
		
		// Init current factory and remove group
		$connection_factory = new Bea_MM_Connection_Factory();
		$connection_factory->load_by_object( 'term_taxonomy', get_current_blog_id(), $tt_id);
		$connection_factory->ungroup();
		
		// Cast values, expected integer
		$_POST['translations'] = array_map('intval', $_POST['translations']);
		
		// Convert array format for class usage
		$translations_to_load = array();
		foreach( $_POST['translations'] as $translation_blog_id => $translation_obj_id ) {
			if ( $translation_obj_id == 0 ) {
				continue;
			}
			
			$translations_to_load[] = array( 'blog_id' => $translation_blog_id, 'object_id' => $tt_id );
		}
		
		// Add current object/blog
		$translations_to_load[] = array( 'blog_id' => get_current_blog_id(), 'object_id' => $tt_id );
		
		// Init new factory and set group !
		$connection_factory = new Bea_MM_Connection_Factory();
		$connection_factory->load( 'term_taxonomy', $translations_to_load );
		
		// Group if more than one translations !
		if ( count($translations_to_load) > 1 ) {
			$connection_factory->group();
		}
	}

}
