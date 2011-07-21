<?php
class BattleTemp extends ORM {
	public static $table = "battle_temp";
	public static $key = array("battleID", "alienID");
	
	public static $attr = array(
		"battleID" => INT,
		"alienID" => INT,
		"attack" => INT,
		"defense" => INT,
		"speed" => INT
	);
}
?>