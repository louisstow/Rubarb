<?php
load("Battle");
data("battle");

$battle = I("Battle")->get($battle);

if($battle->status == "accepted") {
	echo json_encode($battle);
} else {
	echo "{status: false}";
}
?>