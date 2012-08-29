<?php
require( dirname(__FILE__) . '/config.php' );

echo "\n\n\n\n new Bea_MM_Translation_Factory \n";
$translation_factory = new Bea_MM_Translation_Factory('post_type', array('post_id' => 1), 3);
var_dump($translation_factory);

if ($translation_factory -> have_translations()) {
	$i = 0;
	while ($translation_factory -> have_translations()) {
		$i++;
		
		$translation_factory -> the_translation();
		echo "\n\n\n\n Bea_MM_Translation_Factory::the_translation() \n";
		var_dump($translation_factory->translation);
		
		echo "\n\n\n\n Bea_MM_Translation_Factory::translation_exists() \n";
		var_dump($translation_factory->translation_exists());
		if ( !$translation_factory->translation_exists() ) {
			continue; // Or show home page link
		}
	
		// View
		echo "\n\n\n\n Bea_MM_Translation_Factory::get_translation_type() \n";
		$translation_factory -> get_translation_type();
		var_dump($translation_factory->get_translation_type());
		
		echo "\n\n\n\n Bea_MM_Translation_Factory::get_translation_id() \n";
		$translation_factory -> get_translation_id();
		var_dump($translation_factory->get_translation_id());
		
		echo "\n\n\n\n Bea_MM_Translation_Factory::get_translation_classes() \n";
		$translation_factory -> get_translation_classes();
		var_dump($translation_factory->get_translation_classes());
		
		echo "\n\n\n\n Bea_MM_Translation_Factory::get_translation_title() \n";
		$translation_factory -> get_translation_title();
		var_dump($translation_factory->get_translation_title());
		
		echo "\n\n\n\n Bea_MM_Translation_Factory::get_translation_permalink() \n";
		$translation_factory -> get_translation_permalink();
		var_dump($translation_factory->get_translation_permalink());
		
		// Translation/Site
		echo "\n\n\n\n Bea_MM_Translation_Factory::get_blog_id() \n";
		$translation_factory -> get_blog_id();
		var_dump($translation_factory->get_blog_id());
		
		echo "\n\n\n\n Bea_MM_Translation_Factory::get_language_label() \n";
		$translation_factory -> get_language_label();
		var_dump($translation_factory->get_language_label());
		
		echo "\n\n\n\n Bea_MM_Translation_Factory::get_language_code() \n";
		$translation_factory -> get_language_code();
		var_dump($translation_factory->get_language_code());
		
		echo "\n\n\n\n Bea_MM_Translation_Factory::get_home_permalink() \n";
		$translation_factory -> get_home_permalink();
		var_dump($translation_factory->get_home_permalink());
		
		echo $i;
	}
}

die('Fine');
