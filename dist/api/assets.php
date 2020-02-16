<?php
require_once "Authorizer.class.php";
require_once "Files.class.php";
require_once "Utils.php";

// header information
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST");

function getFiles() {
	$response = [];

	$authorized = Authorizer::authorize();
	if ($authorized !== Authorizer::JWT_VALID) {
		$response["unauth"] = $authorized;
		return $response;
	}

	try {
		$response = Files::getContents($_GET["path"]);
	} catch (IllegalPathException $e) {
		$response["err"] = "Illegal path";
	} catch (NonExistingPathException $e) {
		$response["err"] = "Invalid path";
	} catch (InvalidConfigurationException $e) {
		$response["err"] = "Trivialis is configured improperly";
	}

	return $response;
}

function modifyFiles() {
	$response = [];
	
	$authorized = Authorizer::authorize();
	if ($authorized !== Authorizer::JWT_VALID) {
		$response["unauth"] = $authorized;
		return $response;
	}
	
	$cwd = get_post_param("cwd");
	$name = get_post_param("name");
	$name_new = get_post_param("name_new");

	try {
		if ($_POST["method"] === "upload") {
			Files::uploadFile($cwd);
		} else if ($_POST["method"] === "delete") {
			if ($name === null) {
				$response["err"] = "Not enough parameters";
				return $response;
			}
			Files::deleteFile($name);
		} else if ($_POST["method"] === "rename") {
			if ($name === null && $name_new === null) {
				$response["err"] = "Not enough parameters";
				return $response;
			}
			Files::renameFile($name, $name_new);
		} else if ($_POST["method"] === "mkdir") {
			if ($name === null) {
				$response["err"] = "Not enough parameters";
				return $response;
			}
			Files::createDir($name);
		}
	} catch(InvalidConfigurationException $e) {
		$response["err"] = "Trivialis is configured improperly";
	} catch(IllegalPathException $e) {
		$response["err"] = "Illegal path";
	} catch(NonExistingPathException $e) {
		$response["err"] = "Invalid path";
	} catch (Exception $e) {
		$response["err"] = "Internal error." . $e->getError();
	}

	return $response;
}

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getFiles(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	echo json_encode(modifyFiles(), JSON_UNESCAPED_UNICODE);
}
