<?php

require_once "Config.class.php";

class DBException extends Exception {
	private $err;
	private $errno;

	public function __construct($err, $errno) {
		$this->err = $err;
		$this->errno = $errno;
	}

	public function getError() {
		return $this->err;
	}

	public function getErrorCode() {
		return $this->errno;
	}
}

class DB {
	
	private static $conn;

	public static function init() {
		try {
			$db_host = Config::getDBHost();
			$db_user = Config::getDBUser();
			$db_password = Config::getDBPassword();
			$db_name = Config::getDBName();

			// establish connection
			self::$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
			// set charachter encoding
			self::$conn->query("SET NAMES 'utf8'");
			self::$conn->query("SET CHARACTER SET 'utf8'");
			self::$conn->query("SET COLLATE 'utf8_unicode_ci'");
		} catch(InvalidConfigurationException $e) {
			// dunno what happens here
			var_dump($e);
		} catch(Exception $e) {
			echo $e->getMessage();	
		}
	}

	public static function close() {
		if (self::$conn) self::$conn->close();
	}

	public static function get_conn() {
		return self::$conn;
	}
	
	public static function query_json($sql) {
		return json_encode(self::query($sql), JSON_UNESCAPED_UNICODE);
	}	

	public static function query($sql) {
		$data = array();

		if ($result = self::$conn->query($sql)) {
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			$result->close();
		} else {
			throw new DBException(self::$conn->error, self::$conn->errno);
		}

		return $data;
	}

	public static function prepare($sql) {
		return self::$conn->prepare($sql);
	}

	public static function exec($sql) {
		if ($result = self::$conn->query($sql)) {
			return $result;
		} else {
			throw new DBException(self::$conn->error, self::$conn->errno);
		}
	}

	public static function exec_statement($stmt) {
		if ($stmt->execute() === false) {
			throw new DBException($stmt->error, $stmt->errno);
		}
	}

	public static function escape($text) {
		return self::$conn->real_escape_string($text);
	}
}

DB::init();
