<?php
    include("../../config.php");
    if(isset($_POST['name']) && isset($_POST['username'])){ // add to the condition part ")"
        $name = $_POST['name'];
        $date = date("Y-m-d");
        $username = $_POST['username'];

        $query = mysqli_query($con, "INSERT INTO playlists VALUES('','$name','$username','$date')");
    }else{
        echo "Name or username parameter not passed in the file";
    }
?>