<?php
/**
 * Metalsite PHP Class Autoloader
 *
 * ## Overview
 * This file is responsible for dynamically loading PHP Classes by
 * searching through a specific set of directories within the MOVEO 
 * Rewards Application for filenames that match Class names requested
 * within the application.
 *
 * This allows us to only load classes and files into memory on request
 * instead of loading everything and is cleaner than managing requires 
 * and includes embedded in files. This also makes directory and 
 * architecture changes much easier to cope with.
 *
 * @package SatanBarbara
 * @subpackage Application
 * @category Autoloaders
 * @version 0.7.2b
 * @since 0.5.1b
 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
 * @copyright Copyright (c) 2015, Sean Murray
 *
 * @see /www/index.php
 */

	/**
	 * Metalsite PHP Class Autoloader
	 *
	 * This will currently search through the Metalsite Application
	 * for class matches of the following types:
	 *  1. Managers
	 *  2. Connectors
	 *  3. Views
	 *  4. Application
	 */
	function SatanBarbaraAppAutoloader($class){
	    if (file_exists( SATANBARBARA_APP_PATH . '/lib/Managers/' . $class . '.php')) {
	        include_once( SATANBARBARA_APP_PATH . '/lib/Managers/' . $class . '.php');
	        return;
		} else if (file_exists( SATANBARBARA_APP_PATH . '/lib/Connectors/' . $class . '.php')) {
	        include_once( SATANBARBARA_APP_PATH . '/lib/Connectors/' . $class . '.php');
	        return;
		} else if (file_exists( SATANBARBARA_APP_PATH . '/lib/Views/' . $class . '.php')) {
	        include_once( SATANBARBARA_APP_PATH . '/lib/Views/' . $class . '.php');
	        return;
		} else if (file_exists( SATANBARBARA_APP_PATH . '/lib/Requests/' . $class . '.php')) {
	        include_once( SATANBARBARA_APP_PATH . '/lib/Requests/' . $class . '.php');
	        return;
		} else if (file_exists( SATANBARBARA_APP_PATH . '/lib/' . $class . '.php')) {
	        include_once( SATANBARBARA_APP_PATH . '/lib/' . $class . '.php');
	        return;
		} else if (file_exists( SATANBARBARA_APP_PATH . '/lib/Objects/' . $class . '.php')) {
	        include_once( SATANBARBARA_APP_PATH . '/lib/Objects/' . $class . '.php');
	        return;
		}
		
		/** there might be in another autoloader...don't exit */
	}
	
	/**
	 * Register SQL Autoloader so that classes can be dynamically created.
	 */
	spl_autoload_register('SatanBarbaraAppAutoloader');