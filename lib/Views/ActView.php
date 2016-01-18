<?php
	use SatanBarbaraAPI\AccountController;
	use SatanBarbaraAPI\EventController;
	use SatanBarbaraAPI\ActController;
	use SatanBarbaraAPI\VenueController;
	use SatanBarbaraAPI\PerformanceController;
	use SatanBarbaraAPI\HosterController;
	use SatanBarbaraAPI\AttendeeController;
	use SatanBarbaraAPI\RoleController;
	use SatanBarbaraAPI\TagController;
	use SatanBarbaraAPI\MembershipController;

	abstract class ActView extends PageView{
		
		static protected $_data = array();
		static protected $_accessLevel = 0;
		
		static protected $_templateFile = 'pages/act.html';
		
		static public function Init($data){
			
			if (isset($data['page']['params']['id'])){
				$Act = ActController::Get(
					array('ids' => $data['page']['params']['id'])
				);
				
				$Memberships = MembershipController::Search(
					array(
						'act_id' => $Act['id'],
						'operator' => 'eq'
					)
				);
				
				foreach($Memberships as $Membership){
					$Member = AccountController::Get( array('ids' => $Membership['account_id']) );

					$Roles = RoleController::Search(
						array(
							'membership_id' => $Membership['id'],
							'operator' => 'eq'
						)
					);
					
					foreach($Roles as $Role){
						$Tag = TagController::Get(array('ids' => $Role['tag_id']));

						$Act['roles'][ $Tag['title'] ] = $Tag['title'];
							
						$Member['roles'][] = $Tag['title'];
					}
					
					$Act['members'][ $Member['id'] ] = $Member;
				}
				
				$data['act'] = $Act;
			} else {
				
			}
			
			self::SetData($data);
		}
		
	}