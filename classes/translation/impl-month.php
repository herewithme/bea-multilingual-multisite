<?php
class Bea_MM_Translation_View_Month implements Bea_MM_Translation_View {
	private $obj = null;

	function __construct( $args = array() ) {
		$defaults = array( 'blog_id' => 0, 'year' => 0, 'monthnum' => 0, );
		$this->obj = (object) wp_parse_args( $args, $defaults );
	}

	public function get_site_id( ) {
		return $this->obj->blog_id;
	}

	public function get_type( ) {
		return 'month';
	}

	public function get_id( ) {
		return 0;
	}

	public function get_permalink( ) {
		return get_month_link( $this->obj->year, $this->obj->monthnum );
	}

	public function get_title( ) {
		global $wp_locale;
		return sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $this->obj->monthnum ), $this->obj->year );
	}

	public function get_classes( ) {
		return 'month';
	}

	public function is_available( ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT count(ID) FROM {$wpdb->posts} WHERE YEAR(post_date) = %d AND MONTH(post_date) = %d AND post_status = 'publish'", $this->obj->year, $this->obj->monthnum ) );
	}

	public function __get( $key = '' ) {
		return (isset( $this->obj->$key ) ? $this->obj->$key : null);
	}

}
