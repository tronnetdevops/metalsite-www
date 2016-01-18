<?php

	abstract class BaseRequest{
		
		static protected $_params;
		
		static public $wrap = true;
		
		static public function Init($params){
			self::SetParams($params);
			
			return true;
		}

		static public function SetParams($params){
			self::$_params = $params;
			
			return true;
		}
				
		static public function GetParams(){
			return self::$_params;
		}
		
		static public function HasAccess($accessLevel){
			return static::$_accessLevel <= $accessLevel;
		}
	}