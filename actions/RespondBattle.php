<?php
load("BattleRequest, Battle, BattlePVP, BattleSnapshot, BattleTeam");
data("battle, response");

$battle = I("Battle")->get($battle);

if($response == "A") {
	//make sure they are involved in the battle
	if($battle->type == 'team') {
		$q = I("BattleTeam")->getMany(array("battleID" => $battle->battleID, "playerID" => USER));
		if($q->count() < 1) {
			error("Not part of this battle");
		}
	} else {
		$q = I("BattlePVP")->get($battle->battleID);
		
		if($q->playerID != USER && $q->opponentID != USER) {
			error("Not part of this battle");
		}
	}

	//update the player count
	$battle->accepted++;
	if($battle->accepted == $battle->needed) {
		$battle->status = 'accepted';
	}
	$battle->update();
	
	//update the players status
	if($battle->type == "pvp") {
		$me->battleID = $battle->battleID;
		$me->update();
	}

	ok();
} else {
	$battle->remove();
	
	ok();
}	
?>