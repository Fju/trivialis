<?php

require_once "Config.class.php";


class IllegalPathException extends Exception {}

class Files {

	public static function getContents($base_path) {
		$data = [];

		$asset_dir = Config::getAssetsDir();
		$base_path = realpath($asset_dir . "/" . $base_path);

		if (strpos($base_path, $asset_dir) === false) {
			// directory is not inside the assets dir
			throw new IllegalPathException();
		}
		
		if (!is_dir($base_path)) return [];

		$cwd = substr($base_path, strlen($asset_dir) + 1);
		if ($cwd === false) $cwd = "";

		if ($dir = opendir($base_path)) {
			$data["cwd"] = $cwd;
			$data["contents"] = [];
			while (false !== ($entry = readdir($dir))) {
				// skip `..` and `.`
				if ($entry === "." || $entry === "..") continue;

				$path = $base_path . "/" . $entry;				
				if (is_file($path)) {
					$data["contents"][] = array(
						"name" => $entry,
						"type" => "file"
					);	
				} else if (is_dir($path)) {
					$data["contents"][] = array(
						"name" => $entry,
						"type" => "dir"
					);
				}
			}
			closedir($dir);
		}
		return $data;
	}

	public static function getUploadFilename($filename) {
		// TODO: santize filenames
		return Config::getAssetsDir() . "/" . self::santizeFilename($filename);
	}

	public static function santizeFilename($filename) {
		// santize filename, illegal characters will be removed
		// allowed: A-Z, a-z, 0-9 and special characters like -._~,;[]()
		// multiple occurences of dots (.) will be replaced with a single dot
		$filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\)\/])", "", $filename);
		$filename = mb_ereg_replace("([\.]{2,})", ".", $filename);

		return $filename;
	}

	public static function deleteFile($filename) {
		unlink(self::getUploadFilename($filename));
	}

	public static function renameFile($old, $new) {
		$old = self::getUploadFilename($old);
		$new = self::getUploadFilename($new);

		if (file_exists($new)) {
			throw new Exception("File already exists");
		}

		rename($old, $new);
	}
}
