<?php
	error_reporting(E_ALL);

	require_once "core/front_controller.php";
	require_once 'config/constants.php';
	require 'autoload.php';
	include "vendor/autoload.php";
	

	FrontController::resolve();
	
	



