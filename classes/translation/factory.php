<?php
class Bea_MM_Translation_Factory {
	/**
	 *
	 * @access public
	 * @var object Bea_MM_GroupSites_Factory
	 */
	public $groupsites = NULL;

	/**
	 * List of translations.
	 *
	 * @access public
	 * @var array
	 */
	public $translations;

	/**
	 * The amount of translations for the current query.
	 *
	 * @access public
	 * @var int
	 */
	public $translation_count = 0;

	/**
	 * Index of the current item in the loop.
	 *
	 * @access public
	 * @var int
	 */
	public $current_translation = -1;

	/**
	 * Whether the loop has started and the caller is in the loop.
	 *
	 * @access public
	 * @var bool
	 */
	public $in_the_loop = false;

	/**
	 * The current translation.
	 *
	 * @access public
	 * @var object
	 */
	public $translation = NULL;

	/**
	 * Current context
	 */
	public $view = '';
	public $view_blog_id = 0;
	public $view_args = '';

	/**
	 * Constructor
	 * @param string $view [description]
	 * @param array $args [description]
	 * @param integer $blog_id [description]
	 */
	public function __construct( $view = NULL, $args = NULL, $blog_id = 0 ) {
		if ( $view === NULL && $args === NULL ) {
			$this->_setupDataFromQuery( );
		} else {
			$this->view = $view;
			$this->view_args = $args;
		}

		// setup blog id
		$this->view_blog_id = ($blog_id == 0) ? get_current_blog_id( ) : $blog_id;

		// Init group sites
		$this->groupsites = Bea_MM_GroupSites_Factory::get_group_by_blog_id( $this->view_blog_id );

		// Init
		$this->init( );
	}

	/**
	 * [getAvailableTranslations description]
	 * @return [type]
	 */
	public function init( ) {
		foreach ( $this->_getBlogs() as $site ) {
			$this->translations[] = $this->_getObject( $site->get_id( ) );
		}

		$this->translation_count = count( $this->translations );
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
	private function _getBlogs( ) {
		if ( $this->groupsites == false )
			return array( );

		return $this->groupsites['blogs'];
	}

	/**
	 * [getCurrentLanguage description]
	 * @return [type]
	 */
	private function _getBlog( $blog_id = 0 ) {
		if ( $this->groupsites == false || $blog_id == 0 || !isset( $this->groupsites['blogs'][$blog_id] ) )
			return false;

		return $this->groupsites['blogs'][$blog_id];
	}

	/**
	 * [getCurrentLanguage description]
	 * @return [type]
	 */
	private function _getCurrentBlog( ) {
		return $this->_getBlog( $this->view_blog_id );
	}

	/**
	 * Instanciate correct class depending type of view
	 * @return [type]
	 */
	private function _getObject( $blog_id = 0 ) {
		$this->view_args['source_blog_id'] = $this->view_blog_id;
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
		} elseif ( $this->view == 'post_type' || $this->view == 'post' || $this->view == 'page' ) {
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

	/**
	 * Sets up the current translation.
	 *
	 * Retrieves the next translation, sets up the translation, sets the 'in the loop'
	 * property to true.
	 *
	 * @access public
	 */
	public function the_translation( ) {
		$this->in_the_loop = true;
		$this->translation = $this->next_translation( );
	}

	/**
	 * Whether there are more posts available in the loop.
	 *
	 * @access publicd
	 *
	 * @return bool True if posts are available, false if end of loop.
	 */
	public function have_translations( ) {
		if ( $this->current_translation + 1 < $this->translation_count ) {
			return true;
		} elseif ( $this->current_translation + 1 == $this->translation_count && $this->translation_count > 0 ) {
			$this->rewind_translations( );
		}

		$this->in_the_loop = false;
		return false;
	}

	/**
	 * Set up the next translation and iterate current translation index.
	 *
	 * @access public
	 *
	 * @return WP_Post Next post.
	 */
	public function next_translation( ) {
		$this->current_translation++;

		$this->translation = $this->translations[$this->current_translation];
		return $this->translation;
	}

	/**
	 * Rewind the translations and reset translation index.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function rewind_translations( ) {
		$this->current_translation = -1;
		if ( $this->translation_count > 0 ) {
			$this->translation = $this->translations[0];
		}
	}

	public function translation_exists( ) {
		if ( $this->translation == NULL )
			return NULL;

		return $this->translation->is_available( );
	}

	public function get_translation_type( ) {
		if ( $this->translation == NULL )
			return NULL;

		return $this->translation->get_type( );
	}

	public function get_translation_id( ) {
		if ( $this->translation == NULL )
			return NULL;

		return $this->translation->get_id( );
	}

	public function get_translation_classes( ) {
		if ( $this->translation == NULL )
			return NULL;

		return $this->translation->get_classes( );
	}

	public function get_translation_title( ) {
		if ( $this->translation == NULL )
			return NULL;

		return $this->translation->get_title( );
	}

	public function get_translation_permalink( ) {
		if ( $this->translation == NULL )
			return NULL;

		return $this->translation->get_permalink( );
	}

	public function get_translation_blog_id( ) {
		if ( $this->translation == NULL )
			return NULL;

		return $this->translation->get_blog_id( );
	}

	public function get_blog_id( ) {
		if ( $this->translation == NULL )
			return NULL;

		return $this->translation->get_blog_id( );
	}

	public function get_language_label( ) {
		$blog = $this->_getBlog( $this->get_translation_blog_id( ) );
		if ( $blog == false )
			return NULL;

		return $blog->get_language_label( );
	}

	public function get_language_code( ) {
		$blog = $this->_getBlog( $this->get_translation_blog_id( ) );
		if ( $blog == false )
			return NULL;

		return $blog->get_language_code( );
	}

	public function get_home_permalink( ) {
		$blog = $this->_getBlog( $this->get_translation_blog_id( ) );
		if ( $blog == false )
			return NULL;

		return $blog->get_permalink( );
	}

}
