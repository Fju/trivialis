<?php

require_once "DB.class.php";

function checkLogin($username, $password) {
	$username = DB::escape($username);
	$sql = "SELECT password FROM users WHERE username = '$username'";

	// fetch users from user table with matching username
	$users = DB::query($sql);

	if (sizeof($users) === 1) {
		if (password_verify($password, $users[0]["password"])) {
			echo "Correct password";
		} else {
			echo "False password";
		}
	} else {
		echo "Unable to find user";
	}
}

if (isset($_POST["username"]) && isset($_POST["password"])) {
	checkLogin($_POST["username"], $_POST["password"]);
}

