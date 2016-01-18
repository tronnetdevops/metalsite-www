<?php

	use SatanBarbaraAPI\AccountController;
	use SatanBarbaraAPI\EventController;
	use SatanBarbaraAPI\ActController;
	use SatanBarbaraAPI\VenueController;
	use SatanBarbaraAPI\PerformanceController;
	use SatanBarbaraAPI\HosterController;
	use SatanBarbaraAPI\AttendeeController;
	
	abstract class EventsView extends PageView{
		
		static protected $_data = array();
		static protected $_accessLevel = 0;
		
		static protected $_templateFile = 'pages/events.html';
		
		static public function Init($data){
			
			$data['events'] = EventController::Search(
				array(
					'limit' => 10000,
					'activated' => 1
				)
			);
			
			foreach($data['events'] as &$Event){
				$startTimestamp = strtotime($Event['start']);
				$startDate = date('Y-m-d', $startTimestamp );
				$startTime = date('H-i-s', $startTimestamp );

				$Event['start'] = date('Y-m-d H:i:s', $startTimestamp );
				$Event['end'] = date('Y-m-d H:i:s', $startTimestamp );
				
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

				$data['dates'][$startDate][$startTime] = $Event['id'];
				
			}
			
			ksort($data['dates']);
			foreach($data['dates'] as &$times){
				ksort($times);
			}

			self::SetData($data);
			
		}
		
	}