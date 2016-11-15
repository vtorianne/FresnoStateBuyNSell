<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    require_once "DB.php";
    class User{

        public function register($regData){
            $firstName = $regData['firstName'];
            $lastName = $regData['lastName'];
            $email = $regData['email'];
            $password = $regData['password'];
            
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
                $loginData = array(
                    'email' => $email,
                    'password' => $password
                );
                $this->login($loginData);
                return true;  //add to logic so that this won't return if there is an error in db execution
            }
        }
        
        public function login($loginData){
            session_start();
            $email = $loginData['email'];
            $password = $loginData['password'];
            $db = new DB();
            $sql = "SELECT UserID FROM users WHERE Email = '$email' AND Password = MD5('$password');";  //query User record where email and password match those given
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if(!$return){
                //wrong username and or password
                return false;
            }
            else{
                $_SESSION["Current_User"] = $return["UserID"];
                $_SESSION["Logged_In"] = true;
                return true;
            }
            
        }
        
        public function logout(){
            session_unset();
        }
        
        public function getUserProfile($userID){
            $db = new DB();
            $sql = "SELECT * FROM users WHERE '$userID' = UserID"; //getting user data from userID
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $sql = "SELECT * FROM reviews WHERE ProfileID = $userID;";
            $reviewReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $reviewedYet = ($reviewReturn ? true : false);
            $averageRating = ($reviewedYet ? $this->getAverageRating($userID) : "No Reviews Yet");
            echo <<<EOD
            <div class="container">
                <div class="row product">
                    <div class="col-md-5 col-md-offset-0"><img class="img-responsive" src="../img/suit_jacket.jpg"></div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <h5>{$return["FirstName"]}</h5>
                                <h5>{$return["LastName"]}</h5></div>
                        </div>  
                        <p>{$return["Email"]}</p>
EOD;
            if($userID == $_SESSION["Current_User"]) {
                echo <<<EOD
                        <p>Add/Update Profile Picture:</p> 
                        <form method="post" action="">
                        <input type="file" name="pic" accept="image/*">
                        <button style="margin-top: 10px;" type="submit" class="btn btn-primary btn-sm">Upload</button>
                        </form>
EOD;
            }
            echo <<<EOD
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-6">
                        <h4>Overall Rating:</h4></div>
                    <div style="margin-top: 13px;" class="col-sm-6 social-icons">
                        <div> <span style="padding-right: 10px;">Average {$averageRating}</span>  
EOD;
            if($reviewedYet){
                $roundedRating = $this->StarRatingFinder($averageRating);
                $numWholeStars = $roundedRating/1;
                $halfStar = ($roundedRating%1 != 0 ? true : false);
                for($i=0; $i<$numWholeStars; ++$i){
                    echo "<i class=\"fa fa-star\"></i>";
                }
                if($halfStar)
                    echo "<i class=\"fa fa-star-half\"></i>";
            }
            echo <<<EOD
                        </div>
                    </div>
                </div>
                <hr>
                <div class="page-header">
                <h3>Reviews</h3></div>
EOD;
            if($reviewedYet)
                $this->getReviews($userID);

            if($userID != $_SESSION["Current_User"]){
                //show review form
                echo <<<EOD
                <div>
                <hr>
                <h4>Review User:</h4>
                    <form action="index.php?option=add-review&user-id={$userID}" method="post">
                    <div>
                    <textarea name="comment" id="comment" style="font-family:sans-serif;font-size:15px; width: 100%; margin-top: 20px;">Write a Review
                    </textarea>
                      <fieldset>
                        <span class="star-cb-group">
                          <input type="radio" id="rating-5" name="rating" value="5" /><label for="rating-5">5</label>
                          <input type="radio" id="rating-4" name="rating" value="4" checked="checked" /><label for="rating-4">4</label>
                          <input type="radio" id="rating-3" name="rating" value="3" /><label for="rating-3">3</label>
                          <input type="radio" id="rating-2" name="rating" value="2" /><label for="rating-2">2</label>
                          <input type="radio" id="rating-1" name="rating" value="1" /><label for="rating-1">1</label>
                          <input type="radio" id="rating-0" name="rating" value="0" class="star-cb-clear" /><label for="rating-0">0</label>
                        </span>
                      </fieldset>
                    </div>
                    <input type="submit" value="Submit Review " class="btn btn-primary">
                    </form>
                    </div>
EOD;

            }
            echo "<hr></div></br>";
        }
        
        public function review($profileID, $reviewData){
            $db = new DB();
            $commenterID = $_SESSION["Current_User"];
            $starRating = $reviewData["rating"];
            $reviewText = $reviewData["comment"];
            $sql = "INSERT INTO reviews (CommenterID, ProfileID, StarRating, ReviewText) VALUES ($commenterID, $profileID, $starRating, '$reviewText');"; //inserting review record
            $db->execute($sql);
        }
        
        public function getReviews($userID){
            $db = new DB();
            $sql = "SELECT * FROM reviews WHERE $userID = ProfileID"; //get all reviews for specified userID
            $return = $db->query($sql);
            while($row = $row = $return->fetch(PDO::FETCH_ASSOC)){
                $commenterID = $row["CommenterID"];
                $sql = "SELECT FirstName, LastName FROM users WHERE $commenterID = UserID"; //get name of reviewer
                $userReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
                echo <<<EOD
                <div class="media">
                    <div class="media-body">
                        <div>
EOD;
                for($i=0; $i<$row["StarRating"]; ++$i){
                    echo "<i class=\"fa fa-star\"></i>";
                }
                echo <<<EOD
                        </div>
                        <p>{$row["ReviewText"]}</p>
                        <p><span class="reviewer-name"><strong>{$userReturn["FirstName"]} {$userReturn["LastName"]}</strong></span><span class="review-date">{$row["ReviewTimeStamp"]}</span></p>
                    </div>
                </div>
EOD;

            }
        }

        public function getAverageRating($userID){
            $db = new DB();
            $sql = "SELECT ROUND(AVG(StarRating),2) AS StarRatingAverage FROM reviews WHERE $userID = ProfileID";  //get average review (star rating)
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            return $return["StarRatingAverage"];
        }

        //helper function for display logic for how many stars to display
        public function StarRatingFinder($starratingaverage){
            $possibleratings = array(1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5);
            $currMin=50;
            $displayedRating=0;
            foreach($possibleratings as $element){
                if ($currMin > ($element - $starratingaverage) && ($element-$starratingaverage) > 0){
                    $currMin = $element - $starratingaverage;
                    $displayedRating=$element;
                }
            }
            return $displayedRating;
        }
    
    }
    
 ?>
 
