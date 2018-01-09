<?php
    /*
        Written by Ellie
        Started January 6th, 2018
    */

    // Configure
    $system_path = "system";
    $application_path = "application";

    // Resolve Paths
    define('BASE_PATH', str_replace("\\", "/", realpath('.')).'/');
    define('APPLICATION_PATH', BASE_PATH.$application_path.'/');
    define('SYSTEM_PATH', BASE_PATH.$system_path.'/');
	unset($system_path);
	unset($application_path);

    // Initialize System
    require_once(SYSTEM_PATH.'system.php');
    $System = get_system();

?>
