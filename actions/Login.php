<?php
load("Player");
data("username, password");

$password = encrypt($password);
$result = I("Player")->getMany(array("screenName" => $username, "playerPass" => $password));

if($result->count()) {
	$arr = $result->result();
	$_SESSION['id'] = $arr[0]->playerID;
	$arr[0]->status = "online";
	$arr[0]->update();
	echo $result->select("playerID,screenName,email,wins,loses,money,status,location,battleID")->toJSON();
} else {
	error("Details incorrect");
}
?>