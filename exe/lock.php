<?php
if(!isset($_SESSION))
{
    session_start();
}

if (!isset($_SESSION['pump_id']) || !isset($_SESSION['role'])) 
{
    header("location: login.php");
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
date_default_timezone_set("Asia/Kolkata");
$date = date("Y-m-d");
$time = date("Y-m-d H:i:s");
if (isset($_SESSION['user_id'])) {

    $user_id   = $_SESSION['user_id'];
    $token     = $_SESSION['token'];

    $sql = "SELECT `role`,`user_pump_id` FROM `users` WHERE `user_id` = '".$user_id."';";
    $exe = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($exe)){
        $_SESSION['access'] = $row['role'];
        $_SESSION['role'] = $row['role'];
        $pump_id = $row['user_pump_id'];
    }

    $sql = "SELECT 1 FROM `rates` WHERE `pump_id` = '".$pump_id."' AND `date` = '".$date."';";
    $exe = mysqli_query($conn, $sql);
    if(mysqli_num_rows($exe)< 1){
        $_SESSION['day_rate'] = 'unset';
    }else{
        $_SESSION['day_rate'] = 'set';
    }       

} 

?> 