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
                $sql = "INSERT INTO users (Email, Password, FirstName, LastName) VALUES ('$this->email', DB5(’$this->password’), '$this->firstName', '$this->lastName');";  //create user record in database with insert sql statement
                $db->execute($sql);
                return true;  //add to logic so that this won't return if there is an error in db execution
            }
        }
        
        public function login($loginData){
            session_start();
            /*if($loginData){ //not coming from registration
            $this->email = $loginData['email'];
            $this->password = $loginData['password'];
            }*/
            $db = new DB();
            $sql = "SELECT * FROM users WHERE Email = '$this->email' AND Password = DB5(’$this->password’);”;  //query User record where email and password match those given
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
    
    }
    
 ?>
 
