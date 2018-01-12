<?php
	/*
		Written by Ellie
		Started January 11th, 2018
	*/

	// Deny Access to this file directly
	if( !defined('BASE_PATH') || !defined('SYSTEM_PATH') || !defined('APPLICATION_PATH') )
	{
		header('HTTP/1.0 403 Forbidden');
		die("Access Forbidden");
	}

	abstract class Module_Controller
	{
		protected $system = null;

		public function __construct()
		{
			$this->system = System::getSingleton();
			if( $this->system == null )
				throw new ErrorException('Module_controller::system is null');
		}
	};
