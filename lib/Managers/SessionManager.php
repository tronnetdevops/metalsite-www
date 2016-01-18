<?php
/**
 * @brief Session Manager
 *
 * ## Overview
 * This file exists as
 *
 * @package SatanBarbara
 * @subpackage Application
 * @category Managers
 * @version 0.7.2b
 * @since 0.5.1b
 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
 * @copyright Copyright (c) 2015, Sean Murray
 *
 * @see SatanBarbaraApp
 *
 * @uses SatanBarbaraAPI\AccountController
 *
 * @abstract
 */

	use SatanBarbaraAPI\AccountController;
	
	abstract class SessionManager extends RoleManager {
	    /**
	     * Class Properties
	     */
    
	    /**#@+
	     * @static
	     * @access protected
	     */
    
	    /**
		 * Session ID. 
		 * @var integer
		 */
		static protected $id;
		
	    /**
		 * Account that is currently logged into.
		 * @var array
		 */
		static protected $account;
		
	    /**
		 * Extra data for the account.
		 * @var array
		 */
		static protected $data;
		
	    /**
		 * Flag for is the account has been validated and authenticated.
		 * @var bool
		 */
		static protected $authenticated = false;
		
	    /**#@-*/
		
	    /**
	     * Class Methods
	     */
		
	    /**#@+
		 * @static
	     * @access public
	     */
    
	    /**
	     * Initialize Session Manager
	     *
	     * ## Overview
	     *
	     * @uses AccountController
	     * @uses DebugManager
	     * @uses SessionManager
		 *
		 * @see SatanBarbaraApp
	     *
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function Init(){
			DebugManager::Log("Initializing Session Manager", '@');
			
			if (self::IsLoggedIn()){
				$account = self::GetAccount();
				
				if ($account){
					DebugManager::Log("We have an account with an ID of '".$account['id']."', seeing if one exists in the DB!");
					
					$account = AccountController::Get(
						array('ids' => $account['id'])
					);
			
					if (count($account)){
						DebugManager::Log("Looks like we got one! Login is valid!");
						
						/**
						 * If we're using the proxy account, we need to include prefill data
						 */
						if ($account['id'] == 0){
							// SatanBarbaraConnector::$dataClean = false;
						}
						
						/** 
						 * Login is Valid!
						 */
						
						return true;
					} else {
						DebugManager::Log("Invalid session setup! Destroying...");
						
						self::Destroy();
					}
				} else if ($_SESSION['authenticated'] && !isset($_SESSION['account'])) {
					DebugManager::Log("Invalid session setup! Destroying...");
			
					self::Destroy();
				}
			} else {
				DebugManager::Log("No account is logged in for this session!");
				
			}
			
			return true;
		}
		
	    /**
	     * Create Session
	     *
	     * ## Overview
	     *
	     * @uses $_SESSION
		 * @uses DebugManager
		 *
		 * @see SessionManager
	     *
		 * @param array $account The authenticated account associated with session.
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function Create($auth_token, $account = null){
			DebugManager::Log("Creating a session with an account", '@');
			DebugManager::Log($account);
			
			if (self::Authenticate($auth_token)){
				if ($account){
					return self::SetAccount($account, true);
				}
			} else {
				return false;
			}

			return true;
		}
		
	    /**
	     * Destroy Session
	     *
	     * ## Overview
	     *
	     * @uses $_SESSION
		 * @uses DebugManager
		 *
		 * @see SessionManager
		 *
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function Destroy(){
			DebugManager::Log("Destroying Session!", '@');
			
			self::Unauthenticate();
			self::SetAccount(null, true);
		}
		
	    /**
	     * Check Is Session Authenticated
	     *
	     * ## Overview
		 *
		 * @uses SessionManager
		 * @uses DebugManager
		 *
	     * @return bool The current authenticated flag.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function IsLoggedIn(){
			DebugManager::Log("Checking if session is authenticated", '@');
			
			if (!self::$authenticated){
				if (isset($_SESSION['authenticated'])){
					return self::Authenticate($_SESSION['authenticated']);
				} else {
					return false;
				}
			}
			
			DebugManager::Log(self::$authenticated);
			
			return !!self::$authenticated;
		}
	
	    /**
	     * Check Is Session Authenticated
	     *
	     * ## Overview
		 *
		 * @uses SessionManager
		 * @uses DebugManager
		 *
	     * @return bool The current authenticated flag.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function Authenticate($auth_token){
			DebugManager::Log("Setting Authentication Flag With Token: " . $auth_token, '@');
			DebugManager::Log("Current: " . self::$authenticated);
			
			/**
			 * @todo Do some auth token checking.
			 */
			if (is_bool($auth_token)){
				self::$authenticated = $auth_token;
				$_SESSION['authenticated'] = $auth_token;
			}
			
			DebugManager::Log("Set: " . self::$authenticated);
			
			return true;
		}
		
	    /**
	     * Check Is Session Authenticated
	     *
	     * ## Overview
		 *
		 * @uses SessionManager
		 * @uses DebugManager
		 *
	     * @return bool The current authenticated flag.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function Unauthenticate(){
			DebugManager::Log("Setting Authentication Flag To Unauthenticated", '@');
			DebugManager::Log("Current: " . self::$authenticated);
			
			self::$authenticated = null;
			
			unset($_SESSION['authenticated']);
						
			DebugManager::Log("New: " . self::$authenticated);
			return true;
		}
		
	    /**
	     * Get Session Account
	     *
	     * ## Overview
		 *
		 * @uses SessionManager
		 * @uses DebugManager
		 *
	     * @return array The current account associated with this session.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function GetAccount(){
			DebugManager::Log("Getting Session Account", '@');
			DebugManager::Log("Current: ");
			DebugManager::Log(self::$account);
			
			
			if (!isset(self::$account)){
				if (isset($_SESSION['account'])){
					self::SetAccount($_SESSION['account']);
				} else {
					return false;
				}
			}
			
			DebugManager::Log("Set: ");
			DebugManager::Log(self::$account);
			
			return self::$account;
		}
		
	    /**
	     * Set Session Account
	     *
	     * ## Overview
		 *
		 * @uses SessionManager
		 * @uses DebugManager
		 *
	     * @return array The current account associated with this session.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function SetAccount($account, $persist = false){
			DebugManager::Log("Setting Session Account", '@');
			DebugManager::Log("Current: ");
			DebugManager::Log(self::$account);
			
			if ($account){
				
				$match = AccountController::Get( 
					array('ids' => $account['id']) 
				);
				
				if (count($match)){
			
					$account = $match;
				}
			} else {
				/**
				 * Allowing to set 'null' to destroy session
				 */
				// throw new Exception("No account was provided! Can't set session account!");
			}
			
			self::$account = $account;
			
			if ($persist){
				$_SESSION['account'] = $account;
			}
			
			DebugManager::Log("New: ");
			DebugManager::Log(self::$account);
			
			return true;
		}
	    /**#@-*/
		
	}