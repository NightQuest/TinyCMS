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

	class HMVC extends Library
	{
		private $modules = array();

		// Load all of our pages in the construct
		public function __construct()
		{
			// Call the parents ctor
			parent::__construct();

			// Register HMVC as a page handler
			$this->system->registerPageHandler($this);

			//$config = new Config;
			$config = $this->system->config;

			// Only load modules that are specified in the config file
			if( is_array($config->modules) )
			{
				foreach( $config->modules as $module )
				{
					// Make sure the module actually exists
					$module_file = APPLICATION_PATH.'modules/'.strtolower($module).'/controller/'.strtolower($module).'.php';
					if( file_exists($module_file) && is_file($module_file) )
					{
						include_once $module_file;

						// Only add the module to our loaded modules if it has an index function that's callable
						$tmp = new $module;
						if( method_exists($tmp, 'index') && is_callable(array($tmp, 'index')) )
							$this->modules[strtolower($module)] = $tmp;
					}
					else
					{
						throw new Exception("$module is not a valid module.");
					}
				}
			}
		}

		// Function for parsing the path and handing everything off to the modules
		public function handlePage()
		{
			// Default to home_module
			$module = strtolower($this->system->config->home_module);
			$path = 'index'; // "/"
			$args = array();
			$error = false;

			// if we're not on the default page, figure out where we are
			if( $_SERVER['QUERY_STRING'] != '/' )
			{
				$query = explode('/', $_SERVER['QUERY_STRING']);
				$query_elements = count($query);

				// Make sure we have this page loaded
				if( $query_elements >= 2 && strlen($query[1]) )
				{
					$module = strtolower($query[1]);

					if( array_key_exists($module, $this->modules) )
					{
						// Also make sure the function exists
						if( $query_elements >= 3 && strlen($query[2]) )
						{
							$path = $query[2];

							if( method_exists($this->modules[$module], $path) &&
								is_callable(array($this->modules[$module], $path)) )
							{
								// Build the arguments for the function
								if( $query_elements >= 4 )
								{
									for( $x = 3; $x <= $query_elements-1; $x++ )
										$args[] = $query[$x];
								}
							}
							else
								$error = array(404, $module, $path);
						}
					}
					else
						$error = array(404, $module);
				}
			}

			// If we have an error (module or path don't exist), display it
			if( $error != false )
			{
				// Allow module to override if present (errorHandler)
				if( array_key_exists('errorhandler', $this->modules) )
				{
					if( method_exists($this->modules[$module], 'handle'.$error[0]) &&
						is_callable(array($this->modules[$module], 'handle'.$error[0])) )
					{
						call_user_func_array(array($this->modules[$module], 'handle'.$error[0]), $error);
					}
				}
				else
				{
					// Produce a somewhat clear error message.
					// Ex: 404 - home/index
					$errorStr = "Error: $error[0]";
					$error_count = count($error);
					if( $error_count >= 2 )
					{
						$errorStr .= ' - ';
						for( $x = 1; $x < $error_count; $x++ )
						{
							if( $x != 1 )
								$errorStr .= '/';
							$errorStr .= $error[$x];
						}
					}
					throw new Exception($errorStr);
				}
			}
			else if( // If we have a valid module and path (function), execute it
				array_key_exists($module, $this->modules) &&
				method_exists($this->modules[$module], $path) &&
				is_callable(array($this->modules[$module], $path)) )
			{
				call_user_func_array(array($this->modules[$module], $path), $args);
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
