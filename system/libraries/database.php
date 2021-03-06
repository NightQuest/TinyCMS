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

	class Database extends mysqli
	{
		private $system = null;

		public function __construct()
		{
			$this->system = System::getSingleton();
			$config = $this->system->config;

			if( isset($config->database->hostname) &&
				isset($config->database->username) &&
				isset($config->database->password) &&
				isset($config->database->database) )
			{
				parent::__construct($config->database->hostname, $config->database->username, $config->database->password, $config->database->database);

				if( mysqli_connect_error() )
					throw new Exception('Error ['.mysqli_connect_errno().']: '.mysqli_connect_error());
			}
			else
				throw new Exception("Error: Invalid database config values");
		}

		// Allow PQueries that are auto-escaped.
		// Ex: $db->pquery("SELECT * FROM `table` WHERE `id` = '?'", $id)
		public function pquery($query, $args = '', $resultmode = MYSQLI_STORE_RESULT)
		{
			// Default to acting like mysqli::query()
			$real_query = $query;

			// If we have a single argument, turn it into an array
			if( !is_array($args) && strlen($args) )
				$args = array($args);

			if( is_array($args) )
			{
				// Escape each argument
				for( $x = 0; $x < count($args)-1; $x++ )
					$args[$x] = parent::escape_string($args[$x]);

				// Find all our ?s, and make sure the arg count matches
				$parts = explode('?', $query);
				if( count($parts)-1 != count($args) )
					throw new Exception('Error: invalid count on pquery');

				// Replace all the ?s with the escaped arguments.
				$real_query = '';
				for( $x = 0; $x < count($parts)-1; $x++ )
					$real_query .= $parts[$x] . $args[$x];
				$real_query .= end($parts);
			}

			return parent::query($real_query, $resultmode);
		}
	};
