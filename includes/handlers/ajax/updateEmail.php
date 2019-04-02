<?php
    include("../../config.php");
    //return "adsasd";

    if(!isset($_POST['username'])){
        echo("ERROR: Couldn't set username");
        exit();
    }

    if(isset($_POST['email']) && $_POST['email'] != ""){
        $username = $_POST['username'];
        $email = $_POST['email'];

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo "email is invalid";
            exit();
        }

        $emailCheck = mysqli_query($con,"SELECT email FROM users WHERE email='$email' AND username != '$username'");
        if(mysqli_num_rows($emailCheck)>0){
            echo "email is already in use";
            exit();
        }

        $updateQuery = mysqliQuery($con,"UPDATE users SET email = '$eamil' WHERE username = '$username'");
        echo ("update successful");
    }else{
        echo("Please provide an email");
    }


?>