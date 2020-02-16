<?php

require_once "DB.class.php";
require_once "Config.class.php";


header("Content-Type: text/plain; charset=utf-8");

echo "Installing Trivialis CMS version 0.0.1\n\n";

const RESULT_WARN = 1;
const RESULT_ERROR = 2;
const RESULT_INFO = 0;

function output($type, $message) {
	switch ($type) {
		case RESULT_ERROR:
			echo "ERROR: $message\n";
			break;
		case RESULT_WARN:
			echo "WARN: $message\n";
			break;
		default:
			echo "INFO: $message\n";
	}
}

// TODO: accept GET parameters

function createUsers() {
	// drop user table (for development only)
	DB::exec("DROP TABLE IF EXISTS users");
	// create new user table
	DB::exec("CREATE TABLE users (
		username VARCHAR(50) NOT NULL PRIMARY KEY,
		password VARCHAR(256) NOT NULL,
		role ENUM('admin', 'other')	DEFAULT 'other'
	);");

	try {
		// obtain array of users from configuration file
		$users = Config::getUsers();

		// check if there is an admin user defined
		$has_admin = false;
		foreach ($users as $user) {
			if ($user["role"] === "admin") {
				$has_admin = true;
				break;
			}
		}	
		// abort setup if there is no admin user defined
		if (!$has_admin) {
			output(RESULT_ERROR, "There is no admin user defined in the configuration file");
			return 0;
		}

		$statement = DB::prepare("INSERT INTO users VALUES (?, ?, ?)");
		// insert row for each user specified in the config
		foreach ($users as $user) {
			$username = $user["username"];
			$password = password_hash($user["password"], PASSWORD_BCRYPT);
			$role = $user["role"];

			output(RESULT_INFO, "Creating user '$username' with role '$role'");

			$statement->bind_param("sss", $username, $password, $role);
			DB::exec_statement($statement);
		}
		$statement->close();
	} catch (InvalidConfigurationException $e) {
		output(RESULT_ERROR, "The configuration file is invalid");	
		return 0;
	}

	output(RESULT_INFO, "User setup successfully completed");
	return 1;
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

	output(RESULT_INFO, "Fields setup successfully completed");
	return 1;
}


function createPages() {
	//TODO: check table scheme and print check results
	// drop pages table (for development only)
	DB::exec("DROP TABLE IF EXISTS pages");

	// `name` should be unique and cannot be null
	// `route` must be unique but can be null (e. g. when a page shouldn't be accessible via an URL)
	// `layout` is a foreign key that references ANOTHER row in the pages table,
	//  	if the referenced row is deleted the value will be set to NULL
	DB::exec("CREATE TABLE pages (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50) NOT NULL UNIQUE,
		route VARCHAR(50) NULL UNIQUE,
		content TEXT NULL
	)");

	output(RESULT_INFO, "Pages setup successfully completed");
	return 1;
}

try {
	$success = createUsers() && createFields() && createPages();


	if ($success) echo "\nSuccess!\n";
	else echo "\nFailed!\n";
} catch (DBException $e) {
	output(RESULT_ERROR, "Unexpected database error, unable to install Trvialis");
	echo "\nFailed!\n";
}

