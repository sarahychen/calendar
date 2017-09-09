<?php

//use \DateTime;
require 'database.php';
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
ini_set("session.cookie_httponly", 1);
session_start();

$input = $_POST['date']; 
$title = $_POST['title']; 
$tag = $_POST['tag']; 
$username = $_SESSION['username'];

if(!hash_equals($_SESSION['token'], $_POST['token'])){
    echo json_encode(array(
        "success" => false,
        "message" => "Request forgery detected"
    ));
    exit;
} else {
    // get the date form input and parse it
    $replaced_date = str_replace('T', ' ', $input);
    $date = new DateTime($replaced_date, new DateTimeZone('America/Chicago'));
    $year = (int) date_format($date, 'Y');
    $month = (int) date_format($date, 'm');
    $day = (int) date_format($date, 'd');
    $time = date_format($date, 'H:i');
    $m1 = $month-1;
    
    $stmt = $mysqli->prepare("INSERT INTO events (title, day, month, year, time, creator, tags) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed"
        ));
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('siiisss', $title, $day, $m1, $year, $time, $username, $tag); // i = int, s = string
    $stmt->execute(); 
    $stmt->close();
    
    $result = "'".htmlentities($title)."' on ".htmlentities($month)."-".htmlentities($day)."-".htmlentities($year)." at ".htmlentities($time);
    echo json_encode(array(
        "success" => true,
        "message" => $result
    ));
    exit;
} 
?>