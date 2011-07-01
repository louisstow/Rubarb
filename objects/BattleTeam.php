<?php
class BattleTeam extends ORM {
	public static $table = "battle_team";
	public static $key = array("battleID", "playerID", "team");
	
	public static $attr = array(
		"battleID" => INT,
		"playerID" => INT,
		"team" => STRING,
		"alienID" => INT,
		"lastActive" => DATE
	);
}
?>