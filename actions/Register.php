<?php
load("Player");
data("username, email, password");

$password = encrypt($password);
$player = I("Player")->create(D, $username, $email, $password);

if(!$player) {
	//if username taken
	$err = ORM::error();
	if($err[0] == "23000") {
		error("Username taken");
	}
	
	error("Error registering");
}

echo json_encode($player);
?>