<?php
load("Battle, BattlePVP, BattleTeam, BattleSnapshot, Moves, BattleLog, Alien, Species, Player");
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

$move = I("Moves")->get($move); //TODO: check if has move

//grab the instance of the aliens
if($battle->type == "pvp") {
	$p = I("Alien")->get($pvp->{$title . "Alien"});
	$o = I("Alien")->get($pvp->{$opp . "Alien"});
} else if($battle->type == "test") {
	$p = I("BattleSnapshot")->get($battle->battleID, $pvp->{$title . "Alien"});
	$o = I("BattleSnapshot")->get($battle->battleID, $pvp->{$opp . "Alien"});
}

//find the species information of the aliens
$ps = I("Species")->get($p->species);
$os = I("Species")->get($o->species);

if(!$move || !$p || !$o) {
	error("Invalid details");
}

if($move->expSelf > $p->exp) {
	error("Not enough EXP");
}

//if the alien is anything other than carried
if($p->status != "carried") {
	error("Switch aliens");
}

$chance = rand(1, 5) * $p->speed / $o->speed;

//Player Missed
if($chance < 1) {
	echo "{a: 'missed', t: {$battle->turn}, m: '{$move->moveName}'}";
	I("BattleLog")->create($battle->battleID, NOW(), "{a: 'missed', t: {$battle->turn}, m: '{$move->moveName}'}");
	
	//take away EXP
	$p->exp -= $move->expSelf;
	$p->update();
} else { //Move Landed
	//efectivity based on the environment of the aliens
	$effective = Moves::environment($ps->world, $os->world) * ($p->attack * 0.2);

	//bias based on the location of the battle
	$bias = Moves::environment($ps->world, $battle->environment) * ($p->attack / 0.1);

	$damage = $effective + $bias + $move->hpOpp + $p->attack - $o->defense;

	//apply moves to self
	$p->defense += $move->defenseSelf;
	$p->attack += $move->attackSelf;
	$p->speed += $move->speedSelf;

	$p->exp -= $move->expSelf;
	$p->hp -= $move->hpSelf;

	//apply to opponent
	$o->defense -= $move->defenseOpp;
	$o->attack -= $move->attackOpp;
	$o->speed -= $move->speedOpp;

	$o->exp -= $move->expOpp;
	$o->hp -= $damage;

	$fainted = "false";
	$win = "false";
	if($o->hp <= 0) {
		$o->hp = 0;
		$o->status = "fainted";
		$fainted = "true";
		
		$next = Alien::getNext($o->playerID);
		
		//swap the opponents alien with the next
		if($next) {
			$pvp->{$opp . "Alien"} = $next;
			$fainted = $next;
		} else {
			//WINNER
			$win = "true";
			
			//calculate the differences in stats
			$adiff = ($o->attack - $p->attack) < 0 ? 0 : $o->attack - $p->attack;
			$ddiff = ($o->defense - $p->defense) < 0 ? 0 : $o->defense - $p->defense;
			$sdiff = ($o->speed - $p->speed) < 0 ? 0 : $o->speed - $p->speed;
			
			$me->money += ceil(($adiff + $ddiff + $sdiff) * 5 + 100);
			$me->wins++;
			$me->battleID = 0;
			
			$p->attack += ceil(($adiff * 0.5) + 10);
			$p->defense += ceil(($ddiff * 0.5) + 10);
			$p->speed += ceil(($sdiff * 0.5) + 10);
			
			
			//increase level based on the average of the stats
			$level = ceil(($p->attack + $p->defense + $p->speed) / 3 * 0.1);
			if($level > $p->level) {
				$p->level = $level;
			}
			$p->exp += $level * 5;
			
			//update the loses for the opponent
			$opponent = I("Player")->get($pvp->{$opp . "ID"});
			$opponent->loses++;
			$opponent->battleID = 0;
			$opponent->update();
			
			$me->update();
			$battle->remove();
		}
	}
	
	$p->update();
	$o->update();
	
	//log the action
	$opponent = json_encode($o);
	$player = json_encode($p);
	I("BattleLog")->create($battle->battleID, NOW(), "{a: 'attack', w: {$win}, f: {$fainted}, d: {$damage}, m: '{$move->moveName}', o: {$opponent}, p: {$player}, t: {$battle->turn}}");
}

//alternate the turn
$battle->turn = (USER == $pvp->playerID) ? $pvp->opponentID : $pvp->playerID;
$battle->update();

//update last active
$pvp->{$title . "Active"} = NOW();
$pvp->update();

ok();
?>