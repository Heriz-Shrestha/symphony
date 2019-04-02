<?php

    ob_start(); 
    session_start();
    $timezone = date_default_timezone_set("Asia/Kathmandu");
    $adminUsername = "root";
    $adminPassword = "";
    $con = mysqli_connect("localhost",$adminUsername,$adminPassword,"symphony") or die("Failed to connect to database"); //either connects to the database or simply give error message
    // if($con == false){
    //     $conn = mysqli_query("localhost",$adminUsername,$adminPassword,"CREATE DATABASE symphony");
    //     if($conn == true){
    //         $con = mysqli_connect("localhost",$adminUsername,$adminPassword,"symphony");
    //         $temp1 = mysqli_query($con,"CREATE TABLE users (id INT(11) AUTO_INCREMENT PRIMARY KEY,firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL)"
    //     }
    // }
    if(mysqli_connect_errno()){
        echo "Failed to connect: " . mysqli_connect_errno();
    }

    $time = date('Y-m-d H:i:s'); 
	$time = strtotime($time);
    $time = date('H', $time);
    $time = (int)$time;
    //$time = $ttime;
	//echo $time;
?>