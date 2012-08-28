<?php
require( dirname(__FILE__) . '/config.php' );

echo "\n\n\n\n Bea_MM_GroupSites_Factory::get_all_groups \n";
$results = Bea_MM_GroupSites_Factory::get_all_groups();
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Factory::get_current_group \n";
$results = Bea_MM_GroupSites_Factory::get_current_group();
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Factory::get_group(site-switzerland) \n";
$results = Bea_MM_GroupSites_Factory::get_group('site-switzerland');
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Factory::get_group(site-switzerland2) \n";
$results = Bea_MM_GroupSites_Factory::get_group('site-switzerland2');
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Factory::get_group_by_blog_id 5  \n";
$results = Bea_MM_GroupSites_Factory::get_group_by_blog_id(5);
var_dump($results);

echo "\n\n\n\n Bea_MM_GroupSites_Factory::get_group_by_blog_id 6 \n";
$results = Bea_MM_GroupSites_Factory::get_group_by_blog_id(6);
var_dump($results);

die('Fine');
