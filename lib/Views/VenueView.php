<?php
	use SatanBarbaraAPI\AccountController;
	use SatanBarbaraAPI\EventController;
	use SatanBarbaraAPI\ActController;
	use SatanBarbaraAPI\VenueController;
	use SatanBarbaraAPI\PerformanceController;
	use SatanBarbaraAPI\HosterController;
	use SatanBarbaraAPI\AttendeeController;
	use SatanBarbaraAPI\MembershipController;
	use SatanBarbaraAPI\LocationController;

	abstract class VenueView extends PageView{
		
		static protected $_data = array();
		static protected $_accessLevel = 0;
		
		static protected $_templateFile = 'pages/venue.html';
		
		static public function Init($data){
			
			if (isset($data['page']['params']['id'])){
				$Venue = VenueController::Get(
					array('ids' => $data['page']['params']['id'])
				);
				
				if ($Venue['location_id']){
					$Location = LocationController::Get( array('ids' => $Venue['location_id']));
					
					$Location['full'] = $Location['address'].', '.$Location['city']
						.', '.$Location['state'].' '.$Location['postal'].', '.$Location['country'];
					
					$Venue['location'] = $Location;
				}

				$Hostings = HosterController::Search(
					array(
						'venue_id' => $Venue['id'],
						'operator' => 'eq'
					)
				);
				
				foreach($Hostings as $Hosting){
					$Event = EventController::Get( array('ids' => $Hosting['event_id']) );
					
					$Event['attendees'] = AttendeeController::Search(
						array(
							'event_id' => $Event['id'],
							'state' => '1',
							'operator' => 'eq'
						)
					);
					
					$Event['maybees'] = AttendeeController::Search(
						array(
							'event_id' => $Event['id'],
							'state' => '2',
							'operator' => 'eq'
						)
					);
					
					
					$Venue['events'][] = $Event;
				}
				
				$data['venue'] = $Venue;
			} else {
				
			}
			
			self::SetData($data);
		}
		
	}