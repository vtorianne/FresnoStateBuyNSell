<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    require_once "DB.php";
    class Post{
        
        public function getPosts($filters){
            $db = new DB();
            if($filters = NULL){
                $sql = "SELECT * FROM products SORT BY ";
            }
            else{
                //bool for sort by price, text for keywords, categoryID for category
                $sql = "SELECT * FROM products";
                if(array_key_exists("keywords", $filters)){
                $keywords = explode(" ", filters["keywords"]);
                $search_term = "%";
                    foreach($keywords as $keyword){
                        $search_term .= $keyword."%";
                    }
                $sql .= "WHERE '$search_term' LIKE ProductName OR '$search_term' LIKE Description;
                if{array_key_exists("categoryID", $filters))
                $sql .= "WHERE '$categoryID' = CategoryID";}
                if(array_key_exists("price", $filters)){
                $sql .= "ORDER BY Price ASC, PostTime DESC";}
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
            $sql = "SELECT * FROM products WHERE ProductID = $postID;"; //get all post fields
            $postReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $userID = $return["UserID"];
            $sql = "SELECT UserID, FirstName, LastName FROM users WHERE UserID = $userID;"; //get User details (probably just name fields and id)
            $userReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            //display logic here
                if($postReturn["Sold"] == 1){
                    //display Sold icon
                }
                else if($userID = ($_SESSION["Current_User"])->userID){
                    //display mark as sold form
                }
        }
        
        public function createPost($postData){
            $db = new DB();
            $userID;
            $productname;
            $categoryID;
            $price;
            $description;
            $picturepath;
            $sql = "INSERT INTO products (UserID, ProductName, CategoryID, Price, Description, PicturePath) VALUES ($userID, '$productname', $categoryID, $price, '$description','$picturepath'; "; //insert new posts
        }
        
        public function markSold($postID){
            $currUserID = ($_SESSION["Current_User"])->userID; //get userID of current logged in user
            $db = new DB();
            $sql = "SELECT UserID FROM products WHERE ProductID = $postID;"; //get Post's userID
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return["UserID"] != $currUserID ){ //check if the current userID matches the post creator's ID
                return false;
            }
            else{
                $sql = "UPDATE products SET Sold=1 WHERE ProductID = $postID;"; //update "Sold" to true
                $db->execute($sql);
                return true;
            }
        }
        
        public function addComment($postID, $commentData){
            $db = new DB();
            $currUserID = ($_SESSION["Current_User"])->userID;
            $sql = "INSERT INTO comments (ProductID, UserID, Comment) VALUES ($postID, $currUserID, '$commentData');";
            $db->execute($sql);
        }
        
    }
