<?php
/**
 * Site Entry Point
 *
 * ## Overview
 * This file exists as the primary entry point for the Metalsite Site. It 
 * is the first PHP file loaded in the backend workflow and is loaded by an 
 * .htaccess directive that forwards all requests to this domain directly to
 * this file and allow us to parse the request headers and URI for routing.
 *
 * This will also be where all application configuration files are included
 * and the Metalsite Application initialized, formally starting the MOVEO
 * Rewards Application.
 *
 * @package SatanBarbara
 * @subpackage Application
 * @category EntryPoints
 * @version 0.7.2b
 * @since 0.0.1b
 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
 * @copyright Copyright (c) 2015, Sean Murray
 */
	
	/**#@+
	 * Application Global Constants
	 */
	
	/**
	 * Full path to the Metalsite ecosystem directory. This needs to be
	 * defined in order to load the PHP config file as well as initialize
	 * the Metalsite Application.
	 *
	 * @global
	 */
	define('SATANBARBARA_PATH', realpath(dirname( __FILE__ ) .'/../') );
    /**#@-*/
	
	
	/**
	 * Metalsite Application PHP Configuration.
	 *
	 * Loads the application PHP config file. This file will determine the
	 * current environment setup as well as define global constants and paths
	 * that are utilized in the Metalsite Application Core.
	 */
	require_once( SATANBARBARA_PATH . '/www/lib/config.php');
	
	/**
	 * Metalsite Application PHP Class Autoloader.
	 *
	 * Defines the paths to search for Application specific classes and loads
	 * them if found. All classes in the Application Core are loaded by this 
	 * mechanism.
	 */
	require_once( SATANBARBARA_APP_PATH . '/lib/autoloader.php');
	
	DebugManager::Log(SATANBARBARA_APP_PACKAGE_NAME . " Entry Point", '@');
	
	
	/**
	 * Metalsite API PHP Class Autoloader.
	 *
	 * Defines the paths to search for API specific classes and loads them
	 * if found. All classes in the API are loaded by this mechanism.
	 */
	require_once( SATANBARBARA_API_PATH .'/lib/autoloader.php');
	
	/**
	 * Initialize Metalsite Application.
	 *
	 * Utilizing the constants and autoloaders included above, initialize the
	 * Metalsite Application. This is where all session, routing, data and 
	 * requests are handled.
	 */
	SatanBarbaraApp::Init();
