<?php
	/*
		Written by Ellie
		Started January 6th, 2018
	*/

	class System
	{
		function __construct()
		{

		}
	};

	function &get_system()
	{
		static $sys = null;

		if( $sys === null )
			$sys = new System;

		return $sys;
	}
?>
