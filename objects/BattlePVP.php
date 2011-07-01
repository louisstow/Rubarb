<?php
class BattlePVP extends ORM {
	public static $table = "battle_pvp";
	public static $key = array("battleID");
	
	public static $attr = array(
		"battleID" => INT,
		"playerID" => INT,
		"playerAlien" => INT,
		"playerActive" => DATE,
		"opponentID" => INT,
		"opponentAlien" => INT,
		"opponentActive" => DATE
	);
}
?>