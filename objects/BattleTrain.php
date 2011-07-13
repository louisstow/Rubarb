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
		"exp" => INT,
		"level" => INT,
		"hp" => INT
	);
	
	/**
	* Randomly choose a species from that world
	*/
	public static function choose($area) {
		$q = ORM::query("SELECT * FROM species WHERE world = ? ORDER BY RAND() LIMIT 1", array($area));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		return $data;
	}
	
	public static function chooseMove($species, $level, $exp) {
		$q = ORM::query("SELECT m.*
						 FROM ability a INNER JOIN moves m ON a.moveID = m.moveID
						 WHERE a.speciesID = ? AND a.levelAquired <= ? AND m.selfExp <= ?
						 ORDER BY RAND()
						 LIMIT 1", array($species, $level, $exp));
		
		return $q->fetch(PDO::FETCH_ASSOC);
	}
}
?>