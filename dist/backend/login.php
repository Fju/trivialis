<?php

require_once "autoload.php";
require_once "DB.class.php";

use \Firebase\JWT\JWT;


// JSON format
header("Content-Type: application/json; charset=utf-8");


// TODO: read from config
$key = Config::getJWTKey();

function checkLogin($username, $password) {
	$response = array();
	// escape username to prevent SQL injection
	
	$username = DB::escape($username);
	$sql = "SELECT password FROM users WHERE username = '$username'";
	
	try {
		// fetch users from user table with matching username
		$users = DB::query($sql);
		// check if user was found
		if (sizeof($users) === 1) {
			// cannot use because of salt
			if (password_verify($password, $users[0]["password"])) {
				// create token
				$response["token"] = JWT::encode(array(
					"iat" => time(), // issued at
					"exp" => time() + 1000 * 60 * 30, // expiration time
					"iss" => "trivialis"
				), $key);
			} else {
				// invalid password
				$response["err"] = "Incorrect password";
			}
		} else {
			// no user found
			$response["err"] = "Login failed! Unable to authenticate user";
		}
	} catch (DBException $e) {
		// TODO: in debug mode send exception details
		$response["err"] = "Database error!";
	}

	return $response;
}

if (isset($_POST["username"]) && isset($_POST["password"])) {
	echo json_encode(
		checkLogin($_POST["username"], $_POST["password"]),
		JSON_UNESCAPED_UNICODE
	);
}

