<?php
$old_domain = 'sandbox.beapi.fr';
$old_path = '/opensource-devel/';

// Fake WordPress, build server array
$_SERVER = array(
	'HTTP_HOST'      => $old_domain,
	'SERVER_NAME'    => $old_domain,
	'REQUEST_URI'    => $old_path,
	'REQUEST_METHOD' => 'GET',
	'SCRIPT_NAME' 	 => basename(__FILE__),
	'SCRIPT_FILENAME' 	 => basename(__FILE__),
	'PHP_SELF' 		 => $old_path.basename(__FILE__)
);
	
require( dirname(__FILE__) . '/../../../../wp-load.php');

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