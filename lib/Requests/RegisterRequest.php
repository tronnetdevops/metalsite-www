<?php

	use SatanBarbaraAPI\AccountController;

	abstract class RegisterRequest extends BaseRequest{
		
		static public $defaultRedirect = 'home';
		
		static public $_accessLevel = 0;
		
		static public function Init($params){
			DebugManager::Log("Got a logout request!", '@');
						
			$account = AccountController::Create($params);
			
			SessionManager::Create($account);
			
			
			return new ResponseObject();
		}

	}