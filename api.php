<?php
include 'lib.php';
include 'ORM.php';

$a = trim($_GET['action']);
//if the action contains non-alpha hacking attempt
if($a === "" && preg_match("/[^a-zA-Z\-]/", $a)) {
	hacking();
}
//if the action does not exist, label as hacking attempt
if(!file_exists("actions/" . $a . ".php")) {
	hacking();
}

if(!isset($_SESSION['id'])) {
	//if user is not intending to Login or Register
	if($a != "Login" && $a != "Register") error("Please login");
} else {
	load("Player");
	$me = I("Player")->get(USER);
	
	//update the users last active status
	$me->lastActive = NOW();
	if($me->status === "offline") {
		$me->status = "online";
	}
	
	$me->update();
}

include "actions/" . $a . ".php";
?>