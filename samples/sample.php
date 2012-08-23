<?php
global $translation;

$translation_query = new Bea_Translation_Collections();
if ($translation_query -> have_translations()) {
	while ($translation_query -> have_translations()) {
		$translation_query -> the_translation();
	
		// View
		$translation_query -> get_type();
		$translation_query -> get_id();
		$translation_query -> get_classes();
		$translation_query -> get_title();
		$translation_query -> get_permalink();
		
		// Translation/Site
		$translation_query -> get_site_id();
		$translation_query -> get_language_label();
		$translation_query -> get_language_code();
		$translation_query -> get_home_permalink();
	}
}
