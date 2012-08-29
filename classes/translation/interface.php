<?php
Interface Bea_MM_Translation_View {
	public function __construct($args);
	public function get_blog_id();
	public function get_type();
	public function get_id();
	public function get_permalink();
	public function get_title();
	public function get_classes();
	public function is_available();
}
