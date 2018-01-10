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
		private $config;
		private $protectedConfigs;

		private function __construct()
		{
			// Make sure the config file actually exists
			if( !file_exists(APPLICATION_PATH.'config/config.json') ||
				!is_file(APPLICATION_PATH.'config/config.json') )
				die("Missing application config file.");

			$json_file = file_get_contents(APPLICATION_PATH.'config/config.json');
			if( $json_file != false )
				$this->config = json_decode($json_file);

			// Only load the protected config if it exists
			if( file_exists(APPLICATION_PATH.'config/protected_config.json') &&
				is_file(APPLICATION_PATH.'config/protected_config.json') )
			{
				$json_file = file_get_contents(APPLICATION_PATH.'config/protected_configs.json');
				if( $json_file != false )
					$this->protectedConfigs = json_decode($json_file);
			}
		}

		public function __get($name)
		{
			if( array_key_exists($name, $this->config) )
				return $this->config->$name;
			else
				throw new Exception("$name does not exist.");
		}

		public function __set($name, $value)
		{
			if( !array_key_exists($name, $this->protectedConfigs) || $this->protectedConfigs->$name === false )
				$this->config->$name = $value;
			else
				throw new Exception("$name is protected, and cannot be modified.");
		}

		// Function for Singleton-ing the class. This way we always are using the same instance.
		public static function getSingleton()
		{
			static $singleton = null;
			if( $singleton === null )
				$singleton = new self;

			return $singleton;
		}
	};
