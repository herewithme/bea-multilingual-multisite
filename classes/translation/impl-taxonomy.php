<?php
class Bea_MM_Translation_View_Taxonomy implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0 );
		$this->obj = (object) wp_parse_args( $args, $defaults );
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

		return $this->connection->get_id( );
	}

	public function get_permalink( ) {
		if ( $this->connection == NULL )
			return NULL;

		switch_to_blog( $this->obj->blog_id );
		$return_value = get_permalink( $this->connection->get_id( ) );
		restore_current_blog( );

		return $return_value;
	}

	public function get_title( ) {
		if ( $this->connection == NULL )
			return NULL;

		switch_to_blog( $this->obj->blog_id );
		$return_value = get_permalink( $this->connection->get_id( ) );
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
