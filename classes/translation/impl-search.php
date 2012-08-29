<?php
class Bea_MM_Translation_View_Search implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0, 's' => '' );
		$this->obj = (object) wp_parse_args( $args, $defaults );
	}

	public function get_blog_id( ) {
		return $this->obj->blog_id;
	}

	public function get_type( ) {
		return 'search';
	}

	public function get_id( ) {
		return 0;
	}

	public function get_permalink( ) {
		return get_home_url( $this->obj->blog_id, '/?s=' . $this->obj->s );
	}

	public function get_title( ) {
		return sprintf( __( 'Search Results %1$s' ), strip_tags( $this->obj->s ) );
	}

	public function get_classes( ) {
		return 'search';
	}

	public function is_available( ) {
		return true;
	}

}
