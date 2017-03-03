<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    require_once "DB.php";
    class Post{
        
        public function getPosts(){
            if(isset($_POST['searchSubmit'])){
                $filters = array();
                if(isset($_POST["priceSort"]))
                    $filters["priceSort"] = $_POST["priceSort"];
                if(isset($_POST["keywords"]))
                    $filters["keywords"] = $_POST["keywords"];
                if(isset($_POST["category"]))
                    $filters["categoryID"] = $_POST["category"];
            }
            else{
                $filters = null;
            }
            $sql = $this->getFilteredQuery($filters);
            $db = new DB();
            $return = $db->query($sql);
            $posts = array();
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                $post = array (
                    "Sold" => $row["Sold"],
                    "ProductID" => $row["ProductID"],
                    "ProductName" => $row["ProductName"],
                    "PicturePath" => $row["PicturePath"],
                    "Price" => $row["Price"]
                );
                array_push($posts, $post);
            }
            $categories = $this->getPostCategories();
            require_once "../html/header_style2.html"; //header
            require_once "views/listings.php";
            require_once "../html/footer.html"; //footer
        }
        
        public function getPostDetails(){
            $db = new DB();
            $postID = $_GET["post-id"];
            $sql = "SELECT * FROM products WHERE ProductID = $postID;"; //get all post fields
            $postReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $userID = $postReturn["UserID"];
            $sql = "SELECT UserID, FirstName, LastName FROM users WHERE UserID = $userID;"; //get User details (probably just name fields and id)
            $userReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $comments = $this->getComments($postID);
            require_once "../html/header_style2.html"; //header
            require_once "views/listing.php";
            require_once "../html/footer.html"; //footer
        }

        public function getFilteredQuery($filters){
            if($filters == NULL) {
                $sql = "SELECT * FROM products ORDER BY PostTime DESC;";
            }
            else{
                $sql = "SELECT * FROM products WHERE ";
                $searchfilterscount=0;
                if(array_key_exists("keywords", $filters) && $filters["keywords"] != ""){
                    $keywords = explode(" ", $filters["keywords"]);
                    $search_term = "%";
                    foreach($keywords as $keyword){
                        $search_term .= $keyword."%";
                    }
                    if ($searchfilterscount == 0 ){
                        $sql .= "'$search_term' LIKE ProductName OR '$search_term' LIKE Description";
                    }
                    else{
                        $sql .= " AND '$search_term' LIKE ProductName OR '$search_term' LIKE Description";
                    }
                    $searchfilterscount++;
                }
                if(array_key_exists("categoryID", $filters) && $filters["categoryID"] != ""){
                    $categoryID = $filters["categoryID"];
                    if ($searchfilterscount==0){
                        $sql .= "'$categoryID' = CategoryID";
                    }
                    else {
                        $sql .= " AND '$categoryID' = CategoryID";
                    }
                    $searchfilterscount++;
                }

                if($searchfilterscount == 0)
                    $sql.= " 1";

                if(array_key_exists("priceSort", $filters)){
                    $sql .= " ORDER BY Price ASC, PostTime DESC";
                }
                else {
                    $sql .= " ORDER BY PostTime DESC";
                }
                $sql .= ";";
            }
            return $sql;
        }

        public function getPostCategories(){
            $db = new DB();
            $sql = "SELECT * FROM categories;";
            $return = $db->execute($sql);
            $categories = array();
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                $category = array(
                  "CategoryID" => $row["CategoryID"],
                  "CategoryName" => $row["CategoryName"]
                );
               array_push($categories, $category);
            }
            return $categories;
        }
        
        public function createPost(){
            $db = new DB();
            $target_file = "/uploads/listing_pics/".basename($_FILES["pic"]["name"]);
            $target_dir =  $_SERVER['DOCUMENT_ROOT'].$target_file;
            move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir);
            $userID = $_SESSION["Current_User"];
            $productname = $_POST["title"];
            $categoryID = $_POST["category"];
            $price = $_POST["price"];
            $description = (isset($_POST["desc"]) ? $_POST["desc"] : "");
            $picturepath = $target_file;
            $sql = "INSERT INTO products (UserID, ProductName, CategoryID, Price, Description, PicturePath) VALUES ($userID, '$productname', $categoryID, $price, '$description', '$picturepath'); "; //insert new posts
            $db->execute($sql);
        }
        
        public function markSold(){
            $postID = $_GET["post-id"];
            $currUserID = $_SESSION["Current_User"];
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
        
        public function addComment(){
            $db = new DB();
            $currUserID = $_SESSION["Current_User"];
            $postID = $_GET["post-id"];
            $comment = $_POST["comment"];
            $sql = "INSERT INTO comments (ProductID, UserID, Comment) VALUES ($postID, $currUserID, '$comment');";
            $db->execute($sql);
        }
        
        public function getComments($postID){
            $db = new DB();
            $sql = "SELECT * FROM comments WHERE ProductID = $postID ORDER BY CommentTimeStamp ASC;"; //get all comments for a post
            $return = $db->query($sql);
            $comments = array();
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                $commenterID = $row["UserID"];
                $sql = "SELECT * FROM users WHERE UserID = $commenterID;"; //get name of commenter
                $userReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
                $comment = array(
                    "Comment" => $row["Comment"],
                    "FirstName" => $userReturn["FirstName"],
                    "LastName" => $userReturn["LastName"],
                    "CommentTimeStamp" => $row["CommentTimeStamp"]
                );
                array_push($comments, $comment);
            }
            return $comments;
        }
        
    }