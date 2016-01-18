<?php
/**
 * Metalsite Site
 *
 * ## Overview
 * This file exists as
 *
 * @package SatanBarbara
 * @subpackage Application
 * @category Application
 * @version 0.7.2b
 * @since 0.5.1b
 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
 * @copyright Copyright (c) 2015, Sean Murray
 *
 * @see /www/index.php
 * @abstract
 */

	abstract class SatanBarbaraApp{
	    /**
	     * Class Properties
	     */
    
	    /**#@+
	     * @static
	     * @access protected
	     */
    
	    /**
		 * Application configuration settings. 
		 * @var array
		 */
		static protected $appConfig;

	    /**#@-*/
		
	    /**
	     * Class Methods
	     */
		
	    /**#@+
		 * @static
	     * @access public
	     */
    
	    /**
	     * Initialize Metalsite Application
	     *
	     * ## Overview
	     * This will load in the autoloader for third party embedded services
		 * that are managed by `Composer`. After, it will either load in or use
		 * provided application configurations to setup various managers. 
		 *
		 * The `SessionManager` will then be initialized to establish and parse any
		 * session data and then `RouteManager` will be invoked to begin loading
		 * in HTTP variables and render a view corresponding to the data given.
	     *
	     * @uses NotificationManager
	     * @uses SessionManager
	     * @uses RouteManager
	     *
		 * @param array|null An array of app settings, or else use null to load.
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function Init($appConfig = null){
			DebugManager::Log("Initializing Application", '@');
	
			DebugManager::Log("Including Composer Autoloader");
			/**
			 * Auto generated autoloader created for and by Composer installed 
			 * libraries.
			 */
			require_once(SATANBARBARA_APP_PATH . '/includes/vendor/autoload.php');
			
			DebugManager::Log("Checking if an application config was provided.");
			if (!isset($appConfig)){
				DebugManager::Log("No config provided, loading one from: " . SATANBARBARA_APP_CONFIG_PATH);
				$appConfig = json_decode( file_get_contents( SATANBARBARA_APP_CONFIG_PATH ), true );
			}
			
			self::SetConfig($appConfig);
			
			DebugManager::Log("Setting DebugManager file to: " . $appConfig['logs']['app']['path']);
			
			DebugManager::Init();
									
			SessionManager::Init();
						
			RouteManager::Init();
						
			return true;
		}
		
	    /**
	     * Set Application Config Data
	     *
		 * @param array An array of app settings.
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function SetConfig($appConfig){
			DebugManager::Log("Setting Application Configurations");
			DebugManager::Log($appConfig);
			
			self::$appConfig = $appConfig;
			
			return true;
		}
		
	    /**
	     * Get Application Config Data
	     *
	     * @return array An array of app settings.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function GetConfig(){
			DebugManager::Log("Getting Application Configurations");
			DebugManager::Log(self::$appConfig);
			
			return self::$appConfig;
		}
		
	    /**#@-*/
		
	}