<?php
    //session_start();
    $songQuery = mysqli_query($con,"SELECT id FROM songs ORDER BY plays ASC LIMIT 15");
    $resultArray = array(); 
    while($row = mysqli_fetch_array($songQuery)){
        array_push($resultArray,$row['id']);
    }

    $jasonArray = json_encode($resultArray); // json_encode() converts php into jason variable
?>

<script>
    $(document).ready(function(){
        var newPlayList = <?php echo $jasonArray; ?>;
        //console.log(currentPlayList);
        audioElement = new Audio();
        setTrack(newPlayList[0], newPlayList,false);
        updateVolumeProgressBar(audioElement.audio);
        //progressBar [bar not sliding]
        $(".playbackBar .progressBar").mousedown(function(){
            mouseDown = true;
        });

        $(".playbackBar .progressBar").mousemove(function(e){
            if(mouseDown){
                //set time of song depending on position of mouse
                timeFrontOffset(e,this);// this refers to the progressbar 
            }
        });

        $(".playbackBar .progressBar").mouseup(function(e){
            if(mouseDown){
                //set time of song  depending on position of mouse
                timeFrontOffset(e,this);// this refers to the progressbar 
            }
        });

        //Volume
        $(".volumeBar .progressBar").mousedown(function(){
            mouseDown = true;
        });

        $(".volumeBar .progressBar").mousemove(function(e){
            if(mouseDown){
                var percentage = e.offsetX / $(this).width();
                if(percentage >= 0 && percentage <= 1){
                     audioElement.audio.volume = percentage;
                }
            }
        });

        $(".volumeBar .progressBar").mouseup(function(e){
            if(mouseDown){
                var percentage = e.offsetX / $(e).width();
                if(percentage >= 0 && percentage <= 1){
                     audioElement.audio.volume = percentage;
                }
            }
        });

        $(document).mouseup(function(){
            mouseDown = false;
        });

    });

    //timefromoffset
    function timeFrontOffset(mouse, progressBar){
        var precentage = mouse.offsetX / $(progressBar).width() *100;
        var seconds = audioElement.audio.duration * (precentage / 100);
        audioElement.setTime(seconds);
    }

    //setTrack function
    function setTrack(trackId, newPlayList, play){ //this function contains 3 parameter (trackId to be played, playList from which playlist to be played, play/pause)
        
        if(newPlayList != currentPlayList){
            currentPlayList = newPlayList;
            shufflePlayList = currentPlayList.slice(); //.slice() makes the copy of currentPlayList
            shuffleArray(shufflePlayList);
        }

        if(shuffle == true){
            currentIndex = shufflePlayList.indexOf(trackId);
        }else{
            currentIndex = currentPlayList.indexOf(trackId);
        }
        
        //currentIndex = currentPlayList.indexOf(trackId);
        //pauseSong();
         $.post("includes/handlers/ajax/getSongJson.php",{songId: trackId, username:userLoggedIn }, function(data){
            
            var track = JSON.parse(data);//string lai object ma change garxa JSON.parse(data).
            //console.log(track);
            //audioElement.setTrack(track.path); //setting the path of the audio path
            //audioElement.play();
            $(".trackName span").text(track.title);
            
            //refreshes artist name in page
            $.post("includes/handlers/ajax/getArtistJson.php",{artistId: track.artist }, function(data){
                var artist = JSON.parse(data); 
                //console.log(artist);
                $(".trackInfo .artistName span").text(artist.art_name);
                $(".trackInfo .artistName span").attr("onclick","openPage('artist.php?id="+ artist.id + "')");
            });

            //refreshes album cover art image in page
            $.post("includes/handlers/ajax/getAlbumJson.php",{albumId: track.album }, function(data){
                var album = JSON.parse(data);
                //console.log(data);
                $(".content .albumLink img").attr("src",album.artworkPath);
                $(".content .albumLink img").attr("onclick","openPage('album.php?id="+ album.id + "')");
                $(".trackInfo .trackName span").attr("onclick","openPage('album.php?id="+ album.id + "')");
            });

            audioElement.setTrack(track);
            //playSong();
            if(play == true) {
			    playSong();
            } 
        });
    }

    //playSong function
    function playSong(){
        //console.log(userLoggedIn);
        if(audioElement.audio.currentTime == 0) {
            $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id,username:userLoggedIn});
            //console.log(userLoggedIn);
        }
            $(".controlButton.play").hide();
            $(".controlButton.pause").show();
            audioElement.play();

    }

    //pauseSong function
    function pauseSong(){
        $(".controlButton.pause").hide();
        $(".controlButton.play").show();
        audioElement.pause();
    }

    //prevSong function
    function prevSong(){
        if(repeat == true){
            audioElement.setTime(0);
            playSong();
            reutrn;
        }

        if(audioElement.audio.currentTime >= 3 || currentIndex == 0){
            audioElement.setTime(0);  
        }else{
            currentIndex--;
            setTrack(currentPlayList[currentIndex],currentPlayList, true);
        }
    }

    //nextSong function
    function nextSong(){
        if(repeat == true){
            audioElement.setTime(0);
            playSong();
            return;
        }
        if(currentIndex == currentPlayList.length - 1){
            currentIndex = 0;
        }else{
            currentIndex++;
        }

        var trackToPlay = shuffle ? shufflePlayList[currentIndex] : currentPlayList[currentIndex];
        setTrack(trackToPlay, currentPlayList, true);
    }  
    
    //setRepeat function
    function setRepeat(){
        repeat = !repeat;
        var imageName = repeat ? "repeat-active.png" : "repeat.png";
        $(".controlButton.repeat img").attr("src","assets/images/icons/" + imageName);
    }

    //setShuffle function

    function shuffleArray(a) {
        var j, x, i;
        for (i = a.length; i; i--) {
            j = Math.floor(Math.random() * i);
            x = a[i - 1];
            a[i - 1] = a[j];
            a[j] = x;
        }
    }

    function setShuffle() {
        shuffle = !shuffle;
	    var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
        $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);
        
        if(shuffle == true){
            //randomize playlist
            shuffleArray(shufflePlayList);
            currentIndex = shufflePlayList.indexOf(audioElement.currentlyPlaying.id);
        }else{
            //shuffle has been deactivated
            //go back to unshuffle playlist
            currentIndex = currentPlayList.indexOf(audioElement.currentlyPlaying.id);
        }
    }
    
    //mute function
    function setMute() {
        audioElement.audio.muted = !audioElement.audio.muted;
	    var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
	    $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
    }
    
</script>

<div id="nowPlayingBarContainer">

    <div id="nowPlayingBar">

        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                    <img role="link" tabindex="0" src="" class="albumArtwork">
                </span>

                <div class="trackInfo">

                    <span class="trackName">
                        <span role="link" tabindex="0"></span>
                    </span>

                    <span class="artistName">
                        <span role="link" tabindex="0" ></span>
                    </span>

                </div>
            </div>
        </div>
        <div id="nowPlayingCenter">
            <div class="content playerControls">
                <div class="buttons">

                    <button class="controlButton shuffle" title="Shuffle button" onclick = "setShuffle()">
                        <img src="assets/images/icons/shuffle.png" alt="Shuffle">
                    </button>

                    <button class="controlButton previous" title="Previous button" onclick = "prevSong()">
                        <img src="assets/images/icons/previous.png" alt="Previous">
                    </button>

                    <button class="controlButton play" title="Play button" onclick="playSong()">
                        <img src="assets/images/icons/play.png" alt="Play">
                    </button>

                    <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                        <img src="assets/images/icons/pause.png" alt="Pause">
                    </button>

                    <button class="controlButton next" title="Next button" onclick = "nextSong()">
                        <img src="assets/images/icons/next.png" alt="Next">
                    </button>

                    <button class="controlButton repeat" title="Repeat button" onclick = "setRepeat()">
                        <img src="assets/images/icons/repeat.png" alt="Repeat">
                    </button>

                </div>

                <div class="playbackBar">

                    <span class="progressTime current">0.00</span>

                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                    </div>

                    <span class="progressTime remaining">0.00</span>

                </div>
            </div>
        </div>

        <div id="nowPlayingRight">
            <div class="volumeBar">

                <button class="controlButton volume" title="Volume button" onclick = "setMute()">
                    <img src="assets/images/icons/volume.png" alt="Volume">
                </button>

                <div class="progressBar">
                    <div class="progressBarBg">
                        <div class="progress"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>