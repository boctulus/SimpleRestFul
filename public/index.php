<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
	require_once __DIR__ . '../../vendor/autoload.php';
    
    use simplerest\core\Route;
    use simplerest\core\FrontController;

    $config = include __DIR__ . '../../config/config.php';

    if ($config['ROUTER']){        
        include __DIR__ . '../../config/route.php';
        Route::resolve();
    } 

    if ($config['FRONT_CONTROLLER']){        
        FrontController::resolve();
    } 

    //throw new Exception('There is no Router or FrontController enabled!');

	



