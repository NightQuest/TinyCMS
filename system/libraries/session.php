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

	class Session
	{
		const STATE_STARTED = TRUE;
		const STATE_NOT_STARTED = FALSE;

		private $id = "";
		private $state = self::STATE_NOT_STARTED;
		private static $singleton;

		public function __construct()
		{
			$this->id = session_id();
		}

		// Start the session, and store the state.
		public function initialize()
		{
			if( $this->state != self::STATE_STARTED )
				$this->state = session_start();
			return $this->state;
		}

		public function getId() { return $id; }

		// Function for Singleton-ing the class. This way we always are using the same instance.
		public static function getSingleton()
		{
			if( !isset(self::$singleton) )
				self::$singleton = new self;
			self::$singleton->initialize();
			return self::$singleton;
		}

		public function __set($name, $value)
		{
			$_SESSION[$name] = $value;
		}

		public function __get($name)
		{
			// Check to make sure the key actually exists
			// Otherwise by using $_SESSION[] we might create an empty key
			if( array_key_exists($name, $_SESSION) )
				return $_SESSION[$name];
		}

		public function __unset($name)
		{
			unset($_SESSION[$name]);
		}

		// Destroy our session, and return the state.
		public function destroy()
		{
			if( $this->state == self::STATE_STARTED )
			{
				session_unset();
				$this->state = !session_destroy();
				return !$this->state;
			}
			return FALSE;
		}
	};
