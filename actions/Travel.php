<?php
load();
data("to");

$start = $me->location;
$cost = 0;

if($start == $to) {
	ok();
}

if($start == "water" && $to == "fire") $cost = 50;
if($start == "water" && $to == "rock") $cost = 50;
if($start == "water" && $to == "ice") $cost = 50;
if($start == "water" && $to == "gas") $cost = 50;
if($start == "water" && $to == "ice") $cost = 50;

if($me->location
?>