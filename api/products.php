<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


$config =  include '../config/config.php';

//require_once '../helpers/auth_check.php'; 

require_once '../libs/database.php';
require_once '../models/product.php';
	
$data = json_decode(file_get_contents("php://input"));	
	
if ($_SERVER['REQUEST_METHOD']!='OPTIONS'){	
	file_put_contents('log.txt',file_get_contents("php://input")."\n\n", FILE_APPEND);
}
	
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

		$msg = $product->create() ? $product->id : "Error";
		echo json_encode($msg); 
	break;
	
	/* UPDATE */
	case 'PUT':
		$id   = $data->id ?? NULL;
		
		if($id == null)
		{
			echo json_encode(['error' => 'NO id in the request']); return;  
			//return http_response_code(400);	 
		}
		$product->id = $id;
		$product->name = $data->name;
		$product->description = $data->description;
		$product->size = $data->size;
		$product->cost = $data->cost;
		 
		$msg = $product->update() ? "OK" : "Error";
		echo json_encode($msg);
	break;
	
	/* DELETE */
	case 'DELETE':
		$id   = $data->id ?? NULL;
		
		if($id == null)
		{
			echo json_encode(['error' => 'NO id in the request']); return;  
			//return http_response_code(400);	 
		}
		$product->id = $id;
		
		$msg = $product->delete() ? "OK" : "Error";
		echo json_encode($msg);
	break;
	
	/* READ */
	case 'GET':
		if (!isset($_GET['id'])){
			$rows = $product->readAll();
			echo json_encode($rows); 
		}else{
			$product->id = $_GET['id'];
			$product->read();
			echo json_encode($product);
		}
	break;
	
	default:
		throw new Exception("Invalid http verb");
}

