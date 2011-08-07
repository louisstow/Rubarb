<pre>
<?php
function open($id) {
	echo "WRITE [";
	print_r($id);
	echo "]";
}

function close() {
	echo "CLOSE [";
	
	echo "]";
}

function read($id) {
	echo "READ [";
	print_r($id);
	echo "]";
}

function write($id, $data) {
	echo "WRITE [";
	print_r($id);
	echo ",";
	print_r($data);
	echo "]";
}

function destroy($id) {
	echo "DESTROY [";
	file_put_contents($id, "DESTROY");
	print_r($id);
	echo "]";
}

function gc($id) {
	echo "GC [";
	print_r($id);
	echo "]";
}

session_set_save_handler("open", "close", "read", "write", "destroy", "gc"); 

session_start();

$_SESSION['test'] = "NBA";

?>
</pre>