<?php
class Bea_MM_Translation_View_Home implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0 );
		$this->obj = (object) wp_parse_args( $args, $defaults );
	}

	public function get_blog_id( ) {
		return $this->obj->blog_id;
	}

	public function get_type( ) {
		return 'home';
	}

	public function get_id( ) {
		return 0;
	}

	public function get_permalink( ) {
		return get_home_url( $this->obj->blog_id, '/' );
	}

	public function get_title( ) {
		return get_blog_option( $this->obj->blog_id, 'blogname' );
	}

	public function get_classes( ) {
		return 'home';
	}

	public function is_available( ) {
		return true;
	}
}
