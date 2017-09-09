<?php
require 'database.php';
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$title = (string)$_POST['title'];
$day = (int)$_POST['day'];
$month = (int)$_POST['month'];
$year = (int)$_POST['year'];
$time = (string)$_POST['time'];
$tag = (string)$_POST['tag'];
$id= (int)$_POST['id'];
$m1 = $month -1;
$linkedid = (string) $_POST['linkedid'];

$stmt = $mysqli->prepare("UPDATE events SET title=?, day=?, month=?, year=?, time=?, tags=? WHERE id=?");
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => "Query Prep Failed"
    ));
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('siiissi', $title, $day, $m1, $year, $time, $tag, $id);
$stmt->execute();
$stmt->close();

if ($linkedid != "") {
    $link= $mysqli->prepare("UPDATE events SET title=?, day=?, month=?, year=?, time=?, tags=? WHERE id=?");
    if(!$link){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed"
        ));
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $link->bind_param('siiissi', $title, $day, $m1, $year, $time, $tag, $linkedid); // i = int, s = string
    $link->execute();
    $link->close();
}


$result = "'".htmlentities($title)."' on ".htmlentities($month)."-".htmlentities($day)."-".htmlentities($year)." at ".htmlentities($time);
echo json_encode(array(
    "success" => true,
    "message" => $result
));
exit;

?>