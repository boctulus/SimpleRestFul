<?php
	error_reporting(E_ALL);

	require_once 'config/constants.php';
	require_once 'autoload.php';	
	require_once "core/front_controller.php";
	require_once 'libs/factory.php';; 
	include_once "vendor/autoload.php";
	
	\Core\FrontController::resolve();
	
	



