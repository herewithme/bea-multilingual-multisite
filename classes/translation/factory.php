<?php
class Bea_MM_Translation_Factory {
	/**
	 * @var array Collection of Bea_MM_Translation_View
	 */
	private $objects = array( );

	/**
	 * Current context
	 */
	private $view = '';
	private $view_blog_id = 0;
	private $view_args = '';

	/**
	 * Constructor
	 * @param string $view [description]
	 * @param array $args [description]
	 * @param integer $blog_id [description]
	 */
	public function __construct( $view = null, $args = null, $blog_id = 0 ) {
		if ( $view === null && $args === null ) {
			$this->_setupDataFromQuery( );
		} else {
			$this->view = $view;
			$this->view_args = $args;
		}

		// setup blog id
		$this->view_blog_id = ($blog_id == 0) ? get_current_blog_id( ) : $blog_id;
	}

	/**
	 * Get info of current view from WP_Query
	 * @return [type]
	 */
	private function _setupDataFromQuery( ) {
		if ( is_home( ) && is_front_page( ) ) {
			$this->view = 'home';
			$this->view_args = array( );
		} elseif ( is_day( ) ) {
			$this->view = 'day';
			$this->view_args = array( 'year' => get_query_var( 'year' ), 'monthnum' => get_query_var( 'monthnum' ), 'day' => get_query_var( 'day' ) );
		} elseif ( is_month( ) ) {
			$this->view = 'month';
			$this->view_args = array( 'year' => get_query_var( 'year' ), 'monthnum' => get_query_var( 'monthnum' ) );
		} elseif ( is_year( ) ) {
			$this->view = 'year';
			$this->view_args = array( 'year' => get_query_var( 'year' ) );
		} elseif ( is_author( ) ) {
			$this->view = 'author';
			$this->view_args = array( 'author_id' => get_queried_object_id( ) );
		} elseif ( is_search( ) ) {
			$this->view = 'search';
			$this->view_args = array( 's' => get_query_var( 's' ) );
		} elseif ( is_post_type_archive( ) ) {
			$this->view = 'post_type_archive';
			$this->view_args = array( 'post_type' => get_query_var( 'post_type' ) );
		} elseif ( is_single( ) || (is_home( ) && !is_front_page( )) || (is_page( ) && !is_front_page( )) ) {
			$this->view = 'post_type';
			$this->view_args = array( 'post_id' => get_queried_object_id( ) );
		} elseif ( is_category( ) || is_tag( ) || is_tax( ) ) {
			$term = get_queried_object( );

			$this->view = 'taxonomy';
			$this->view_args = array( 'term_id' => get_queried_object_id( ), 'taxonomy' => $term->taxonomy );
		}
	}

	/**
	 * [getSites description]
	 * @return [type]
	 */
	public function getSites( ) {
		$objects = array( );

		$group_sites = Bea_MM_GroupSites_Factory::get_group_by_blog_id( $this->view_blog_id );
		foreach ( $group_sites['blogs'] as $blog ) {
			$objects[] = new Bea_MM_Translation_Site( $blog['blog_id'] );
		}

		return $objects;
	}

	/**
	 * [getCurrentLanguage description]
	 * @return [type]
	 */
	public function getCurrentLanguage( ) {
		$group_sites = Bea_MM_GroupSites_Factory::get_group_by_blog_id( $this->view_blog_id );
		if ( $group_sites == false || !isset( $group_sites[$this->view_blog_id] ) )
			return false;

		return $group_sites[$this->view_blog_id]->get_language_code( );
	}

	/**
	 * [getAvailableTranslations description]
	 * @return [type]
	 */
	public function getAvailableTranslations( ) {
		foreach ( $this->getSites() as $site ) {
			$instance = new $this->_getObject( $site->get_blog_id( ) );
			if ( $instance == false ) {
				continue;
			}

			$this->objects[$site->getLanguageCode( )] = $instance;
		}

		return $this->objects;
	}

	/**
	 * Instanciate correct class depending type of view
	 * @return [type]
	 */
	private function _getObject( $blog_id = 0 ) {
		$this->view_args['blog_id'] = ($blog_id == 0) ? $this->view_blog_id : $blog_id;

		if ( $this->view == 'home' ) {
			return new Bea_MM_Translation_View_Home( $this->view_args );
		} elseif ( $this->view == 'day' ) {
			return new Bea_MM_Translation_View_Day( $this->view_args );
		} elseif ( $this->view == 'month' ) {
			return new Bea_MM_Translation_View_Month( $this->view_args );
		} elseif ( $this->view == 'year' ) {
			return new Bea_MM_Translation_View_Year( $this->view_args );
		} elseif ( $this->view == 'author' ) {
			return new Bea_MM_Translation_View_Author( $this->view_args );
		} elseif ( $this->view == 'search' ) {
			return new Bea_MM_Translation_View_Search( $this->view_args );
		} elseif ( $this->view == 'post_type_archive' ) {
			return new Bea_MM_Translation_View_PostTypeArchive( $this->view_args );
		} elseif ( $this->view == 'post_type' ) {
			return new Bea_MM_Translation_View_PostType( $this->view_args );
		} elseif ( $this->view == 'taxonomy' ) {
			return new Bea_MM_Translation_View_Taxonomy( $this->view_args );
		} else {
			return false;
		}
	}

	/**
	 * [getTranslation description]
	 * @param  string $language [description]
	 * @return [type]
	 */
	public function getTranslation( $language = '' ) {
		return $this->objects[$language];
	}

	/******************************* WP_Query API Client Inspiration ****************************************/

	public function have_translations( ) {
	}

	public function the_translation( ) {
	}

	public function get_id( ) {
	}

	public function get_title( ) {
	}

	public function get_permalink( ) {
	}

	public function get_language_label( ) {
	}

	public function get_language_code( ) {
	}

	/**
	 * Key or null
	 * @param  string $key [description]
	 * @return [type]
	 */
	public function __get( $key = '' ) {
		return (isset( $this->obj->$key ) ? $this->obj->$key : null);
	}

}
