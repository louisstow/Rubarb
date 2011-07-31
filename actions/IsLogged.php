<?php
if(!$me) {
	session_destroy();
	session_unset();
	error("Please register");
}
echo json_encode($me);
?>