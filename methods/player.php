<?php
/**
* Grab the details for the players
*/
function playerDetails() {
	$q = query("SELECT * FROM player WHERE playerID = :user LIMIT 1", array("user" => USER));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	return $data;
}

/**
* Get players at a certain area
*/
function getPlayers($area) {
	$q = query("SELECT playerID, screenName, wins, loses FROM players WHERE location = :area AND status = 'online'",
		array("area" => $area));
		
	$data = array();
	while($row = $q->fetch(PDO::FETCH_ASSOC)) {
		if($row) $data[] = $row;
	}
	return $data;
}

/**
* Grab the details of the players friends
*/
function getFriends() {
	$q = query("SELECT playerID, screenName, wins, loses, status, location 
				FROM (SELECT friendID
						FROM friends
						WHERE playerID = :user

						UNION

						SELECT playerID
						FROM friends
						WHERE friendID = :user) f
					INNER JOIN players p ON f.friendID = p.playerID",
		array("area" => $area));
		
	$data = array();
	while($row = $q->fetch(PDO::FETCH_ASSOC)) {
		if($row) $data[] = $row;
	}
	return $data;
}

/**
* Register a player
*/
function addPlayer($screen, $email, $pass) {
	query("INSERT INTO players VALUES(DEFAULT, :screen, :email, :pass, 0, 0, 500, 'online')",
		array("screen" => $screen, "email" => $email, "pass" => encrypt($pass)));
		
	return lastID();
}
?>