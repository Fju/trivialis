<?php

require_once "Config.class.php";


class IllegalPathException extends Exception {}
class NonExistingPathException extends Exception {}
class FileExistsException extends Exception {}

class Files {

	public static function getContents($base_path) {
		$data = [];
		
		$base_path = self::getRealPath($base_path);

		// cut off the
		$cwd = substr($base_path, strlen(Config::getAssetsDir()) + 1);
		if ($cwd === false) $cwd = "";

		$data["cwd"] = $cwd;
		$data["contents"] = [];

		// sort file entries by name
		// TODO: maybe directories first then files
		$entries = scandir($base_path, SCANDIR_SORT_ASCENDING);
		foreach ($entries as $entry) {
			// skip `..` and `.`
			if ($entry === "." || $entry === "..") continue;

			$path = $base_path . "/" . $entry;				
			if (is_file($path)) {
				$data["contents"][] = [
					"name" => $entry,
					"type" => "file",
					"size" => filesize($path)
				];
			} else if (is_dir($path)) {
				$size = self::getDirSize($path);
				$data["contents"][] = [
					"name" => $entry,
					"type" => "dir",
					"size" => $size
				];
			}
		}
		return $data;
	}

	private static function getDirSize($path) {
		// helper function for getting the size of directory (sum of the filesize of the contents)
		$io = popen("ls -ltrR \"$path\" | awk \"{print \\$5}\" | awk \"BEGIN{sum=0} {sum=sum+\\$1} END {print sum}\"", "r");
		$size = intval(fgets($io, 80));
		pclose($io);
		return $size;
	}

	public static function uploadFile($cwd) {
		$dirname = self::getRealPath($cwd);
		$basename = self::santizeName($_FILES["file"]["name"]);

		$path = $dirname . "/" . $basename;

		if (file_exists($path)) {
			throw new FileExistsException();
		}
		// move file from temporary directory to the desired destination
		move_uploaded_file($_FILES["file"]["tmp_name"], $path);
	}

	public static function getRealPath($path) {
		$asset_dir = Config::getAssetsDir();
		$path = realpath($asset_dir . "/" . $path);

		if ($path === false) {
			// directory doesn't exist
			throw new NonExistingPathException();
		}

		if (strpos($path, $asset_dir) === false) {
			// directory is not inside the assets dir
			throw new IllegalPathException();
		}

		return $path;
	}

	// this function is currently obsolete and unused
	public static function santizeName($name) {
		// santize filename, illegal characters will be removed
		// allowed: A-Z, a-z, 0-9 and special characters like -._~,;[]()
		// multiple occurences of dots (.) will be replaced with a single dot
		$name = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\)\.])", "_", $name);
		$name = mb_ereg_replace("([\.]{2,})", ".", $name);

		return $name;
	}

	public static function deleteFile($path) {
		$path = self::getRealPath($path);

		// recursively delete directories that are not empty
		// TODO: `rmdir` only works with empty directories 
		if (is_dir($path)) rmdir($path);
		else if (is_file($path)) unlink($path);
	}

	public static function renameFile($old, $new) {
		$old = self::getRealPath($old);

		$new_dirname = self::getRealPath(dirname($new));
		$new_basename = self::santizeName(basename($new));

		$new = $new_dirname . "/" . $new_basename;

		if (file_exists($new)) {
			throw new Exception("File already exists");
		}

		rename($old, $new);
	}

	public static function createDir($path) {
		$dirname = self::getRealPath(dirname($path));
		$basename = self::santizeName(basename($path));

		mkdir($dirname . "/" . $basename);
	}
}
