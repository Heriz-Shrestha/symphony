<?php

    class Album{
        private $con;
        private $id;
        private $title;
        private $artistId;
        private $genre,$artworkPath;

        public function __construct($con,$id){
            $this->con = $con;
            $this->id = $id;

            $albumQuery = mysqli_query($this->con, "SELECT * FROM albums WHERE id = '$this->id'");
            $album = mysqli_fetch_array($albumQuery);

            $this->title = $album['title'];
            $this->artistId = $album['artist'];
            $this->genre = $album['genre'];
            //$this->artworkPath = $album['artworkPath'];

        }

        public function getTitle(){
            return $this->title;
        }

        public function getArtist(){
            return new Artist($this->con, $this->artistId);
        }

        public function getArtworkPath(){
            return "assets/images/artwork/albumCover.jpg";
        }

        public function getGenre(){
            return $this->genre;
        }

        public function getNoOfSongs(){
            $noOfSongsQuery = mysqli_query($this->con,"SELECT * FROM songs WHERE album = '$this->id'");
            return mysqli_num_rows($noOfSongsQuery);
        }

        public function getSongIds(){
            $songIds = mysqli_query($this->con,"SELECT id FROM songs WHERE album='$this->id' ORDER BY albumOrder ASC"); //lowest albumOrder is at top of the list
            //var_dump($songIds);
            $array = array();
            while($row = mysqli_fetch_array($songIds)){
                array_push($array,$row['id']);
            }
            return $array;
        }
    }

?>