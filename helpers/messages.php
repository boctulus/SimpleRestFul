<?php 

function sendData($data, $http_code = null, $http_code_msg = null){
	if ($http_code != null && is_int($http_code) && is_string($http_code_msg)){
		
		header("HTTP/1.0 $response_code $http_code_msg");
		exit();
	}	
	
	echo json_encode($data); 
	exit();  	 
}	


function sendError($msg_error, $http_code = null){
	sendData(['error' => $msg_error], $http_code);
}

function logger($data, $file='log.txt'){
	if (is_array($data) || is_object($data))
		$data = json_encode($data);
	
	file_put_contents($file,$data, FILE_APPEND);
}