<?php
load("Alien");

if($me->battleID) {
	error("Currently in battle");
}

Alien::heal(USER);

ok();
?>