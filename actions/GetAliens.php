<?php
load("Alien");
data("stored");

//grab all the Aliens associated to the current USER
if(isset($stored)) {
	$sql = "SELECT * FROM aliens WHERE playerID = ? AND status = 'stored' ORDER BY alienOrder";
} else {
	$sql = "SELECT * FROM aliens WHERE playerID = ? AND (status = 'carried' OR status = 'fainted') ORDER BY alienOrder";
}

$q = ORM::query($sql, array(USER));

$data = array();
while($row = $q->fetch(PDO::FETCH_ASSOC)) $data[] = $row;

echo json_encode($data);
?>