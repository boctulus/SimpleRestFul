<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


$config =  include '../config/config.php';

if ($config['enabled_auth'])
	require_once '../helpers/auth_check.php'; 

require_once '../helpers/messages.php';
require_once '../libs/database.php';
require_once '../models/product.php';
	
$data = json_decode(file_get_contents("php://input"));	


$conn = Database::getConnection($config);
$product = new Product($conn);


switch($_SERVER['REQUEST_METHOD'])
{
	/* CREATE */
	case 'POST':
		$product->name = $data->name;
		$product->description = $data->description;
		$product->size = $data->size;
		$product->cost = $data->cost;

		$product->create();
		if ($product->id){
			sendData(['id' => $product->id], 201);
		}	
		else
			sendError("Error: Create fails!");
	break;
	
	/* UPDATE */
	case 'PUT':
		$id   = $_GET['id'] ?? NULL;
		
		if($id == null)
		{
			sendError("Lacks id in request",400);
		}
		$product->id = $id;
		$product->name = $data->name;
		$product->description = $data->description;
		$product->size = $data->size;
		$product->cost = $data->cost;
		
		if ($product->update()){
			
			sendData("OK");
		}	
		else
			sendError("Error: update for id=$id fails!");
		
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
	
	default:
		sendError("Invalid http verb",400);
}

	
