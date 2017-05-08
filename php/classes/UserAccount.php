<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    require_once "DB.php";

    class UserAccount{
        private $db;

        public function __construct(){
            $this->db = new DB();
        }

        public function register(){
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            //check if user record already exists
            $sql = "SELECT Email FROM users WHERE Email = '$email';";  //count query for number of records with matching email
            $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return){  //record with email already exists in database
                return false;
            }
            else{
                //generate sql for insert statement
                $sql = "INSERT INTO users (Email, Password, FirstName, LastName) VALUES ('$email', MD5('$password'), '$firstName', '$lastName');";  //create user record in database with insert sql statement
                $this->db->execute($sql);
                $_POST['email'] = $email;
                $_POST['password'] = $password;
                $this->login();
                $this->sendValidationEmail();
                return true;  //add to logic so that this won't return if there is an error in db execution
            }

        }

        public function login(){
            $email = $_POST['email'];
            $password = $_POST['password'];
            $sql = "SELECT UserID, Locked FROM users WHERE Email = '$email';";  //query User record where email match given
            $returnLock = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if(!$returnLock){
                /*account does not exist.*/
                return "wrong_email_or_password";
            }
            else if ($returnLock['Locked'] == 1){
                //return account locked (prior to login attempt)
                return "account_locked";
            }
            else {
                $sql = "SELECT UserID, EmailValidated FROM users WHERE Email = '$email' AND Password = MD5('$password');";  //query User record where email and password match those given
                $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
                if (!$return){
                    $curtime = time(); // Gets current time
                    $sqlinsert = "UPDATE users SET LastFailedLogin = '$curtime', NumFailedLogins = NumFailedLogins +1 WHERE Email = '$email';";  //insert new time stamp into failed LastFailedLogin
                    $this->db->execute($sqlinsert);
                    $timest = "SELECT LastFailedLogin, NumFailedLogins FROM users WHERE Email = '$email';"; //last failed login and num failed logins sql
                    $returnedquery = $this->db->query($timest)->fetch(PDO::FETCH_ASSOC); //last failed login and num failed logins in associative array
                    $singletimestamp = $returnedquery['LastFailedLogin']; //grab lastfailedlogin from array
                    $numfailedlogins = $returnedquery['NumFailedLogins']; //grab numfailedlogins from array
                    //curtime - singletimestamp = seconds since login failure. This requires a 5 minute waiting period.

                    if (($curtime-$singletimestamp) < 300 && $numfailedlogins > 2){
                        $this->lockaccount($email);
                        $this->sendAccUnlockEmail($email);
                        return "account_locked";
                    }
                    //wrong email/password
                    return "wrong_email_or_password";
                }
                else {
                    $sqlinsert = "UPDATE users SET NumFailedLogins = 0 WHERE Email = '$email';";  //insert new failed login count
                    $this->db->execute($sqlinsert);
                    //login successful
                    $_SESSION["Current_User"] = $return["UserID"];
                    $_SESSION["Logged_In"] = true;
                    $_SESSION["Email_Validated"] = $return["EmailValidated"];
                    return "success";
                }
            }
        }

        public function logout(){
            session_destroy();
        }

        public function sendEmail($recipient, $emailbody, $emailsubject){
            $mail             = new PHPMailer();
            $body = $emailbody;
            //$body             = eregi_replace("[\]",'',$emailbody); //replace use of deprecated function
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host       = "smtp.gmail.com"; // SMTP server
            $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
            $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
            $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
            $mail->Username   = "fresnostatebuynsell@gmail.com";  // GMAIL username
            $mail->Password   = GetEmailPassword();            // GMAIL password
            $mail->SetFrom('fresnostatebuynsell@gmail.com', 'Fresno State Buy N Sell');
            $mail->Subject    = $emailsubject;
            $mail->MsgHTML($body);
            $mail->AddAddress($recipient);
            $mail->Send();
        }

        public function sendValidationEmail(){
            if(isset($_SESSION["Current_User"])){
                $UserID = $_SESSION["Current_User"];
            }
            elseif(isset($_GET["user-id"])){
                $UserID = $_GET["user-id"];
            }
            else{
                return false;
            }
            $sql = "SELECT Email, EmailValidated, FirstName, LastName FROM users WHERE UserID = $UserID;";
            $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if(!$return || $return["EmailValidated"]){ //user email does not exist or is already validated
                return false;
            }
            else{
                $recipientEmail = $return["Email"];
                //create hash token
                $HashToken=  md5( rand(0,1000) );
                //store in database
                $sql = "UPDATE users SET HashToken='$HashToken' WHERE UserID = $UserID;";
                $this->db->execute($sql);
                $emailBody = getValidationEmailBody($UserID, $HashToken, $return['FirstName'], $return['LastName']);
                $this->sendEmail($recipientEmail, $emailBody, "Validate Email");
                return true; //change this later to if email was able to be sent
            }
        }

        public function sendPasswordResetEmail(){
            $email = $_POST["email"];
            $sql = "SELECT * FROM users WHERE Email = '$email';";
            $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if(!$return){ //email does not exist
                return false;
            }
            else{
                $UserID = $return["UserID"];
                $HashToken=  md5( rand(0,1000) );
                //store in database
                $sql = "UPDATE users SET HashToken='$HashToken' WHERE UserID = $UserID;";
                $this->db->execute($sql);
                $emailBody = getPassResetEmailBody($UserID, $HashToken, $return['FirstName'], $return['LastName']);
                $this->sendEmail($email, $emailBody, "Reset Password");
                return true;
            }
        }
        
        public function sendAccUnlockEmail($email){
            $sql = "SELECT * FROM users WHERE Email = '$email';";
            $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $UserID = $return["UserID"];
            $HashToken=  md5( rand(0,1000) );
            //store in database
            $sql = "UPDATE users SET HashToken='$HashToken' WHERE UserID = $UserID;";
            $this->db->execute($sql);
            $emailBody = getAcccountUnlockEmailBody($UserID, $HashToken, $return['FirstName'], $return['LastName']);
            $this->sendEmail($email, $emailBody, "Unlock Account");
        }

        public function checkHashToken(){
            //get user ID and hash token from GET
            $userID = $_GET["user-id"];
            $hashToken = $_GET["hash-token"];
            //search for match in the db -> $sql
            $sql = "SELECT * FROM users WHERE UserID = $userID and HashToken = '$hashToken';";
            $return = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            return $return ? true : false;
        }

        public function validateEmail(){
            $userID = $_GET["user-id"];
            if(!$this->checkHashToken()){
                //no match found, either userID dne or hash token is wrong
                return false;
            }
            else{
                //update emailValidated bit in db
                $sql = "UPDATE users SET EmailValidated=1 WHERE UserID = $userID;";
                $this->db->execute($sql);
                $_SESSION["Email_Validated"] = true;
                return true;
            }
        }

        public function resetPassword(){
            $userID = $_GET["user-id"];
            $password = $_POST["password"];
            $sql = "UPDATE users SET Password=MD5('$password') WHERE UserID = $userID;";
            $this->db->execute($sql);
        }

        public function lockaccount($email){
            $sqlinsert = "UPDATE users SET Locked = 1 WHERE Email = '$email';";  //insert new time stamp into failed LastFailedLogin
            $this->db->execute($sqlinsert);
        }

        public function unlockaccount($userID){
            $sqlinsert = "UPDATE users SET Locked = 0, LastFailedLogin = 0, NumFailedLogins = 0 WHERE UserID = $userID;";  //Account unlocked
            $this->db->execute($sqlinsert);
        }

    }
