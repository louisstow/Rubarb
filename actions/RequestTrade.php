<?php
load("Trade");
data("friend");

//remove past trade requests
$data = I("Trade")->getMany(array("status" => "Waiting", "playerID" => USER, "friendID" => $friend));

foreach($data as $trade) {
	$trade->remove();
}

//create a temporary trade request. Deleted after 1 day of no response
I("Trade")->create(D, USER, $friend, NOW());

ok();
?>