<?php

namespace simplerest\libs;

use simplerest\libs\Url; 

class Debug
{
	protected static function pre(callable $fn, ...$args){
		echo '<pre>';
		$fn($args);
		echo '</pre>';
	}

	protected static function export($v, $msg = null) 
	{			
		$postman = Url::is_postman();
		
		$cli  = (php_sapi_name() == 'cli');
		$br   = ($cli || $postman) ? PHP_EOL : '<br/>';
		$p    = ($cli || $postman) ? PHP_EOL . PHP_EOL : '<p/>';

		$type = gettype($v);
		
		$fn = function($x) use ($type){
			if ($type == 'boolean'){
				echo $x;
			} else {
				echo var_export($x);
			}	
		};

		
		if ($type == 'boolean'){
			$v = $v ? 'true' : 'false';
		}	

		
		ob_start();

		if (!empty($msg)){
			echo "--[ $msg ]-- ". $br;
		}
			
		$fn($v);	
		
		if ($type != "array"){
			echo $p;
		}		

		if ($cli || $postman){
			echo $p;
		}

		$ret = ob_get_contents();
		ob_end_clean();

		return $ret;
	}	

	static public function dd($var, $msg = null){
		$cli = (php_sapi_name() == 'cli');
		
		$pre = !$cli;
		
		if (Url::is_postman()){
			$pre = false;
		}

		if ($pre) {
			self::pre(function() use ($var, $msg){ 
				echo self::export($var, $msg); 
			});
		} else {
			echo self::export($var, $msg);
		}
	}

}