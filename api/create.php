<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 86400");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

require_once '../config/config.php';
require_once '../libs/database.php';
require_once '../models/sale.php';
 
$db = new Database($db_name);
$conn = $db->conn;

$sale = new Sale($conn);
 
// escucho el stream
$data =  json_decode(file_get_contents("php://input"));

$sale->name = $data->name;
$sale->description = $data->description;
$sale->size = $data->size;
$sale->cost = $data->cost;

$msg = $sale->create() ? $sale->id : "Error";
echo json_encode($msg);
?>