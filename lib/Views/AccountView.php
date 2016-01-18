<?php
	use SatanBarbaraAPI\AccountController;
	use SatanBarbaraAPI\EventController;
	use SatanBarbaraAPI\ActController;
	use SatanBarbaraAPI\VenueController;
	use SatanBarbaraAPI\PerformanceController;
	use SatanBarbaraAPI\HosterController;
	use SatanBarbaraAPI\AttendeeController;
	use SatanBarbaraAPI\RoleController;
	use SatanBarbaraAPI\MembershipController;
	use SatanBarbaraAPI\TagController;

	abstract class AccountView extends PageView{
		
		static protected $_data = array();
		static protected $_accessLevel = 0;
		
		static protected $_templateFile = 'pages/account.html';
		
		static public function Init($data){
			
			if (isset($data['page']['params']['id'])){
				$Account = AccountController::Get(
					array('ids' => $data['page']['params']['id'])
				);
				
				$Memberships = MembershipController::Search(
					array(
						'account_id' => $Account['id'],
						'operator' => 'eq'
					)
				);
				
				foreach($Memberships as $Membership){
					$Act = ActController::Get( array('ids' => $Membership['act_id']) );
					
					$Roles = RoleController::Search(
						array(
							'membership_id' => $Membership['id'],
							'operator' => 'eq'
						)
					);
					
					foreach($Roles as $Role){
						$Tag = TagController::Get(array('ids' => $Role['tag_id']));
						
						$Act['roles'][ $Tag['title'] ] = $Tag['title'];
							
						$Account['roles'][ $Tag['title'] ][ $Act['id'] ] = $Act;
					}
					
					$Performances = PerformanceController::Search( array('act_id' => $Act['id']) );
				
					foreach($Performances as $Performance){
						$Event = EventController::Get(array('ids' => $Performance['event_id']));
												
						$Account['performances'][ $Performance['id'] ] = array(
							'where' => $Event,
							'as' => $Act,
							'when' => $Performance,
							'with' => array()
						);
						
						$Act['performances'][ $Event['id'] ] = $Event;
					}
					
					$Account['acts'][ $Act['id'] ] = $Act;
				}
				
				$Attendings = AttendeeController::Search(
					array(
						'account_id' => $Account['id'],
						'operator' => 'eq'
					)
				);
				foreach($Attendings as $Attending){
					$Event = EventController::Get( array('ids' => $Attending['event_id']) );
					
					$Account['attending'][ $Event['id'] ] = $Event;
				}
				
				
				$data['account'] = $Account;
			} else {
				
			}
			
			self::SetData($data);
		}
		
	}