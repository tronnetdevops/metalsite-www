<?php
/**
 * @brief Notification Manager
 *
 * ## Overview
 * This file exists as
 *
 * @package SatanBarbara
 * @subpackage Application
 * @category Managers
 * @version 0.7.2b
 * @since 0.5.1b
 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
 * @copyright Copyright (c) 2015, Sean Murray
 *
 * @see SatanBarbaraApp
 * @abstract
 */

	abstract class NotificationManager {

	    /**
	     * Class Methods
	     */
		
	    /**#@+
		 * @static
	     * @access public
	     */
		
	    /**
	     * Send A Reward Redeemed Email via SendGrid
	     *
	     * ## Overview
	     *
	     * @uses NotificationManager
		 *
		 * @see SatanBarbaraApp
	     *
		 * @param array The HTTP params for SendGrid API; such as message, to, etc.
	     * @return string A response string for request from SendGrid API.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function RewardRedeemed(array $data){
			return SendgridConnector::SendRedemptionTemplate($data);
		}
		
	    /**
	     * Send A Welcome Email via SendGrid
	     *
	     * ## Overview
	     *
	     * @uses NotificationManager
		 *
		 * @see SatanBarbaraApp
	     *
		 * @param array The HTTP params for SendGrid API; such as message, to, etc.
	     * @return string A response string for request from SendGrid API.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function SendWelcomeTemplate(array $data){
			return SendgridConnector::SendWelcomeTemplate($data);
		}
		
		
	    /**
	     * Send A Welcome Email via SendGrid
	     *
	     * ## Overview
	     *
	     * @uses NotificationManager
		 *
		 * @see SatanBarbaraApp
	     *
		 * @param array The HTTP params for SendGrid API; such as message, to, etc.
	     * @return string A response string for request from SendGrid API.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function NoAccountFound(array $data){
			return SendgridConnector::NoAccountFound($data);
		}
			
	    /**
	     * Send A Welcome Email via SendGrid
	     *
	     * ## Overview
	     *
	     * @uses NotificationManager
		 *
		 * @see SatanBarbaraApp
	     *
		 * @param array The HTTP params for SendGrid API; such as message, to, etc.
	     * @return string A response string for request from SendGrid API.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function ProgramActivationRequest(array $data){
			return SendgridConnector::ProgramActivationRequest($data);
		}
		
	    /**
	     * Send A Notification via SendGrid
	     *
	     * ## Overview
	     *
	     * @uses NotificationManager
		 *
		 * @see SatanBarbaraApp
	     *
		 * @param array The HTTP params for SendGrid API; such as message, to, etc.
	     * @return string A response string for request from SendGrid API.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function Send(array $params){
			DebugManager::Log("Sending an email via SendGrid", '@');
			DebugManager::Log($params);
			
			return SendgridConnector::SendEmail($params);
		}
				
	    /**#@-*/
		
	}