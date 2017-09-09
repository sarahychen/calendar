<?php


phpinfo();
////
////$date = new DateTime('2017-07-12', new DateTimeZone('America/Chicago'));
////$year = date_format($date, 'Y-m-d H:i:sP') . "\n";
//////echo date_format($date, 'Y-m-d H:i:sP') . "\n";
////echo $year;
////
//
//require 'database.php';
//header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
//
//$input = $_POST['newevent-date'];
//$title = $_POST['newevent-title'];
//$tag = $_POST['newevent-tag'];
//
//echo $title;
//echo $tag;
//
////$username = $_SESSION['username'];
////$token = $_SESSION['token'];
////
////if(!hash_equals($_SESSION['token'], $_POST['token'])){
////    die("Request forgery detected");
////} else {
//    // get the date form input and parse it
//    $replaced_date = str_replace('T', ' ', $input);
//    $date = new DateTime($replaced_date, new DateTimeZone('America/Chicago'));
//    //Y-m-d H:i:sP
//    $year = date_format($date, 'Y');
//    $month = date_format($date, 'm');
//    $day = date_format($date, 'd');
//    $time = date_format($date, 'H:i');
//
//echo $year;
//echo $month;
//echo $day;
//echo $time;
////} 
//?>