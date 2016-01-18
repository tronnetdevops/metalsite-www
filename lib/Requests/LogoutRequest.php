<?php

	abstract class LogoutRequest extends BaseRequest{
		
		static public $defaultRedirect = 'home';
		
		static public $_accessLevel = 1;
		
		static public function Init($params){
			DebugManager::Log("Got a logout request!", '@');
			
			SessionManager::Destroy();
			
			return new ResponseObject();
		}

	}