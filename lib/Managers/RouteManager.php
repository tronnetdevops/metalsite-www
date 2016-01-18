<?php
	
	abstract class RouteManager {
		static private $_requestParams;
		
		static private $_defaultPage = 'home';
		static private $_defaultLoggedInPage = 'home';
		static private $_errorPage = '404';
		
		static private $_allowedPages = array(
			'splash' => array(),
			'register' => array(),
			'login' => array(),
			'home' => array(),
			'events' => array(),
			'event' => array(
				'params' => array('id')
			),
			'acts' => array(),
			'act' => array(
				'params' => array('id')
			),
			
			'venues' => array(),
			'venue' => array(
				'params' => array('id')
			),
			
			'account' => array(
				'params' => array('id')
			),

			'community' => array(),
			'404' => array(
				'rewrite' => 'pageNotFound'
			),

			'request' => array(
				'allowed' => array(
					'login' => array(),
					'forgotpassword' => array(),
					'logout' => array(),
					'register' => array(),
					'login' => array(),
					
					'createEvent' => array(),
					'updateVenueLocation' => array()
					
				)
			)
		);
		
		static public function Init(){
			$params = self::GetParams();
			
			if ($params['type'] == 'request'){
				RequestManager::Init($params);
			} else {
				PageManager::Init($params);
			}
			
		}
		
		static public function GetParams(){
			if (!isset(self::$_requestParams)){
			
				$requestParams = array('type' => 'page');
			
				$cleanReqParams = explode('?', $_SERVER['REQUEST_URI']);
			
				$delReqParams = explode('/', $cleanReqParams[0]);
			
				/**
				 * Remove initial slash
				 */
				array_shift($delReqParams);
			
				/**
				 * Remove any trailing GET params from last item. (this could be the first item in many cases).
				 */
				
				$pageURI = implode('/', $delReqParams);
				

				
				$reqPageName = $delReqParams[0];
				$getData = $_REQUEST;
			
				/**
				 * @todo Add some fault handling here.
				 */		
				if (empty($reqPageName)){
					$requestParams['page'] = self::$_defaultPage;
					$extended = self::$_allowedPages[ self::$_defaultPage ];
					if (isset($extended['rewrite'])){
						$requestParams['page'] = $extended['rewrite'];
						unset($extended['rewrite']);
					}
				} else if (!isset(self::$_allowedPages[ $reqPageName ])) {
					$requestParams['page'] = '404';
					$extended = self::$_allowedPages['404'];
					if (isset($extended['rewrite'])){
						$requestParams['page'] = $extended['rewrite'];
						unset($extended['rewrite']);
					}
				} else {
					if ($reqPageName == 'request'){
						
						if (isset($delReqParams[1]) && isset(self::$_allowedPages['request']['allowed'][ $delReqParams[1] ])){
							$requestAction = $delReqParams[1];
							$requestExtended = self::$_allowedPages['request']['allowed'][ $requestAction ];
							
							if (isset($requestExtended['rewrite'])){
								$requestAction = $requestExtended['rewrite'];
							}
							
							$requestParams['type'] = 'request';
							$requestParams['request'] = array(
								'action' => $requestAction,
								'params' => array_merge($_REQUEST, $requestExtended),
								'routes' => $delReqParams
							);
							
							
							if (isset($delReqParams[2])){
								$requestSecondPositionItem = $delReqParams[2];
								
								/**
								 * Checking if third param is an ID.
								 */
								if (is_numeric($requestSecondPositionItem)) {
									$requestID = $requestSecondPositionItem;
									DebugManager::Log("GOT AN ID FOR THIS PAGE!");
								
							
									$requestParams['id'] = $requestID;
								
									/**
									 * Beware... this may lead to unintended overwrites...
									 */
							
									if (!isset($getData['id'])){
										$getData['id'] = $requestID;
									}
									
									$extended = $requestExtended;
									
								} else if (isset(self::$_allowedPages[ $requestSecondPositionItem ]) ) {
									/**
									 * Using 3rd place param as redirect method.
									 */	
									$requestParams['page'] = $requestSecondPositionItem;
									$extended = self::$_allowedPages[ $requestSecondPositionItem ];									
								} else {
									$extended = $requestExtended;
								}
						
						} else {
							$requestParams['page'] = '404';
							$extended = self::$_allowedPages['404'];
							
							if (isset($extended['rewrite'])){
								$requestParams['page'] = $extended['rewrite'];
								unset($extended['rewrite']);
							}
						}
					} else {
						$requestParams['page'] = $reqPageName;
						$extended = self::$_allowedPages[ $reqPageName ];
						
						if (isset($extended['rewrite'])){
							$requestParams['page'] = $extended['rewrite'];
							unset($extended['rewrite']);
						}
						
						if (isset($delReqParams[1]) && is_numeric($delReqParams[1])){
							DebugManager::Log("GOT AN ID FOR THIS PAGE!");
							$extended['id'] = $delReqParams[1];
							/**
							 * Beware... this may lead to unintended overwrites...
							 */
							if (!isset($getData['id'])){
								$getData['id'] = $delReqParams[1];
							}
						}
					}
				}
				
				$requestParams['uri'] = $pageURI;
				
				$requestParams['params'] = array_merge($getData, $extended);
						
				self::$_requestParams = $requestParams;
			}
			
			return self::$_requestParams;
		}
		
		static public function GoToPageURI($page, $getParams = array(), $domain = null){
			$HTTPVars = '';
			
			$appConfig = SatanBarbaraApp::GetConfig();
			
			if (!isset($domain)){
				$domain = $appConfig[ SATANBARBARA_CURRENT_ENVIRONMENT ]['baseURI'];
			}
			
			$domain = preg_replace('/\/+$/', '', $domain) . '/';
			$page = preg_replace('/\/+$/', '', $page) . '/';
			
			if (is_array($getParams) && count($getParams)){
				$vars = array();
				
				foreach ($getParams as $name=>$val){
					$vars[] = urlencode($name) . '=' . rawurlencode($val);
				}
				
				if (substr($pageURI, -1) !== '/'){
					$pageURI .= '/';
				}
				
				$HTTPVars .= '?' . implode('&', $vars);
			}
			
			header('Location: ' . $domain . $page . $HTTPVars);
			
			exit(0);
		}
	}
?>