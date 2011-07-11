<?php
class BattleRequest extends ORM {
	public static $table = "battle_request";
	public static $key = array("battleID", "playerID");
	
	public static $attr = array(
		"battleID" => INT,
		"playerID" => INT,
		"requestDate" => DATE
	);
}
?>