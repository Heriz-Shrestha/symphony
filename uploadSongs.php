<?php
        include("includes/config.php");
        include("includes/classes/User.php");

        
    
        if(isset($_SESSION['userLoggedIn'])) {
            $userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
            $username = $userLoggedIn->getUsername();

            function sanitizeFormString($inputText) {
                $inputText = strip_tags($inputText); //removes tags from $inputText
                $inputText = ucfirst(strtolower($inputText));
                return $inputText;
            }           
        
            if(isset($_POST['uploadSong'])) {
                //upload button was pressed
                $generName = sanitizeFormString($_POST['genreName']);
                var_dump($generName);
                $albumName = sanitizeFormString($_POST['albumName']);

                //defining moodName
                if($generName == "Jazz"){
                    $moodName = "working,dinner,relaxing,sleeping";
                }
                if($generName == "Hard rock" || $generName == "Edm" || $generName == "Trap"){
                    $moodName = "morning";
                }
                if($generName == "Country"){
                    $moodName = "working";
                }
                if($generName == "Instrumental"){
                    $moodName = "relaxing";
                }
                if($generName == "Classical"){
                    $moodName = "sleeping";
                }
                if($generName == "R&b" || $generName == "Pop" ){
                    $moodName = "driving";
                }

                //var_dump($albumName);

                //$songName = sanitizeFormString($_POST['songName']);
                // $allowedExts = array("mp3");
                // $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            
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
                       move_uploaded_file($file_tmp,"assets/music/".$file_name);
                       echo "Success";
                        $path = "assets/music/$file_name";
                        $fileName = basename($path,".mp3");
                        //echo($fileName);
                    }else{
                       print_r($errors);
                       exit();
                    }
                }
                
            }
            
            //insert artist name
            $query = mysqli_query($con,"SELECT art_name FROM artists WHERE art_name = '$username'");
            if(mysqli_num_rows($query) == 0){
                $artistQuery= mysqli_query($con,"INSERT INTO artists VALUES('','$username')");
                $artistIdQuery = mysqli_query($con,"SELECT id FROM artists WHERE art_name = '$username'");
                $row = mysqli_fetch_array($artistIdQuery);
                $artistId = $row['id'];
            }else{
                $artistIdQuery = mysqli_query($con,"SELECT id FROM artists WHERE art_name = '$username'");
                $row = mysqli_fetch_array($artistIdQuery);
                $artistId = $row['id'];
            }
            echo $artistId."<br>";

            //insert gener name
            $query = mysqli_query($con,"SELECT COUNT(name) as couter FROM genres WHERE name = '$generName'");
            $rows = mysqli_fetch_array($query);
            $temp = $rows['couter'];
            if($temp == 0){
                $genreQuery= mysqli_query($con,"INSERT INTO genres VALUES('','$generName')");
                $genreIdQuery = mysqli_query($con,"SELECT id FROM genres WHERE name = '$generName'");
                $row = mysqli_fetch_array($genreIdQuery);
                $genreId = $row['id'];
            }else{
                $genreIdQuery = mysqli_query($con,"SELECT id FROM genres WHERE name = '$generName'");
                $row = mysqli_fetch_array($genreIdQuery);
                $genreId = $row['id'];
            }
            //echo $genreId."<br>";

            //insert album name if any
            
            if($albumName!="" || $albumName!="undefined" || $albumName!="NULL"){
                
                $query = mysqli_query($con,"SELECT title FROM albums WHERE title = '$albumName'");
                if(mysqli_num_rows($query) == 0){
                    $albumQuery= mysqli_query($con,"INSERT INTO albums VALUES('','$albumName','$artistId','$genreId','assets/images/artwork/albumCover.jpg')");
                    $albumIdQuery = mysqli_query($con,"SELECT id FROM albums WHERE title = '$albumName'");
                    $row = mysqli_fetch_array($albumIdQuery);
                    $albumId = $row['id'];
                    $album = "new";
                }else{
                    $albumIdQuery = mysqli_query($con,"SELECT id FROM albums WHERE title = '$albumName'");
                    $row = mysqli_fetch_array($albumIdQuery);
                    $albumId = $row['id'];
                    $album = "old";
                }
            }else{
                $albumIdQuery = mysqli_query($con,"SELECT id FROM albums WHERE title = 'noAlbum'");
                $row = mysqli_fetch_array($albumIdQuery);
                $albumId = $row['id'];
                $album = "old";
            }
            // echo $albumId."<br>";
            // echo $album."<br>";
            //insert songs name
            $query = mysqli_query($con,"SELECT id FROM songs WHERE title = '$fileName'");
            //var_dump($query);
            if(mysqli_num_rows($query) == 0){
            if($album == "old"){
                $albumOrderQuery = mysqli_query($con, "SELECT MAX(albumOrder) + 1 as albumOrder FROM songs WHERE album='$albumId'"); 
                $row = mysqli_fetch_array($albumOrderQuery);
                $albumOrder = $row['albumOrder'];
                echo($albumOrder);
            }else{
                $albumOrder = 1;
                //echo($albumOrder);
            }
            $genreQuery= mysqli_query($con,"INSERT INTO songs VALUES('','$fileName','$artistId','$albumId','$genreId','$moodName','$path','$albumOrder',0)");
                
            }else{
                echo("Song Title Already Exits");
            }

            //go to
            header("Location: profile.php");
        }
        else {
            //echo "Username variable was not passed into page. Check the openPage JS function"."<br>";
            echo "Please Login";
            exit();
        }


    

?>