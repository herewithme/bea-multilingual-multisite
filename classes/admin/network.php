<?php
/**
 * This class to manage (add/edit/delete) "translation group sites" on network administration
 */
class Bea_MM_Admin_Network {
	const admin_slug = 'bea-translation-groups';

	/**
	 * Register hooks
	 */
	public function __construct( ) {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'network_admin_menu', array( __CLASS__, 'network_admin_menu' ), 9 );
	}

	/**
	 * Call functions for check POST data form
	 */
	public static function admin_init( ) {
		self::check_form_add( );
		self::check_form_edit( );
	}

	/**
	 * Add menu on network admin
	 */
	public static function network_admin_menu( ) {
		add_submenu_page( 'settings.php', __( 'Translations Groups', 'bea-mm' ), __( 'Translations Groups', 'bea-mm' ), 'manage_options', self::admin_slug, array( __CLASS__, 'page' ) );
	}

	/**
	 * Build HTML of plugin network page
	 */
	public static function page( ) {
		// Current settings
		$db_settings = get_site_option( BEA_MM_OPTION );
		if ( $db_settings == false ) {
			$db_settings = array( );
		}

		// Get group sites factory
		$groupsites = Bea_MM_GroupSites_Factory::get_all_groups( );

		// Split array between API et DB groups
		$api_groups = $db_groups = array( );
		foreach ( $groupsites as $groupsite_name => $groupsite ) {
			if ( isset( $db_settings[$groupsite_name] ) ) {
				$db_groups[$groupsite_name] = $groupsite;
			} else {
				$api_groups[$groupsite_name] = $groupsite;
			}
		}

		// Show page title.
		include (BEA_MM_DIR . '/classes/admin/views/network-group-info.php');

		// Show API list
		include (BEA_MM_DIR . '/classes/admin/views/network-group-list-api.php');

		// Show DB list
		include (BEA_MM_DIR . '/classes/admin/views/network-group-list-db.php');

		// Show form
		include (BEA_MM_DIR . '/classes/admin/views/network-group-form.php');

		return true;
	}

	/**
	 * Get plugin settings URL
	 */
	public static function get_admin_url( ) {
		return network_admin_url( 'settings.php?page=' . self::admin_slug );
	}

	/**
	 * Check POST form data for add new translation group
	 */
	private static function check_form_add( ) {
		if ( isset( $_POST['add-translation-group'] ) ) {
			check_admin_referer( 'add-translation-group' );

			// Strip ?
			$_POST['ngroup'] = stripslashes_deep( $_POST['ngroup'] );

			// Santize data
			$_POST['ngroup']['name'] = sanitize_key( $_POST['ngroup']['name'] );
			$_POST['ngroup']['label'] = strip_tags( $_POST['ngroup']['label'] );

			// All field are filled ?
			if ( empty( $_POST['ngroup']['name'] ) || empty( $_POST['ngroup']['label'] ) ) {
				add_settings_error( 'bea-mm-network', 'translation_group', __( 'All fields are required.' ), 'error' );
				return false;
			}

			// Get group sites factory
			$groupsites = Bea_MM_GroupSites_Factory::get_all_groups( );

			// Test if group exist ?
			if ( isset( $groupsites[$_POST['ngroup']['name']] ) ) {
				add_settings_error( 'bea-mm-network', 'translation_group', __( 'A translation group already exists with this name.' ), 'error' );
				return false;
			}

			// Get existing settings
			$db_settings = get_site_option( BEA_MM_OPTION );
			if ( $db_settings == false ) {
				$db_settings = array( );
			}

			// Add empty blogs
			$_POST['ngroup']['blogs'] = array( );

			// Add data into settings and update
			$db_settings[$_POST['ngroup']['name']] = $_POST['ngroup'];
			update_site_option( BEA_MM_OPTION, $db_settings );
			
			// Register new group
			Bea_MM_GroupSites_Factory::register( $_POST['ngroup']['name'], $_POST['ngroup']['label'], $_POST['ngroup']['blogs'] );
			
			add_settings_error( 'bea-mm-network', 'translation_group', __( 'Translation group added.' ), 'updated' );
		}
	}

	/**
	 * Check POST form data for edit/delete existing translation group
	 */
	private static function check_form_edit( ) {
		if ( isset( $_POST['save-translation-group'] ) ) {
			check_admin_referer( 'save-translation-group' );

			// Get existing settings
			$db_settings = get_site_option( BEA_MM_OPTION );
			if ( $db_settings == false ) {
				$db_settings = array( );
			}

			// Strip ?
			$_POST['groupsites'] = stripslashes_deep( $_POST['groupsites'] );
			
			// Santize data
			foreach ( $_POST['groupsites'] as $groupsite_name => $groupsite ) {
				// Check tcheat ?
				if ( !isset( $db_settings[$groupsite_name] ) ) {
					wp_die( __( 'Tcheater' ) );
				}

				// Label are filled ?
				if ( empty( $groupsite['label'] ) ) {
					add_settings_error( 'bea-mm-network', 'translation_group', __( 'All label fields are required.' ), 'error' );
					return false;
				}

				// Update value
				$db_settings[$groupsite_name]['label'] = $groupsite['label'];

				// Check for delation ?
				if ( isset( $groupsite['delete'] ) ) {
					unset( $db_settings[$groupsite_name] );
					Bea_MM_GroupSites_Factory::deregister($groupsite_name);
				}
			}

			// Update data into settings
			update_site_option( BEA_MM_OPTION, $db_settings );

			add_settings_error( 'bea-mm-network', 'translation_group', __( 'Translation group updated.' ), 'updated' );
		}
	}
}
