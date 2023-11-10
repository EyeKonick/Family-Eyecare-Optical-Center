<?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];


    if($_POST){
        if(isset($_POST["booknow"])){
            $apponum=$_POST["apponum"];
            $scheduleid=$_POST["scheduleid"];
            $date=$_POST["date"];
            $service_type = $_POST["book-services"];

            $scheduleid=$_POST["scheduleid"];
            if (isset($_POST["product-id"])) {
                $product_id = $_POST["product-id"];
                $sql2="insert into appointment(pid,apponum,scheduleid,appodate,service,product) values ($userid,$apponum,$scheduleid,'$date','$service_type', '$product_id')";
            } else {
                $sql2="insert into appointment(pid,apponum,scheduleid,appodate,service) values ($userid,$apponum,$scheduleid,'$date','$service_type')";
            }
            $result= $database->query($sql2);
            //echo $apponom;
            header("location: appointment.php?action=booking-added&id=".$apponum."&titleget=none");

        }
    }
 ?>