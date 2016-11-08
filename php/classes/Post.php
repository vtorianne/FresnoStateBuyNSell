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
                //display post html
                //if sold, show sold icon
                //else if userID = current user, show mark as sold form
                //else just show post
            }
        }
        
        public function createPost($postData){
            $db = new DB();
            //need to handle non required fields
            //for each in postData
                //append to sql
             $sql = ""; //insert new posts
        }
        
        public function markSold($postID){
            $currUserID = ($_SESSION["Current_User"])->userID; //get userID of current logged in user
            $db = new DB();
            $sql = ""; //get Post's userID
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return["UserID"] != $currUserID ){ //check if the current userID matches the post creator's ID
                return false;
            }
            else{
                $sql = ""; //update "Sold" to true
                $db->execute($sql);
                return true;
            }
        }
        
        public function addComment($postID, $commentData){
            
        }
        
    }