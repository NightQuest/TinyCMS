<?php
	/*
		Written by Ellie
		Started January 9th, 2018
	*/

	// Deny Access to this file directly
	if( !defined('BASE_PATH') || !defined('SYSTEM_PATH') || !defined('APPLICATION_PATH') )
	{
		header('HTTP/1.0 403 Forbidden');
		die("Access Forbidden");
	}

	class HMVC
	{
		private $modules = array();

		// Load all of our pages in the construct
		public function __construct()
		{
			//$config = new Config;
			$config = System::getSingleton()->config;

			// Only load modules that are specified in the config file
			if( is_array($config->modules) )
			{
				foreach( $config->modules as $module )
				{
					// Make sure the module actually exists
					$module_file = APPLICATION_PATH.'modules/'.strtolower($module).'/controller/'.strtolower($module).'.php';
					if( file_exists($module_file) && is_file($module_file) )
					{
						include_once($module_file);

						// Only add the module to our loaded modules if it has an index function that's callable
						$tmp = new $module;
						if( method_exists($tmp, 'index') && is_callable($tmp, 'index') )
							$this->modules[$module] = $tmp;
					}
					else
					{
						throw new Exception("$module is not a valid module.");
					}
				}
			}
		}

		// Provide a getter for the modules - we're skipping setters and callers for now.
		public function __get($name)
		{
			foreach( $this->modules as $module )
			{
				if( $name == get_class($module) )
					return $module;
			}
		}
	};