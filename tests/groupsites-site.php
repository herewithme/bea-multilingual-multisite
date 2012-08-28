<?php
require( dirname(__FILE__) . '/config.php' );

$instance = new Bea_MM_GroupSites_Site( 1, 'en_US', 'English 1', 'English 2' );

echo "\n\n\n\n Bea_MM_GroupSites_Site->exists \n";
$results = $instance->exists();
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Site->get_id \n";
$results = $instance->get_id();
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Site->get_language_code \n";
$results = $instance->get_language_code();
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Site->get_language_label FALSE \n";
$results = $instance->get_language_label();
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Site->get_language_label TRUE \n";
$results = $instance->get_language_label( true );
var_dump($results);


echo "\n\n\n\n Bea_MM_GroupSites_Site->get_permalink \n";
$results = $instance->get_permalink();
var_dump($results);

$instance = new Bea_MM_GroupSites_Site( 100, 'en_US', 'English 1', 'English 2' );

echo "\n\n\n\n Bea_MM_GroupSites_Site->exists \n";
$results = $instance->exists();
var_dump($results);


echo "\n\n\n\n Bea_MM_GroupSites_Site->get_id \n";
$results = $instance->get_id();
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Site->get_language_code \n";
$results = $instance->get_language_code();
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Site->get_language_label FALSE \n";
$results = $instance->get_language_label();
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Site->get_language_label TRUE \n";
$results = $instance->get_language_label( true );
var_dump($results);


echo "\n\n\n\n Bea_MM_GroupSites_Site->get_permalink \n";
$results = $instance->get_permalink();
var_dump($results);

die('Fine');
