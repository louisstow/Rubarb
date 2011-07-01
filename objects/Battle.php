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
}
?>