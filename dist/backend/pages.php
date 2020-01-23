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

	try {
		// TODO: escape parameters outside the if statement
		// TODO: only ID is
		if ($_POST["method"] === "update") {
			// check parameters
			if (!isset($_POST["name"]) || !isset($_POST["content"]) || !isset($_POST["id"]) ||
				!isset($_POST["route"]) || !isset($_POST["layout"])) {
				$response["err"] = "Not enough parameters specified for updating data";
				return $response;
			}
			
			// escape parameters to prevent SQL injections
			$name = DB::escape($_POST["name"]);
			$content = DB::escape($_POST["content"]);
			$id = DB::escape($_POST["id"]);
			$route = DB::escape($_POST["route"]);
			$layout = DB::escape($_POST["layout"]);

			DB::exec("UPDATE pages SET name='$name', content='$content', route='$route', layout='$layout' WHERE id = $id");
		} else if ($_POST["method"] === "create") {
			// check parameters
			if (!isset($_POST["name"]) || !isset($_POST["content"]) || !isset($_POST["route"])) {
				$response["err"] = "Not enough parameters specified for creating a new page";
				return $response;
			}

			$name = DB::escape($_POST["name"]);
			$content = DB::escape($_POST["content"]);
			$route = DB::escape($_POST["route"]);

			DB::exec("INSERT INTO pages (name, content, route) VALUES ('$name', '$content', '$route')");
		}
	} catch (Exception $e) {
		var_dump($e);
	}

	return $response;
} 

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getPages(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	echo json_encode(setPages(), JSON_UNESCAPED_UNICODE);
}
