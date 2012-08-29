<?php
class Bea_MM_Translation_View_PostTypeArchive implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0, 'post_type' => '' );
		$this->obj = (object) wp_parse_args( $args, $defaults );
	}

	public function get_blog_id( ) {
		return $this->obj->blog_id;
	}

	public function get_type( ) {
		return 'post_type_archive';
	}

	public function get_id( ) {
		return 0;
	}

	public function get_permalink( ) {
		if ( empty( $this->obj->post_type ) )
			return NULL;

		switch_to_blog( $this->obj->blog_id );
		$return_value = get_post_type_archive_link( $this->obj->post_type );
		restore_current_blog( );

		return $return_value;
	}

	public function get_title( ) {
		if ( empty( $this->obj->post_type ) )
			return NULL;

		switch_to_blog( $this->obj->blog_id );
		$cpt_obj = get_post_type_object( $this->obj->post_type );
		$return_value = $cpt_obj->labels->name;
		restore_current_blog( );

		return $return_value;
	}

	public function get_classes( ) {
		return 'post_type_archive';
	}

	public function is_available( ) {
	}

}
