<?php

	use SatanBarbaraAPI\ActController;

	abstract class ActsView extends PageView{
		
		static protected $_data = array();
		static protected $_accessLevel = 0;
		
		static protected $_templateFile = 'pages/acts.html';
		
		static public function Init($data){
			
			$data['acts'] = ActController::Search(
				array(
					'limit' => 10000,
					'activated' => 1
				)
			);
			
			$data['ordered'] = array();
			foreach($data['acts'] as $Act){
				$firstLetter = substr($Act['title'], 0, 1);
				$data['ordered'][ $firstLetter ][ $Act['title'] ] = $Act['id'];
			}
			
			ksort($data['ordered']);
			foreach($data['ordered'] as &$titles){
				ksort($titles);
			}
			
			self::SetData($data);
			
		}
		
	}