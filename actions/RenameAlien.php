<?php
load("Alien");
data("alien, name");

$alien = I("Alien")->get($alien);
if($alien->playerID != USER) {
	hacking();
}

$alien->alienAlias = $name;
$alien->update();

ok();
?>