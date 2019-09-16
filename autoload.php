<?php
	//require_once 'helpers/strings.php';

	function my_autoload ($class_name)
	{
		$things = ['Controller', 'Model'];
		foreach ($things as $thing) {
		
			if($pos = strpos($class_name, $thing)!==false){			
				//echo "LEN: ".strlen($thing).'<br/>';

				$path = substr($class_name, 0, strlen($class_name)- strlen($thing)). '.php';

				//echo "$thing<br/>";	
				//echo "CLASS_NAME: $class_name <br/>";
				//echo "PATH: $path <br/>";

				$path = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $path));
				$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);

				if( file_exists($path) == false ) {
					//echo "NOT found '$path'\n</br>";
	                return false;
				} //else echo "found '$path'\n</br>";
				
	        	require($path);
			}

		}
    }
	spl_autoload_register("my_autoload");
	
	