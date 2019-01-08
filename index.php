<?php
	error_reporting(E_ALL);

	require_once "config/config.php";
	include "vendor/autoload.php";
	include "core/front_controller.php";

	$c = $_GET['c'] ?? 'products';
	$a = $_GET['a'] ?? 'index';
	
	FrontController::resolve($c,$a,$_REQUEST);
	
	



