<?php
class BattleTrain extends ORM {
	public static $table = "battle_trainers";
	public static $key = array("battleID");
	
	public static $attr = array(
		"battleID" => INT,
		"alienID" => INT,
		"alienAlias" => STRING,
		"species" => INT,
		"attack" => INT,
		"defense" => INT,
		"speed" => INT,
		"level" => INT,
		"hp" => INT,
		"maxHP" => INT
	);
	
	/**
	* Randomly choose a species from that world
	*/
	public static function choose($area) {
		switch($area) {
			case "water":
				$id = 7;
				$name = "Dooth";
				break;
			case "fire":
				$id = 1;
				$name = "Possel";
				break;
			case "ice":
				$id = 4;
				$name = "Skarrier";
				break;
			case "jungle":
				$id = 10;
				$name = "Apelim";
				break;
			case "rock":
				$id = 16;
				$name = "Diggimal";
				break;
			case "lava":
				$id = 17;
				$name = "Samalanda";
				break;
			case "gas":
				$id = 20;
				$name = "Sepelem";
				break;
		}
		
		return array("speciesID" => $id, "speciesName" => $name);
		$q = ORM::query("SELECT * FROM species WHERE world = ? ORDER BY RAND() LIMIT 1", array($area));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		return $data;
	}
	
	public static function chooseMove($species, $level) {
		$q = ORM::query("SELECT m.*
						 FROM ability a INNER JOIN moves m ON a.moveID = m.moveID
						 WHERE a.speciesID = ? AND a.levelAquired <= ?
						 ORDER BY RAND()
						 LIMIT 1", array($species, $level));
		
		return $q->fetch(PDO::FETCH_OBJ);
	}
}
?>