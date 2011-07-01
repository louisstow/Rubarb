<?php
load("Alien");

//grab all the Aliens associated to the current USER
echo I("Alien")->getMany(array("playerID" => USER))->toJSON();
?>