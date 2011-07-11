<?php
load("Alien");
data("alien, store");

$a = I("Alien")->get($alien);
if($a->playerID != USER) {
	error("No alien found");
}

if($store == "carried") {
	$q = Alien::getMany(array("status" => "carried", "playerID" => USER));
	if($q->count() >= 10) {
		error("Can only carry 10 aliens");
	} else {
		$a->status = "carried";
	}
} else if($store == "stored") {
	$a->status = "stored";
} else {
	error("Store not possible");
}

$a->update();
ok();
?>