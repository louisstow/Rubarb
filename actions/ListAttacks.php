<?php
load("Ability, Moves, Energy");
data("alien");

$data = Energy::getAttacks($alien);
echo json_encode($data);
?>