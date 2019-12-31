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
		return Config::getAssetsDir() . "/" . $filename;
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
