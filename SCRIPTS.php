<?php
$data = file_get_contents("assets/animations.js");
file_put_contents("zippedanimations.js", gzcompress($data));
?>