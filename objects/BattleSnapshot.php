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
		"hp" => INT,
		"maxExp" => INT,
		"maxHP" => INT
	);
	
	public static function setup($battle, $player, $friend) {
		ORM::query("INSERT INTO battle_snapshot 
					SELECT {$battle}, alienID, alienAlias, species, playerID, attack, defense, speed, exp, level, hp, maxHP
					FROM aliens
					WHERE (playerID = ? OR playerID = ?) AND status = 'carried'", array($player, $friend));
	}
	
	public static function get($user) {
		$q = ORM::query("SELECT b.battleID, p.screenName, p2.screenName AS playerName, b.playerID, b.opponentID, b.playerActive, b.opponentActive, x.environment, x.turn
						 FROM battle_pvp b 
							INNER JOIN battle x USING(battleID)
							INNER JOIN players p ON b.opponentID = p.playerID
							INNER JOIN players p2 ON b.playerID = p2.playerID
						 WHERE (x.status = 'accepted' OR (x.ownerID = :user AND x.status = 'waiting')) AND (b.playerID = :user OR b.opponentID = :user)", array("user" => $user));
			
		$data = array();
		while($row = $q->fetch(PDO::FETCH_ASSOC)) {
			//determine who is the player and who is the opponent
			if($row['playerID'] == $user) {
				$row['playerID'] = $row['opponentID'];
			} else {
				$row['screenName'] = $row['playerName'];
			}
			
			if(strtotime($row['playerActive']) > strtotime($row['opponentActive'])) {
				$row['lastActive'] = $row['playerActive'];
			} else {
				$row['lastActive'] = $row['opponentActive'];
			}
			
			//normalize the property names
			unset($row['opponentID']);
			unset($row['playerName']);
			unset($row['playerActive']);
			unset($row['opponentActive']);
			
			$data[] = $row;
		}
		
		return $data;
	}
	
	
	/**
	* Get the next alien available from a player. If none left,
	* then return FALSE.
	*/
	public static function getNext($user, $alien=NULL) {
		//grab the first alien the user is carrying
		$q = ORM::query("SELECT alienID FROM battle_snapshot WHERE playerID = ? AND status = 'carried' AND alienID <> ? ORDER BY alienOrder LIMIT 1", 
			array($user, $alien));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		if($data) {
			return $data['alienID'];
		} else return false;
	}
}
?>