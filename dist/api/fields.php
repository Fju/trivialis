<?php

require_once "DB.class.php";
require_once "Authorizer.class.php";
require_once "Utils.php";

// header information
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST");


function getFields() {
	$response = [];
	
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
	$response = [];

	$authorized = Authorizer::authorize();
	// TODO: return error code (e. g. empty token, malformed token, expired token, etc.)
	if ($authorized !== Authorizer::JWT_VALID) {
		$response["unauth"] = $authorized;
		return $response;
	}

	$id = get_post_param("id");
	$name = get_post_param("name");
	$content = get_post_param("content");

	try {
		if ($_POST["method"] === "update") {
			// check obligatory parameters
			if ($id === null) {
				$response["err"] = "An ID must be provided when updating a field";
				return $response;
			}

			$statement = DB::prepare("UPDATE fields SET name=?, content=? WHERE id=?");
			$statement->bind_param("ssd", $name, $content, $id);

			DB::exec_statement($statement);
		} else if ($_POST["method"] === "create") {
			// check parameters
			if ($name === null) {
				$response["err"] = "A name must be provided when creating a new field";
				return $response;
			}

			$statement = DB::prepare("INSERT INTO fields (name, content) VALUES (?, ?)");
			$statement->bind_param("ss", $name, $content);

			DB::exec_statement($statement);
		} else if ($_POST["method"] === "delete") {
			// check parameters
			if ($id === null) {
				$response["err"] = "An ID must be provided when deleting a field";
				return $response;
			}

			$statement = DB::prepare("DELETE FROM fields WHERE id=?");
			$statement->bind_param("d", $id);

			DB::exec_statement($statement);
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
		}
		// other error 
		$response["err"] = "Database error (#$err_code)\n$err_msg";
	} finally {
		if ($statement) $statement->close();
	}

	return $response;
}


$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getFields(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	echo json_encode(setFields(), JSON_UNESCAPED_UNICODE);
}
