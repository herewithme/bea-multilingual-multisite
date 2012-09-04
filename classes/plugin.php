<?php
class Bea_MM_Plugin {
	/**
	 * Active plugin
	 * @return [type]
	 */
	public static function activate( ) {
		global $wpdb;

		if ( !function_exists( 'is_multisite' ) || !is_multisite( ) ) {
			deactivate_plugins( __FILE__ );
			wp_die( __( "This plugin needs the activation of the multisite-feature for working properly. Please read <a href='http://codex.wordpress.org/Create_A_Network'>this post</a> if you don't know the meaning.", 'bea-mm' ) );
		}

		if ( !empty( $wpdb->charset ) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( !empty( $wpdb->collate ) )
			$charset_collate .= " COLLATE $wpdb->collate";

		// Add one library admin function for next function
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

		maybe_create_table( $wpdb->bea_mm_connections, "CREATE TABLE $wpdb->bea_mm_connections (
			`id` bigint(10) NOT NULL AUTO_INCREMENT,
			`object_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			`blog_id` int(10) NOT NULL,
			`object_id` int(10) NOT NULL,
			`group_id` int(10) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `idx_unique` (`object_type`, `blog_id`, `object_id`, `group_id`)
			) $charset_collate;" );

		return true;
	}

	/**
	 * Deactive plugin
	 * @return [type]
	 */
	public static function deactivate( ) {
	}
	
	/**
	 * Deactive plugin
	 * @return [type]
	 */
	public static function get_connection_types() {
		return array( 'post_type', 'term_taxonomy' );
	}

	/**
	 * Retrieve or display list of objects as a dropdown (select list).
	 *
	 *
	 * @param array|string $args Optional. Override default arguments.
	 * @return string HTML content, if not displaying.
	 */
	public static function dropdown_post_type_objects( $query_args = '', $args = '' ) {
		// Query args
		$defaults_query = array( 'depth' => 0, 'child_of' => 0, 'post_type' => 'page', 'no_paging' => true );
		$query_args = wp_parse_args( $query_args, $defaults_query );
		
		// Args
		$defaults = array( 'depth' => 0, 'selected' => 0, 'echo' => 1, 'class' => '', 'name' => 'page_id', 'id' => '', 'show_option_none' => '', 'show_option_no_change' => '', 'option_none_value' => '', );
		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		$objects_query = new WP_Query( $query_args );
		$output = '';
		$name = esc_attr( $name );
		$class = esc_attr( $class );
		// Back-compat with old system where both id and name were based on $name argument
		if ( empty( $id ) )
			$id = $name;

		if ( $objects_query->have_posts( ) ) {
			$output = "<select name=\"$name\" id=\"$id\" class=\"$class\">\n";
			if ( $show_option_no_change )
				$output .= "\t<option value=\"-1\">$show_option_no_change</option>";
			if ( $show_option_none )
				$output .= "\t<option value=\"" . esc_attr( $option_none_value ) . "\">$show_option_none</option>\n";

			$output .= walk_page_dropdown_tree( $objects_query->posts, $depth, $r );
			$output .= "</select>\n";
		}

		if ( $echo )
			echo $output;

		return $output;
	}

}
