<?php
// login_ajax.php
require 'database.php';
header("Content-Type: application/json");

$username = $_POST['newuser'];
$password = $_POST['newpass'];

//check if username already exists
$stmt = $mysqli->prepare("SELECT COUNT(*) from users where username=?");// returns how much many usernames match the username you bind
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $username); // bind the submitted username
$stmt->execute(); //execute statement
$stmt->bind_result($cnt); // bind the results to $cnt variable
$stmt->fetch(); // stores the result to $cnt;
$stmt->close();

if ($cnt != 0) { // if there is a username that matches what you have, then
    echo json_encode(array(
		"success" => false,
		"message" => "Username already exists"
	));
	exit;
} else {
    //hash the password
    //$pwd = $_POST['password'];
    $crypted_password = password_hash($password, PASSWORD_BCRYPT);
    
    //username does not exist already, so add the new user information to the table
    $add_user = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if(!$add_user){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $add_user->bind_param('ss', $username, $crypted_password);
    $add_user->execute();
    $add_user->close();
	ini_set("session.cookie_httponly", 1);
    session_start();
	
    $_SESSION['username'] = $username;
	$_SESSION['token'] = substr(md5(rand()), 0, 10);
    
    echo json_encode(array(
		"success" => true
	));
	
	exit;
    
}
?>