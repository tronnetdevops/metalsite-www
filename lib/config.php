<?php
/**
 * Metalsite Application PHP Configuration
 *
 * ## Overview
 * This file exists as the primary configuration file for the Metalsite
 * Application. It is loaded by the primary entry point for application PHP
 * requests and is responsible for determining the current environment and 
 * defining application wide paths.
 *
 * @package SatanBarbara
 * @subpackage Application
 * @category Configurations
 * @version 0.7.2b
 * @since 0.5.1b
 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
 * @copyright Copyright (c) 2015, Sean Murray
 *
 * @see /www/index.php
 */

	/**
	 * PHP Configuration
	 * 
	 * We must start the PHP session as close to the request entry point as
	 * possible, so we are during it now.
	 */
	session_start();
	
	/**
	 * The PHP Timezone must be set in order to utilize the `date` function
	 * without triggering a warning.
	 */
	date_default_timezone_set('America/Los_Angeles');


	/**#@+
	 * Application Global Constants
	 */
	
	define('SATANBARBARA_APP_PACKAGE_NAME', 'Satan Barbara');
	
	/**
	 * Sets the environment we're working in. This will determine which
	 * app config settings will be utilized within the application.
	 */
	if ($_SERVER['HTTP_HOST'] == "satanbarbara.com"){
		/**
		 * Sets the current application environment mode to 'production'.
		 *
		 * @global
		 */
		define('SATANBARBARA_CURRENT_ENVIRONMENT', 'prod' );
	} else{
		/**
		 * Sets the current application environment mode to 'development'.
		 *
		 * @global
		 */
		define('SATANBARBARA_CURRENT_ENVIRONMENT', 'dev' );
	}

	/**
	 * A global password salt for hashing passwords during account creation.
	 *
	 * @global
	 */
	define('SATANBARBARA_PASSWORD_SALT', '14Y4gkGrEu0uXPXtRMBfsg==' );
	
	/**
	 * A authentication secret to be used with requests to the Metalsite
	 * API. 
	 *
	 * @todo Add persistence and possible session based auth secrets
	 *
	 * @global
	 */
	define('SATANBARBARA_AUTH_SECRET', 'CWPZAUruEy9pUwNjD+y9qg==' );
	
	/**
	 * Set global path variables.
	 *
	 * @global
	 */
	define('SATANBARBARA_API_PATH', SATANBARBARA_PATH . '/api' );
	define('SATANBARBARA_APP_PATH', SATANBARBARA_PATH . '/www' );

	define('SATANBARBARA_MYSQL_PATH', SATANBARBARA_PATH . '/resources/db/mysql' );

	define('SATANBARBARA_APP_CONFIG_PATH', SATANBARBARA_APP_PATH . '/data/config.json' );
	define('SATANBARBARA_APP_INCLUDES_PATH', SATANBARBARA_APP_PATH . '/includes' );

	/**
	 * The Metalsite API Namespace.
	 *
	 * @global
	 */
	define('SATANBARBARA_API_NAMESPACE', 'SatanBarbaraAPI\\');
	
	/**
	 * The Metalsite Site Namespace.
	 *
	 * @global
	 */
	define('SATANBARBARA_APP_NAMESPACE', 'SatanBarbaraApp\\');
	
    /**#@-*/
	