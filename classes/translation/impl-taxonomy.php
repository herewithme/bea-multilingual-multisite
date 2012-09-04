<?php
class Bea_MM_Translation_View_Taxonomy implements Bea_MM_Translation_View {
	private $obj = null;
	private $connection = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0, 'source_blog_id' => 0, 'term_id' => 0, 'taxonomy' => 0, 'term_taxonomy_id' => 0 );
		$this->obj = (object) wp_parse_args( $args, $defaults );

		// Get all data need with term_id and taxonomy OR term_taxonomy_id only
		if ( (int)$this->obj->term_taxonomy_id > 0 && ((int)$this->obj->term_id == 0 || empty( $this->obj->taxonomy )) ) {
			$term = $this->_get_term_by_tt_id( (int)$this->obj->term_taxonomy_id );
			if ( $term != false ) {
				$this->obj->term_id = $term->term_id;
				$this->obj->taxonomy = $term->taxonomy;
			} else {
				$this->obj->term_taxonomy_id = 0;
			}
		} elseif ( (int)$this->obj->term_id > 0 && !empty( $this->obj->taxonomy ) ) {
			$term = get_term( $this->obj->term_id, $this->obj->taxonomy );
			if ( $term != false ) {
				$this->obj->term_taxonomy_id = $term->term_taxonomy_id;
			} else {
				$this->obj->term_taxonomy_id = 0;
			}
		}

		// Go out if no TT_ID valid
		if ( $this->obj->term_taxonomy_id == 0 )
			return false;

		// Get orginal connection for get group
		$connexion = new Bea_MM_Connection_Object( 'term_taxonomy', $this->obj->source_blog_id, $this->obj->term_taxonomy_id, false );
		if ( $connexion->exists( ) ) {
			// If group exist, load connections for this group
			$factory = new Bea_MM_Connection_Factory( );
			$factory->load_by_group_id( $connexion->get_group_id( ) );
			
			// Get translated connection for destination blog id
			$this->connection = $factory->get_by_blog_id( $this->obj->blog_id );
			if ( $this->connection == false ) {
				return false;
			}
			
			// Setup destination term for API function usage
			switch_to_blog( $this->obj->blog_id );
			$term = $this->_get_term_by_tt_id( $this->connection->get_object_id( ) );
			restore_current_blog( );

			if ( $term != false ) {
				$this->connection->term_id = $term->term_id;
				$this->connection->taxonomy = $term->taxonomy;
			}
		}

		return true;
	}

	public function get_blog_id( ) {
		return $this->obj->blog_id;
	}

	public function get_type( ) {
		return 'taxonomy';
	}

	public function get_id( ) {
		if ( $this->connection == NULL )
			return NULL;

		return $this->connection->get_object_id( );
	}

	public function get_permalink( ) {
		if ( $this->connection == NULL )
			return NULL;

		switch_to_blog( $this->obj->blog_id );
		$return_value = get_term_link( $this->connection->term_id, $this->connection->taxonomy );
		restore_current_blog( );

		return $return_value;
	}

	public function get_title( ) {
		if ( $this->connection == NULL )
			return NULL;

		switch_to_blog( $this->obj->blog_id );
		$term = get_term( $this->connection->term_id, $this->connection->taxonomy );
		if ( $term == false ) {
			$return_value = '';
		} else {
			$return_value = $term->name;
		}
		restore_current_blog( );

		return $return_value;
	}

	public function get_classes( ) {
		return 'taxonomy';
	}

	public function is_available( ) {
		if ( $this->connection == NULL )
			return false;

		return $this->connection->exists( );
	}

	/**
	 * Get term datas with only term taxonomy ID.
	 *
	 * @param (integer) $term_taxonomy_id
	 * @return false/object
	 */
	private function _get_term_by_tt_id( $term_taxonomy_id = 0 ) {
		global $wpdb;

		$term_taxonomy_id = (int)$term_taxonomy_id;
		if ( $term_taxonomy_id == 0 )
			return false;

		return $wpdb->get_row( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.term_taxonomy_id = %d LIMIT 1", $term_taxonomy_id ) );
	}

}
