<?php
load("Battle, BattlePVP, BattleLog");
data("battle");

if(isset($battle)) {
	$battle = I("Battle")->get($battle);
} else {
	$battle = I("Battle")->get($me->battleID);
}

if(!$battle) {
	error("Battle not found");
}

$pvp = I("BattlePVP")->get($battle->battleID);

//show the latest action first
$latest = BattleLog::getLatest($battle->battleID);
echo $latest['data'];

//determine who's the Player and who is the Opponent
$title = (USER == $pvp->playerID) ? "player" : "opponent";
$opp = (USER == $pvp->playerID) ? "opponent" : "player";

//if waiting on the opponent
if($battle->turn != USER && $battle->type == "pvp") {
	$inactive = strtotime($pvp->{$opp . "Active"});
	if(strtotime("+5 minutes", $inactive) > NOW()) {
		$battle->turn = USER;
		$battle->update();
		
		$opp = $pvp->{$opp . "ID"};
		I("BattleLog")->create($battle->battleID, NOW(), "{a: 'inactive', turn: $opp}");
	}
}
?>