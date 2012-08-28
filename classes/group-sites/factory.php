<?php
/**
 *  Class for make groups sites factory, allow to get 
 */
class Bea_MM_GroupSites_Factory {
	/**
	 * @var array Collection of Bea_MM_GroupSites_Site
	 */
	private $objects = array();

	function __construct() {
		$defaults = array('name' => null, 'label' => null, 'sites' => array( array('blog_id' => 0, 'language_code' => '', 'public_label' => '', 'admin_label' => '')));
		$args = wp_parse_args($args, $defaults);
	}
	
	function get_current() {
		
	}
	
	function get_registered() {
		
	}
	
	function get() {
		
	}
	
	function register() {
		
	}
	
	function deregister() {
		
	}
}
