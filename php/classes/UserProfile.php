<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    require_once "DB.php";

    class UserProfile{
        private $db;

        public function __construct(){
            $this->db = new DB();
        }

        public function getUserProfile(){
            $userID = (isset($_GET["user-id"]) ? $_GET["user-id"] : ($_SESSION["Current_User"]));
            $sql = "SELECT * FROM users WHERE '$userID' = UserID"; //getting user data from userID
            $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $sql = "SELECT * FROM reviews WHERE ProfileID = $userID;";
            $reviewReturn = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $reviewedYet = ($reviewReturn ? true : false);
            $averageRating = ($reviewedYet ? $this->getAverageRating($userID) : "No Reviews Yet");
            if($reviewedYet) {
                $numWholeStars = floor($averageRating);
                $halfStar = $averageRating - $numWholeStars;
                $reviews = $this->getReviews($userID);
            }
            $userImg = ($return["PicturePath"] != null ? $return["PicturePath"] : "/FresnoStateBuyNSell/img/default_user.png");

            include_once "../html/header_style2.html"; //header
            include_once "views/userprofile.php";
            include_once "../html/footer2.html"; //footer
        }

        public function getReviews($userID){
            $sql = "SELECT * FROM reviews WHERE $userID = ProfileID"; //get all reviews for specified userID
            $return = $this->db->query($sql);
            $reviews = array();
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                $commenterID = $row["CommenterID"];
                $sql = "SELECT FirstName, LastName FROM users WHERE $commenterID = UserID"; //get name of reviewer
                $userReturn = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
                $review = array(
                    "StarRating" => $row["StarRating"],
                    "ReviewText" => $row["ReviewText"],
                    "ReviewTimeStamp" => $row["ReviewTimeStamp"],
                    "FirstName" => $userReturn["FirstName"],
                    "LastName" => $userReturn["LastName"]
                );
                array_push($reviews, $review);
            }
            return $reviews;
        }

        public function getAverageRating($userID){
            $sql = "SELECT ROUND(AVG(StarRating),2) AS StarRatingAverage FROM reviews WHERE $userID = ProfileID";  //get average review (star rating)
            $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            return $return["StarRatingAverage"];
        }

        public function review(){
            $profileID = $_GET["user-id"];
            $commenterID = $_SESSION["Current_User"];
            $starRating = $_POST["rating"];
            $reviewText = $_POST["comment"];
            $sql = "INSERT INTO reviews (CommenterID, ProfileID, StarRating, ReviewText) VALUES ($commenterID, $profileID, $starRating, '$reviewText');"; //inserting review record
            $this->db->execute($sql);
        }

        public function addProfilePic(){
            $target_file = "/uploads/profile_pics/".basename($_FILES["pic"]["name"]);
            $target_dir =  $_SERVER['DOCUMENT_ROOT'].$target_file;
            move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir);
            $userID = $_SESSION["Current_User"];
            $sql = "UPDATE users SET PicturePath = '$target_file' where UserID = $userID;";
            $this->db->execute($sql);
        }

    }