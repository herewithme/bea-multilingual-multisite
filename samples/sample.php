<?php
// Automatic init view
$translation_factory = new Bea_MM_Translation_Factory();
if ($translation_factory -> have_translations()) {
	while ($translation_factory -> have_translations()) {
		$translation_factory -> the_translation();
	
		// View
		$translation_factory -> get_type();
		$translation_factory -> get_id();
		$translation_factory -> get_classes();
		$translation_factory -> get_title();
		$translation_factory -> get_permalink();
		
		// Translation/Site
		$translation_factory -> get_site_id();
		$translation_factory -> get_language_label();
		$translation_factory -> get_language_code();
		$translation_factory -> get_home_permalink();
	}
}

// Manual init view
$translation_factory = new Bea_MM_Translation_Factory('home', array('blog_id' => 3));
if ($translation_factory -> have_translations()) {
	while ($translation_factory -> have_translations()) {
		$translation_factory -> the_translation();
	
		// View
		$translation_factory -> get_type();
		$translation_factory -> get_id();
		$translation_factory -> get_classes();
		$translation_factory -> get_title();
		$translation_factory -> get_permalink();
		
		// Translation/Site
		$translation_factory -> get_blog_id();
		$translation_factory -> get_language_label();
		$translation_factory -> get_language_code();
		$translation_factory -> get_home_permalink();
	}
}