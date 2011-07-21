<?php
load("Battle, BattleTrain, BattleTemp, Alien, Species, Moves, Energy");
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

//grab the aliens and species
$train = I("BattleTrain")->get($me->battleID);
$alien = I("Alien")->get($train->alienID);
$species = I("Species")->get($alien->species);
$temp = I("BattleTemp")->get($me->battleID, $train->alienID);
$energy = I("Energy")->get($train->alienID, $move->moveID);

//if the counter is down to 0
if($energy->amount < 1) {
	error("Ran out of that move");
}

$energy->amount--;
$chance = rand(1, 5) * $temp->speed / $train->speed;

$log = array();

//Player Missed if no damage intended
if($chance < 1 && $move->hpOpp) {
	$a = json_encode($alien);
	
	$log[0] = array(
		"action" => "missed",
		"move" => $move->moveName,
		"moveID" => $move->moveID,
		"me" => $alien
	);
	
} else { //Move Landed
	$effective = Battle::environment($species->world, $me->location);
	
	Battle::applyMove($alien, $temp, $train, $train $move, $effective, $effective);
	
	$log[0] = array(
		"action" => "attack",
		"move" => $move->moveName,
		"moveID" => $move->moveID,
		"damage" => $damage,
		"opp" => clone $train,
		"me" => clone $alien
	);

	//if the alien has lost, player wins training
	if($train->hp <= 0) {
		$train->hp = 0;
			
		Battle::award($alien, $train);
		
		$me->update();
		$alien->update();
		$battle->remove();
		
		$log[0]['win'] = clone $alien;
		
		//echo the current structure
		echo json_encode($log);
		exit;
	}
}

//fight back
$move = BattleTrain::chooseMove($train->species, $train->level, $train->exp);

//no move found, skip
if(!$move) {
	$log[1] = array(
		"action" => "skip"
	);
} else {
	//bias based on the location of the battle
	$effective = Battle::environment($battle->environment, $species->world);
	
	$damage = Battle::applyMove($train, $train, $alien, $temp, $move, $effective, $effective);
	
	$win = "false";
	
	//player lost
	if($alien->hp <= 0) {
		$alien->hp = 0;
		$alien->status = "fainted";
		
		$me->update();
		$battle->remove();
		$win = "true";
	}

	$log[1] = array(
		"action" => "attack",
		"move" => $move['moveName'],
		"moveID" => $move['moveID'],
		"damage" => $damage,
		"me" => clone $alien,
		"opp" => clone $train,
		"win" => $win
	);
}

echo json_encode($log);

$alien->update();
$train->update();
?>