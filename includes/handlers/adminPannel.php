<?php
    include("../config.php");

    function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText); //removes tags from $inputText
        $inputText = ucfirst(strtolower($inputText));
        return $inputText;
    }    

    //insert songs           
    if(isset($_POST['upload'])) {
        //upload button was pressed
        if($_POST['songAritstName'] != NULL ){
            $artistName = sanitizeFormString($_POST['songAritstName']);
            $query1=mysqli_query($con,"SELECT id FROM artists WHERE art_name = '$artistName'");
            if(mysqli_num_rows($query1) != 0){
                $artistIdQuery = mysqli_query($con,"SELECT id FROM artists WHERE art_name = '$artistName'");
                $row = mysqli_fetch_array($artistIdQuery);
                $artistId = $row['id'];
            }else{
                $ArtistQuery = mysqli_query($con,"INSERT INTO artists VALUES ('','$artistName')");
                $artistIdQuery = mysqli_query($con,"SELECT id FROM artists WHERE art_name = '$artistName'");
                $row = mysqli_fetch_array($artistIdQuery);
                $artistId = $row['id'];
            }

            //genre
            if($_POST['songGenreName'] != NULL){
                $genreName = sanitizeFormString($_POST['songGenreName']);
                $query2=mysqli_query($con,"SELECT id FROM genres WHERE name = '$genreName'");
                if(mysqli_num_rows($query2) != 0){
                    $genreIdQuery = mysqli_query($con,"SELECT id FROM genres WHERE name = '$genreName'");
                    $row = mysqli_fetch_array($genreIdQuery);
                    $genreId = $row['id'];
                }else{
                    $GenreQuery = mysqli_query($con,"INSERT INTO genres VALUES ('','$genreName')");
                    $genreIdQuery = mysqli_query($con,"SELECT id FROM genres WHERE name = '$genreName'");
                    $row = mysqli_fetch_array($genreIdQuery);
                    $genreId = $row['id'];
                }

                //defining moodName
                if($genreName == "Jazz"){
                    $moodName = "working,dinner,relaxing,sleeping";
                }
                if($genreName == "Hard rock" || $genreName == "Edm" || $genreName == "Trap"){
                    $moodName = "morning";
                }
                if($genreName == "Country"){
                    $moodName = "working";
                }
                if($genreName == "Instrumental"){
                    $moodName = "relaxing";
                }
                if($genreName == "Classical"){
                    $moodName = "sleeping";
                }
                if($genreName == "R&b" || $genreName == "Pop" ){
                    $moodName = "driving";
                }

                //album
                if($_POST['songAlbumName'] != NULL){
                    $albumName = sanitizeFormString($_POST['songAlbumName']);
                    $query3=mysqli_query($con,"SELECT id FROM albums WHERE title = '$albumName'");
                    if(mysqli_num_rows($query3) != 0){
                        $albumIdQuery = mysqli_query($con,"SELECT id FROM albums WHERE title = '$albumName'");
                        $row = mysqli_fetch_array($albumIdQuery);
                        $albumId = $row['id'];
                        $album = "old";
                    }else{
                        $albumQuery= mysqli_query($con,"INSERT INTO albums VALUES('','$albumName','$artistId','$genreId','assets/images/artwork/albumCover.jpg')");
                        $albumIdQuery = mysqli_query($con,"SELECT id FROM albums WHERE title = '$albumName'");
                        $row = mysqli_fetch_array($albumIdQuery);
                        $albumId = $row['id'];
                        $album = "new";
                    }

                    if(isset($_FILES['userfile'])){
                        $errors= array();
                        $file_name = $_FILES['userfile']['name'];
                        $file_size = $_FILES['userfile']['size'];
                        $file_tmp = $_FILES['userfile']['tmp_name'];
                        $file_type = $_FILES['userfile']['type'];
                        //echo($file_name."<br>");
                        $file_ext=strtolower(end(explode('.',$_FILES['userfile']['name'])));
                        
                        $expensions= array("mp3");
                        
                        if(in_array($file_ext,$expensions)=== false){
                           $errors[]="extension not allowed, please choose a .mp3 file.";
                        }
                        
                        if($file_size > 10485760) {
                           $errors[]='File size must be excately 10 MB';
                        }
                        
                        if(empty($errors)==true) {
                           move_uploaded_file($file_tmp,"../../assets/music/".$file_name);
                           echo "Success";
                            $path = "assets/music/$file_name";
                            $fileName = basename($path,".mp3");
                            //echo($fileName);
                        }else{
                           print_r($errors);
                           exit();
                        }

                        $query = mysqli_query($con,"SELECT id FROM songs WHERE title = '$fileName'");
                        //var_dump($query);
                        if(mysqli_num_rows($query) == 0){
                            if($album == "old"){
                                $albumOrderQuery = mysqli_query($con, "SELECT MAX(albumOrder) + 1 as albumOrder FROM songs WHERE album='$albumId'"); 
                                $row = mysqli_fetch_array($albumOrderQuery);
                                $albumOrder = $row['albumOrder'];
                                //echo($albumOrder);
                            }else{
                                $albumOrder = 1;
                                //echo($albumOrder);
                            }
                            $genreQuery= mysqli_query($con,"INSERT INTO songs VALUES('','$fileName','$artistId','$albumId','$genreId','$moodName','$path','$albumOrder',0)");
                                
                        }else{
                            echo("Song Title Already Exits");
                            exit();
                        }
                    }
                }
            }
            
        }elseif($_POST['songGenreName'] != NULL){
            $genreName = sanitizeFormString($_POST['songGenreName']);
            $query2=mysqli_query($con,"SELECT id FROM genres WHERE name = '$genreName'");
            if(mysqli_num_rows($query2) == 0){
                $GenreQuery = mysqli_query($con,"INSERT INTO genres VALUES ('','$genreName')");
            }
        }else{
            echo("invalid details");
            exit();
        }
    }        
        
    //update
    if(isset($_POST['update'])){
        if($_POST['CartistName'] != NULL && $_POST['RartistName'] != NULL){
            $CartistName = ($_POST['CartistName']);
            $RartistName = ($_POST['RartistName']);
            $query1=mysqli_query($con,"SELECT id FROM artists WHERE art_name = '$CartistName'");
            $query2=mysqli_query($con,"SELECT id FROM artists WHERE art_name = '$RartistName'");
            if(mysqli_num_rows($query1) != 0){
                if(mysqli_num_rows($query2) == 0){
                    $row = mysqli_fetch_array($query1);
                    $updateArtistId = $row['id']; 
                    $artistUpdateQuery = mysqli_query($con,"UPDATE artists SET art_name = '$RartistName' where id = '$updateArtistId'");
                }else{
                    echo("Artist name already present please enter unique.");
                    exit();
                }
                
            }else{
                echo("No Artist name found Please re-enter the value in respective page");
                exit();
            }
        }

        if($_POST['CgenreName'] != NULL && $_POST['RgenreName'] != NULL){
            $CgenreName = ($_POST['CgenreName']);
            $RgenreName = ($_POST['RgenreName']);
            $query1=mysqli_query($con,"SELECT id FROM genres WHERE name = '$CgenreName'");
            $query2=mysqli_query($con,"SELECT id FROM genres WHERE name = '$RgenreName'");
            if(mysqli_num_rows($query1) != 0){
                if(mysqli_num_rows($query2) == 0){
                    $row = mysqli_fetch_array($query1);
                    $updateGenerId = $row['id'];
                    $artistUpdateQuery = mysqli_query($con,"UPDATE genres SET name = '$RgenreName' where id='$updateGenerId'");
                }else{
                    echo("Genre name already present please enter unique.");
                    exit();
                }
            }else{
                echo("No Genre name found Please re-enter the value in respective page");
                exit();
            }
        }

        if($_POST['CalbumName'] != NULL && $_POST['RalbumName'] != NULL){
            $CalbumName = ($_POST['CalbumName']);
            $RalbumName = ($_POST['RalbumName']);
            $query1=mysqli_query($con,"SELECT id FROM albums WHERE name = '$CalbumName'");
            $query2=mysqli_query($con,"SELECT id FROM albums WHERE name = '$RalbumName'");
            if(mysqli_num_rows($query1) != 0){
                if(mysqli_num_rows($query2) == 0){
                    $row = mysqli_fetch_array($query1);
                    $updateAlbumId = $row['id'];
                    $artistUpdateQuery = mysqli_query($con,"UPDATE albums SET name = '$RalbumName' where id='$updateAlbumId'");
                }else{
                    echo("Album name already present please enter unique.");
                    exit();
                }
                
            }else{
                echo("No Album name found Please re-enter the value in respective page");
                exit();
            }
        }
        
        if($_POST['CsongName'] != NULL && $_POST['RsongName'] != NULL){
            $CsongName = ($_POST['CsongName']);
            $RsongName = ($_POST['RsongName']);
            $query1=mysqli_query($con,"SELECT id FROM songs WHERE title = '$CsongName'");
            var_dump($query1);
            $query2=mysqli_query($con,"SELECT id FROM songs WHERE title = '$RsongName'");
            var_dump($query2);
            if(mysqli_num_rows($query1) != 0){
                if(mysqli_num_rows($query2) == 0){
                    $row = mysqli_fetch_array($query1);
                    $updateSongId = $row['id'];
                    $artistUpdateQuery = mysqli_query($con,"UPDATE songs SET title = '$RsongName' where id='$updateSongId'");
                    
                }else{
                    echo("Songs name already present please enter unique.");
                    exit();
                }
                
            }else{
                echo("No Songs name found Please re-enter the value in respective page");
                exit();
            }
        }
    }

    //delete
    if(isset($_POST['delete'])){
        if($_POST['DalbumName'] != NULL){
            $DalbumName = ($_POST['DalbumName']);
            $query=mysqli_query($con,"SELECT id FROM albums WHERE title = '$DalbumName'");
            
            if(mysqli_num_rows($query) != 0){
                //echo("Artist Name already exist");
                $temp = mysqli_fetch_array($query);
                $albumId = $temp['id'];
                $songIdQuery = mysqli_query($con,"SELECT id FROM songs WHERE album = '$albumId'");
                while($row = mysqli_fetch_array($songIdQuery)){
                    $albumSongPlaylistQuery = mysqli_query($con,"DELETE FROM playlistSongs WHERE songId = '".$row['id']."'");
                }
                $songQuery = mysqli_query($con,"DELETE FROM songs WHERE album = '$albumId'"); 
                $albumQuery = mysqli_query($con,"DELETE FROM albums WHERE id = '$albumId'");
            }else{
                echo("No Album found...");
                exit();
            }
        }

        if($_POST['DalbumName'] != NULL){
            $DsongName = ($_POST['DsongName']);
            $query=mysqli_query($con,"SELECT id FROM songs WHERE title = '$DsongName'");
            
            if(mysqli_num_rows($query) != 0){
                //echo("Artist Name already exist");
                $temp = mysqli_fetch_array($query);
                $songId = $temp['id'];
                $songQuery = mysqli_query($con,"DELETE FROM songs WHERE id = '$songId'"); 
                $songPlaylistQuery = mysqli_query($con,"DELETE FROM playlistSongs WHERE songId = '$songId'"); 
                
            }else{
                echo("No Song found...");
                exit();
            }
        } 
    }
    header("Location: ../../adminPage.php");
?>