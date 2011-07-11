<?php
class BattleSnapshot extends ORM {
	public static $table = "battle_snapshot";
	public static $key = array("battleID", "alienID");
	
	public static $attr = array(
		"battleID" => INT,
		"alienID" => INT,
		"alienAlias" => STRING,
		"playerID" => INT,
		"species" => STRING,
		"attack" => INT,
		"defense" => INT,
		"speed" => INT,
		"exp" => INT,
		"level" => INT,
		"hunger" => INT,
		"thirst" => INT,
		"hp" => INT
	);
	
	public static function setup($battle, $player, $friend) {
		ORM::query("INSERT INTO battle_snapshot 
					SELECT {$battle}, alienID, alienAlias, playerID, species, attack, defense, speed, exp, level, hunger, thirst, hp
					FROM aliens
					WHERE (playerID = ? OR playerID = ?) AND status = 'carried'", array($player, $friend));
	}
}
?>