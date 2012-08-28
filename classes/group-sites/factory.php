<?php
/**
 *  Class for make groups sites factory, allow to get 
 *  This class used a global for save group, as WP for CPT or Taxonomies
 */
class Bea_MM_GroupSites_Factory {
	/**
	 * Constructor, do nothing
	 */
	function __construct() {
		$defaults = array('name' => null, 'label' => null, 'sites' => array());
		$args = wp_parse_args($args, $defaults);
	}


	
	function get_current() {
		
	}
	
	function get_registered() {
		
	}
	
	function get() {
		
	}
	
	function register( $name = '', $label = '', $blogs = array() ) {
		
	}
	
	function deregister() {
		
	}
}
