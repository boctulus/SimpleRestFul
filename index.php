<?php
	error_reporting(E_ALL);

	require_once "core/front_controller.php";
	include "vendor/autoload.php";
	require_once 'config/constants.php';

	
	$class_paths = [
		CONTROLLERS_PATH,
		MODELS_PATH,
		LIBS_PATH,
	];
	
	foreach ($class_paths as $_path){
		set_include_path(get_include_path() . PATH_SEPARATOR . CONTROLLERS_PATH);
	}
	   
	function my_autoload ($classname)
	{
		if($classname == 'MyController')
			$filename = 'my_controller';
		else
			$filename = strtolower(str_replace('_','',str_replace('Controller','',$classname)));
	   
		include($filename.'.php');
			
    }
	spl_autoload_register("my_autoload");
	

	FrontController::resolve();
	
	



