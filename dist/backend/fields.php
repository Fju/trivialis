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

function setFields() {
	$response = array();


	// don't escape if `$id` is empty
   	if ($id) $id = DB::escape($id);
	
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
		try {	
			DB::exec("UPDATE fields SET name='$name', content='$content' WHERE id = $id");
		} catch (DBException $e) {
			$response["err"] = "Database error";
			$response["err_desc"] = $e->getMessage();
			$response["err_no"] = $e->getCode();
		}
	} else if ($_POST["method"] === "create") {
		// check parameters
		if (!isset($_POST["name"]) || !isset($_POST["content"])) {
			$response["err"] = "Not enough parameters specified for creating new data";
			return $response;
		}

		$name = DB::escape($_POST["name"]);
		$content = DB::escape($_POST["content"]);
		try {
			DB::exec("INSERT INTO fields (name, content) VALUES ('$name', '$content')");	
		} catch (DBException $e) {
			$response["err"] = "Database error";
			$response["err_desc"] = $e->getMessage();
			$response["err_no"] = $e->getCode();
		}
	} else if ($_POST["method"] === "delete") {
		// check parameters
		if (!isset($_POST["id"])) {
			$response["err"] = "Not enough parameters specified for deleting data";
			return $response;
		}

		$id = DB::escape($_POST["id"]);
		try {
			DB::exec("DELETE FROM fields WHERE id = $id");
		} catch (DBException $e) {
			$response["err"] = "Database error";
			$response["err_desc"] = $e->getMessage();
			$response["err_no"] = $e->getCode();
		}
	} else {
		// invalid method parameter provided
		$response["err"] = "Invalid value for method parameter";
	}
	return $response;
}


if (!Authorizer::authorize()) {
	echo "{ \"auth\": false }";
} else {
	$method = $_SERVER["REQUEST_METHOD"];
	if ($method === "GET") {
		echo json_encode(getFields(), JSON_UNESCAPED_UNICODE);
	} else if ($method === "POST") {
		echo json_encode(setFields(), JSON_UNESCAPED_UNICODE);
	}
}
