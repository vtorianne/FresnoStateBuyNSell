<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    require_once "DB.php";
    class Post{
        
        public function getPosts(){
            $db = new DB();
            $sql = "";
            $return = $db->query($sql);
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                
            }
        }
        
        public function createPost($postData){
            
        }
        
    }