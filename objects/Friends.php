<?php
class Friends extends ORM {
	public static $table = "friends";
	public static $key = array("playerID", "friendID");
	
	public static $attr = array(
		"playerID" => INT,
		"friendID" => INT
	);
	
	public static function getFriends($user) {
		$q = ORM::query("SELECT playerID, screenName, wins, loses, status, location 
				FROM (SELECT friendID
						FROM friends
						WHERE playerID = :user

						UNION

						SELECT playerID
						FROM friends
						WHERE friendID = :user) f
					INNER JOIN players p ON f.friendID = p.playerID",
			array("user" => $user));
			
		$data = array();
		while($row = $q->fetch(PDO::FETCH_ASSOC)) {
			if($row) $data[] = $row;
		}
		return $data;
	}
}
?>