<?php
class Bea_MM_Plugin {
	public static function activate() {
		global $wpdb;

		if (!function_exists('is_multisite') || !is_multisite()) {
			deactivate_plugins(__FILE__);
			wp_die(__("This plugin needs the activation of the multisite-feature for working properly. Please read <a href='http://codex.wordpress.org/Create_A_Network'>this post</a> if you don't know the meaning.", 'bea-mm'));
		}

		if (!empty($wpdb -> charset))
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if (!empty($wpdb -> collate))
			$charset_collate .= " COLLATE $wpdb->collate";

		// Add one library admin function for next function
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

		maybe_create_table($wpdb -> bea_mm_translations, "CREATE TABLE $wpdb->bea_mm_translations (
			`id` bigint(10) NOT NULL AUTO_INCREMENT,
			`object_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			`blog_id` int(10) NOT NULL,
			`object_id` int(10) NOT NULL,
			`group_id` int(10) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `idx_unique` (`object_type`, `blog_id`, `object_id`, `group_id`)
			) $charset_collate;");

		return true;
	}

	public static function deactivate() {
	}

}
