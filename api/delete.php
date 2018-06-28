<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 86400");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");
 
require_once '../config/config.php';
require_once '../libs/database.php';
require_once '../models/sale.php';
 
// Get db connection  
$db = new Database($db_name,$host,$user,$pass);
$conn = $db->conn;

$sale = new Sale($conn);
 
// Stream
$data = json_decode(file_get_contents("php://input"));
 
// Id of sale to be erased 
$sale->id = $data->id;
 
$msg = $sale->delete() ? "OK" : "Error";
echo json_encode($msg); // Send enconded response
?>