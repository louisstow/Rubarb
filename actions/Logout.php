<?php
$me->status = "offline";
$me->update();

session_destroy();
session_unset();
?>