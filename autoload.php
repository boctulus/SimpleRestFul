<?php

	function my_autoload ($class_name)
	{
		$things = ['Controller', 'Model'];
		foreach ($things as $thing) {
		
			if($pos = strpos($class_name, $thing)!==false){		

				$path = substr($class_name, 0, strlen($class_name)- strlen($thing)). '.php';
				$path = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $path));
				$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);

				if( file_exists($path) == false ) {
	                return false;
				}
				
	        	require($path);
			}

		}
    }
	spl_autoload_register("my_autoload");
	
	