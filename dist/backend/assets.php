<?php
require_once "Authorizer.class.php";
require_once "Files.class.php";

// header information
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST");

function getFiles() {
	$response = Files::getContents($_GET["path"]);
	return $response;
}

function modifyFiles() {
	$response = array();

	if ($_POST["method"] === "upload") {
		try {
			$filename = Files::getUploadFilename($_FILES["file"]["name"]);
			if (file_exists($filename)) {
				$response["err"] = "Filename already exists";
				return $response;
			}
			move_uploaded_file($_FILES["file"]["tmp_name"], $filename);
		} catch (InvalidConfigurationException $e) {
			$response["err"] = "Trivialis is configured improperly";
			return $response;
		} catch (Exception $e) {
			$response["err"] = "Unable to save uploaded file!";
			return $response;
		}
	} else if ($_POST["method"] === "delete") {
		if (!isset($_POST["name"])) {
			$response["err"] = "Not enough parameters provided";
			return $response;
		}
		try {
			Files::deleteFile($_POST["name"]);
		} catch (Exception $e) {
			$response["err"] = "Unable to delete file";
			return $response;
		}
	} else if ($_POST["method"] === "rename") {
		if (!isset($_POST["name"]) || !isset($_POST["name_new"])) {
			$response["err"] = "Not enough parameters provided";
			return $response;
		}

		try {
			Files::renameFile($_POST["name"], $_POST["name_new"]);
		} catch (Exception $e) {
			$response["err"] = "Unable to rename file";
			return $response;
		}
	}


	return $response;
}

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getFiles(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	echo json_encode(modifyFiles(), JSON_UNESCAPED_UNICODE);
}
