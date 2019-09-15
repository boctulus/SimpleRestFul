<?php
	error_reporting(E_ALL);

	require_once "core/front_controller.php";
	require_once 'config/constants.php';
	require_once 'helpers/factory.php';; 
	require_once 'autoload.php';
	include_once "vendor/autoload.php";
	
	FrontController::resolve();
	
	



