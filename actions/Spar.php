<?php
load("BattleTrain, Alien, Species, Moves");
data("move");

if(!$me->battleID) {
	error("Not in battle");
}

//grab data
$move = I("Moves")->get($move);
$battle = I("Battle")->get($me->battleID);

//check all valid
if(!$move || !$battle || $battle->type != "training") {
	error("Invalid battle");
}

$train = I("BattleTrain")->get($me->battleID);

//grab the aliens and species
$alien = I("Alien")->get($train->alienID);
$species = I("Species")->get($alien->species);

$chance = rand(1, 5) * $alien->speed / $train->speed;

//Player Missed
if($chance < 1) {
	echo "{a: 'missed', t: {$battle->turn}, m: '{$move->moveName}'}";
	//take away EXP
	$alien->exp -= $move->expSelf;
	$alien->update();
	
} else { //Move Landed
	//effectivity based on the environment of the aliens
	$effective = Moves::environment($species->world, $me->location) * ($alien->attack * 0.2);

	//bias based on the location of the battle
	$bias = Moves::environment($species->world, $battle->environment) * ($alien->attack / 0.1);

	$damage = $effective + $bias + $move->hpOpp + $alien->attack - $train->defense;

	//apply moves to self
	$alien->defense += $move->defenseSelf;
	$alien->attack += $move->attackSelf;
	$alien->speed += $move->speedSelf;

	$alien->exp -= $move->expSelf;
	$alien->hp -= $move->hpSelf;

	//apply to opponent
	$train->defense -= $move->defenseOpp;
	$train->attack -= $move->attackOpp;
	$train->speed -= $move->speedOpp;

	$train->exp -= $move->expOpp;
	$train->hp -= $damage;

	//if the alien has lost, player wins training
	if($train->hp <= 0) {
		$train->hp = 0;
			
		//calculate the differences in stats
		$adiff = ($train->attack - $alien->attack) < 0 ? 0 : $train->attack - $alien->attack;
		$ddiff = ($train->defense - $alien->defense) < 0 ? 0 : $train->defense - $alien->defense;
		$sdiff = ($train->speed - $alien->speed) < 0 ? 0 : $train->speed - $alien->speed;
		
		$me->battleID = 0;
		
		$alien->attack += ceil(($adiff * 0.5) + 10);
		$alien->defense += ceil(($ddiff * 0.5) + 10);
		$alien->speed += ceil(($sdiff * 0.5) + 10);
		
		//increase level based on the average of the stats
		$level = ceil(($alien->attack + $alien->defense + $alien->speed) / 3 * 0.1);
		if($level > $alien->level) {
			$alien->level = $level;
		}
		$alien->exp += $level * 5;
		
		$me->update();
		$battle->remove();
		
		$a = json_encode($alien);
		echo "{a: 'win', n: {$a}}";
	} else {
		//fight back
		$move = BattleTrain::chooseMove($train->species, $train->level, $train->exp);
		
		//effectivity based on the environment of the aliens
		$effective = Moves::environment($me->location, $species->world) * ($alien->attack * 0.2);

		$damage = $effective + $move->hpOpp + $train->attack - $alien->defense;

		//apply moves to self
		$train->defense += $move->defenseSelf;
		$train->attack += $move->attackSelf;
		$train->speed += $move->speedSelf;

		$train->exp -= $move->expSelf;
		$train->hp -= $move->hpSelf;

		//apply to opponent
		$alien->defense -= $move->defenseOpp;
		$alien->attack -= $move->attackOpp;
		$alien->speed -= $move->speedOpp;

		$alien->exp -= $move->expOpp;
		$alien->hp -= $damage;
		
		$win = "false";
		//player lost
		if($alien->hp <= 0) {
			$me->update();
			$battle->remove();
			$win = "true";
		}
		
		$a = json_encode($alien);
		echo "{a: 'attack', m: '{$move->moveName}', w: {$alien}, win: '{$win}'}";
	}
	
	$alien->update();
	$train->update();
	
}

?>