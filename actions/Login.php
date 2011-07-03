<?php
load("Player");
data("username, password");

$password = encrypt($password);
$result = I("Player")->getMany(array("screenName" => $username, "playerPass" => $password));

if($result->count()) {
	//get the first user
	$arr = $result->result();
	$arr = $arr[0];
	
	$_SESSION['id'] = $arr->playerID;
	$arr->status = "online";
	$arr->update();
	
	unset($arr->playerPass);
	unset($arr->_updateFlag);
	echo json_encode($arr);
} else {
	error("Details incorrect");
}
?>