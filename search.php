<?php 
include("includes/includedFiles.php");

 if(isset($_GET['term'])){
    $term = urldecode($_GET['term']); //urldecode() decodes the url
 }else{
     $term = "";
 }
?>

<div class = "searchContainer">
    <h4> Search for an artist, album or song </h4>
    <input type = "text" class = "searchInput" value="<?php echo $term ?>" placeholder = "Start Typing....">
</div>

<script>
    $(".searchInput").focus(); //always keeps the focus
    $(function(){
        
        $(".searchInput").keyup(function(){
            clearTimeout(timer); //sets clear timer to zero for every type
            timer = setTimeout(function(){ //execute the function after 2000ms = 2sec
                var val = $(".searchInput").val(); // gets the value written in the search bar
                openPage("search.php?term=" +val);
            },2000);
        });
    });
</script>

<?php 
    if($term == ""){
        exit();
    }
?>

<!-- for song --> 
<div class ="trackListContainer borderBottom">
    <h2> Songs </h2>
    <ul class = "trackList">
        <?php
            $songsQuery = mysqli_query($con,"SELECT id FROM songs WHERE title LIKE '$term%'");
            if(mysqli_num_rows($songsQuery) == 0){
                echo "<span class ='noResults'>No songs found matching " . $term . "</span>";
            } else {
            
                $songIdArray = array();
                $i=1;
                while($row = mysqli_fetch_array($songsQuery)){
                    array_push($songIdArray, $row['id']);
                    $albumSong = new Song($con, $row['id']);
                    $ablumArtist = $albumSong->getArtist();
                    //var_dump($songIdArray);
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
                        
                    }
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
<div class="gridViewContainer borderBottom">
    <h2> Albums </h2>
	<?php 
        $albumQuery = mysqli_query($con,"SELECT * FROM albums WHERE title LIKE '$term%'");
        if(mysqli_num_rows($albumQuery) == 0){
            echo "<span class ='noResults'>No albums found matching " . $term . "</span>";
        }
        
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

<!-- Artist -->
<div class="artistsContainer">
    <h2>ARTISTS </h2>
    <?php
       $artistsQuery = mysqli_query($con,"SELECT id FROM artists WHERE art_name LIKE '$term%'"); 
       if(mysqli_num_rows($artistsQuery) == 0){
        echo "<span class ='noResults'>No artists found matching " . $term . "</span>";
       }
        while($row = mysqli_fetch_array($artistsQuery)){
            $artistFound = new Artist($con,$row['id']);
            echo"<div class='searchResultRow'>
                    <div class='artistName'>
                        <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=". $artistFound->getId() ."\")'>
                        "
                        .$artistFound->getName().
                        "
                        </span>
                    </div>
                 </div>";
        }
    ?>
</div>

<nav class = "optionMenu">
    <input type = "hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con,$userLoggedIn->getUsername()); ?> 
</nav>
