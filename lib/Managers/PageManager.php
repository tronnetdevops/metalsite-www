<?php
/**
 * @brief Page Manager
 *
 * ## Overview
 * This file exists as
 *
 * @package SatanBarbara
 * @subpackage Application
 * @category Managers
 * @version 0.7.2b
 * @since 0.7.1b
 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
 * @copyright Copyright (c) 2015, Sean Murray
 *
 * @abstract
 */

	abstract class PageManager {
	    /**
	     * Class Methods
	     */
		
	    /**#@+
		 * @static
	     * @access public
	     */
    
	    /**
	     * Initialize Page Manager
	     *
	     * ## Overview
	     *
	     * @uses SatanBarbaraApp
		 * @uses SessionManager
		 * @uses ViewManager
		 * @uses DebugManager
		 * @uses RouteManager
		 * @uses PageView
		 *
		 * @see RouteManager
	     *
		 * @param array An array of creds for SendGrid API.
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function Init($params){
			DebugManager::Log("Initializing Page Manager", '@');
			DebugManager::Log($params);
				
			$appConfig = SatanBarbaraApp::GetConfig();
			
			/**
			 * @todo have config in it's own 'config' position instead of array_merge
			 */
			$data = array(
				'app' => array_merge(
					$appConfig[ SATANBARBARA_CURRENT_ENVIRONMENT ], 
					array()
				),
				'page' => $params
			);
			
			
			DebugManager::Log("checking if logged in...", null, 3);
			
			if (SessionManager::IsLoggedIn()){
				$data['session'] = array(
					'is_auth' => true,
					'account' => SessionManager::GetAccount()
				);
				
				DebugManager::Log("Got an account, checking for a saved program...", null, 3);
			}
			
			$Page = ucfirst($params['page']) .'View';
			
			DebugManager::Log("Searching for view with class name: " . $Page);
						
			if ($Page::HasAccess( SessionManager::GetAccessLevel() )){
				$Page::Init($data);
				
				ViewManager::Render($Page);
			} else {
				DebugManager::Log("looks like this page requires auth but user isn't authenticated!");
				RouteManager::GoToPageURI('login' );
			}
			
			return true;
		}
		
	    /**#@-*/
		
	}