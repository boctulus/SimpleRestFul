<?php
	error_reporting(E_ALL); // donde setearlo?

	require_once "core/front_controller.php";
	require_once 'config/constants.php';
	require_once 'helpers/factory.php';; 
	require 'autoload.php';
	include "vendor/autoload.php";
	
	front_controller()->resolve();
	
	



