<?php

	use SatanBarbaraAPI\VenueController;

	abstract class VenuesView extends PageView{
		
		static protected $_data = array();
		static protected $_accessLevel = 0;
		
		static protected $_templateFile = 'pages/venues.html';
		
		static public function Init($data){
			
			$data['venues'] = VenueController::Search(
				array(
					'limit' => 10000,
					'activated' => 1
				)
			);
			
			/**
			 * @todo Have a sort based on proximety to location!!
			 */
			
			$data['ordered'] = array();
			foreach($data['venues'] as $Venue){
				$firstLetter = substr($Venue['title'], 0, 1);
				$data['ordered'][ $firstLetter ][ $Venue['title'] ] = $Venue['id'];
			}
			
			ksort($data['ordered']);
			foreach($data['ordered'] as &$titles){
				ksort($titles);
			}
			
			self::SetData($data);
			
		}
		
	}