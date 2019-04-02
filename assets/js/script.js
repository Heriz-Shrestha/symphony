var currentPlayList = [];
var shufflePlayList = [];
var tempPlayList = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;
var limit = 5;
var start = 0;
var action = 'inactive';



//logout function
function logout(url){
    $.post("includes/handlers/ajax/logout.php").done(function(){
        document.location.href="index.php";
    });
    //history.pushState(null,null,url);
}

//email update function
function updateEmail(emailClass){
    var emailValue = emailClass;
    $.post("includes/handlers/ajax/updateEmail.php",{email : emailValue, username: userLoggedIn}).done(function(response){
        $("."+email).val("dasdad");
    });
}

//add songs to the playlist
$(document).on("change","select.playlist", function(){ //select.playlist means select item with the class playlist and change means when that select is changed
    var select = $(this);
    var playlistId = select.val();
    //console.log(playlistId);
    var songId = select.prev(".songId").val();
    $.post("includes/handlers/ajax/addToPlaylist.php",{playlistId: playlistId, songId: songId}).done(function(error){
        if(error != ""){
            alert(error);
            return;
        }
        hideOptionMenu();
        select.val("");
    });
    // console.log("playlistId: " + playlistId);
    // console.log("songId: " + songId);
});

//remove playlist
function removeFromPlaylist(button,playlistId){
    var songId = $(button).prevAll(".songId").val();
    $.post("includes/handlers/ajax/removeFromPlayList.php",{playlistId : playlistId,songId : songId}).done(function(error){ //what done does is that it exectues the fucntion when ajax call is done
        if(error != ""){
            alert(error);
            return;
        }
        //do something when ajax returns
        openPage("playlist.php?id=" + playlistId);
    });
}


//fuction that hides optionMenu on scrolling
$(window).scroll(function(){
    hideOptionMenu();
});

//function that hides optionMenu on clicking out of the menu
$(document).click(function(click){
    var target = $(click.target);
    if(!target.hasClass("item") && !target.hasClass("optionsButton")){
        hideOptionMenu();
    }

});

//showing option menu function
function showOptionsMenu(button){
    var songId = $(button).prevAll(".songId").val(); //gets the id of the song of which the button is pressed
    var menu = $(".optionMenu");
    var menuWidth = menu.width();
    menu.find(".songId").val(songId);
    //console.log(songId);
    var scrollTop = $(window).scrollTop(); //distance from top of window to top of document
    var elementOffSet = $(button).offset().top; // get the position of button from the top of the document

    var top = elementOffSet - scrollTop;
    var left = $(button).position().left; //how far from the left of the button is
    menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline" }); 
}

//hide option menu
function hideOptionMenu(){
    var menu = $(".optionMenu");
    if(menu.css("display") != "none"){
        menu.css("display", "none");
    }
}


//openPage without reload function
function openPage(url){
    if(timer != null){
        clearTimeout(timer);
    }
    if(url.indexOf("?") == -1){
        url = url + "?";
    }
    var encodedUrl = encodeURI(url + "&userLoggedIn=" +  userLoggedIn);
    //console.log(userLoggedIn);
    $("#mainContent").load(encodedUrl);
    $("body").scrollTop(0); //scrolls to the top when we change pages
    history.pushState(null,null,url); //virtually changes the url when we move from one page to another
}

//create playlist function
function createPlayList(){
    var popup = prompt("Please enter the name of your playlist");
    if(popup != ""){
        $.post("includes/handlers/ajax/createPlayList.php",{name:popup, username: userLoggedIn}).done(function(error){ // what 'done' does is that it exectues the fucntion when ajax call is done
            if(error != ""){
                alert(error);
                return;
            }
            //do something when ajax returns
            openPage("yourMusic.php");
        });
    }
}

//delete playlist function
function deletePlaylist(playlistId){
    var prompt = confirm("Are you sure you want to delte this playlist?");
    if(prompt == true){
        $.post("includes/handlers/ajax/deletePlayList.php",{playlistId : playlistId}).done(function(error){ //what done does is that it exectues the fucntion when ajax call is done
            if(error != ""){
                alert(error);
                return;
            }
            //do something when ajax returns
            openPage("yourMusic.php");
        });
    }
}

//artist.php play button function
function playFirstSong(){
    setTrack(tempPlayList[0],tempPlayList, true);
}

//formate time function
function formatTime(timeSec){
    var time = Math.round(timeSec);
    var minutes = Math.floor(time / 60); //rounds number down
    var seconds = time - (minutes * 60);
    var extraZero = (seconds < 10 ? "0" : "");
    return (minutes + ":" +extraZero + seconds); 
}

//update time progress bar function
function updateTimeProgressBar(audio){
    $(".progressTime.current").text(formatTime(audio.currentTime));
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));
    var progress = (audio.currnetTime /audio.duration) * 100;
    $(".playbackBar .progress").css("width",progress + "%");
}


//update volume progress bar function
function updateVolumeProgressBar(audio){
    var volume = (audio.volume * 100);
    $(".volumeBar .progress").css("width",volume + "%");
}

//audio class
function Audio(){
    this.currentlyPlaying;
    this.audio = document.createElement('audio'); //here audio is the audio built in html code

    this.audio.addEventListener("ended",function(){
        nextSong();
    });

    this.audio.addEventListener("canplay", function(){ //canplay refers to played songs
        //'this' refers to the object that the event was called on
        var duration = formatTime(this.duration);
        $(".progressTime.remaining").text(duration); //time display gaarxa
    });

    this.audio.addEventListener("timeupdate", function(){ //canplay refers to played songs
        if(this.duration){
            updateTimeProgressBar(this); //pointing to current audio playing
        }
    });
    
    this.setTrack = function(track){
        this.currentlyPlaying = track;
        this.audio.src = track.path;
    }

    this.play = function(){
        this.audio.play();
    }

    this.pause = function(){
        this.audio.pause();
    }

    this.audio.addEventListener("volumechange", function(){
        updateVolumeProgressBar(this);
    });

    this.setTime = function(seconds){
        this.audio.currentTime = seconds;
    }
}