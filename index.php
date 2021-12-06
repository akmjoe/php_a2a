<?php
// set session name for shared session
if($_GET['shared_game']) {
	session_name($_GET['shared_game']);
	setcookie('shared_game', $_GET['shared_game'], 86400);
	define('SHARED_GAME', $_GET['shared_game']);
} elseif(isset($_COOKIE['shared_game'])) {
	session_name($_COOKIE['shared_game']);
	define('SHARED_GAME', $_COOKIE['shared_game']);
}
session_start();
// refresh user cookie
if(isset($_COOKIE['user']) && !isset($_GET['logout'])) {
	setcookie('user', $_COOKIE['user'], 3600);
}
// main handler page
// load configuration
require_once 'config.php';
require_once DIR_INCLUDES.'application_top.php';
// load page (page classes must extend play)
if(isset($_GET['page']) && $_GET['page']) {
	$page = $_GET['page'];
	$game = new $page();
} else {
	$game = new play();
}
// load required page
require DIR_TEMPLATES.$game->template;

?>