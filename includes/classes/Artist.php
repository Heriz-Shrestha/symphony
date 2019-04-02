<?php

    class Artist{
        private $con;
        private $id;

        public function __construct($con,$id){
            $this->con = $con;
            $this->id = $id;
        }

        public function getId(){
            return $this->id;
        }

        public function getName(){
            $artistQuery = mysqli_query($this->con, "SELECT * FROM artists WHERE id = '$this->id'");
            $artist = mysqli_fetch_array($artistQuery);
            return $artist['art_name'];
        }

        public function getSongIds(){
            $songIds = mysqli_query($this->con,"SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays DESC"); //lowest albumOrder is at top of the list
            //var_dump($songIds);
            $array = array();
            while($row = mysqli_fetch_array($songIds)){
                array_push($array,$row['id']);
            }
            return $array;
        }
    }

?>