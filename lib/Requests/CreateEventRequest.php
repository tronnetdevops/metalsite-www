<?php

	use SatanBarbaraAPI\AccountController;
	use SatanBarbaraAPI\EventController;
	use SatanBarbaraAPI\ActController;
	use SatanBarbaraAPI\VenueController;
	use SatanBarbaraAPI\PerformanceController;
	use SatanBarbaraAPI\HosterController;
	use SatanBarbaraAPI\AttendeeController;

	abstract class CreateEventRequest extends BaseRequest{
		
		static public $defaultRedirect = 'shows';
		
		static public $_accessLevel = 1;
		
		static public function Init($params){
			DebugManager::Log("Got a logout request!", '@');

			$params['start'] = date('c', strtotime($params['start']) );
			$params['end'] = date('c', strtotime($params['end']) );
			
			$Account = SessionManager::GetAccount();

			try{
				$Event = EventController::Create($params);
			} catch(Exception $e){
				return new ResponseObject("Error", 1);
			}
			
			if (isset($params['acts']) && is_array($params['acts'])){
								
				foreach($params['acts'] as $pos=>$act){
					try{
						$Act = ActController::Create(
							array('title' => $act)
						);
					} catch(Exception $e){
						$Acts = ActController::Search(
							array('title' => $act)
						);
						
						$Act = reset($Acts);
					}

					try{
						if (isset($params['slots'][ $pos ])){
							$slot = date('c', strtotime($params['slots'][ $pos ]) );
						} else {
							if (isset($slot)){
								$slot = date('c', strtotime('+1 hour', $slot));
							} else {
								$slot = date('c', strtotime('+30 minutes', $params['start']));
							}
						}
						
						$Performance = PerformanceController::Create(
							array(
								'act_id' => $Act['id'],
								'event_id' => $Event['id'],
								'position' => $pos,
								'slot' => $slot
							)
						);
					} catch(Exception $e){

					}
				}
			}
			
			if (isset($params['venue'])){
				try{
					$Venue = VenueController::Create(
						array('title' => $params['venue'])
					);
				} catch(Exception $e){
					$Venues = VenueController::Search(
						array('title' => $params['venue'])
					);
					
					$Venue = reset($Venues);
				}
				
				try{	
					$Hoster = HosterController::Create(
						array(
							'venue_id' => $Venue['id'],
							'event_id' => $Event['id']
						)
					);
				} catch(Exception $e){
		
				}
			}
			

			$Attendee = AttendeeController::Create(
				array(
					'account_id' => $Account['id'],
					'event_id' => $Event['id'],
					'privilege_level' => 3,
					'role' => 3,
					'type' => 3,
				)
			);
			
			self::$defaultRedirect = 'event/'.$Event['id'];
			
			return new ResponseObject();
		}

	}