<?php
class BEA_CSF_Server_Client {
	/**
	 * Constructor
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public function __construct( ) {
	}

	/**
	 * Build URL with action, make a POST request on each client
	 */
	public static function send_to_clients( $class = '', $method = '', $datas, $blog_id = null ) {

		$blog_id = (int)$blog_id;

		switch_to_blog( $blog_id );
		$result = call_user_func( array( $class, $method ), $datas );
		restore_current_blog( );

		return $result;
	}

}
