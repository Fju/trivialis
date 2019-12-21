<?php
require_once "Authorizer.class.php";

// header information
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST");


function uploadFile() {
	$response = array();
	
	$response["filename"] = $_FILES["file"]["name"];
	return $response;
}

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "POST") {
	echo json_encode(uploadFile(), JSON_UNESCAPED_UNICODE);
}
