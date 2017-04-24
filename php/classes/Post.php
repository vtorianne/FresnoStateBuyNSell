<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    require_once "DB.php";
    class Post{

        public function getPosts(){
            $sql = isset($_POST['searchSubmit']) ? $this->getFilteredQuery() : "SELECT * FROM products ORDER BY PostTime DESC;";
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
            require_once "../html/footer2.html"; //footer
        }

        public function getCurrUserPosts(){
            $currUserID = $_SESSION["Current_User"];
            $sql = "SELECT * FROM products WHERE UserID = $currUserID;";
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
            require_once "../html/header_style2.html"; //header
            require_once "views/mylistings.php"; //template
            require_once "../html/footer2.html"; //footer

        }

        public function getPostDetails(){
            $db = new DB();
            $postID = $_GET["post-id"];
            $sql = "SELECT * FROM products WHERE ProductID = $postID;"; //get all post fields
            $postReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $userID = $postReturn["UserID"];
            $conditionID = $postReturn["ConditionID"];
            $sql = "SELECT UserID, FirstName, LastName FROM users WHERE UserID = $userID;"; //get User details (probably just name fields and id)
            $userReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $comments = $this->getComments($postID);
            $sql = "SELECT ConditionName FROM conditions WHERE ConditionID = $conditionID;";
            $conditionReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            require_once "../html/header_style2.html"; //header
            require_once "views/listing.php";
            require_once "../html/footer2.html"; //footer
        }

        public function getCurrUserPostDetails(){
            $db = new DB();
            $postID = $_GET["post-id"];
            $currUserID = $_SESSION["Current_User"];
            $sql = "SELECT * FROM products WHERE ProductID = $postID AND UserID = $currUserID;"; //get all post fields
            $postReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($postReturn){
                $categories = $this->getPostCategories();
                $conditions = $this->getPostConditions();
                require_once "../html/header_style2.html"; //header
                require_once "views/dashboard.php"; //template
                require_once "../html/footer2.html"; //footer
            }
            else{
                header("Location: index.php?option=forbidden");
            }
        }

        public function getFilteredQuery(){
            $sql = "SELECT * FROM products";
            $filters = false; //for SQL generation
            $conditionFilters = false;
            if(isset($_POST["Min"]) && $_POST["Min"] != ""){
                if(!$filters){
                    $sql .= " WHERE ";
                    $filters = true;
                }
                $sql .= "Price >= ".$_POST["Min"];
            }
            if(isset($_POST["Max"]) && $_POST["Max"] != ""){
                if(!$filters){
                    $sql .= " WHERE ";
                    $filters = true;
                }
                else{
                    $sql .= " AND ";
                }
                $sql .= "Price <= ".$_POST["Max"];
            }
            if(isset($_POST["New"])){
                if(!$filters){
                    $sql .= " WHERE ";
                    $filters = true;
                    $conditionFilters = true;
                }
                else{
                    if(!$conditionFilters){
                        $sql .= " AND ";
                        $conditionFilters = true;
                    }
                    else{
                        $sql .= " OR ";
                    }
                }
                $sql .= "ConditionID = ".$_POST["New"];
            }
            if(isset($_POST["Used"])){
                if(!$filters){
                    $sql .= " WHERE ";
                    $filters = true;
                    $conditionFilters = true;
                }
                else{
                    if(!$conditionFilters){
                        $sql .= " AND ";
                        $conditionFilters = true;
                    }
                    else{
                        $sql .= " OR ";
                    }
                }
                $sql .= "ConditionID = ".$_POST["Used"];
            }
            if(isset($_POST["category"]) && $_POST["category"] != ""){
                if(!$filters){
                    $sql .= " WHERE ";
                    $filters = true;
                }
                else{
                    $sql .= " AND ";
                }
                $sql .= "CategoryID = ".$_POST["category"];
            }
            if(isset($_POST["keywords"]) && $_POST["keywords"] != ""){
                if(!$filters){
                    $sql .= " WHERE ";
                    $filters = true;
                }
                else{
                    $sql .= " AND ";
                }
                $keywords = explode(" ", $filters["keywords"]);
                //to be finished
            }
            switch($_POST["Filter"]){ //sortBy
                case "Most Recent":
                    $sql .= " ORDER BY PostTime DESC";
                    break;
                case "Price low to high":
                    $sql .= " ORDER BY Price ASC";
                    break;
                /*case "Best User rating":
                    break;*/
            }
            $sql .= ";";
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

        public function getPostConditions(){
            $db = new DB();
            $sql = "SELECT * FROM conditions;";
            $return = $db->execute($sql);
            $conditions = array();
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                $condition = array(
                    "ConditionID" => $row["ConditionID"],
                    "ConditionName" => $row["ConditionName"]
                );
                array_push($conditions, $condition);
            }
            return $conditions;
        }

        public function createPost(){
            $db = new DB();
            $target_file = "/uploads/listing_pics/".basename($_FILES["pic"]["name"]);
            $target_dir =  $_SERVER['DOCUMENT_ROOT'].$target_file;
            move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir);
            $userID = $_SESSION["Current_User"];
            $productname = $_POST["title"];
            $categoryID = $_POST["category"];
            $conditionID = $_POST["condition"];
            $price = $_POST["price"];
            $description = (isset($_POST["desc"]) ? $_POST["desc"] : "");
            $picturepath = $target_file;
            $sql = "INSERT INTO products (UserID, ProductName, CategoryID, ConditionID, Price, Description, PicturePath) VALUES ($userID, '$productname', $categoryID, $conditionID, $price, '$description', '$picturepath'); "; //insert new posts
            $db->execute($sql);
        }

        public function editPost(){
            $db = new DB();
            $postID = $_GET["post-id"];
            $productname = $_POST["title"];
            $categoryID = $_POST["category"];
            $conditionID = $_POST["condition"];
            $price = $_POST["price"];
            $description = (isset($_POST["desc"]) ? $_POST["desc"] : "");
            $sql = "UPDATE products SET ProductName = '$productname', CategoryID = $categoryID, ConditionID = $conditionID, Price = $price, Description = '$description', ModifiedTime = NOW() WHERE ProductID = $postID;";
            echo $sql;
            $db->execute($sql);
        }

        public function markIfSold(){
            $postID = $_GET["post-id"];
            $sold = $_GET["sold"];
            $currUserID = $_SESSION["Current_User"];
            $db = new DB();
            $sql = "SELECT UserID FROM products WHERE ProductID = $postID;"; //get Post's userID
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return["UserID"] != $currUserID ){ //check if the current userID matches the post creator's ID
                return false;
            }
            else{
                $sql = "UPDATE products SET Sold=$sold WHERE ProductID = $postID;"; //update "Sold" to true
                $db->execute($sql);
                return true;
            }
        }

        public function deletePost(){
            $postID = $_GET["post-id"];
            $currUserID = $_SESSION["Current_User"];
            $db = new DB();
            $sql = "SELECT UserID FROM products WHERE ProductID = $postID;"; //get Post's userID
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return["UserID"] != $currUserID ){ //check if the current userID matches the post creator's ID
                return false;
            }
            else{
                $sql = "DELETE FROM products WHERE ProductID = $postID;";
                $db->execute($sql);
                return true;
            }
        }

        public function updateListingPic(){
            $postID = $_GET["post-id"];
            $db = new DB();
            $target_file = "/uploads/listing_pics/".basename($_FILES["pic"]["name"]);
            $target_dir =  $_SERVER['DOCUMENT_ROOT'].$target_file;
            move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir);
            $sql = "UPDATE products SET PicturePath = '$target_file' where ProductID = $postID;";
            $db->execute($sql);
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
