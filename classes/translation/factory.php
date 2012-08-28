<?php
class Bea_MM_Translation_Factory {
	/**
	 * @var array Collection of Bea_MM_Translation_View
	 */
	private $objects = array();

	private $view = '';
	private $view_args = '';

	/**
	 * Constructor
	 * @param [type] $view [description]
	 * @param [type] $args [description]
	 */
	public function __construct($view = null, $args = null) {
		if ($view === null && $args === null) {
			$this -> _getDataFromQuery();
		}
	}

	/******************************* Private functions ****************************************/
	/**
	 * Get info from WP_Query if constructor if empty
	 * @return [type]
	 */
	private function _getDataFromQuery() {
		if (is_home() && is_front_page()) {
			$this -> view = 'home';
			$this -> view_args = array();
		} elseif (is_day()) {
			$this -> view = 'day';
			$this -> view_args = array('year' => get_query_var('year'), 'monthnum' => get_query_var('monthnum'), 'day' => get_query_var('day'));
		} elseif (is_month()) {
			$this -> view = 'month';
			$this -> view_args = array('year' => get_query_var('year'), 'monthnum' => get_query_var('monthnum'));
		} elseif (is_year()) {
			$this -> view = 'year';
			$this -> view_args = array('year' => get_query_var('year'));
		} elseif (is_author()) {
			$this -> view = 'author';
			$this -> view_args = array('author_id' => get_queried_object_id());
		} elseif (is_search()) {
			$this -> view = 'search';
			$this -> view_args = array('s' => get_query_var('s'));
		} elseif (is_post_type_archive()) {
			$this -> view = 'post_type_archive';
			$this -> view_args = array('post_type' => get_query_var('post_type'));
		} elseif (is_single() || (is_home() && !is_front_page()) || (is_page() && !is_front_page())) {
			$this -> view = 'post_type';
			$this -> view_args = array('post_id' => get_queried_object_id());
		} elseif (is_category() || is_tag() || is_tax()) {
			$term = get_queried_object();

			$this -> view = 'taxonomy';
			$this -> view_args = array('term_id' => get_queried_object_id(), 'taxonomy' => $term -> taxonomy);
		}

		// Add current blog_id
		$this -> view_args['blog_id'] = get_current_blog_id();
	}

	/**
	 * [getSites description]
	 * @return [type]
	 */
	public function getSites() {
		$objects = array();
		
		$results = $wpdb -> get_results();
		foreach ($results as $result) {
			$objects[] = Bea_MM_Translation_Site($result -> blog_id);
		}

		return $objects;
	}

	/******************************* Public functions ****************************************/
	/**
	 * [getCurrentLanguage description]
	 * @return [type]
	 */
	public function getCurrentLanguage() {
		$site = Bea_MM_Translation_Site($blog_id);
		return $site;
	}

	/**
	 * [getAvailableTranslations description]
	 * @return [type]
	 */
	public function getAvailableTranslations() {
		foreach ($this->getSites() as $result) {
			$instance = new $this->_getObject();
			if ($object == false) {
				continue;
			}

			$this -> objects[$result -> getLanguageCode()] = $instance;
		}

		return $this -> objects;
	}
	
	/**
	 * Instanciate correct class depending type of view
	 * @return [type]
	 */
	private function _getObject() {
		if ($this -> view == 'home') {
			return new Bea_MM_Translation_View_Home($this -> view_args);
		} elseif ($this -> view == 'day') {
			return new Bea_MM_Translation_View_Day($this -> view_args);
		} elseif ($this -> view == 'month') {
			return new Bea_MM_Translation_View_Month($this -> view_args);
		} elseif ($this -> view == 'year') {
			return new Bea_MM_Translation_View_Year($this -> view_args);
		} elseif ($this -> view == 'author') {
			return new Bea_MM_Translation_View_Author($this -> view_args);
		} elseif ($this -> view == 'search') {
			return new Bea_MM_Translation_View_Search($this -> view_args);
		} elseif ($this -> view == 'post_type_archive') {
			return new Bea_MM_Translation_View_PostTypeArchive($this -> view_args);
		} elseif ($this -> view == 'post_type') {
			return new Bea_MM_Translation_View_PostType($this -> view_args);
		} elseif ($this -> view == 'taxonomy') {
			return new Bea_MM_Translation_View_Taxonomy($this -> view_args);
		} else {
			return false;
		}
	}

	/**
	 * [getTranslation description]
	 * @param  string $language [description]
	 * @return [type]
	 */
	public function getTranslation($language = '') {
		return $this -> objects[$language];
	}

	/******************************* WP_Query API Client Inspiration ****************************************/
	public function have_translations() {
	}

	public function the_translation() {
	}

	public function get_id() {
	}

	public function get_title() {
	}

	public function get_permalink() {
	}

	public function get_language_label() {
	}

	public function get_language_code() {
	}

	/**
	 * Key or null
	 * @param  string $key [description]
	 * @return [type]
	 */
	public function __get( $key = '' ) {
		return( isset( $this->obj->$key ) ? $this->obj->$key : null );
	}
}
