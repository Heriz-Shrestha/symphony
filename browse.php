
<?php 
	include("includes/includedFiles.php"); 
	if(isset($_SESSION['userLoggedIn'])) {
		
    }
    else {
		echo("Invalid Access");
		exit();
        //header("Location: index.php");
	}
	
?>

<div class="gridViewContainer">
	<div class="moodContent">
		<?php
			// $timezone = date_default_timezone_set("Asia/Kathmandu");
			// $time = date('Y-m-d H:i:s'); 
			// $time = strtotime($time);
			// $time = date('H', $time);
			//echo $time;
			
			if($time>4 and $time <=8){
				$mood = "morning";
			}elseif($time==9){
				$mood = "driving";
			}elseif($time>=10 and $time <=15){
				$mood = "working";
			}elseif($time==16){
				$mood = "driving";
			}elseif($time>=17 and $time <=19){
				$mood = "relaxing";
			}elseif($time>=20 and $time<=22){
				$mood = "dinner";
			}else{
				$mood = "sleeping";
			}

			echo("<h1 class='pageHeadingBig'>".$mood.' hour songs'." </h1>");
			// echo("<li class='trackListRow'>
			// 	</li>");
			// $query = mysqli_query($con,"SELECT id FROM genres WHERE name = '$gener'");
			// $rows = mysqli_fetch_array($query);
			// //var_dump($rows);

			// $id = $rows['id'];
			// // var_dump($id);
			
			$songQuery = mysqli_query($con, "SELECT * FROM songs WHERE mood LIKE '%$mood%'");
			//var_dump($songQuery);
			
			$resultArrayId = array();
			while($row = mysqli_fetch_array($songQuery)) {
				array_push($resultArrayId,$row['id']);
			}	 
			$i = 1;
			foreach($resultArrayId as $songId){
				$song = new Song($con,$songId);
				$artistId = $song->getArtist();
				//$artistSong = new Artist($con, $songId);
				$artistName = $artistId->getName();
				//var_dump($ablumArtist);
				echo("<li class='trackListRow'>
						<div class = 'trackCount'>
							<img class= 'play' src='assets/images/icons/play-white.png' onclick= 'setTrack(\"". $songId ."\",tempPlayList,true)'>
							<span class= 'trackNo'> $i </span>
						</div>   
						<div class='trackInfo'>
							<span class = 'trackName'>". $song->getTitle() ."</span>
							<span class = 'artistName'>". $artistName."</span>
						</div>

						<div class= 'trackOption'>
                                <input type = 'hidden' class = 'songId' value= '" . $song->getId(). "'> 
                                <img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>      
                        </div>

						
					</li>");
					
					$i++;
					if($i>5){
						break;
					}
			}
		?>	
		<script>
			var tempSongIds = '<?php echo json_encode($resultArrayId); ?>';
			tempPlayList = JSON.parse(tempSongIds);
		 	//console.log(tempPlayList);
		</script>	
	</div>
<h1 class="pageHeadingBig"> Frequenlty Listened Artist's Album </h1>
	<?php
		if(isset($_SESSION['userLoggedIn'])){
			$username = $userLoggedIn->getUsername();
			//var_dump($username);
			// $time = date('Y-m-d H:i:s'); 
			// $time = strtotime($time);
			// $time = date('H', $time);
			//echo $time;
			if($time>4 and $time <=8){
				$moodTime = "morning";
			}elseif($time=9){
				$moodTime = "driving";
			}elseif($time>=10 and $time <=15){
				$moodTime = "working";
			}elseif($time=16){
				$moodTime = "driving";
			}elseif($time>=17 and $time <=19){
				$moodTime = "relaxing";
			}elseif($time>=20 and $time<=22){
				$moodTime = "dinner";
			}else{
				$moodTime = "sleeping";
			}
	
			$query = mysqli_query($con,"SELECT artistId FROM useralbum WHERE plays = (SELECT MAX(plays) FROM useralbum WHERE (owner='$username' and time = '$moodTime')) AND (owner='$username' and time = '$moodTime')");
			if(mysqli_num_rows($query)==0){
				$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");

				while($row = mysqli_fetch_array($albumQuery)) {
					echo "<div class='gridViewItem'>
							<span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
								<img src='" . $row['artworkPath'] . "'>

								<div class='gridViewInfo'>"
									. $row['title'] .
								"</div>
							</span>

						</div>";
				}
			}else{
				while($row = mysqli_fetch_array($query)){
					$artistalbumId = $row['artistId'];
					$albumQuery=mysqli_query($con,"SELECT * FROM albums WHERE artist = '$artistalbumId'");
					while($rows = mysqli_fetch_array($albumQuery)) {
						echo "<div class='gridViewItem'>
								<span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $rows['id'] . "\")'>
									<img src='" . $rows['artworkPath'] . "'>
	
									<div class='gridViewInfo'>"
										. $rows['title'] .
									"</div>
								</span>
	
							</div>";
					}
				}
			}
		 }
		
	?>
</div>

<nav class = "optionMenu">
    <input type = "hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con,$userLoggedIn->getUsername()); ?> 
</nav>