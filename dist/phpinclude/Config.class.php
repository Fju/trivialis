<?php

require_once "autoload.php";

use Symfony\Component\Yaml\Yaml;

class InvalidConfigurationException extends Exception {}
class IllegalConfigurationException extends Exception {}

class Config {
	private static $config = array();

	public static function init($path) {
		$path = realpath($_SERVER["DOCUMENT_ROOT"] . "/" . $path);
		if ($path === false) return;
		
		self::$config = Yaml::parseFile($path);
	}

	public static function getUsers() {
		if (!isset(self::$config["users"])) {
			throw new InvalidConfigurationException();
		}
		return self::$config["users"]; 
	}

	public static function getJWTKey() {
		if (!isset(self::$config["jwt_key"])) {
			throw new InvalidConfigurationException();
		}
		if (self::$config["jwt_key"] === "") {
			// empty encryption key is an illegal configarution
			throw new IllegalConfigurationException();
		}
		return self::$config["jwt_key"];
	}

	public static function getDBHost() {
		if (!isset(self::$config["db"]["host"])) {
			throw new InvalidConfigurationException();
		}
		return self::$config["db"]["host"];
	}

	public static function getDBUser() {
		if (!isset(self::$config["db"]["user"])) {
			throw new InvalidConfigurationException();
		}
		return self::$config["db"]["user"];
	}
	
	public static function getDBPassword() {
		if (!isset(self::$config["db"]["password"])) {
			throw new InvalidConfigurationException();
		}
		return self::$config["db"]["password"];
	}

	public static function getDBName() {
		if (!isset(self::$config["db"]["name"])) {
			throw new InvalidConfigurationException();
		}
		return self::$config["db"]["name"];
	}

	public static function getAssetsDir() {
		if (!isset(self::$config["files"]["assets_dir"])) {
			throw new InvalidConfigurationException();
		}
		return $_SERVER["DOCUMENT_ROOT"] . "/" . self::$config["files"]["assets_dir"];
	}
}

Config::init("config.yml");
