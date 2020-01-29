<?php

require_once "DB.class.php";
require_once "Authorizer.class.php";
require_once "Utils.php";

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
		// `get_post_param` escapes parameter by default
		$id = get_post_param("id");
		$name = get_post_param("name");
		$content = get_post_param("content", false);
		$route = get_post_param("route");
		$layout = get_post_param("layout");

		if ($_POST["method"] === "update") {
			// check obligatory parameters
			if ($id === null) {
				$response["err"] = "An ID has to be specified when updating a page.";
				return $response;
			}
			// TODO: how to deal with null-able attributes?
			if ($layout === "") $layout = null;

			$statement = DB::prepare("UPDATE pages SET name=?, content=?, route=?, layout=? WHERE id = ?");
			$statement->bind_param("sssdd", $name, $content, $route, $layout, $id);

			DB::exec_statement($statement);
		} else if ($_POST["method"] === "create") {
			// check parameters
			if ($name === null) {
				$response["err"] = "Not enough parameters specified for creating a new page";
				return $response;
			}

			$statement = DB::prepare("INSERT INTO pages (name, content, route, layout) VALUES (?, ?, ?, ?)");
			$statement->bind_param("sssd", $name, $content, $route, $layout);

			DB::exec_statement($statement);
		} else if ($_POST["method"] === "delete") {
			if ($id === null) {
				$response["err"] = "An ID has to be specified when deleting a page.";
				return $response;
			}

			$statement = DB::prepare("DELETE FROM pages WHERE id = ?");
			$statement->bind_param("d", $id);

			DB::exec_statement($statement);
		}
	} catch (DBException $e) {
		$err_code = $e->getErrorCode();
		$err_msg = $e->getError();

		if ($err_code === 1062) {
			// duplicate entry
			$response["err"] = "name parameter must be unique";
		} else if ($err_code === 1452) {
			// foreign key constraint violated
			$response["err"] = "Specified layout parameter is invalid ($layout)";
		} else var_dump($e);
	} finally {
		if ($statement) $statement->close();
	}

	return $response;
} 

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getPages(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	echo json_encode(setPages(), JSON_UNESCAPED_UNICODE);
}
