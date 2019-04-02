<?php
    include("../../config.php");
    //include("../../includedFiles.php")
    if(isset($_POST['songId'])){
        $songId = $_POST['songId'];
        $artistIdQuery = mysqli_query($con,"SELECT artist FROM songs WHERE id='$songId'");
        $songQuery = mysqli_query($con,"UPDATE songs SET plays = plays + 1 WHERE id='$songId'");
        //$albumQuery = mysqli_query($con,"UPDATE albums SET plays = plays + 1 WHERE id='$albumId'");
        if(isset($_POST['username'])){
            $username = $_POST['username'];
            if($username != "undefined" || $username != NULL){
                //var_dump($username);
                // $time = date('Y-m-d H:i:s'); 
                // $time = strtotime($time);
                // $time = date('H', $time);
                //echo $time;
                if($time>4 and $time <=8){
                    $moodTime = "morning";
                }elseif($time ==9){
                    $moodTime = "driving";
                }elseif($time>=10 and $time <=15){
                    $moodTime == "working";
                }elseif($time=16){
                    $moodTime = "driving";
                }elseif($time>=17 and $time <=19){
                    $moodTime = "relaxing";
                }elseif($time>=20 and $time<=22){
                    $moodTime = "dinner";
                }else{
                    $moodTime = "sleeping";
                }
                
                while($rows = mysqli_fetch_array($artistIdQuery)){
                    $artistId = $rows['artist'];
                    $query = mysqli_query($con,"SELECT id FROM useralbum WHERE owner='$username' and artistId = '$artistId' and time = '$moodTime'");
                    if(mysqli_num_rows($query)==0){
                        $insertQuery = mysqli_query($con,"INSERT INTO useralbum VALUES('','$username','$artistId','$moodTime',1)");
                    }else{
                        while($row = mysqli_fetch_array($query)){
                            $useralbumId = $row['id'];
                            $updateQuery=mysqli_query($con,"UPDATE useralbum SET plays = plays + 1 WHERE id = '$useralbumId'");
                        }
                    }
                }
            }
        }
    }
?>