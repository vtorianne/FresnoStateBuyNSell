<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    require_once "DB.php";

    class PostRetrieval{
        private $db;

        public function __construct(){
            $this->db = new DB();
        }

        public function getPostCategories(){
            $sql = "SELECT * FROM categories;";
            $return = $this->db->execute($sql);
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
            $sql = "SELECT * FROM conditions;";
            $return = $this->db->execute($sql);
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
                $keywords = explode(" ", $_POST["keywords"]);
                $searchTerm = implode("%", $keywords);
                $searchTerm = "%".$searchTerm."%";
                $sql .= "ProductName LIKE '$searchTerm' OR Description LIKE '$searchTerm'";
            }
            switch($_POST["Filter"]){ //sortBy
                case "Most Recent":
                    $sql .= " ORDER BY PostTime DESC";
                    break;
                case "Price low to high":
                    $sql .= " ORDER BY Price ASC";
                    break;
                case "Best User rating":
                    $sql .= " INNER JOIN (SELECT ProfileID, AVG(StarRating) AS AVGRating FROM reviews GROUP BY ProfileID) ReviewsAverage on ProfileID = products.userID ORDER BY AVGRating DESC";
                    break;
                case "Last Updated":
                    $sql .= " ORDER BY ModifiedTime DESC";
                    break;
            }
            $sql .= ";";
            return $sql;
        }

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
            $return = $this->db->query($sql);
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
            $postID = $_GET["post-id"];
            $sql = "SELECT * FROM products WHERE ProductID = $postID;"; //get all post fields
            $postReturn = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $userID = $postReturn["UserID"];
            $conditionID = $postReturn["ConditionID"];
            $sql = "SELECT UserID, FirstName, LastName FROM users WHERE UserID = $userID;"; //get User details (probably just name fields and id)
            $userReturn = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $comments = $this->getComments($postID);
            $sql = "SELECT ConditionName FROM conditions WHERE ConditionID = $conditionID;";
            $conditionReturn = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            require_once "../html/header_style2.html"; //header
            require_once "views/listing.php";
            require_once "../html/footer2.html"; //footer
        }

        public function getComments($postID){
            $sql = "SELECT * FROM comments WHERE ProductID = $postID ORDER BY CommentTimeStamp ASC;"; //get all comments for a post
            $return = $this->db->query($sql);
            $comments = array();
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                $commenterID = $row["UserID"];
                $sql = "SELECT * FROM users WHERE UserID = $commenterID;"; //get name of commenter
                $userReturn = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
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

        public function getCurrUserPostDetails(){
            $postID = $_GET["post-id"];
            $currUserID = $_SESSION["Current_User"];
            $sql = "SELECT * FROM products WHERE ProductID = $postID AND UserID = $currUserID;"; //get all post fields
            $postReturn = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
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

    }
