<?php

header('access-control-allow-credentials: true');
header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS'); 
header('access-control-allow-Origin: *');
header('content-type: application/json; charset=UTF-8');

/*
	API Rest examples:
	
	https://jsonplaceholder.typicode.com/
*/

$config =  include '../config/config.php';

require_once '../helpers/messages.php';
require_once '../libs/database.php';
require_once '../models/product.php';
require_once '../helpers/auth_check.php'; 
include_once '../helpers/debug.php';


// logger($_SERVER['REQUEST_METHOD']);
// logger(file_get_contents("php://input"));


try {
	$input = file_get_contents("php://input");	
	$data  = json_decode($input);	
	
	
	if ($config['enabled_auth'] && $_SERVER['REQUEST_METHOD']!='OPTIONS'){
		check_auth();	
		
		$conn = Database::getConnection($config);
		$product = new Product($conn);
	}	
	
	
	switch($_SERVER['REQUEST_METHOD'])
	{
		case 'OPTIONS':
			// pass (without any authorization check), see:
			// https://stackoverflow.com/questions/35202347/angular2-to-rest-webapi-cors-issue
			http_response_code(200);
			exit();
		break;
		
		/* CREATE */
		case 'POST':
			if ($data == null)
				sendError('Invalid JSON',400);
			
			if (!$product->has_properties($data, ['id']))
				sendError('Lack some properties in your request: '.implode(',',$product->getMissingProperties()));
			
			foreach ($data as $key => $value){
				$product->{$key} = $value;
			}

			$product->create();
			if ($product->id){
				sendData(['id' => $product->id], 201);
			}	
			else
				sendError("Error: creation of resource fails!");
		break;
		
		/* UPDATE */
		case 'PUT':
			$id   = $_GET['id'] ?? NULL;
			
			if ($id == null)
			{
				sendError("Lacks id in request",400);
			}
			
			if ($data == null)
				sendError('Invalid JSON',400);
			
			if (!$product->has_properties($data, ['id']))
				sendError('Lack some properties in your request: '.implode(',',$product->getMissingProperties()));
			
			$product->id = $id;
			foreach ($data as $key => $value){
				$product->{$key} = $value;
			}
			
			if (!$product->exists()){
				sendError("Register for id=$id does not exists",404);
			}
			
			try {
				$product->update();
				sendData("OK");
			} catch (Exception $e) {
				sendError("Error during update for id=$id with message: {$e->getMessage()}",500);
			}
		break;
		
		/* DELETE */
		case 'DELETE':
			$id   = $_GET['id'] ?? NULL;
			
			if($id == null)
			{
				sendError("Lacks id in request",400);
			}
			$product->id = $id;
			
			if($product->delete()){
				sendData("OK");
			}	
			else
				sendError("Error: delete for id=$id fails!",410);
				
		break;
		
		/* READ */
		case 'GET':
			$id   = $_GET['id'] ?? NULL;
		
			if (!$id){
				$rows = $product->readAll();
				sendData($rows,200); 
			}else{ 
				$product->id = $_GET['id'];
				if ($product->read() === false)
					sendError("Not found for id={$_GET['id']}",404);
				else
					sendData($product, 200);
			}
		break;
		
		/* 
		
			UPDATE by PATCH  (easy way implementation)
			
			TODO: perform a better implementation because ...
			... this one makes an avoidable previous SQL fetch
		*/
		case 'PATCH':
			$id   = $_GET['id'] ?? NULL;
			
			if ($id == null)
			{
				sendError("Lacks id in request",400);
			}
			
			if ($data == null)
				sendError('Invalid JSON',400);
			
			$product->id = $id;
			
			if ($product->read() === false)
					sendError("Not found for id={$_GET['id']}",404);
			
			foreach ($data as $key => $value){
				$product->{$key} = $value;
			}
			
			try {
				$product->update();
				sendData("OK");
			} catch (Exception $e) {
				sendError("Error during update for id=$id with message: {$e->getMessage()}",500);
			}
		break;
		
	}
} catch (Exception $error) {
	sendError($error);
}
	
