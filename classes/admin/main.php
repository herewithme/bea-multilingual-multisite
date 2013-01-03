<?php
/**
 * This class to TODO? on each site administration
 */
class Bea_MM_Admin {
	const admin_slug = 'bea-translation';

	/**
	 * Register hooks
	 */
	public function __construct( ) {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 9 );
	}

	/**
	 * Call functions for check POST data form
	 */
	public static function admin_init( ) {
		if ( !is_super_admin( ) ) {
			return false;
		}

		self::check_save_settings( );
		return true;
	}

	/**
	 * Add menu on network admin
	 */
	public static function admin_menu( ) {
		if ( !is_super_admin( ) ) {
			return false;
		}

		add_options_page( __( 'Translations', 'bea-mm' ), __( 'Translations', 'bea-mm' ), 'manage_options', self::admin_slug, array( __CLASS__, 'page' ) );
		return true;
	}

	/**
	 * Build HTML of plugin page
	 */
	public static function page( ) {
		// Current settings
		$db_settings = get_site_option( BEA_MM_OPTION );
		if ( $db_settings == false ) {
			$db_settings = array( );
		}

		// Get group sites factory
		$groupsites = Bea_MM_GroupSites_Factory::get_all_groups( );

		// Split array between API et DB groups, keep only DB groups
		$db_groups = array( );
		foreach ( $groupsites as $groupsite_name => $groupsite ) {
			if ( isset( $db_settings[$groupsite_name] ) ) {
				$db_groups[$groupsite_name] = $groupsite;
			}
		}

		// Show page title.
		include (BEA_MM_DIR . '/classes/admin/views/site-translation-info.php');

		// No DB group ?
		if ( empty( $db_groups ) ) {
			include (BEA_MM_DIR . '/classes/admin/views/site-translation-nodb.php');
			return false;
		}

		// Get current group sites
		$current_group = Bea_MM_GroupSites_Factory::get_current_group( );
		$current_site = Bea_MM_GroupSites_Factory::get_current_site( );

		// Test DB or API
		if ( $current_group == false ) {
			include (BEA_MM_DIR . '/classes/admin/views/site-translation-db-new.php');
		} elseif ( isset( $db_groups[$current_group['name']] ) ) {
			include (BEA_MM_DIR . '/classes/admin/views/site-translation-db-edit.php');
		} else {
			include (BEA_MM_DIR . '/classes/admin/views/site-translation-api.php');
		}

		return true;
	}

	/**
	 * Get plugin settings URL
	 */
	public static function get_admin_url( ) {
		return admin_url( 'options-general.php?page=' . self::admin_slug );
	}

	/**
	 * Check POST form data for add new translation group
	 */
	private static function check_save_settings( ) {
		if ( isset( $_POST['save-translation'] ) ) {
			check_admin_referer( 'save-translation' );

			// Strip ?
			$_POST['translation'] = stripslashes_deep( $_POST['translation'] );

			// Santize data
			$group_name = $_POST['translation']['group'] = strip_tags( $_POST['translation']['group'] );
			$_POST['translation']['language_code'] = strip_tags( $_POST['translation']['language_code'] );
			$_POST['translation']['public_label'] = strip_tags( $_POST['translation']['public_label'] );
			$_POST['translation']['admin_label'] = strip_tags( $_POST['translation']['admin_label'] );
			$_POST['translation']['user_language'] = strip_tags( $_POST['translation']['user_language'] );
			
			// Custom check for code language

			// All field are filled ?
			if ( empty($group_name) || empty( $_POST['translation']['language_code'] ) || empty( $_POST['translation']['public_label'] ) || empty( $_POST['translation']['admin_label'] ) || empty( $_POST['translation']['user_language'] ) ) {
				add_settings_error( 'bea-mm-network', 'translation_group', __( 'All fields are required.' ), 'error' );
				return false;
			}

			// Get group sites factory
			$groupsites = Bea_MM_GroupSites_Factory::get_all_groups( );

			// Test if group exist ?
			if ( !isset( $groupsites[$group_name] ) ) {
				add_settings_error( 'bea-mm-network', 'translation_group', __( 'This group not exists.' ), 'error' );
				return false;
			}
			
			// Get existing settings
			$db_settings = get_site_option( BEA_MM_OPTION );
			if ( $db_settings == false ) {
				add_settings_error( 'bea-mm-network', 'translation_group', __( 'No group on DB.' ), 'error' );
				return false;
			}
			
			// Add current blog id
			$_POST['translation']['blog_id'] = get_current_blog_id();

			// Add data into settings and update
			$db_settings[$group_name]['blogs'] = array();
			$db_settings[$group_name]['blogs'][get_current_blog_id()] = $_POST['translation'];
			update_site_option( BEA_MM_OPTION, $db_settings );

			// Register new group
			Bea_MM_GroupSites_Factory::append($group_name, $_POST['translation'] );

			add_settings_error( 'bea-mm-network', 'translation_group', __( 'Translation group saved.' ), 'updated' );
		}
	}
	
	public static function is_language_code( $code = '' ) {
		if ( strpos($code, '_') === false || strlen($code) != 5 ) {
			return false;
		}
	
		$code = explode('_', $code);
		if ( strtolower($code[0]) == $code[0] && strtoupper($code[1]) == $code[1] ) {
			return true;
		}
		
		return false;
	}
}
