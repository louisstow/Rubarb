<?php
load("Battle, BattlePVP, BattleTemp, BattleSnapshot, Moves, BattleLog, Alien, Energy, Species, Player");
data("move, battle");

if(isset($battle)) {
	//if Test Match
	$battle = I("Battle")->get($battle);
} else {
	$battle = I("Battle")->get($me->battleID);
}

if(!$battle || $battle->turn != USER) {
	error("Not your turn");
}

$pvp = I("BattlePVP")->get($battle->battleID);

//determine who's the Player and who is the Opponent
$title = (USER == $pvp->playerID) ? "player" : "opponent";
$opp = (USER == $pvp->playerID) ? "opponent" : "player";

if(USER == $pvp->playerID) {
	$pID = $pvp->playerID;
	$oID = $pvp->opponentID;
	$pA = $pvp->playerAlien;
	$oA = $pvp->opponentAlien;
	$title = "p1";
	$otitle = "p2";
} else {
	$pID = $pvp->opponentID;
	$oID = $pvp->playerID;
	$pA = $pvp->opponentAlien;
	$oA = $pvp->playerAlien;
	$title = "p2";
	$otitle = "p1";
}

$move = I("Moves")->get($move);

//check all valid
if(!$move || !$battle) {
	error("Invalid battle");
}

//grab the instance of the aliens
if($battle->type == "pvp") {
	$p = I("Alien")->get($pA);
	$o = I("Alien")->get($oA);
	$ptemp = I("BattleTemp")->get($battle->battleID, $pA);
	$otemp = I("BattleTemp")->get($battle->battleID, $oA);
} else if($battle->type == "test") {
	$p = I("BattleSnapshot")->get($battle->battleID, $pA);
	$o = I("BattleSnapshot")->get($battle->battleID, $oA);
	$ptemp = $p;
	$otemp = $o;
}

$energy = I("Energy")->get($pA, $move->moveID);

//find the species information of the aliens
$ps = I("Species")->get($p->species);
//$os = I("Species")->get($o->species);

if(!$move || !$p || !$o || !$energy) {
	error("Invalid details");
}

//if the counter is down to 0
if($energy->amount < 1) {
	error("Ran out of that move");
}

$energy->amount--;
$energy->update();

$chance = rand(1, 5) * $p->speed / $o->speed;

$log = array();

//Player Missed
if($chance < 1 && $move->hpOpp) {
	$log['action'] = "missed";
	$log['move'] = clone $move;
	$log['turn'] = $battle->turn;
	$log[$title] = clone $p;
	
	I("BattleLog")->create($battle->battleID, NOW(), json_encode($log));
} else { //Move Landed
	$effective = Battle::environment($ps->world, $battle->environment);
	$movebias = Battle::environment($move->moveType, $battle->environment);
	
	$damage = Battle::applyMove($p, $ptemp, $o, $otemp, $move, $movebias, $effective);
	
	if($o->hp <= 0) {
		$o->hp = 0;
		$o->status = "fainted";
		
		if($battle->type == "test") {
			$next = Alien::getNext($o->playerID, $o->alienID);
		} else {
			$next = BattleSnapshot::getNext($o->playerID, $o->alienID);
		}
		
		//swap the opponents alien with the next
		if($next) {
			$pvp->{$opp . "Alien"} = $next;
			$log['replace'] = I("Alien")->get($next);
		} else {
			//WINNER
			$awards = Battle::award($p, $o);
			
			//update the loses for the opponent
			$opponent = I("Player")->get($oID);
			$opponent->loses++;
			$opponent->update();
			
			$me->wins++;
			$me->update();
			$battle->remove();
			
			$log['win'] = $awards;
		}
	}
	
	$log["action"] = "attack";
	$log["move"] = clone $move;
	$log["damage"] = $damage;
	$log['turn'] = $battle->turn;
	$log[$title] = clone $p;
	$log[$otitle] = clone $o;
	
	if($battle->type == "pvp") {
		$log[$title."stats"] = clone $ptemp;
		$log[$otitle."stats"] = clone $otemp;
	}
	
	$p->update();
	$o->update();
	
	//only update if not test match
	if($battle->type != "test") {
		$ptemp->update();
		$otemp->update();
	}
	
	//log the action
	I("BattleLog")->create($battle->battleID, NOW(), json_encode($log));
}

//alternate the turn
$battle->turn = (USER == $pvp->playerID) ? $pvp->opponentID : $pvp->playerID;
$battle->update();

//update last active
$pvp->{$title . "Active"} = NOW();
$pvp->update();

echo json_encode($log);
?>