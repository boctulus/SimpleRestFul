<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 86400");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");
 
$config =  include '../config/config.php';
require_once '../helpers/tokens.php';
require_once '../libs/database.php';
require_once '../models/product.php';


// Get db connection  
$db = new Database($config);
$conn = $db->conn;

$product = new Product($conn);
 
// Stream
$data = json_decode(file_get_contents("php://input"));
 
// Id of product to be erased 
$product->id = $data->id;
 
$msg = $product->delete() ? "OK" : "Error";
echo json_encode($msg); // Send enconded response
?>