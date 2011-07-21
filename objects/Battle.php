<?php
class Battle extends ORM {
	public static $table = "battle";
	public static $key = array("battleID");
	
	public static $attr = array(
		"battleID" => INT,
		"type" => STRING,
		"ownerID" => INT,
		"turn" => INT,
		"startTime" => DATE,
		"endTime" => DATE,
		"environment" => STRING
	);
	
	public static function environment($env, $env2) {
		if($env == $env2) return 1;
		
		//strengths
		if($env == "water" && $env2 == "fire") return 1.5;
		if($env == "gas" && $env2 == "water") return 1.5;
		if($env == "fire" && $env2 == "jungle") return 1.5;
		if($env == "jungle" && $env2 == "gas") return 1.5;
		if($env == "rock" && $env2 == "ice") return 1.5;
		if($env == "lava" && $env2 == "rock") return 1.5;
		if($env == "ice" && $env2 == "lava") return 1.5;
		
		//weakness
		if($env2 == "water" && $env == "fire") return 0.5;
		if($env2 == "gas" && $env == "water") return 0.5;
		if($env2 == "fire" && $env == "jungle") return 0.5;
		if($env2 == "jungle" && $env == "gas") return 0.5;
		if($env2 == "rock" && $env == "ice") return 0.5;
		if($env2 == "lava" && $env == "rock") return 0.5;
		if($env2 == "ice" && $env == "lava") return 0.5;
		
		//else neutral
		return 1;
	}
	
	/**
	* Apply a move to a Alien.
	*
	* @param player - Alien executing the move
	* @param ptemp - Temporary stats
	* @param opp - Opponent alien who recieves the move
	* @parama otemp - Temporary opponent stats
	* @param move - Move to apply
	*/
	public static function applyMove(&$player, &$ptemp, &$opp, &$otemp, $move, $effective=1, $bias=1) {
		//only inflict damage if the move is intended to
		if($move->hpOpp) {
			$damage = floor($player->level * (($move->hpOpp / 10) + 1) + $ptemp->attack - $otemp->defense - $opp->level * $effective * $bias * rand(0.85, 1));
		} else {
			$damage = 0;
		}
		
		//apply moves to self
		$ptemp->defense += ceil($ptemp->defense * ($move->defenseSelf / 100));
		$ptemp->attack += ceil($ptemp->attack * ($move->attackSelf / 100));
		$ptemp->speed += ceil($ptemp->speed * ($move->speedSelf / 100));

		$player->hp -= $move->hpSelf;

		//apply to opponent
		$otemp->defense -= ceil($otemp->defense * ($move->defenseOpp / 100));
		$otemp->attack -= ceil($otemp->attack * ($move->attackOpp / 100));
		$otemp->speed -= ceil($otemp->speed * ($move->speedOpp / 100));

		$opp->hp -= $damage;
		
		return $damage;
	}
	
	public static function award(&$player, &$opp) {
		//calculate the differences in stats
		$ldiff = ($opp->level - $player->level) < 0 ? 0 : $opp->level - $player->level;
		$adiff = ($opp->attack - $player->attack) < 0 ? 0 : $opp->attack - $player->attack;
		$ddiff = ($opp->defense - $player->defense) < 0 ? 0 : $opp->defense - $player->defense;
		$sdiff = ($opp->speed - $player->speed) < 0 ? 0 : $opp->speed - $player->speed;
		
		$player->attack += ceil($adiff * 0.5);
		$player->defense += ceil($ddiff * 0.5);
		$player->speed += ceil($sdiff * 0.5);
		
		//increase level based on the average of the stats
		$player->exp += ceil(($adiff + $ddiff + $sdiff) / 3 + 30 + $ldiff * 10);
		$player->maxHP = 3 * ($level + 1) + 1;
		
		//amount of EXP required is exp^3
		$level = ceil(pow($player->exp, 1/3));
		
		if($level > $player->level) {
			$player->level = $level;
			Energy::init();
		}
		
		
	}
}
?>