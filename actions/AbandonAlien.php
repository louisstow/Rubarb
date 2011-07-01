<?php
load("Alien");
data("alien");

//remove the alien
$alien = I("Alien")->get($alien);

//ensure they own the alien
if($alien->playerID == USER) {
	$alien->remove();
} else {
	hacking();
}

ok();
?>