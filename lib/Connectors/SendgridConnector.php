<?php
/**
 * @link https://github.com/sendgrid/sendgrid-php
 */
	abstract class SendgridConnector {
	    /**
	     * Class Properties
	     */
    
	    /**#@+
	     * @static
	     * @access private
	     */
    
		static private $_templates = array(
			'rewardRedeemed' => 'b893c0c5-a034-45b7-8fa9-00142c48fed8',
			'redemptionsVoucher' => 'b893c0c5-a034-45b7-8fa9-00142c48fed8',
			'redemptionsCode' => 'c4efb72f-c376-4819-9fab-b7d71dcb6db2',
			'programActivationRequest' => '05d22112-a3c7-4aa9-8b9c-d57e4c6f91da',
			'accountNotFound' => 'f1ecee61-e233-4142-82da-8639dcc0fe39',
			'welcomeNewUser' => 'bf9858dc-16ec-4e31-8ff0-e81879fa7f2c'
		);
		
		static private $_interface;
		
	    /**
		 * Credentials for making requests to SendGrid. Usually loaded from
		 * a config file.
		 * @var array
		 */
		static private $_credentials;
		
		static private $dry_run = false;
		
	    /**#@-*/
		
	    /**
	     * Class Methods
	     */
		
	    /**#@+
		 * @static
	     * @access public
	     */
		
		static public function Init(){
			DebugManager::Log("Initializing Sendgrid Email Connector", '@');
			
			$appConfig = SatanBarbaraApp::GetConfig();
			self::SetCreds($appConfig['sendgrid']);
			
			DebugManager::Log("Creating new SendGrid Interface!");
			$interface = new SendGrid(self::$_credentials['username'], self::$_credentials['password']);
			self::SetInterface( $interface );
			
			if (SATANBARBARA_CURRENT_ENVIRONMENT == 'dev'){
				self::$dry_run = true;
			}

			return true;
		}
    
	    /**
	     * Set SendGrid Credentials
	     *
	     * ## Overview
	     *
	     * @uses SendgridConnector
		 *
		 * @see NotificationManager
	     *
		 * @param array An array of creds for SendGrid API.
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function SetCreds(array $creds){
			DebugManager::Log("Setting SendGrid Credentials", '@');
			DebugManager::Log($creds);
			
			self::$_credentials = $creds;
						
			return true;
		}
		
	    /**
	     * Get SendGrid Credentials
	     *
	     * ## Overview
	     *
	     * @uses SendgridConnector
		 *
		 * @see NotificationManager
	     *
		 * @return array An array of creds for SendGrid API.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function GetCreds(){
			DebugManager::Log("Getting SendGrid Credentials", '@');
			
			if (!isset(self::$_credentials)){
				self::Init();
			}
			
			DebugManager::Log(self::$_credentials);
			
			return self::$_credentials;
		}
		
	    /**
	     * Set SendGrid Credentials
	     *
	     * ## Overview
	     *
	     * @uses SendgridConnector
		 *
		 * @see NotificationManager
	     *
		 * @param array An array of creds for SendGrid API.
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function SetInterface(SendGrid $interface){
			DebugManager::Log("Setting SendGrid Interface", '@');
			DebugManager::Log(self::$_interface);
			
			self::$_interface = $interface;
						
			return true;
		}
		
	    /**
	     * Get SendGrid Credentials
	     *
	     * ## Overview
	     *
	     * @uses SendgridConnector
		 *
		 * @see NotificationManager
	     *
		 * @return array An array of creds for SendGrid API.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function GetInterface(){
			DebugManager::Log("Getting SendGrid Interface", '@');
			
			if (!isset(self::$_interface)){
				self::Init();
			}
			
			DebugManager::Log(self::$_interface);
			
			return self::$_interface;
		}
		
		static public function SendWelcomeTemplate($params){
			DebugManager::Log("Sending a Redemption Email Template!", '@');
			DebugManager::Log($params); 
			
			$interface = self::GetInterface();
			
			DebugManager::Log("Setting Sendgrid Email Headers");
			$email = new SendGrid\Email();
			$email
			    ->addTo($params['toEmail'])
			    ->setFromName($params['fromName'])
			    ->setFrom($params['fromEmail'])
			    ->setSubject('Welcome to Metalsite')
			    ->setText('To Metalsite! We hope you enjoy your stay and start loggin some visits!')
			    ->setHtml('To Metalsite! We hope you enjoy your stay and start loggin some visits!')

			    ->addSubstitution(':name', array( $params['name']) )

				->setTemplateId( self::$_templates['welcomeNewUser'] );
				
			
			DebugManager::Log($email);
			
			DebugManager::Log("Sending Sendgrid Email!!");
			
			$response = true;
			if (!self::$dry_run){
				$response = $interface->send( $email );
			}
			
			DebugManager::Log("Got a response!");
			DebugManager::Log($response);
			
			return $response;
		}
		
		static public function NoAccountFound($params){
			DebugManager::Log("Sending a Redemption Email Template!", '@');
			DebugManager::Log($params); 
			
			$interface = self::GetInterface();
			
			DebugManager::Log("Setting Sendgrid Email Headers");
			$email = new SendGrid\Email();
			$email
			    ->addTo($params['toEmail'])
			    ->setFromName($params['fromName'])
			    ->setFrom($params['fromEmail'])
			    ->setSubject('Looking to join Metalsite?')
			    ->setText('We noticed you tried to login to Metalsite, but your studio either can\'t be found or hasn\'t been activated yet!')
			    ->setHtml('We noticed you tried to login to Metalsite, but your studio either can\'t be found or hasn\'t been activated yet!')

				->setTemplateId( self::$_templates['accountNotFound'] );
				
			
			DebugManager::Log($email);
			
			DebugManager::Log("Sending Sendgrid Email!!");
			
			$response = true;
			if (!self::$dry_run){
				$response = $interface->send( $email );
			}			
			DebugManager::Log("Got a response!");
			DebugManager::Log($response);
			
			return $response;
		}
		
		static public function ProgramActivationRequest($params){
			DebugManager::Log("Sending a Redemption Email Template!", '@');
			DebugManager::Log($params); 
			
			$interface = self::GetInterface();
			
			DebugManager::Log("Setting Sendgrid Email Headers");
			$email = new SendGrid\Email();
			$email
			    ->addTo($params['toEmail'])
			    ->setFromName($params['fromName'])
			    ->setFrom($params['fromEmail'])
			    ->setSubject('Looking to join Metalsite?')
			    ->setText('We\'re excited to have you aboard the Metalsite platform!')
			    ->setHtml('We\'re excited to have you aboard the Metalsite platform!!')

			    ->addSubstitution(':name', array( $params['name']) )

				->setTemplateId( self::$_templates['programActivationRequest'] );
				
			
			DebugManager::Log($email);
			
			DebugManager::Log("Sending Sendgrid Email!!");
			
			$response = true;
			if (!self::$dry_run){
				$response = $interface->send( $email );
			}			
			DebugManager::Log("Got a response!");
			DebugManager::Log($response);
			
			return $response;
		}
		
		static public function SendRedemptionTemplate($params){
			DebugManager::Log("Sending a Redemption Email Template!", '@');
			DebugManager::Log($params);
			
			$interface = self::GetInterface();
			
			DebugManager::Log("Setting Sendgrid Email Headers");
			$email = new SendGrid\Email();
			$email
			    ->addTo($params['toEmail'])
			    ->setFromName($params['fromName'])
			    ->setFrom($params['fromEmail'])
			    ->setSubject($params['subject'])
			    ->setText("You used ".$params['points']." visits to redeem")
			    ->setHtml("You used ".$params['points']." visits to redeem")

			    ->addSubstitution(':title', array($params['title']) )
			    ->addSubstitution(':id', array($params['id']) )
			    ->addSubstitution(':advertiser_title', array($params['advertiser_title']) )
			    ->addSubstitution(':advertiser_subtitle', array($params['advertiser_subtitle']) )
			    ->addSubstitution(':description', array( $params['description']) )
			    ->addSubstitution(':advertiser_description', array($params['advertiser_description']) )
			    ->addSubstitution(':subtitle', array( $params['subtitle']) )
			    ->addSubstitution(':name', array( $params['toName']) )
			    ->addSubstitution(':offer', array( $params['title']) )
			    ->addSubstitution(':instructions', array( $params['instructions']) )
			    ->addSubstitution(':points', array( $params['points'] ) )
				->setTemplateId( self::$_templates['rewardRedeemed'] );
			
			DebugManager::Log($email);
			
			DebugManager::Log("Sending Sendgrid Email!!");
						
			$response = true;
			if (!self::$dry_run){
				$response = $interface->send( $email );
			}			
			DebugManager::Log("Got a response!");
			DebugManager::Log($response);
			
			return $response;
		}
		
		static public function SendEmail($params){
			$interface = self::GetInterface();
			
			$email = new SendGrid\Email();
			$email
			    ->addTo($params['toEmail'])
			    ->setFromName($params['fromName'])
			    ->setFrom($params['fromEmail'])
			    ->setSubject($params['subject'])
			    ->setText($params['text'])
			    ->setHtml($params['html']);

			$response = true;
			if (!self::$dry_run){
				$response = $interface->send( $email );
			}			
			return $response;
		}
		
	    /**#@-*/
		
	}