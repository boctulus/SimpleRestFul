<?php
	require_once 'helpers/strings.php';

	function my_autoload ($class_name)
	{
		//echo $class_name;
		
		if($pos = strpos($class_name, 'Controller')!==false){
			$file_name = strtolower(substr($class_name, 0, strlen($class_name)- 10)). '.php';
			$path = str_replace('/', DIRECTORY_SEPARATOR, CONTROLLERS_PATH . $file_name);

			if( file_exists($path) == false ) {
                return false;
			}
        	require($path);
		}

		if($pos = strpos($class_name, 'Model')!==false){
			$file_name = strtolower(substr($class_name, 0, strlen($class_name)- 5)). '.php';
			$path = str_replace('/', DIRECTORY_SEPARATOR, MODELS_PATH . $file_name);

			if( file_exists($path) == false ) {
                return false;
			}
        	require($path);
		}
    }
	spl_autoload_register("my_autoload");
	