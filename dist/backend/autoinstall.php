<?php

require_once "DB.class.php";


function createUsers() {
	// drop user table (for development only)
	DB::exec("DROP TABLE IF EXISTS users");
	// create new user table
	DB::exec("CREATE TABLE users (
		username VARCHAR(50) NOT NULL UNIQUE,
		password VARCHAR(256) NOT NULL,
		role ENUM('admin', 'other')	DEFAULT 'other'
	);");

	// TODO: read from config file
	$users = [
		[ "username" => "trivialis", "password" => "trivialis" ]	
	];

	// insert row for each user specified in the config
	foreach ($users as $user) {
		$username = DB::escape($user["username"]);
		$password = DB::escape(password_hash($user["password"], PASSWORD_BCRYPT));
		DB::exec("INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'admin');");
	}


	echo "Erfolgreich installiert";
}


createUsers();


