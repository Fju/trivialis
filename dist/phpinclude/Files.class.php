<?php

require_once "Config.class.php";

// TODO: implement filename sanitizer!!

class Files {

	public static function getFiles() {
		$files = [];

		if ($dir = opendir(Config::getAssetsDir())) {
			while (false !== ($entry = readdir($dir))) {
				// skip `..` and `.`
				if ($entry === ".." || $entry === ".") continue;

				$path = self::getUploadFilename($entry);
				
				// skip directories, etc.
				if (!is_file($path)) continue;
					
				$files[] = array(
					"name" => $entry,
					"size" => filesize($path)
				);
			}
			closedir($dir);
		}
		return $files;
	}

	public static function getUploadFilename($filename) {
		// TODO: santize filenames
		return Config::getAssetsDir() . "/" . self::santizeFilename($filename);
	}

	public static function santizeFilename($filename) {
		// santize filename, illegal characters will be removed
		// allowed: A-Z, a-z, 0-9 and special characters like -._~,;[]()
		// multiple occurences of dots (.) will be replaced with a single dot
		$filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", "", $filename);
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
