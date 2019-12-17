<?php

require_once "DB.class.php";
require_once "Authorizer.class.php";

// JSON format
header("Content-Type: application/json; charset=utf-8");


function getFields() {
	$response = array();
	
	$authorized = Authorizer::authorize();
	// TODO: return error code (e. g. empty token, malformed token, expired token, etc.)
	if ($authorized !== Authorizer::JWT_VALID) {
		$response["unauth"] = $authorized;
		return $response;
	}

	try {
		$response["fields"] = DB::query("SELECT * FROM fields");
	} catch (DBException $e) {
		$err_code = $e->getErrorCode();
		$err_msg = $e->getError();
		$response["err"] = "Database error (#$err_code)\n$err_msg";
	}

	return $response;
}

function setFields() {
	$response = array();

	$authorized = Authorizer::authorize();
	// TODO: return error code (e. g. empty token, malformed token, expired token, etc.)
	if ($authorized !== Authorizer::JWT_VALID) {
		$response["unauth"] = $authorized;
		return $response;
	}

	try {
		if ($_POST["method"] === "update") {
			// check parameters
			if (!isset($_POST["name"]) || !isset($_POST["content"]) || !isset($_POST["id"])) {
				$response["err"] = "Not enough parameters specified for updating data";
				return $response;
			}

			// escape parameters to prevent SQL injections
			$name = DB::escape($_POST["name"]);
			$content = DB::escape($_POST["content"]);
			$id = DB::escape($_POST["id"]);

			DB::exec("UPDATE fields SET name='$name', content='$content' WHERE id = $id");
		} else if ($_POST["method"] === "create") {
			// check parameters
			if (!isset($_POST["name"]) || !isset($_POST["content"])) {
				$response["err"] = "Not enough parameters specified for creating new data";
				return $response;
			}

			$name = DB::escape($_POST["name"]);
			$content = DB::escape($_POST["content"]);

			DB::exec("INSERT INTO fields (name, content) VALUES ('$name', '$content')");	
		} else if ($_POST["method"] === "delete") {
			// check parameters
			if (!isset($_POST["id"])) {
				$response["err"] = "Not enough parameters specified for deleting data";
				return $response;
			}

			$id = DB::escape($_POST["id"]);
		
			DB::exec("DELETE FROM fields WHERE id = $id");
		} else {
			// invalid method parameter provided
			$response["err"] = "Invalid value for method parameter";
		}
	} catch (DBException $e) {
		$err_code = $e->getErrorCode();
		$err_msg = $e->getError();

		if ($err_code === 1062) {
			// duplicate entry
			$response["err"] = "Field names must be unique";
			return $response;
		}
		// other error 
		$response["err"] = "Database error (#$err_code)\n$err_msg";
	}

	return $response;
}


$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getFields(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	echo json_encode(setFields(), JSON_UNESCAPED_UNICODE);
}
