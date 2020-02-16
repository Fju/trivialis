<?php

require_once "DB.class.php";
require_once "Authorizer.class.php";

// header information
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET");

function getStats() {
	$response = [];
	
	$authorized = Authorizer::authorize();
	if ($authorized !== Authorizer::JWT_VALID) {
		$response["unauth"] = $authorized;
		return $response;
	}

	try {
		// fetch views of pages (`views` is 0 if there is no entry in the stats table)
		$response["stats"] = DB::query("SELECT P.name AS page, COALESCE(S.views, 0) AS views FROM stats S RIGHT OUTER JOIN pages P ON P.id = S.page_id");
	} catch (DBException $e) {
		$err_code = $e->getErrorCode();
		$err_msg = $e->getError();
		$response["err"] = "Database error (#$err_code)\n$err_msg";
	}

	return $response;
}

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getStats(), JSON_UNESCAPED_UNICODE);
}
