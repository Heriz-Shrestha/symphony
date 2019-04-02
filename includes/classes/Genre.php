<?php

    class Genre{
        private $con;
        private $id;
        private $name;
        //private $artistId;
        //private $songIds;

        public function __construct($con,$id){
            $this->con = $con;
            $this->id = $id;

            $albumQuery = mysqli_query($this->con, "SELECT * FROM genres WHERE id = '$this->id'");
            $genre = mysqli_fetch_array($genreQuery);

            $this->name = $genre['name'];

        }

        public function getName(){
            return $this->name;
        }

        public function getId(){
            return $this->id;
        }

        public function getNoOfSongs(){
            $noOfSongsQuery = mysqli_query($this->con,"SELECT * FROM songs WHERE genre = '$this->id'");
            return mysqli_num_rows($noOfSongsQuery);
        }

        public function getSongIds(){
            $songIds = mysqli_query($this->con,"SELECT id FROM songs WHERE genre='$this->id' ORDER BY plays ASC"); //lowest albumOrder is at top of the list
            //var_dump($songIds);
            $array = array();
            while($row = mysqli_fetch_array($songIds)){
                array_push($array,$row['id']);
            }
            return $array;
        }
    }

?>