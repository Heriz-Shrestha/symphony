<?php 
    include("includes/includedFiles.php");
    if(isset($_GET['id'])){ //$_GET['id'] gives the value of id from the url
        $playlistId =  $_GET['id'];
        if(isset($_SESSION['userLoggedIn'])) {
             
        }else{
            echo("Invalid user access");
            exit();
        }
    }else{
        echo("Playlist id not set");
        exit();
        //header("Location: index.php"); //if id is not set or given go to index.php
    }

    $playlist = new Playlist($con,$playlistId);
    $owner = new User($con,$playlist->getOwner());
?>

    <div class="entityInfo"> 
        <div class="leftSection">
            <div class="playlistImage">
                <img src="assets/images/icons/playlist.png">
            </div>
        </div>
        <div class="rightSection">
            <h2> <?php echo $playlist->getName(); ?></h2>
            <p>By <?php echo $playlist->getOwner(); ?></p>
            <p>No of Songs:  <?php echo $playlist->getNumberOfSongs(); ?> songs</p>
            <button class="button" onclick="deletePlaylist('<?php echo $playlistId; ?>')">DELETE PLAYLIST</button>
        </div>
    </div>

<div class ="trackListContainer">
    <ul class = "trackList">
        <?php
            $songIdArray = $playlist->getSongIds();
            $i=1;
            foreach($songIdArray as $songId){
                $playlistSong = new Song($con, $songId);
                $songArtist = $playlistSong->getArtist();
                //var_dump($ablumArtist);
                echo("<li class='trackListRow'>
                        <div class = 'trackCount'>
                            <img class= 'play' src='assets/images/icons/play-white.png' onclick= 'setTrack(\"". $playlistSong->getId() ."\",tempPlayList,true)'>
                            <span class= 'trackNo'> $i </span>
                        </div>   
                        <div class='trackInfo'>
                            <span class = 'trackName'>". $playlistSong->getTitle() ."</span>
                            <span class = 'artistName'>". $songArtist->getName()."</span>
                        </div>

                        <div class= 'trackOption'>
                                <input type = 'hidden' class = 'songId' value= '" . $playlistSong->getId(). "'> 
                                <img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>      
                        </div>
                    </li>");
                    $i++;
            }
        ?>
        <script>

            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlayList = JSON.parse(tempSongIds);
            //console.log(tempPlayList);
        </script>
    </ul>
</div>

<nav class = "optionMenu">
    <input type = "hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con,$userLoggedIn->getUsername()); ?> 
    <div class="item" onclick="removeFromPlaylist(this,'<?php echo $playlistId; ?>')"> Remove From Playlist </div>
</nav>