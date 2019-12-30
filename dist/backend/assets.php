<?php
require_once "Authorizer.class.php";
require_once "Files.class.php";

// header information
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST");

function getFiles() {
	$response = array();

	$response["files"] = Files::getFiles();

	return $response;
}

function uploadFile() {
	$response = array();

	if ($_POST["method"] === "upload") {
		try {
			$filename = Files::getUploadFilename($_FILES["file"]["name"]);
			if (file_exists($filename)) {
				$response["err"] = "Filename already exists";
				return $response;
			}
			move_uploaded_file($_FILES["file"]["tmp_name"], $filename);
		} catch (Exception $e) {
			$response["err"] = "Unable to save uploaded file!";
			return $response;
		}
	}


	return $response;
}

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "GET") {
	echo json_encode(getFiles(), JSON_UNESCAPED_UNICODE);
} else if ($method === "POST") {
	echo json_encode(uploadFile(), JSON_UNESCAPED_UNICODE);
}
