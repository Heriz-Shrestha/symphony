<?php   
    include("includes/includedFiles.php");
    if($_SESSION['userLoggedIn'] == "admin"){
        
    }else{
        header("Location:index.php");
    }

    
?>

<div class="userInfo">   
    <h1 class="pageHeadingBig"><?php echo "Welcome ".$userLoggedIn->getFirstAndLastName(); ?> </h1>
    <h2 class="pageSubHeadingBig"><?php echo "ADMIN PANNEL" ?> </h1>
</div>

<div class="entityInfo">
    <div class="InsertSection">
        <?php echo("INSERT"); ?>
        <form enctype="multipart/form-data" action="includes/handlers/adminPannel.php" method="POST">
        <p>
            <label for="songAritstName">Artist name</label>
            <input id="songAritstName" name="songAritstName" type="text" placeholder="e.g. Mick">
        </p>

        <p>
            <label for="songGenreName">Genre name</label>
            <input id="songGenreName" name="songGenreName" type="text" placeholder="e.g. Pop">
        </p>

        <p>
            <label for="songAlbumName">Album name</label>
            <input id="songAlbumName" name="songAlbumName" type="text" placeholder="e.g. Roll N' Pappers">
        </p>
        <p>
            <label for="songFile">Song File</label>
            <input name="userfile" type="file"/>
        </p>
        <button class="form-button green" type="submit" name="upload"> Upload </button>
        </form>
    </div>    

    <div class="UpdateSection">
        <div class="artist">
            <?php echo("UPDATE"); ?>
            <form action="includes/handlers/adminPannel.php" method = "POST">
                <p>
                    <label for="CartistName">Artist name that is to be changed:</label>
                    <input id="CartistName" name="CartistName" type="text" placeholder="e.g. April" required>
                </p>
                <p>
                    <label for="RartistName">Change name:</label>
                    <input id="RartistName" name="RartistName" type="text" placeholder="e.g. Apriled" required>
                </p>
                <p>
                    <label for="CgenreName">Genre name that is to be changed:</label>
                    <input id="CgenreName" name="CgenreName" type="text" placeholder="e.g. Pop" required>
                </p>
                <p>
                    <label for="RgenreName">Change name</label>
                    <input id="RgenreName" name="RgenreName" type="text" placeholder="e.g. Pop" required>
                </p>
                <p>
                    <label for="CalbumName">Album name that is to be changed:</label>
                    <input id="CalbumName" name="CalbumName" type="text" placeholder="e.g. AM" required>
                </p>
                <p>
                    <label for="RalbumName">Change name:</label>
                    <input id="RalbumName" name="RalbumName" type="text" placeholder="e.g. AM" required>
                </p>
                <p>
                <label for="CsongName">Song name that is to be changed:</label>
                <input id="CsongName" name="CsongName" type="text" placeholder="e.g. AC/DC" required>
                </p>
                <p>
                    <label for="RsongName">Change name:</label>
                    <input id="RsongName" name="RsongName" type="text" placeholder="e.g. knock" required>
                </p>
                <button class = "form-button green" type="submit" name="update"> UPDATE </button>
            </form> 
        </div>
    </div>

    <div class="DeleteSection">
        <div class="album">
            <?php echo("DELETE ALBUM NAME"); ?>
            <form action="includes/handlers/adminPannel.php" method = "POST">
                <p>
                    <label for="DalbumName">Album name</label>
                    <input id="DalbumName" name="DalbumName" type="text" placeholder="e.g. AM" required>
                </p>
                <p>
                    <label for="DsongName">Select Song</label>
                    <input id="DsongName" name="DsongName" type="text" placeholder ="e.g Rock On" required>
                </p>
                <button class = "form-button green" type="submit" name="delete"> DELETE </button>
            </form> 
        </div>
    </div>
</div>    

<div class="display">
    <?php echo("<h2>Please the option which you want to display</h2>"); ?>
    <form action="test.php" method="POST" id="form_name" target="_blank">
        Select:
        <input type="radio" value = "song" name = "dataValue">Song
        <input type="radio" value = "artist" name = "dataValue">Artist
        <input type="radio" value = "album" name = "dataValue">Album
        <input type="radio" value = "genre" name = "dataValue">Genre
        <input name="sub" type="submit" class = "form-button green" id ="submit" value="OK" onclick="formData()">
    </form>   
</div>
<!-- <script>
    function formData(){
        $.post
    }
</script> -->

