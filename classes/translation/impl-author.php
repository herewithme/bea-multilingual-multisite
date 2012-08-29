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
	public function get_blog_id( ) {
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
		switch_to_blog( $this->obj->blog_id );
		$return_value = get_author_posts_url( $this->get_id( ) );
		restore_current_blog();
		
		return $return_value;
	}

	/**
	 *
	 * @return [type]      [description]
	 */
	public function get_title( ) {
		switch_to_blog( $this->obj->blog_id );
		$return_value = get_the_author_meta( 'display_name', $this->get_id( ) );
		restore_current_blog();
		
		return $return_value;
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
		
		switch_to_blog( $this->obj->blog_id );
		$return_value = $wpdb->get_var( $wpdb->prepare( "SELECT count(ID) FROM {$wpdb->posts} WHERE post_author = %d AND post_status = 'publish'", (int) $this->obj->author_id ) );
		restore_current_blog();
		
		return $return_value;
	}
}
