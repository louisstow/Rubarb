<pre>
<?php
include 'ORM.php';
include 'objects/Player.php';

//$player = I('Player')->get(1)->remove();

//print_r($player);
//$player->wins++;
//$player->update();

$player = I("Player")->create(0, "Louis", "louisstow@gmail.com", "test");

print_r($player);
?>
</pre>