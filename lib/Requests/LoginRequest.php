<?php

	use SatanBarbaraAPI\AccountController;

	abstract class LoginRequest extends BaseRequest{
		
		static public $defaultRedirect = 'events';
		
		static public $_accessLevel = 0;
		
		static public function Init($params){
			DebugManager::Log("Got a logout request!", '@');
			
			/**
			 * @todo ValidateLogin should provide a token, which is passed to
			 *       SessionManager
			 */
			if (AccountController::ValidateLogin($params)){
				$account = AccountController::Search($params);
				
				SessionManager::Create(true, reset($account) );
			} else {
				self::$defaultRedirect = 'login';
			}
			
			return new ResponseObject();
		}

	}