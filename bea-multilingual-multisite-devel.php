<?php
/*
 Plugin Name: BEA Multilingual Multisite Devel
 Version: 0.0.1
 Plugin URI: https://github.com/herewithme/bea-multilingual-multisite
 Description: Register GroupSites for devel
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Network: true
 Text Domain: bea-mm

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

add_action('init', 'init_bea_multilingual_multisite_devel');
function init_bea_multilingual_multisite_devel() {
	// Plugin usage or functions.php
	Bea_MM_GroupSites_Factory::register( 
		'site-canada',
		'Canada',
		array(
			array( 'blog_id' => 1, 'language_code' => 'en_US', 'public_label' => 'English',  'admin_label' => 'English' ),
			array( 'blog_id' => 2, 'language_code' => 'fr_CA', 'public_label' => 'Français', 'admin_label' => 'French' )
		)
	);
	
	bea_mm_groupsites_register(
		'site-switzerland',
		'Switzerland',
		array(
			array( 'blog_id' => 3, 'language_code' => 'de_DE', 'public_label' => 'German',   'admin_label' => 'German' ),
			array( 'blog_id' => 4, 'language_code' => 'fr_FR', 'public_label' => 'Français', 'admin_label' => 'French' ),
			array( 'blog_id' => 5, 'language_code' => 'it_IT', 'public_label' => 'Italiano', 'admin_label' => 'Italian' ),
			array( 'blog_id' => 6, 'language_code' => 'it_IT2', 'public_label' => 'Italiano 2', 'admin_label' => 'Italian 2' )
		)
	);
}