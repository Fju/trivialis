<?php

require_once "Config.class.php";

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

}
