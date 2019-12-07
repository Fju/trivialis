<?php

class DBException extends Exception {

}
class DB {
	
	const DB_HOST = "trivialis_db_1";
	const DB_USER = "root";
	const DB_PASSWORD = "example";
	const DB_NAME = "trivialis";

	private static $conn;

	public static function init() {
		self::$conn = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME);

		self::$conn->query("SET NAMES 'utf8'");
		self::$conn->query("SET CHARACTER SET 'utf8'");
	}

	public static function close() {
		if (self::$conn) self::$conn->close();
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
			throw new DBException(self::$conn->error);	
		}

		return $data;
	}

	public static function exec($sql) {
		if ($result = self::$conn->query($sql)) {
			return $result;
		} else {
			throw new DBException(self::$conn->error);
		}
	}

	public static function escape($text) {
		return self::$conn->real_escape_string($text);
	}

}

DB::init();
