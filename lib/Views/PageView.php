<?php

	abstract class PageView{
		
		static protected $_viewFunctions = array();
		static protected $_data = array();
		
		static public function Init($data){
			self::SetData($data);
			
			return true;
		}
		
		static public function SetData($data){
			static::$_data = $data;
			
			return true;
		}
				
		static public function GetData(){
			return static::$_data;
		}
		
		static public function GetTemplateFile(){
			return static::$_templateFile;
		}
		
		static public function GetViewFunctions(){
			return static::$_viewFunctions;
		}
		
		static public function HasAccess($accessLevel){
			return static::$_accessLevel <= $accessLevel;
		}
	}