<?php

function get_post_param($name) {
	if (!isset($_POST[$name])) return null;
	return $_POST[$name];
}

