<?php
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
		array( 'blog_id' => 5, 'language_code' => 'it_IT', 'public_label' => 'Italiano', 'admin_label' => 'Italian' )
	)
);
?>