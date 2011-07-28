<?php
load("BattleSnapshot");

echo json_encode(BattleSnapshot::get(USER));
?>