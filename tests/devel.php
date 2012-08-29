<?php
require( dirname(__FILE__) . '/config.php' );

echo "\n\n\n\n new Bea_MM_Translation_Factory \n";
$translation_factory = new Bea_MM_Translation_Factory('post_type', array('post_id' => 1), 3);
if ($translation_factory -> have_translations()) {
	while ($translation_factory -> have_translations()) {
		$translation_factory -> the_translation();
		
		echo "\n\n\n\n Bea_MM_Translation_Factory::translation_exists() \n";
		var_dump($translation_factory->translation_exists());
		if ( !$translation_factory->translation_exists() ) {
			continue; // Or show home page link
		}
	}
}

die('Fine');
