<?php
load("Request, Friends");
data("friend, response");

$r = I("Request")->get($friend, USER);
if(!$r) {
	error("No request found");
}

//if accept
if($response == "A") {
	I("Friends")->create($friend, USER, NOW());
	$r->remove();
} else if($response == "D") {
	$r->remove();
}

ok();
?>