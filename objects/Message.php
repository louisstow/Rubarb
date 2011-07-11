<?php
class Message extends ORM {
	public static $table = "messages";
	public static $key = array("messageID");
	
	public static $attr = array(
		"messageID" => INT,
		"fromID" => INT,
		"toID" => INT,
		"message" => STRING,
		"sentDate" => DATE
	);
}
?>