<?php

require_once "DB.class.php";
require_once "Config.class.php";


// TODO: generate default config
// TODO: create random key for signing web tokens!
// TODO: accept GET parameters
// TODO: error handling / retries
// TODO: progress outputs

function createUsers() {
	// drop user table (for development only)
	DB::exec("DROP TABLE IF EXISTS users");
	// create new user table
	DB::exec("CREATE TABLE users (
		username VARCHAR(50) NOT NULL PRIMARY KEY,
		password VARCHAR(256) NOT NULL,
		role ENUM('admin', 'other')	DEFAULT 'other'
	);");

	// TODO: read from config file
	$users = Config::getUsers();
	//var_dump($users);

	// insert row for each user specified in the config
	foreach ($users as $user) {
		$username = DB::escape($user["username"]);
		$password = DB::escape(password_hash($user["password"], PASSWORD_BCRYPT));
		$role = DB::escape($user["role"]);
		DB::exec("INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role');");
	}


	echo "Erfolgreich installiert";
}

function createFields() {
	// drop fields table (for development only)
	DB::exec("DROP TABLE IF EXISTS fields");
	// create fields table
	DB::exec("CREATE TABLE fields (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50) NOT NULL UNIQUE,
		content TEXT
	)");

	echo "Alles fresh";
}


function createPages() {
	// drop pages table (for development only)
	DB::exec("DROP TABLE IF EXISTS pages");

	DB::exec("CREATE TABLE pages (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50) NOT NULL UNIQUE,
		route VARCHAR(50) NULL UNIQUE,
		layout INT NULL,
		content TEXT NULL,
		FOREIGN KEY (layout) REFERENCES pages(id) ON DELETE SET NULL,
		CHECK(layout <> id)
	)");
	//FIXME: why is the check constraint ignored
}


//createUsers();
//createFields();
createPages();

