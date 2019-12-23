<?php
require_once "Authorizer.class.php";
require_once "Files.class.php";

// header information
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST");

function getFiles() {
	$response = array();

	$response["files"] = Files::getFiles();

	return $response;
}

function uploadFile() {
	$response = array();
	
	$response["filename"] = $_FILES["file"]["name"];
	$response["method"] = $_POST["method"];
	return $response;
}

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getFiles(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	echo json_encode(uploadFile(), JSON_UNESCAPED_UNICODE);
}
