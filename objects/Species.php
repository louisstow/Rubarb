<?php
class Species extends ORM {
	public static $table = "species";
	public static $key = array("speciesID");
	
	public static $attr = array(
		"speciesID" => INT,
		"speciesName" => STRING,
		"speciesDescr" => STRING,
		"world" => STRING,
		"attack" => INT,
		"defense" => INT,
		"speed" => INT
	);
}
?>