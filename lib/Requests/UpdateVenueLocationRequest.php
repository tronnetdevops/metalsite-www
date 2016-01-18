<?php

	use SatanBarbaraAPI\AccountController;
	use SatanBarbaraAPI\EventController;
	use SatanBarbaraAPI\ActController;
	use SatanBarbaraAPI\VenueController;
	use SatanBarbaraAPI\PerformanceController;
	use SatanBarbaraAPI\HosterController;
	use SatanBarbaraAPI\LocationController;

	abstract class UpdateVenueLocationRequest extends BaseRequest{
		
		static public $defaultRedirect = 'shows';
		
		static public $_accessLevel = 1;
		
		static public function Init($params){
			DebugManager::Log("Got a logout request!", '@');

			$Account = SessionManager::GetAccount();
			
			try{
				$Location = LocationController::Create($params);
			} catch(Exception $e){
				$Locations = LocationController::Search($params);
				
				$Location = reset($Locations);
			}

			try{
				
				$Venue = VenueController::Update(
					array(
						'ids' => $params['venue_id'],
						'location_id' => $Location['id']
					)
				);
				
			} catch(Exception $e){

			}
			
			self::$defaultRedirect = 'venue/'.$Venue['id'];
			
			return new ResponseObject();
		}

	}