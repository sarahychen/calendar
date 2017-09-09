<?php
 
require 'database.php';
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
 
$username = $_POST['username'];
$pwd_guess = $_POST['password'];


// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
$stmt = $mysqli->prepare("SELECT COUNT(*), password FROM users WHERE username=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($cnt, $pwd_hash);
$stmt->fetch();
$stmt->close();

 
if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
	ini_set("session.cookie_httponly", 1);
	session_start();
	$_SESSION['username'] = $username;
	$_SESSION['token'] = substr(md5(rand()), 0, 10);
	echo json_encode(array(
		"success" => true
	));
	//header("Location: calendar.php");
	exit;
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}
?>