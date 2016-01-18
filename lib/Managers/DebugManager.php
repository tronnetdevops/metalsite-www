<?php
/**
 * @brief Debug Manager
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
 * @abstract
 */

	abstract class DebugManager {
	    /**
	     * Class Properties
	     */
    
	    /**#@+
	     * @static
	     * @access public
	     */
		
		static public $logFile;
    
	    /**
		 * Flag that determines if logs actually get recorded in logs. 
		 * @var bool
		 */
		static public $active = true;
		static public $level = 1;
				
		const ALERT_LEVEL = -1;

		const DEBUG_LEVEL = 1;
		const CRITICAL_LEVEL = 2;
		const WARNING_LEVEL = 3;
		const SYSTEM_LEVEL = 4;
		
		static private $_log = array();
		
	    /**#@-*/
		
	    /**
	     * Class Methods
	     */
		
	    /**#@+
		 * @static
	     * @access public
	     */
		
		static public function Init(){
			$appConfig = SatanBarbaraApp::GetConfig();
			
			self::SetLogFile($appConfig[ SATANBARBARA_CURRENT_ENVIRONMENT ]['basePath'] . $appConfig['logs']['app']['path']);
			
			return true;
		}
		
		static public function SetLogFile($logFile){
			if (!is_file($logFile) || !is_writable($logFile)){
				throw new Exception("Logfile either doesn't exist or isn't writable! Attempted location: " . $logFile);
			}
			
			self::$logFile = $logFile;
			
			return true;
		}
    	
		static public function Log($obj, $sep = false, $level = null){
			return self::CreateRecord($obj, $sep, 4);
		}
		
		static public function Status($obj, $sep = false, $level = null){
			return self::CreateRecord($obj, $sep, 4);
		}
		
		static public function Warning($obj, $sep = false, $level = null){
			return self::CreateRecord($obj, $sep, 3);
		}
		
		static public function Error($obj, $sep = false, $level = null){
			return self::CreateRecord($obj, $sep, 2);
		}
		
		static public function Debug($obj, $sep = false, $level = null){
			return self::CreateRecord($obj, $sep, 1);
		}
		
	    /**
	     * Log Message
	     *
	     * ## Overview
	     *
	     * @uses DebugManager
	     *
		 * @param mixed $message Either a string or an object to be logged.
	     * @return true Always unless fatal error or exception is thrown.
	     *
		 * @version 2015-07-05.1
		 * @since 0.5.1b
	     * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	     */
		static public function CreateRecord($message, $sep = false,  $level = null){
			$classes = array();
				
			if (!$level){
				$level = self::SYSTEM_LEVEL;
			}
			
			$trace = debug_backtrace();
			
			$function = $trace[1]['function'];
			$line = $trace[1]['line'];
			
			if (count($trace) >= 3){
				foreach($trace as $depth=>$cb){
					if ($depth >= 2){
						$classes[] = $cb['class'];
						
						if ($depth == 2){
							$function = $cb['function'];
							$line = $cb['line'];
						}
					}
				}
			} else {
				$classes = array('Main');
			}
			
			$class = implode('/', array_reverse($classes));
			
			$preamble = '[:'.$line.' @ '.$class.'::'.$function.']';
					
			if (self::$active && $level <= self::$level){
				
				if ($class == 'Main'){
					self::$_log[] = array(
						'preamble' => $preamble,
						'message' => '',
						'open' => true
					);
				}
				
				
				if (is_string($message)){
					self::$_log[] = array(
						'preamble' => $preamble,
						'message' => $message,
						'sep' => $sep
					);
				} else {
					self::$_log[] = array(
						'preamble' => $preamble,
						'message' => var_export( $message, true),
						'sep' => $sep
					);
				}
			}
			
			if (self::$logFile){
				$content = '';
				
				/**
				 * @todo will this cause a racecondition...?
				 */
				foreach(self::$_log as $entry){
					if (isset($entry['open']) && $entry['open']){
						$content .= PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;
					}
					
					if (isset($entry['sep']) && $entry['sep']){
						$content .= PHP_EOL.PHP_EOL.str_repeat($entry['sep'], 80) . PHP_EOL;
					}
					
					$content .= $entry['preamble'] . ' '. $entry['message'] . PHP_EOL;
				}
				
				if (!empty($content)){
					file_put_contents(self::$logFile, $content, FILE_APPEND | LOCK_EX);
				}
				
				self::$_log = array();
			}
			
			return true;
		}
		
	    /**#@-*/
		
	}