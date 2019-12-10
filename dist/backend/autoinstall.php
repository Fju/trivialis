<?php

require_once "DB.class.php";
require_once "Config.class.php";


// TODO: generate default config
// TODO: create random key for signing web tokens!

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
	$users = Config::getUsers();
	var_dump($users);

	// insert row for each user specified in the config
	foreach ($users as $user) {
		$username = DB::escape($user["username"]);
		$password = DB::escape(password_hash($user["password"], PASSWORD_BCRYPT));
		$role = DB::escape($user["role"]);
		DB::exec("INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role');");
	}


	echo "Erfolgreich installiert";
}

createUsers();


