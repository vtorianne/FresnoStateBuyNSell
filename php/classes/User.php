<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    require_once "DB.php";
    class User{
        var $userID;
        var $firstName;
        var $lastName;
        var $email;
        private $password;
        
        public function register($regData){
            $this->firstName = $regData['firstName'];
            $this->lastName = $regData['lastName'];
            $this->email = $regData['email'];
            $this->password = $regData['password'];
            
            //check if user record already exists
            $db = new DB();
            //generate sql for query
            ////$sql = "$test"."hi".$var; //sample example of string in php
            $sql = "SELECT Email FROM users WHERE Email = $this->email";  //count query for number of records with matching email
            $return = $db->query($sql);
            if(true/*record already exists*/){ //will specify this when the query is done
                return false;
            }
            else{
                //generate sql for insert statement
                $sql = "INSERT INTO users(Email, Password, FirstName, LastName) VALUES($this->email, $this->password, $this->firstname, $this->lastname)";  //create user record in database with insert sql statement
                $db->execute($sql);
                //$this->login();
                return true;
            }
        }
        
        public function login($loginData){
            //if($loginData){ //not coming from registration
            $this->email = $loginData['email'];
            $this->password = $loginData['password'];
            //}
            $db = new DB();
            $sql = "SELECT Email, Password FROM users WHERE  $this->email = Email AND $this->password = Password";  //query User record where email and password match those given
            //if using password encryption, make sure given password is encrypted before comparison
            $return = $db->query($sql);
            if(/*existing not record found*/){
                //wrong username and or password
            }
            else{ ///
                session_start();
                $_SESSION["Current_User"] = $this;
                $_SESSION["Logged_In"] = true;  //keep this?
            }
            
        }
    
    }
    
 ?>
 