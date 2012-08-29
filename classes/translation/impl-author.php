<?php
class Bea_MM_Translation_View_Author implements Bea_MM_Translation_View {
	private $obj = null;

	/**
	 * Constructor
	 * @param array $args [description]
	 */
	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0, 'author_id' => 0 );
		$this->obj = (object) wp_parse_args( $args, $defaults );
	}

	/**
	 *
	 * @return [type]      [description]
	 */
	public function get_site_id( ) {
		return $this->obj->blog_id;
	}

	/**
	 *
	 * @return [type]      [description]
	 */
	public function get_type( ) {
		return 'author';
	}

	/**
	 *
	 * @return [type]      [description]
	 */
	public function get_id( ) {
		return $this->obj->author_id;
	}

	/**
	 *
	 * @return [type]      [description]
	 */
	public function get_permalink( ) {
		return get_author_posts_url( $this->get_id( ) );
	}

	/**
	 *
	 * @return [type]      [description]
	 */
	public function get_title( ) {
		return get_the_author_meta( 'display_name', $this->get_id( ) );
	}

	/**
	 *
	 * @return [type]      [description]
	 */
	public function get_classes( ) {
		return 'author';
	}

	/**
	 * Test authors have publish content
	 * @return [type]      [description]
	 */
	public function is_available( ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT count(ID) FROM {$wpdb->posts} WHERE post_author = %d AND post_status = 'publish'", (int)$this->obj->author_id ) );
	}

	/**
	 * Key or null
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function __get( $key = '' ) {
		return (isset( $this->obj->$key ) ? $this->obj->$key : null);
	}

}
