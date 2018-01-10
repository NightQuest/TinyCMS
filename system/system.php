<?php
	/*
		Written by Ellie
		Started January 6th, 2018
	*/

	// Deny Access to this file directly
	if( !defined('BASE_PATH') || !defined('SYSTEM_PATH') || !defined('APPLICATION_PATH') )
	{
		header('HTTP/1.0 403 Forbidden');
		die("Access Forbidden");
	}

	class System
	{
		private $classes = array();

		// The construct for System initializes all of our libraries
		private function __construct()
		{
			// Load the config class
			require_once(SYSTEM_PATH.'libraries/config.php');

			$this->classes['config'] = Config::getSingleton();
		}

		private function loadLibraries()
		{
			// Load all the libraries specified in the config
			foreach($this->classes['config']->libraries as $lib)
			{
				// Prevent config from being loaded & initialized twice
				if( strtolower($lib) == "config" )
					continue;

				// Make sure the library exists, then load & initialize it
				if( is_file(SYSTEM_PATH.'libraries/'.strtolower($lib).'.php') )
				{
					include_once(SYSTEM_PATH.'libraries/'.strtolower($lib).'.php');
					$tmp = new $lib;
					$this->classes[strtolower(get_class($tmp))] = $tmp;
				}
				else
					throw new Exception("$lib is not a valid library.");
			}
		}

		public function __get($name)
		{
			if( array_key_exists($name, $this->classes) )
				return $this->classes[$name];
			else
				throw new Exception("$name is not a loaded library.");
		}

		// Function for Singleton-ing the class. This way we always are using the same instance.
		public static function getSingleton()
		{
			static $singleton = null;
			if( $singleton === null )
			{
				$singleton = new self;
				$singleton->loadLibraries();
			}

			return $singleton;
		}
	};
