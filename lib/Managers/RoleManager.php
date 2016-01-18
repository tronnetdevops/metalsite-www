<?php
	
	abstract class RoleManager {
		
		const ADMIN_ROLE = 3;
		
		static private $_roles = array(
			0 => 'beta',
			1 => 'user',
			2 => 'manager',
			3 => 'admin'
		);
		
		static public function GetAccessLevel(){
			/**
			 * @todo eventually do priv lev checks too
			 */
			return static::$account['role'];
		}
		
		static public function IsBetaAccount(){
			DebugManager::Log("Checking session account if beta account", '@');
			
			if (isset(static::$account['role'])){
				return (self::$_roles[ static::$account['role'] ] == 'beta' || static::$account['id'] == '0');
			} else {
				return false;
			}
		}
	}