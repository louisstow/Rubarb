<?php
class BattleLog extends ORM {
	public static $table = "battle_log";
	public static $key = array("battleID", "logTime");
	
	public static $attr = array(
		"battleID" => INT,
		"logTime" => DATE,
		"data" => STRING
	);
	
	public static function getLatest($battle) {
		$q = ORM::query("SELECT data, logTime FROM battle_log WHERE battleID = ? ORDER BY logTime desc LIMIT 1", array($battle));
		
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return $data;
	}
}
?>