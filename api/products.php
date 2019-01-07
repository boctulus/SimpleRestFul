<?php
declare(strict_types=1);

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

require_once ROOT_PATH.'api/auth/auth_check.php';
require_once ROOT_PATH.'api/helpers/http.php';; 
require_once ROOT_PATH.'libs/database.php';
require_once ROOT_PATH.'models/product.php';
include_once ROOT_PATH.'helpers/debug.php';

// logger(file_get_contents("php://input"));
try {
	$input = file_get_contents("php://input");	
	$data  = json_decode($input, true);	
	
	if ($config['enabled_auth'] && $_SERVER['REQUEST_METHOD']!='OPTIONS')
		check_auth();	
	
	if ($_SERVER['REQUEST_METHOD']!='OPTIONS') {	
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
		
		/* 
			READ 

			admit a Paginator to accept urls like:
			/api/products&order[cost]=DESC&limit=3&offset=1
			/api/products&order[cost]=DESC&order[name]=ASC&limit=2&offset=1&size=2L
		*/
		case 'GET':
			$id   = $_GET['id'] ?? NULL;

			if (array_key_exists("id", $_GET)) 
				$_GET["id"] = (int) $_GET["id"];

			$fields = isset($_GET['fields']) ? explode(',',$_GET['fields']) : NULL;
			unset($_GET['fields']);

			if (!$id){
				$limit  = $_GET['limit'] ?? NULL;
				$offset = $_GET['offset'] ?? 0;
				$order  = $_GET['order'] ?? NULL;

				unset($_GET['_php']); // junk
				unset($_GET['limit']);
				unset($_GET['offset']);
				unset($_GET['order']);
				
				if($limit>0 || $order!=NULL){
					try {
						$paginator = new Paginator();
						$paginator->limit  = $limit;
						$paginator->offset = $offset;
						$paginator->orders = $order;
						$paginator->properties = $product->getProperties();
						$paginator->compile();
					}catch (Exception $e){
						sendError("Pagination error: {$e->getMessage()}");
					}
				}else
					$paginator = null;

				try {
					if (!empty($_GET)){
						$rows = $product->filter($fields, $_GET, $paginator);
						SendData($rows,200); 
					}else {
						$rows = $product->fetchAll($fields, $paginator);
						sendData($rows,200); 
					}	
				} catch (Exception $e) {
					sendError('Error in fetch: '.$e->getMessage());
				}		
					

			}else{ 
				// one product by id
				$product->id = $_GET['id'];
				if ($product->fetchOne($fields) === false)
					sendError("Not found for id={$_GET['id']}",404);
				else
					sendData($product, 200);
			}
		break;
		
		/* CREATE */
		case 'POST':
			if ($data == null)
				sendError('Invalid JSON',400);
			
			if (!$product->has_properties($data, ['id']))
				sendError('Lack some properties in your request: '.implode(',',$product->getMissingProperties()));
		
			if ($product->create($data)!==false){
				sendData(['id' => $product->id], 201);
			}	
			else
				sendError("Error: creation of resource fails!");
		break;
		
		/* UPDATE */
		case 'PUT':
			$id   = $_GET['id'] ?? NULL;
			
			if ($id == null)
				sendError("Lacks id in request",400);
			
			if (empty($data))
				sendError('Invalid JSON',400);
			
			if (!$product->has_properties($data, ['id']))
				sendError('Lack some properties in your request: '.implode(',',$product->getMissingProperties()));
			
			$product->id = $id;
			if (!$product->exists()){
				sendError("Register for id=$id does not exists",404);
			}
			
			try {
				if($product->update($data)!==false)
					sendData("OK");
				else
					sendError("Error in UPDATE");
			} catch (Exception $e) {
				sendError("Error during update for id=$id with message: {$e->getMessage()}",500);
			}
		break;
		
		/* DELETE */
		case 'DELETE':
			$id   = $_GET['id'] ?? NULL;
			
			if($id == null)
				sendError("Lacks id in request",400);

			$product->id = $id;
			
			if($product->delete()){
				sendData("OK");
			}	
			else
				sendError("Record not found",404);
		break;
		
		case 'PATCH':	
			$id   = $_GET['id'] ?? NULL;
			
			if ($id == null)
				sendError("Lacks id in request",400);
				
			if ($data == null)
				sendError('Invalid JSON',400);
			
			$product->id = $id;

			try {
				if($product->update($data)!==false)
					sendData("OK");
				else
					sendError("Error in PATCH",404);	
			} catch (Exception $e) {
				sendError("Error during PATCH for id=$id with message: {$e->getMessage()}",500);
			}
		break;
		
	}
} catch (Exception $error) {
	sendError($error->getMessage());
}
	
