<?php

require_once "DB.class.php";
require_once "Authorizer.class.php";

// JSON format
header("Content-Type: application/json; charset=utf-8");


function getFields() {
	$response = array();

	$response["fields"] = DB::query("SELECT * FROM fields");
	
	return $response;
}

if (!Authorizer::authorize()) {
	echo json_encode(
		array("auth" => false),
		JSON_UNESCAPED_UNICODE
	);
} else {
	echo json_encode(
		getFields(),
		JSON_UNESCAPED_UNICODE
	);
}
//if (isset($_GET["jwt"])) {
	//$jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NzYwNjg0NjYsImV4cCI6MTU3Nzg2ODQ2NiwiaXNzIjoidHJpdmlhbGlzIn0.vX_7Zwit7GDQVphmH2egh7nBXsdSIWweu3q69bmVZbA";
	//Authorizer::verifyToken($jwt);
//}
