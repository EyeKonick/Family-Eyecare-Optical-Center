<?php

    //learn from w3schools.com

session_start();

if(!$_SESSION['isLoggedIn']) {
    header('location: ../login.php');
}

$useremail = $_SESSION['pemail'];

//import database
include("../connection.php");
$userrow = $database->query("select * from patient where pemail='$useremail'");
$userfetch=$userrow->fetch_assoc();
$userid= $userfetch["pid"];
$username=$userfetch["pname"];

date_default_timezone_set('Asia/Kolkata');

$today = date('Y-m-d');


    if($_POST){
        if(isset($_POST["booknow"])){
            $apponum=$_POST["apponum"];
            $scheduleid=$_POST["scheduleid"];
            $date=$_POST["date"];
            $service_type = $_POST["book-services"];
            $product_id = $_POST["product-id"];

            $scheduleid=$_POST["scheduleid"];
            $sql2="insert into appointment(pid,apponum,scheduleid,appodate,service,product) values ($userid,$apponum,$scheduleid,'$date','$service_type', '$product_id')";
            $result= $database->query($sql2);
            //echo $apponom;
            header("location: appointment.php?action=booking-added&id=".$apponum."&titleget=none");

        }
    }
 ?>