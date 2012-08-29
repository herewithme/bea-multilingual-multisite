<?php
function bea_mm_groupsites_register( $name = '', $label = '', $blogs = array() ) {
	return Bea_MM_GroupSites_Factory::register( $name, $label, $blogs );
}

function bea_mm_groupsites_deregister( $name = '' ) {
	return Bea_MM_GroupSites_Factory::deregister( $name );
}

function bea_mm_groupsites_get_group( $name = '' ) {
	return Bea_MM_GroupSites_Factory::get_group( $name );
}

function bea_mm_groupsites_get_all_groups( $name = '' ) {
	return Bea_MM_GroupSites_Factory::get_all_groups( $name );
}

function bea_mm_groupsites_get_current_group( $name = '' ) {
	return Bea_MM_GroupSites_Factory::get_current_group( $name );
}

function bea_mm_groupsites_get_group_by_blog_id( $name = '' ) {
	return Bea_MM_GroupSites_Factory::get_group_by_blog_id( $name );
}
