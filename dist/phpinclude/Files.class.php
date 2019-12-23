<?php

require_once "Config.class.php";

class Files {

	public static function getFiles() {
		$files = [];
		try {
			if ($dir = opendir(Config::getAssetsDir())) {
				while (false !== ($entry = readdir($dir))) {
					if ($entry === ".." || $entry === ".") continue;
					$files[] = $entry;
				}

				closedir($dir);
			}
			return $files;
		} catch (InvalidConfigurationException $e) {
			// couldn't open directory
			return [];
		}	
	}	
}
