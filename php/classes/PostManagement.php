<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    require_once "DB.php";

    class PostManagement{
        private $db;

        public function __construct(){
            $this->db = new DB();
        }

        public function createPost(){
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
            $this->db->execute($sql);
        }

        public function editPost(){
            $postID = $_GET["post-id"];
            $productname = $_POST["title"];
            $categoryID = $_POST["category"];
            $conditionID = $_POST["condition"];
            $price = $_POST["price"];
            $description = (isset($_POST["desc"]) ? $_POST["desc"] : "");
            $sql = "UPDATE products SET ProductName = '$productname', CategoryID = $categoryID, ConditionID = $conditionID, Price = $price, Description = '$description', ModifiedTime = NOW() WHERE ProductID = $postID;";
            $this->db->execute($sql);
        }

        public function markIfSold(){
            $postID = $_GET["post-id"];
            $sold = $_GET["sold"];
            $currUserID = $_SESSION["Current_User"];
            $sql = "SELECT UserID FROM products WHERE ProductID = $postID;"; //get Post's userID
            $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return["UserID"] != $currUserID ){ //check if the current userID matches the post creator's ID
                return false;
            }
            else{
                $sql = "UPDATE products SET Sold=$sold WHERE ProductID = $postID;"; //update "Sold" to true
                $this->db->execute($sql);
                return true;
            }
        }

        public function deletePost(){
            $postID = $_GET["post-id"];
            $currUserID = $_SESSION["Current_User"];
            $sql = "SELECT UserID FROM products WHERE ProductID = $postID;"; //get Post's userID
            $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return["UserID"] != $currUserID ){ //check if the current userID matches the post creator's ID
                return false;
            }
            else{
                $sql = "DELETE FROM products WHERE ProductID = $postID;";
                $this->db->execute($sql);
                return true;
            }
        }

        public function updateListingPic(){
            $postID = $_GET["post-id"];
            $target_file = "/uploads/listing_pics/".basename($_FILES["pic"]["name"]);
            $target_dir =  $_SERVER['DOCUMENT_ROOT'].$target_file;
            move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir);
            $sql = "UPDATE products SET PicturePath = '$target_file' where ProductID = $postID;";
            $this->db->execute($sql);
        }

        public function addComment(){
            $currUserID = $_SESSION["Current_User"];
            $postID = $_GET["post-id"];
            $comment = $_POST["comment"];
            $sql = "INSERT INTO comments (ProductID, UserID, Comment) VALUES ($postID, $currUserID, '$comment');";
            $this->db->execute($sql);
        }

    }