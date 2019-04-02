<?php
    include("../../config.php");

    if(isset($_POST['playlistId'])){
        $playlistId = $_POST['playlistId'];
        $playlistQuery = mysqli_query($con,"DELETE FROM playlists WHERE id='$playlistId'");
        //if playlist is deleted then related songs in that playlist should be deleted too
        $songslistQuery = mysqli_query($con,"DELETE FROM playlistSongs WHERE playlistId='$playlistId'");
    }else{
        echo("playlistId was not passed into deletePlayList.php");
    }
?>