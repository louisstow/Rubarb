<?php
load("Battle, BattlePVP, BattleLog");
data("battle");

if(isset($battle)) {
	$b = I("Battle")->get($battle);
} else {
	$b = I("Battle")->get($me->battleID);
	$battle = $me->battleID;
	
	if(!$b) {
		$me->battleID = NULL;
		$me->update();
	}
}

//show the latest action first
$latest = BattleLog::getLatest($battle);
echo $latest['data'];

$pvp = I("BattlePVP")->get($battle);
if(!$pvp) {
	exit;
}

//determine who's the Player and who is the Opponent
$title = (USER == $pvp->playerID) ? "player" : "opponent";
$opp = (USER == $pvp->playerID) ? "opponent" : "player";

//if waiting on the opponent
if($b->turn != USER && $b->type == "pvp") {
	$inactive = strtotime($pvp->{$opp . "Active"});
	
	if(strtotime("+5 minutes", $inactive) < time()) {
		$b->turn = USER;
		$b->update();
		
		//make update the activeness
		$pvp->{$opp . "Active"} = NOW();
		$pvp->{$title . "Active"} = NOW();
		$pvp->update();
		
		$opp = $pvp->{$opp . "ID"};
		
		I("BattleLog")->create($b->battleID, NOW(), "{action: 'inactive', turn: $opp}");
	}
}
?>