<?php

	abstract class ViewManager{
		
		static private $_loader;
		static private $_interface;
		
		static public function Init(){
			Twig_Autoloader::register();

			self::$_loader = new Twig_Loader_Filesystem('templates');
	
			self::$_interface = new Twig_Environment(self::$_loader, array(
			    'cache' => false //'templates/cached',
			));
	
		}
		
		static public function createViewFunction($name, $funcName){
			return new Twig_SimpleFunction($name, $funcName);
		}
		
		static protected function getInterface(){
			if (!self::$_interface){
				self::Init();
			}
			
			return self::$_interface;
		}
		
		static public function Render($page){
			$interface = self::getInterface();
			
			$functions = $page::getViewFunctions();
			
			if (count($functions)){
				foreach($functions as $funcName=>$func){
					$interface->addFunction( self::createViewFunction($funcName, $func) );
				}
			}
			
			echo $interface->render( $page::GetTemplateFile() , $page::GetData() );
		}
	}