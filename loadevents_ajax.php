<?php

//use \DateTime;
require 'database.php';
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$p_month= (int)$_POST['month']; 
$p_year= (int)$_POST['year'];

$username = $_SESSION['username'];

$stmt = $mysqli->prepare("SELECT title, day, month, year, time, tags, id, linkedid FROM events WHERE creator=? AND month=? AND year=? ORDER BY day");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => "Query Prep Failed"
    ));
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('sii', $username, $p_month, $p_year); // i = int, s = string
$stmt->execute();

$result = $stmt->get_result();
for ($events = array(); $row = $result->fetch_assoc(); $events[]=$row);

$stmt->close();


$safe = [];
for($i = 0; $i<sizeof($events); $i++) {
    $safe[$i]['title'] = htmlentities($events[$i]['title']);
    $safe[$i]['day'] = htmlentities($events[$i]['day']);
    $safe[$i]['month'] = htmlentities($events[$i]['month']);
    $safe[$i]['year'] = htmlentities($events[$i]['time']);
    $safe[$i]['tags'] = htmlentities($events[$i]['tags']);
    $safe[$i]['time'] = htmlentities($events[$i]['time']);
    $safe[$i]['id'] = htmlentities($events[$i]['id']);
    $safe[$i]['linkedid'] = htmlentities($events[$i]['linkedid']);
    
    $get=$mysqli->prepare("SELECT creator FROM events WHERE id=?");
    if(!$get) {
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed"
        ));
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $get->bind_param('i', $safe[$i]['linkedid']);
    $get->execute();
    $get->bind_result($sharedby);
    $get->fetch();
    $safe[$i]['sharedby'] = htmlentities($sharedby);
    $get->close();
    
}

echo json_encode(array(
    "success" => true,
    "result" => $safe
    ));
exit;



















?>