<?php
load("Alien, BattleRequest, Battle, BattlePVP, BattleSnapshot, BattleTeam");
data("battle, response");

$battle = I("Battle")->get($battle);
if(!$battle) {
	error("Battle not found");
}

if($response == "A") {
	//make sure they are involved in the battle
	$q = I("BattlePVP")->get($battle->battleID);
	
	if($q->playerID != USER && $q->opponentID != USER) {
		error("Not part of this battle");
	}
	
	$battle->status = 'accepted';
	$battle->update();
	
	//update the players status
	if($battle->type == "pvp") {
		$me->battleID = $battle->battleID;
		$me->update();
	}

	echo json_encode(array(
		"battle" => $battle,
		"p1" => I("Alien")->get($q->playerAlien),
		"p2" => I("Alien")->get($q->opponentAlien)
	));
} else {
	$battle->remove();
	
	ok();
}	
?>