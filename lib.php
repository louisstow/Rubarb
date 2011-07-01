<?php
session_start();
$_SESSION['id'] = 1;

/**
* If a user is suspected of hacking attempts,
* put a strike on their record and 3 attempts
* means banned.
*/
define("LOW", 0);
define("MEDIUM", 1);
define("SEVERE", 2);

define("SALT", "AND PEPPER");

function hacking($level=LOW) {
	//TODO: log this attempt
	exit;
}

/**
* Include the methods from this file
*/
function load($list) {
	$vars = explode(',',$list);
	foreach($vars as $var) {
		$var = trim($var);
		if(!class_exists($var) && file_exists("objects/" . $var . ".php")) {
			include "objects/" . $var . ".php";
		}
	}
}

/**
* Grab the data from URL variables and
* create constants
*/
function data($list) {
	$vars = explode(',',$list);
	foreach($vars as $var) {
		$var = trim($var);
		if(isset($_GET[$var])) {
			$GLOBALS[$var] = $_GET[$var];
		}
	}
}

/**
* Encrypt a string with SHA1 and a salt
*/
function encrypt($str) {
	return sha1(SALT . $str);
}

/**
* Get the current Date
*/
function NOW() {
	return date('Y-m-d H:i:s');
}

/**
* Send an error to the client
*/
function error($msg) {
	echo "{error: '{$msg}'}";
	exit;
}

function ok() {
	echo "{status: 'ok'}";
	exit;
}

define("USER", $_SESSION['id']);
?>