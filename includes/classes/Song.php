<?php

    class Song{
        private $con;
        private $id;
        private $mysqliData;
        private $title,$artistId,$albumId,$genre,$path;

        public function __construct($con,$id){
            $this->con = $con;
            $this->id = $id;
            $songQuery = mysqli_query($this->con,"SELECT * FROM songs WHERE id = '$this->id'");
            //var_dump($albumQuery);
            $this->mysqliData = mysqli_fetch_array($songQuery);
            $this->title = $this->mysqliData['title'];
            $this->artistId = $this->mysqliData['artist'];
            $this->albumId = $this->mysqliData['album'];
            $this->genre = $this->mysqliData['genre'];
            $this->path = $this->mysqliData['path'];
        }

        public function getTitle(){
            return $this->title;
        }

        public function getId(){
            return $this->id;
        }

        public function getArtist(){
            return new Artist($this->con,$this->artistId);
        }

        public function getAlbumId(){
            return new Album($this->con,$this->albumId);
        }

        public function getGenre(){
            return new Genre($this->con,$this->genre);
        }

        // public function getDuration(){
        //     return $this->duration;
        // }

        public function getPath(){
            return $this->path;
        }

        public function getMysqliData(){
            return $this->mysqliData;
        }

        
    }

?>