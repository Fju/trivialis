<?php

require_once "DB.class.php";
require_once "Authorizer.class.php";
require_once "Parameters.php";

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
		$id = get_post_param("id");
		$name = get_post_param("name");
		$content = get_post_param("content");
		$route = get_post_param("route");
		$layout = get_post_param("layout");

		if ($_POST["method"] === "update") {
			// check parameters
			if ($id === false) {
				$response["err"] = "An ID has to be specified when updating a page.";
				return $response;
			}
			
			// escape parameters to prevent SQL injections
			$name = DB::escape($name);
			$content = DB::escape($content);
			$id = DB::escape($id);
			$route = DB::escape($route);
			$layout = DB::escape($layout);

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
