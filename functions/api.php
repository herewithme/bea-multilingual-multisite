<?php

function bea_mm_register_translation_sets( $args ) {
	$defaults = array( 'name' => null, 'label' => null, 'sites' => array( array( 'blog_id' => 0, 'language_code' => 'fr_FR', 'language_label' => 'French' ) ) );
	$args = wp_parse_args( $args, $defaults );

}

function bea_mm_deregister_translation_sets( $name = '' ) {

}
