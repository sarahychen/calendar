<?php
//require it into other files that require database access.
 
$mysqli = new mysqli('localhost', 'calendar', 'mod5', 'calendar');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>