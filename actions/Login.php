<?php
load("Player");
data("username, password");

$password = encrypt($password);
$result = I("Player")->getMany(array("screenName" => $username, "userPass" => $password))->result();

if(count($result)) {
	$_SESSION['id'] = $result[0]->playerID;
	ok();
} else {
	error("Details incorrect");
}
?>