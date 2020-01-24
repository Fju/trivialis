<?php

require_once "DB.class.php";

function get_post_param($name, $escape = true) {
	if (!isset($_POST[$name])) return null;
	if ($escape) return DB::escape($_POST[$name]);
	return $_POST[$name];
}

