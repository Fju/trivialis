<?php

require_once "DB.class.php";
require_once "Authorizer.class.php";

// header information
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST");


function getPages() {
	$response = array();
	
	$authorized = Authorizer::authorize();
	if ($authorized !== Authorizer::JWT_VALID) {
		$response["unauth"] = $authorized;
		return $response;
	}

	try {
		$response["pages"] = DB::query("SELECT * FROM pages");
	} catch (DBException $e) {
		$err_code = $e->getErrorCode();
		$err_msg = $e->getError();
		$response["err"] = "Database error (#$err_code)\n$err_msg";
	}

	return $response;
}

function setPages() {
	$response = array();
	
	$authorized = Authorizer::authorize();
	if ($authorized !== Authorizer::JWT_VALID) {
		$response["unauth"] = $authorized;
		return $response;
	}

	return $response;
} 

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getPages(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	echo json_encode(setFields(), JSON_UNESCAPED_UNICODE);
}
