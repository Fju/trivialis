<?php

require_once "Config.class.php";

class Files {

	public static function getFiles() {
		$files = [];
		try {
			
			if ($dir = opendir(Config::getAssetsDir())) {
				while (false !== ($entry = readdir($dir))) {
					// skip `..` and `.`
					if ($entry === ".." || $entry === ".") continue;

					$path = Config::getAssetsDir()."/$entry";
					
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
		} catch (InvalidConfigurationException $e) {
			// couldn't open directory
			return [];
		}	
	}

	public static function getUploadFilename($filename) {
		// TODO: santize filenames
		return Config::getAssetsDir() . "/" . $filename;
	}

}
