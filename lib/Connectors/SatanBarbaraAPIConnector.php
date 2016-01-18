<?php
	
	use SatanBarbaraAPI\ActivityController;

	use SatanBarbaraAPI\AccountController;

	
	abstract class SatanBarbaraAPIConnector {
		
		static public function Create($objectName, $properties){
			DebugManager::Status("API Create Request");
			DebugManager::Status($objectName);
			DebugManager::Status($properties);
			
			$Controller = "SatanBarbaraAPI\\".$objectName."Controller";

			return $Controller::Create($properties);
		}
		
		
	}