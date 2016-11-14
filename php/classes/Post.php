<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    require_once "DB.php";
    class Post{
        
        public function getPosts($filters){
            $db = new DB();
            if($filters = NULL){
                $sql = "";  
            }
            else{
                //bool for sort by price, text for keywords, categoryID for category
                $sql .= "";
            }
            $sql .= ";";
            $return = $db->query($sql);
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                //display post html
                //if sold, show sold icon
                if($return["Sold"] == 1){
                    //display Sold icon/text
                }
            }
        }
        
        public function getPostDetails($postID){ 
            $db = new DB();
            $sql = ""; //get all post fields
            $postReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $userID = $return["UserID"];
            $sql = ""; //get User details (probably just name fields and id)
            $userReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            //display logic here
                if($postReturn["Sold"] == 1){
                    //display Sold icon
                }
               // else if($userID = ($_SESSION["Current_User"])->userID){
                    //display mark as sold form
               //}
        }
        
        public function createPost($postData){
            $db = new DB();
            //need to handle non required fields
            //for each in postData
                //append to sql
             $sql = ""; //insert new posts
        }
        
        public function markSold($postID){
            //$currUserID = ($_SESSION["Current_User"])->userID; //get userID of current logged in user
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
            $db = new DB();
            //$currUserID = ($_SESSION["Current_User"])->userID;
            $sql = ""; 
            $db->execute($sql);
        }
        
        public function getComments($postID){
            $db = new DB();
            $sql = ""; //get all comments for a post
            $return = $sql->query($sql);
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                $commenterID = $return["UserID"];
                $sql = ""; //get name of commenter
            }
        }
        
    }