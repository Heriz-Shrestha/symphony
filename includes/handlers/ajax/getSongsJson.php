<?php
include("../../config.php");
include("../../classes/Song.php");
include("../../classes/Artist.php");

if(($_POST['mood'] && $_POST['start'] && $_POST['limit'])){
    $mood = $_POST['mood'];
    $start = $_POST['start'];
    $limit = $_POST['limit'];

    $songQuery = mysqli_query($con, "SELECT * FROM songs WHERE mood ='$mood' ORDER BY plays DESC LIMIT '$start','$limit'");
        //var_dump($songQuery);
        
    $resultArrayId = array();
    while($row = mysqli_fetch_array($songQuery)) {
        array_push($resultArrayId,$row['id']);
    }	
    $i = 1;
    while($songId = mysqli_fetch_array($resultArrayId)){    
        $song = new Song($con,$songId);
        $artistSong = new Artist($con, $songId);
        $artistName = $artistSong->getName();
        //var_dump($ablumArtist);
        echo("<li class = 'trackListRow'>
                <div class = 'trackCount'>
                    <img class= 'play' src='assets/images/icons/play-white.png' onclick= 'setTrack(\"". $songId ."\",tempPlayList,true)'>
                    <span class= 'trackNo'> $i </span>
                </div>   
                <div class='trackInfo'>
                    <span class = 'trackName'>". $song->getTitle() ."</span>
                    <span class = 'artistName'>". $artistName."</span>
                </div>

                <div class= 'trackOption'>
                    <input type = 'hidden' class = 'songId' value= '" . $artistSong->getId(). "'> 
                    <img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>      
                </div>

            </li>    
            ");
            
            $i++;
    }
}
?>