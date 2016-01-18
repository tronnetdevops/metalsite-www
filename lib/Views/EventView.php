<?php
	use SatanBarbaraAPI\AccountController;
	use SatanBarbaraAPI\EventController;
	use SatanBarbaraAPI\ActController;
	use SatanBarbaraAPI\VenueController;
	use SatanBarbaraAPI\PerformanceController;
	use SatanBarbaraAPI\HosterController;
	use SatanBarbaraAPI\AttendeeController;

	abstract class EventView extends PageView{
		
		static protected $_data = array();
		static protected $_accessLevel = 0;
		
		static protected $_templateFile = 'pages/event.html';
		
		static public function Init($data){
			
			if (isset($data['page']['params']['id'])){
				$Event = EventController::Get(
					array('ids' => $data['page']['params']['id'])
				);
				
				$Hostings = HosterController::Search(
					array(
						'event_id' => $Event['id'],
						'operator' => 'eq'
					)
				);
				
				foreach($Hostings as $Hosting){
					$Venue = VenueController::Get( array('ids' => $Hosting['venue_id']) );

					$Event['venues'][ $Venue['id'] ] = $Venue;
				}
				
				$Performances = PerformanceController::Search( 
					array(
						'event_id' => $Event['id'],
						'operator' => 'eq'
					)
				);
				
				foreach($Performances as $Performance){
					$Act = ActController::Get( array('ids' => $Performance['act_id']) );
					
					$Act['slot'] = $Performance['slot'];
					$Event['acts'][ $Act['slot'] ] = $Act;
					
				}
				
				$data['event'] = $Event;
			} else {
				
			}
			
			self::SetData($data);
		}
		
	}