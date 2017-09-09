<?php
require 'database.php';
header("Content-Type: application/json"); 
ini_set("session.cookie_httponly", 1);
session_start();

$eventid = $_POST['eventid'];
$sharer = $_POST['sharer'];

$user = $_SESSION['username'];

if(!hash_equals($_SESSION['token'], $_POST['token'])){
    echo json_encode(array(
        "success" => false,
        "message" => "Request forgery detected"
    ));
    exit;
} elseif ($sharer == $user) {
    echo json_encode(array(
        "success" => false,
        "message" => "Cannot share event with yourself"
    ));
} else {
    $stmt = $mysqli->prepare("SELECT title, day, month, year, time, tags FROM events WHERE id=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed"
        ));
        exit;
    }
    $stmt->bind_param('i', $eventid); 
    $stmt->execute();
    $stmt->bind_result($title, $day, $month, $year, $time, $tag);
    $stmt->fetch();
    $stmt->close();
    
    $new = $mysqli->prepare("INSERT INTO events (title, day, month, year, time, creator, tags, linkedid) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
    if(!$new){
        echo json_encode(array(
            "success" => false,
            "message" => "Second Query Prep Failed"
        ));
        //printf("Second Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $new->bind_param('siiisssi', $title, $day, $month, $year, $time, $sharer, $tag, $eventid);
    $new->execute(); 
    $new->close();
    
    echo json_encode(array(
        "success" => true
    ));
    
    
}
?>