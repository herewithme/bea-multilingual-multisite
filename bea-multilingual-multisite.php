<?php
/*
Plugin Name: BEA Multilingual Multisite
Version: 0.0.1
Plugin URI: https://github.com/herewithme/bea-multilingual-multisite
Description: A simple but powerful plugin that will help you to manage the relations of posts, pages, custom post types, categories, tags and custom taxonomies in your multilingual multisite-installation.
Author: Amaury Balmer
Author URI: http://www.beapi.fr

----

Copyright 2012 Amaury Balmer (amaury@beapi.fr)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Plugin constants
define ( 'BEA_MM_VERSION', '0.0.1' );
define ( 'BEA_MM_OPTION',  'bea-multilingual-multisite' );
define ( 'BEA_MM_FOLDER',  'bea-multilingual-multisite' );
define ( 'BEA_MM_URL', plugins_url('', __FILE__) );
define ( 'BEA_MM_DIR', dirname(__FILE__) );

// Function for easy load files
function _bea_mm_load_files( $dir, $files ) {
	foreach ( $files as $file )
		require_once $dir.$file.".php";
}

// Plugin functions
_bea_mm_load_files(  BEA_MM_DIR . '/functions/', array('api', 'theme') );

// Plugin client classes
_bea_mm_load_files(  BEA_MM_DIR . '/classes/', array('base', 'client', 'widget') );

// Plugin client interface/class/implementations
_bea_mm_load_files(  BEA_MM_DIR . '/classes/translation/', array('interface', 'collections', 'site') );
_bea_mm_load_files(  BEA_MM_DIR . '/classes/translation/impl-', array('author', 'day', 'home', 'month', 'post_type_archive', 'post_type', 'search', 'taxonomy', 'year') );

// Plugin admin classes
if ( is_admin() ) {
	_bea_mm_load_files(  BEA_MM_DIR . '/classes/admin/', array('main', 'network', 'post-type', 'taxonomy') );
}

// Plugin activate/desactive hooks
register_activation_hook  ( __FILE__, array('Bea_Multilingual_Multisite_Base', 'activate') );
register_deactivation_hook( __FILE__, array('Bea_Multilingual_Multisite_Base', 'deactivate') );

add_action( 'plugins_loaded', 'init_bea_multilingual_multisite' );
function init_bea_multilingual_multisite() {
	global $bea_multilingual_multisite;
	
	// Load translations
	load_plugin_textdomain ( 'bea-mm', false, basename(rtrim(BEA_MM_DIR, '/')) . '/languages' );
	
	// Client
	$bea_multilingual_multisite['client-base']  = new Bea_Multilingual_Multisite_Client();
	
	// Admin
	if ( is_admin() ) {
		// Class admin
		$bea_multilingual_multisite['admin-base'] 		 = new Bea_Multilingual_Multisite_Admin();
		$bea_multilingual_multisite['admin-network']     = new Bea_Multilingual_Multisite_Admin_Network();

		$bea_multilingual_multisite['admin-post-type']   = new Bea_Multilingual_Multisite_Admin_PostType();
		$bea_multilingual_multisite['admin-taxonomy']    = new Bea_Multilingual_Multisite_Admin_Taxonomy();
	}
	
	// Widget
	add_action( 'widgets_init', create_function('', 'return register_widget("Bea_Multilingual_Multisite_Widget");') );
}
?>