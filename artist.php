<?php
    include("includes/includedFiles.php");
    if(isset($_GET['id'])){
        $artistId = $_GET['id'];
        
    }else{
        echo("Please set artist id");
        exit();
        //header("Location: index.php");
    }
    $artist = new Artist($con,$artistId);
?>

<div class= "entityInfo borderBottom">
    <div class="centerSection">
        <div class= "artistInfo">
            <h1 class= "artistName"> <?php echo $artist->getName(); ?></h1>
            <div class="headerButtons">
                <button class="button green" onclick = "playFirstSong()"> 
                    play
                </button>
            </div>
        </div>
    </div>
</div>

<!-- for song --> 
<div class ="trackListContainer borderBottom">
    <h2> Songs </h2>
    <ul class = "trackList">
        <?php
            $songIdArray = $artist->getSongIds();
            $i=1;
            
            foreach($songIdArray as $songId){
                if($i > 5){
                    break;
                }
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

<!--for album --> 
<div class="gridViewContainer">
    <h2> Albums </h2>
	<?php 
		$albumQuery = mysqli_query($con,"SELECT * FROM albums WHERE artist='$artistId'");
		while($row = mysqli_fetch_array($albumQuery)){
			//$temp = $row['artworkPath'];
			echo ("<div class = 'gridViewItem'>
					  
			<span role='link' tabindex= '0' onclick = 'openPage(\"album.php?id=".$row['id']."\")'>
					<img src = '".$row['artworkPath']."'>
					<div class = 'gridViewInfo'>"
					.$row['title'].
					"</div>
			</span>
				  </div>");
		}
	?>
</div>

<nav class = "optionMenu">
    <input type = "hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con,$userLoggedIn->getUsername()); ?> 
</nav>

