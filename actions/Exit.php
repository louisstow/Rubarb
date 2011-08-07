<?php
load("Battle");
data("battle");

if(isset($battle)) {
	$b = I("Battle")->get($battle);
} else {
	$b = I("Battle")->get($me->battleID);
}

if($b->status == "waiting") {
	$b->remove();
	$me->battleID = NULL;
	$me->update();
} else {
	error("Battle has started. You must Forfeit");
}

ok();
?>