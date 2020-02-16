<?php

require_once "autoload.php";
require_once "DB.class.php";

header("Content-Type: text/html; charset=utf-8");
header("Access-Control-Allow-Methods: GET");

$pages = DB::query("SELECT * FROM pages");
$templates = [];
$routes = [];
foreach ($pages as $page) {
	$content = $page["content"];
	if ($content === null) $content = "";

	$templates[$page["name"]] = $content;

	if ($page["route"]) $routes[$page["route"]] = $page["name"];
}

$fields = DB::query("SELECT * FROM fields");
$substitutes = [];
foreach ($fields as $field) {
	if ($field["content"] === null) continue;
	$name = $field["name"];
	$content = $field["content"];
	$substitutes[$name] = $content;
}

$loader = new \Twig\Loader\ArrayLoader($templates);
$twig = new \Twig\Environment($loader);

$method = $_SERVER["REQUEST_METHOD"];
$route = $_GET["route"];
if ($method === "GET") {
	if (isset($routes[$route])) {
		// update view count
		DB::increment_views($route);
		echo $twig->render($routes[$route], $substitutes);
	} else {
		echo "<h1>404</h1>";
	}
}
