<?php 

function sendData($data, $http_code = 200, $http_code_msg = null){
	if ($http_code != null && is_int($http_code)){
		header(trim("HTTP/1.0 $http_code $http_code_msg"));
	}	
	
	echo json_encode($data); 
	exit();  	 
}	

function sendError($msg_error, $http_code = null){
	sendData(['error' => $msg_error], $http_code);
}

function logger($data, $file='../logs/log.txt'){
	if (is_array($data) || is_object($data))
		$data = json_encode($data);
	
	file_put_contents($file,$data, FILE_APPEND);
	file_put_contents($file,"\n", FILE_APPEND);
}