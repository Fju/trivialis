<?php

require_once "DB.class.php";
require_once "Authorizer.class.php";

// header information
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST");


function getPages() {
	$response = array();
	
	$authorized = Authorizer::authorize();
	// TODO: return error code (e. g. empty token, malformed token, expired token, etc.)
	if ($authorized !== Authorizer::JWT_VALID) {
		$response["unauth"] = $authorized;
		return $response;
	}

	$sql = "SELECT p1.id, p1.name, p1.route, p1.content, p1.layout AS layout_id, p2.name AS layout_name
	   FROM pages p1 LEFT JOIN pages p2 ON p1.layout = p2.id";
	try {
		// select all pages and resolve the layout reference
		$response["pages"] = DB::query($sql);
	} catch (DBException $e) {
		$err_code = $e->getErrorCode();
		$err_msg = $e->getError();
		$response["err"] = "Database error (#$err_code)\n$err_msg";
	}

	return $response;
}

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getPages(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	//echo json_encode(setFields(), JSON_UNESCAPED_UNICODE);
}
