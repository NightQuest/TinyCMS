<?php
    /*
        Written by Ellie
        Started January 6th, 2018
    */

    // Paths
    define('BASE_PATH', realpath('.').'/');
    define('APPLICATION_PATH', BASE_PATH.'application/');
    define('SYSTEM_PATH', BASE_PATH.'system/');

    // Initialize System
    require_once(SYSTEM_PATH.'system.php');
    $System = get_system();


?>
