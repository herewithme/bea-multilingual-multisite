<?php
require( dirname(__FILE__) . '/config.php' );

$wpdb->query("TRUNCATE TABLE {$wpdb->bea_mm_connections}");

$instance = new Bea_MM_Connection_Object( 'post_invalid', 1, 1, false );

echo "\n\n\n\n Bea_MM_Connection_Object->exists / exist \n";
$results = $instance->exists();
var_dump($results);


$instance = new Bea_MM_Connection_Object( 'post_type', 1, 2, false );

echo "\n\n\n\n Bea_MM_Connection_Object->exists / constructor 1 2 false \n";
$results = $instance->exists();
var_dump($results);

$instance = new Bea_MM_Connection_Object( 'post_type', 1, 2, true );

echo "\n\n\n\n Bea_MM_Connection_Object->exists / constructor 1 2 true \n";
$results = $instance->exists();
var_dump($results);

$instance = new Bea_MM_Connection_Object( 'post_type', 1, 2, false );

echo "\n\n\n\n Bea_MM_Connection_Object->exists / constructor 1 2 false \n";
$results = $instance->exists();
var_dump($results);

echo "\n\n\n\n Bea_MM_Connection_Object->get_id / constructor 1 2 false \n";
$results = $instance->get_id();
var_dump($results);

echo "\n\n\n\n Bea_MM_Connection_Object->get_group_id / constructor 1 2 false \n";
$results = $instance->get_group_id();
var_dump($results);

echo "\n\n\n\n Bea_MM_Connection_Object->set_group_id 2 / constructor 1 2 false \n";
$results = $instance->set_group_id(2);
var_dump($results);

echo "\n\n\n\n Bea_MM_Connection_Object->get_group_id / constructor 1 2 false \n";
$results = $instance->get_group_id();
var_dump($results);

echo "\n\n\n\n Bea_MM_Connection_Object->set_group_id 0 / constructor 1 2 false \n";
$results = $instance->set_group_id(0);
var_dump($results);

echo "\n\n\n\n Bea_MM_Connection_Object->get_group_id / constructor 1 2 false \n";
$results = $instance->get_group_id();
var_dump($results);

echo "\n\n\n\n Bea_MM_Connection_Object->delete / constructor 1 2 false \n";
$results = $instance->delete();
var_dump($results);

echo "\n\n\n\n Bea_MM_Connection_Object->exists / constructor 1 2 false \n";
$results = $instance->exists();
var_dump($results);

$wpdb->query("TRUNCATE TABLE {$wpdb->bea_mm_connections}");

die('Fine');