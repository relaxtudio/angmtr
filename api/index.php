<?php  
require_once('conn/db-conf.php');
require_once('en_route.php');
require_once('en_func.php');

$url = explode('api/', $_SERVER['REQUEST_URI']);
$param = explode('/', $url[1]);
$func = $param[0];
if (function_exists($func)) {
	$func();
} else {
	return null;
}