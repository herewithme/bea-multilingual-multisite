<?php
require( dirname(__FILE__) . '/config.php' );

$wpdb->query("TRUNCATE TABLE {$wpdb->bea_mm_connections}");

echo "\n\n\n\n new Bea_MM_Connection_Factory / exist \n";
$instance = new Bea_MM_Connection_Factory();

echo "\n\n\n\n Bea_MM_Connection_Factory->load / exist \n";
$instance->load( 'post_type', array(
	array('blog_id' => 3, 'object_id' => 1),
	array('blog_id' => 4, 'object_id' => 1),
) );
var_dump($instance);

echo "\n\n\n\n Bea_MM_Connection_Factory->append \n";
$instance->append( 'post_type', array('blog_id' => 5, 'object_id' => 1) );
var_dump($instance);

echo "\n\n\n\n Bea_MM_Connection_Factory->ungroup \n";
$instance->ungroup();

echo "\n\n\n\n Bea_MM_Connection_Factory->get_all \n";
var_dump($instance->get_all());

echo "\n\n\n\n Bea_MM_Connection_Factory->get_by_blog_id \n";
var_dump($instance->get_by_blog_id(3));

echo "\n\n\n\n Bea_MM_Connection_Factory->get_new_group_id \n";
$new_id = $instance->get_new_group_id();
var_dump($new_id);

echo "\n\n\n\n Bea_MM_Connection_Factory->group \n";
$instance->group($new_id);

echo "\n\n\n\n Bea_MM_Connection_Factory->get_by_blog_id \n";
var_dump($instance->get_by_blog_id(3));

echo "\n\n\n\n Bea_MM_Connection_Factory->ungroup \n";
$instance->ungroup();

echo "\n\n\n\n Bea_MM_Connection_Factory->get_by_blog_id \n";
var_dump($instance->get_by_blog_id(3));

echo "\n\n\n\n Bea_MM_Connection_Factory->ungroup_blog \n";
$instance->ungroup_blog(3);

echo "\n\n\n\n Bea_MM_Connection_Factory->get_by_blog_id \n";
var_dump($instance->get_by_blog_id(3));

$wpdb->query("TRUNCATE TABLE {$wpdb->bea_mm_connections}");

die('Fine');