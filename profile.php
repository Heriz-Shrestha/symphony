<?php
    include("includes/includedFiles.php");
    if(isset($_SESSION['userLoggedIn'])) {
        
    }
    else {
        echo("Invalid user access");
        exit();
        //header("Location: index.php");
    }
?>

<div class = "entityInfo">
    <div class = "centerSection">
        <div class = "userInfo">
            <h1><?php echo $userLoggedIn->getFirstAndLastName(); ?> </h1>
            <div class="profilePic">
                <img src = "assets\images\profile-pic\pp.png" alt="Profile Pic" height="200px" width="200px">
            </div>
        </div>
    </div>
    <div class = "buttonItems">
        <!-- <button class = "button" onclick="openPage('updateDetails.php')"> User Details </button> -->
        <button class = "button" onclick="logout('index.php')"> LogOut </button>
    </div>
    <div class="songUploadForm">
        <h2> Upload Songs </h2>
    <form enctype="multipart/form-data" action="uploadSongs.php" method = "POST">
        <p>
            <label for="genreName">Genre name</label>
            <input id="genreName" name="genreName" type="text" placeholder="e.g. Pop" required>
        </p>

        <p>
            <label for="albumName">Album name</label>
            <input id="albumName" name="albumName" type="text" placeholder="e.g. Roll N' Pappers">
        </p>

        <!-- <p>
            <label for="songName">Song Name</label>
            <input id="songName" name="songName" type="text" placeholder="e.g. American Idot" required>
        </p> -->
        <p>
            <label for="songFile">Song File</label>
            <input name="userfile" type="file" required/>
        </p>
        <button type="submit" name="uploadSong"> Upload </button>
    </form>
    </div>
</div>