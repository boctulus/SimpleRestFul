<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 86400");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");
 
$config =  include '../config/config.php';
require_once '../libs/database.php';
require_once '../models/product.php';

 
// Stream
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
	echo "Error in request";
	exit();
}

$data =  json_decode(file_get_contents("php://input"));


// Get db connection  
$conn = Database::getConnection($config);

$product = new Product($conn);
 
// Id of product to be edited
$product->id = $data->id;
 
$product->name = $data->name;
$product->description = $data->description;
$product->size = $data->size;
$product->cost = $data->cost;
 
$msg = $product->update() ? "OK" : "Error";
echo json_encode($msg); // Send enconded response
?>