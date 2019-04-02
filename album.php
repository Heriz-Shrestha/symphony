<?php 
    include("includes/includedFiles.php");
    if(isset($_GET['id'])) {
        $albumId = $_GET['id'];
        
    }
    else {
        echo("Please set the album id");
        exit();
        //header("Location: index.php");
    }

    $album = new Album($con, $albumId);
    $artist = $album->getArtist();
    $artistId = $artist->getId();
      
?>

<div class="entityInfo"> 
    <div class="leftSection">
        <img src="<?php echo $album->getArtworkPath(); ?>">
    </div>
    <div class="rightSection">
        <h2> <?php echo $album->getTitle(); ?></h2>
        <p>By <?php echo $artist->getName(); ?></p>
        <p>No of Songs:  <?php echo $album->getNoOfSongs(); ?></p>
    </div>
</div>

<div class ="trackListContainer">
    <ul class = "trackList">
        <?php
                $songIdArray = $album->getSongIds();
                $i=1;
                foreach($songIdArray as $songId){
                    $albumSong = new Song($con, $songId);
                    $ablumArtist = $albumSong->getArtist();
                    //var_dump($ablumArtist);
                    echo("<li class='trackListRow'>
                            <div class = 'trackCount'>
                                <img class= 'play' src='assets/images/icons/play-white.png' onclick= 'setTrack(\"". $albumSong->getId() ."\",tempPlayList,true)'>
                                <span class= 'trackNo'> $i </span>
                            </div>   
                            <div class='trackInfo'>
                                <span class = 'trackName'>". $albumSong->getTitle() ."</span>
                                <span class = 'artistName'>". $ablumArtist->getName()."</span>
                            </div>

                            <div class= 'trackOption'>
                                <input type = 'hidden' class = 'songId' value= '" . $albumSong->getId(). "'> 
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
</nav>

