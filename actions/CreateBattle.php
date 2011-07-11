<?php
load("BattleRequest, Battle, BattleTeam, BattlePVP, BattleSnapshot");
data("type, area, friend, teama, teamb");


$battle = I("Battle")->create(D, $type, USER, USER, NOW(), 'NULL', $area);

//Team Battle
if($type == "team") {
	$teama = explode(',', $teama);
	$teamb = explode(',', $teamb);
	
	//send a request for each member except self
	foreach($teama as $player) {
		if($player != USER) {
			I("BattleRequest")->create($battle->battleID, $player, NOW());
		}
		I("BattleTeam")->create($battle->battleID, $player, 'A', 0, NOW());
	}
	
	foreach($teamb as $player) {
		if($player != USER) {
			I("BattleRequest")->create($battle->battleID, $player, NOW());
		}
		I("BattleTeam")->create($battle->battleID, $player, 'B', 0, NOW());
	}
	
	//update the amount of players needing to confirm
	$battle->needed = count($teama) + count($teamb);
	$battle->update();
//Player vs Player
} else if($type == "pvp") {
	I("BattlePVP")->create($battle->battleID, USER, 0, NOW(), $friend, 0, NOW());
} else if($type == "test") {
	I("BattlePVP")->create($battle->battleID, USER, 0, NOW(), $friend, 0, NOW());
	BattleSnapshot::setup($battle->battleID, USER, $friend);
}

ok();
?>