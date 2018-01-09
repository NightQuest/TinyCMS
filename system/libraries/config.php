<?php
	/*
		Written by Ellie
		Started January 8th, 2018
	*/

	// Deny Access to this file directly
	if( !defined('BASE_PATH') || !defined('SYSTEM_PATH') || !defined('APPLICATION_PATH') )
	{
		header('HTTP/1.0 403 Forbidden');
		die("Access Forbidden");
	}

	class Config
	{
		private static $config;
		private static $protectedConfigs;

		public function __construct()
		{
			if( !isset(self::$config) )
			{
				// Make sure the config file actually exists
				if( !is_file(APPLICATION_PATH.'config/config.php') )
					die("Missing application config file.");

				include_once APPLICATION_PATH.'config/config.php';
				if( isset($config) && is_array($config) )
				{
					self::$config = $config;
					unset($config);
				}
			}

			if( !isset(self::$protectedConfigs) )
			{
				include_once APPLICATION_PATH.'config/protected_configs.php';
				if( isset($protectedConfigs) && is_array($protectedConfigs) )
				{
					self::$protectedConfigs = $protectedConfigs;
					unset($protectedConfigs);
				}
			}
		}

		public function __get($name)
		{
			if( array_key_exists($name, self::$config) )
				return self::$config[$name];
			else
				throw new Exception("$name does not exist.");
		}

		public function __set($name, $value)
		{
			if( !array_key_exists($name, self::$protectedConfigs) || self::$protectedConfigs[$name] === false )
				self::$config[$name] = $value;
			else
				throw new Exception("$name is protected, and cannot be modified.");
		}
	};
