<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    require_once "DB.php";
    class User{
        var $userID;
        var $firstName;
        var $lastName;
        var $email;
        var $password;  //possibly switch to private
        
        public function register($regData){
            $this->firstName = $regData['firstName'];
            $this->lastName = $regData['lastName'];
            $this->email = $regData['email'];
            $this->password = $regData['password'];
            
            //check if user record already exists
            $db = new DB();
            $sql = "SELECT Email FROM users WHERE Email = '$this->email';";  //count query for number of records with matching email
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return){  //record with email already exists in database
                return false;
            }
            else{
                //generate sql for insert statement
                $sql = "INSERT INTO users (Email, Password, FirstName, LastName) VALUES ('$this->email', MD5('$this->password'), '$this->firstName', '$this->lastName');";  //create user record in database with insert sql statement
                $db->execute($sql);
                $loginData = array(
                    'email' => $this->email,
                    'password' => $this->password
                );
                $this->login($loginData);
                return true;  //add to logic so that this won't return if there is an error in db execution
            }
        }
        
        public function login($loginData){
            session_start();
            $this->email = $loginData['email'];
            $this->password = $loginData['password'];
            $db = new DB();
            $sql = "SELECT * FROM users WHERE Email = '$this->email' AND Password = MD5('$this->password');";  //query User record where email and password match those given
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if(!$return){
                //wrong username and or password
                return false;
            }
            else{ 
                $this->userID = $return["UserID"];
                $this->firstName = $return["FirstName"];
                $this->lastName = $return["LastName"];
                $this->email = $return["Email"];
                $this->password = $return["Password"];
                $_SESSION["Current_User"] = $this;
                $_SESSION["Logged_In"] = true;  //keep this?
                return true;
            }
            
        }
        
        public function logout(){
            session_unset();
        }
        
        public function getUserProfile($userID){
            $db  = new DB();
            $sql = ""; //getting user data from userID
            
            if($userID !=  ($_SESSION["Current_User"])->userID)
                //show review form
        }
        
        public function review($profileID, $reviewData){
            $db = new DB();
            $commenterID = ($_SESSION["Current_User"])->userID;
            $sql = ""; //inserting review record
        }
    
    }
    
 ?>
 
