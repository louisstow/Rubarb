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
		"hpSelf" => INT,
		"attackOpp" => INT,
		"defenseOpp" => INT,
		"speedOpp" => INT,
		"hpOpp" => INT,
		"maxAmount" => INT
	);
}
?>