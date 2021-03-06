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

	class Home extends Module_Controller
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function index($noun = null)
		{
			if( $noun == null )
				$noun = "World";

			echo "Hello $noun!";
		}
	};
