<?php
require 'database.php';
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$id = (int)$_POST['id'];
$username = $_SESSION['username'];

if(!hash_equals($_SESSION['token'], $_POST['token'])){
    echo json_encode(array(
        "success" => false,
        "message" => "Request forgery detected"
    ));
    exit;
} else {
    $stmt = $mysqli->prepare("DELETE FROM events WHERE id=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed"
        ));
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }    
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true
    ));
}

?>