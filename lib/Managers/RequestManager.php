<?php


	use SatanBarbaraAPI\AJAX;
	
	abstract class RequestManager {
		
		static public function Init($requestParams){
			
			$appConfig = SatanBarbaraApp::GetConfig();
			
			$data = $requestParams['params'];

			DebugManager::Log("Got a ".$requestParams['request']['action']." request!", '@');
		

			DebugManager::Log("Got some data");
			DebugManager::Log( $data );
							
			$requestName = ucfirst($requestParams['request']['action']) .'Request';
	
			DebugManager::Log("Searching for view with class name: " . $requestName);
		
		
			if ($requestName::HasAccess( SessionManager::GetAccessLevel() )){
			
				$response = $requestName::Init($data);
			
			} else {
				DebugManager::Log("looks like this page requires auth but user isn't authenticated!");
				RouteManager::GoToPageURI('login', array("message" => "That page requires authenticated access!") );
			}
	
			if (isset($data['_format']) && $data['_format'] == 'json'){				
				self::AJAXResponse($response);
			} else {
				
				$format = isset($data['_format']) ? $data['_format'] : 'html';
				if (isset($data['_redirect'])){
					$redirect = $data['_redirect'];
				} else if (isset($requestName::$defaultRedirect)) {
					$redirect = $requestName::$defaultRedirect;
				} else {
					$redirect = 'home'; // $requestParams['uri']; <-- can't do this, it will cause circular loop because it's getting current URI and not last URI.
				}
			
				$redirectURI = $redirect;
				
				$HTTPVars = array();
				if ($response->message != 'Success!'){
					if ($response->code){
						$HTTPVars['error'] = $response->message;
					} else {
						$HTTPVars['success'] = $response->message;
					}
				}
				
				RouteManager::GoToPageURI( $redirectURI, $HTTPVars );
			}

		}
		
		static public function AJAXResponse($data, $message = 'Success!', $code = 1, $wrap = true){
			if ($data instanceof ResponseObject){
				
				if ($data->wrap){
					$response = json_encode(
						array(
							'data' => $data->data,
							'status' => array(
								'code' => $data->code,
								'message' => $data->message
							)
						)
					);
				} else {
					$response = json_encode($data->data);
				}

			} else if ($wrap){
				$response = json_encode(
					array(
						'data' => $data,
						'status' => array(
							'code' => $code,
							'message' => $message
						)
					)
				);
			} else {
				$response = json_encode($data);
			}
			
			echo $response;
			
			exit();
		}

	}