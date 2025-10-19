<?php
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
if ($REQUEST_METHOD !== 'GET') {
    // Headers for GET Request
    header("Access-Control-Allow-Methods: $REQUEST_METHOD");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods,Content-type,Access-Control-Allow-Origin, Authorization, X-Requested-With");
    
    // Get raw POSTed data
    $data = file_get_contents("php://input") != null ? json_decode(file_get_contents("php://input")) : die();
}
?>