<?php
class Moves extends ORM {
	public static $table = "moves";
	public static $key = array("moveID");
	
	public static $attr = array(
		"moveID" => INT,
		"moveName" => STRING,
		"attackSelf" => INT,
		"defenseSelf" => INT,
		"speedSelf" => INT,
		"expSelf" => INT,
		"hpSelf" => INT,
		"attackOpp" => INT,
		"defenseOpp" => INT,
		"speedOpp" => INT,
		"expOpp" => INT,
		"hpOpp" => INT
	);
	
	public static function environment($env, $env2) {
		if($env == $env2) return 0;
		
		//strengths
		if($env == "water" && $env2 == "fire") return 1;
		if($env == "gas" && $env2 == "water") return 1;
		if($env == "fire" && $env2 == "jungle") return 1;
		if($env == "jungle" && $env2 == "gas") return 1;
		if($env == "rock" && $env2 == "ice") return 1;
		if($env == "lava" && $env2 == "rock") return 1;
		if($env == "ice" && $env2 == "lava") return 1;
		
		//weakness
		if($env2 == "water" && $env == "fire") return -1;
		if($env2 == "gas" && $env == "water") return -1;
		if($env2 == "fire" && $env == "jungle") return -1;
		if($env2 == "jungle" && $env == "gas") return -1;
		if($env2 == "rock" && $env == "ice") return -1;
		if($env2 == "lava" && $env == "rock") return -1;
		if($env2 == "ice" && $env == "lava") return -1;
		
		//else neutral
		return 0;
	}
}
?>