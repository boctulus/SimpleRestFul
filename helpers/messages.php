<?php 

if (!defined('logged')){
	function logger($data, $file='../logs/log.txt'){
		if (is_array($data) || is_object($data))
			$data = json_encode($data);
		
		file_put_contents($file,$data, FILE_APPEND);
		file_put_contents($file,"\n", FILE_APPEND);
	}
}