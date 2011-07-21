<?php
load("Alien, Energy");

if($me->battleID) {
	error("Currently in battle");
}

Alien::heal(USER);
Energy::restore(USER);

ok();
?>