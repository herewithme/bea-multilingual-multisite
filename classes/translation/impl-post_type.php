<?php
class Bea_MM_Translation_View_PostType implements Bea_MM_Translation_View {
	private $obj = null;
	private $connection = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0, 'source_blog_id' => 0, 'post_id' => 0 );
		
		$this->obj = (object) wp_parse_args( $args, $defaults );
		
		// Post ID exist ?
		if ( $this->obj->post_id == 0 ) {
			return false;
		}
		
		// Get orginal connection for get group
		$connexion = new Bea_MM_Connection_Object( 'post_type', $this->obj->source_blog_id, $this->obj->post_id, false );
		if ( $connexion->exists( ) ) {
			// If group exist, load connections for this group
			$factory = new Bea_MM_Connection_Factory( );
			$factory->load_by_group_id( $connexion->get_group_id( ) );
			
			// Get translated connection for destination blog id
			$this->connection = $factory->get_by_blog_id( $this->obj->blog_id );
		}
		
		return true;
	}

	public function get_blog_id( ) {
		return $this->obj->blog_id;
	}

	public function get_type( ) {
		return 'post_type';
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
		$return_value = get_permalink( $this->get_id( ) );
		restore_current_blog( );

		return $return_value;
	}

	public function get_title( ) {
		if ( $this->connection == NULL )
			return NULL;

		switch_to_blog( $this->obj->blog_id );
		$return_value = get_permalink( $this->get_id( ) );
		restore_current_blog( );

		return $return_value;
	}

	public function get_classes( ) {
		return 'post_type';
	}

	public function is_available( ) {
		if ( $this->connection == NULL )
			return false;

		return $this->connection->exists( );
	}

}
