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
    $stmt = $mysqli->prepare("SELECT title, day, month, year, time, tags, linkedid FROM events WHERE id=?");
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
    $stmt->bind_result($title, $day, $month, $year, $time, $tag, $linkedid);
    $stmt->fetch();
    $stmt->close();
    $m1 = $month+1;
    echo json_encode(array(
        "success" => true,
        "title" => htmlentities($title),
        "day" => htmlentities($day),
        "month" => htmlentities($m1),
        "year" => htmlentities($year),
        "time" => htmlentities($time),
        "tag" => htmlentities($tag),
        "linkedid"=> htmlentities($linkedid)
    ));
}

?>