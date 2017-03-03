<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    require_once "DB.php";
    class User{

        public function register(){
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            //check if user record already exists
            $db = new DB();
            $sql = "SELECT Email FROM users WHERE Email = '$email';";  //count query for number of records with matching email
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return){  //record with email already exists in database
                return false;
            }
            else{
                //generate sql for insert statement
                $sql = "INSERT INTO users (Email, Password, FirstName, LastName) VALUES ('$email', MD5('$password'), '$firstName', '$lastName');";  //create user record in database with insert sql statement
                $db->execute($sql);
                $_POST['email'] = $email;
                $_POST['password'] = $password;
                $this->login();
                return true;  //add to logic so that this won't return if there is an error in db execution
            }
        }
        
        public function login(){
            session_start();
            $email = $_POST['email'];
            $password = $_POST['password'];
            $db = new DB();
            $sql = "SELECT UserID, EmailValidated FROM users WHERE Email = '$email' AND Password = MD5('$password');";  //query User record where email and password match those given
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if(!$return){
                //wrong username and or password
                return false;
            }
            else{
                $_SESSION["Current_User"] = $return["UserID"];
                $_SESSION["Logged_In"] = true;
                $_SESSION["Email_Validated"] = $return["EmailValidated"];
                return true;
            }
            
        }
        
        public function logout(){
            session_unset();
        }

        public function sendEmail($recipient, $emailBody){
            
        }

        public function sendValidationEmail(){
            $db = new DB();
            if(isset($_SESSION["user-id"])){
                $userID = $_SESSION["user-id"];
            }
            elseif(isset($_GET["user-id"])){
                $userID = $_GET["user-id"];
            }
            else{
                return false;
            }
            $sql = "SELECT Email, EmailValidated FROM users WHERE UserID = $userID;";
            $return = $db->query($sql);
            if(!$return || $return["EmailValidated"]){ //if no matching user or user email already validated
                return false;
            }
            else{
                $recipientEmail = $return["Email"];
                //create hash token
                //store in database
                //$emailBody = getEmailBody(userID, hashtoken)
                //sendEmail(recipientEmail, EmailBody);
                return true; //change this later to if email was able to be sent
            }
        }

        public function validateEmail(){
            $db = new DB();
            //get user ID and hash token from GET
            //search for match in the db -> $sql
            $sql = "";
            $return = $db->query($sql);
            if(!$return){
                //no match found (either userID dne or hash token is wrong
                return false;
            }
            else{
                //update emailValidated bit in db
                $_SESSION["Email_Validated"] = true;
                return true;
            }
        }
        
        public function getUserProfile(){
            $db = new DB();
            $userID = (isset($_GET["user-id"]) ? $_GET["user-id"] : ($_SESSION["Current_User"]));
            $sql = "SELECT * FROM users WHERE '$userID' = UserID"; //getting user data from userID
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $sql = "SELECT * FROM reviews WHERE ProfileID = $userID;";
            $reviewReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $reviewedYet = ($reviewReturn ? true : false);
            $averageRating = ($reviewedYet ? $this->getAverageRating($userID) : "No Reviews Yet");
            if($reviewedYet) {
                $numWholeStars = floor($averageRating);
                $halfStar = $averageRating - $numWholeStars;
                $reviews = $this->getReviews($userID);
            }
            $userImg = ($return["PicturePath"] != null ? $return["PicturePath"] : "/FresnoStateBuyNSell/img/default_user.png");

            require_once "../html/header_style2.html"; //header
            require_once "views/userprofile.php";
            require_once "../html/footer.html"; //footer
        }
        
        public function review(){
            $db = new DB();
            $profileID = $_GET["user-id"];
            $commenterID = $_SESSION["Current_User"];
            $starRating = $_POST["rating"];
            $reviewText = $_POST["comment"];
            $sql = "INSERT INTO reviews (CommenterID, ProfileID, StarRating, ReviewText) VALUES ($commenterID, $profileID, $starRating, '$reviewText');"; //inserting review record
            $db->execute($sql);
        }
        
        public function getReviews($userID){
            $db = new DB();
            //updated query to for reviews to be sorted by time?
            $sql = "SELECT * FROM reviews WHERE $userID = ProfileID"; //get all reviews for specified userID
            $return = $db->query($sql);
            $reviews = array();
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                $commenterID = $row["CommenterID"];
                $sql = "SELECT FirstName, LastName FROM users WHERE $commenterID = UserID"; //get name of reviewer
                $userReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
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
            $db = new DB();
            $sql = "SELECT ROUND(AVG(StarRating),2) AS StarRatingAverage FROM reviews WHERE $userID = ProfileID";  //get average review (star rating)
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            return $return["StarRatingAverage"];
        }

        public function addProfilePic(){
            $db = new DB();
            $target_file = "/uploads/profile_pics/".basename($_FILES["pic"]["name"]);
            $target_dir =  $_SERVER['DOCUMENT_ROOT'].$target_file;
            move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir);
            $userID = $_SESSION["Current_User"];
            $sql = "UPDATE users SET PicturePath = '$target_file' where UserID = $userID;";
            echo $sql;
            $db->execute($sql);
        }
    
    }
    
 ?>
 
