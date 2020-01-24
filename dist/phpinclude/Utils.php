<?php

function get_post_param($name) {
	if (!isset($_POST[$name]) return false;
	return $_POST[$name];
}

