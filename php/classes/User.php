<?php
    require_once "DB.php";
    class User{
        var $userID;
        var $firstName;
        var $lastName;
        var $email;
        private $password;
        
        public function register($regData){
            //process data here
            $db = new DB();
            //check if user record already exists
            //generate sql for query
            $return = $db->query($sql);
            if(/*record already exists*/){
                return false;
            }
            else{
                //generate sql for insert statement
                $db->execute($sql);
                return true;
            }
        }
    
    }
    
 ?>