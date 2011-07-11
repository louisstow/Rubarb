<?php
load("Ability, Moves");
data("alien");

$data = Ability::getAttacks($alien);
echo json_encode($data);
?>