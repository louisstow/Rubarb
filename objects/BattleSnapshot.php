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
}
?>