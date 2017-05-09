<?php
    require_once "../php/classes/UserAccount.php";
    require_once "../php/classes/DB.php";
    require_once "../../PHPMailer-master/PHPMailerAutoload.php";
    require_once "../../EmailPassword.php";
    require_once "../php/views/email.php";
    require_once "../php/views/passresetemail.php";
    require_once "../php/views/acclocked.php";

    class UserAccountTest extends PHPUnit_Framework_TestCase{
        private $userAccount;

        function setUp(){
            @session_start();
            $this->userAccount = new UserAccount();
        }

        function testRegister(){
            $_POST["firstName"] = "John";
            $_POST["lastName"] = "Doe";
            $_POST["email"] = "test@mail.fresnostate.edu";
            $_POST["password"] = "abcdefghijk";
            $this->userAccount->register();
            $_POST["email"] = "unit-Testing@mail.fresnostate.edu";
            $this->userAccount->register();
            //also tests correct login, as the register function calls the login function
            //also calls the sendValidationEmail method with a logged in user and indirectly the sendMail function
        }

        function testLogin(){
            $_POST["email"] = "nonexisting_email";
            $_POST["password"] = "123";
            $this->userAccount->login();
            $_POST["email"] = "test@mail.fresnostate.edu";
            $_POST["password"] = "wrong password";
            for($i = 0; $i<=3; $i++){ //repeat until locked out
                $this->userAccount->login();
            }
            //also calls and thus tests sendEmail, lockAccount, and sendAccUnlockEmail methods directly and indirectly
        }

        function testSendValidationEmail(){
            if(isset($_SESSION["Current_User"]))
                unset($_SESSION["Current_User"]);
            $_GET["user-id"] = 8;
            $this->userAccount->sendValidationEmail();
            unset($_GET["user-id"]);
            $this->userAccount->sendValidationEmail();
        }

        function testSendPasswordResetEmail(){
            $_POST["email"] = "nonexisting email";
            $this->userAccount->sendPasswordResetEmail();
            $_POST["email"] = "test@mail.fresnostate.edu";
            $this->userAccount->sendPasswordResetEmail();
        }

        function testValidateEmail(){
            $_GET["user-id"] = 8;
            $_GET["hash-token"] = "incorrect_token";
            $this->userAccount->validateEmail();
            $db = new DB();
            $sql = "SELECT HashToken FROM users WHERE UserID = ".$_GET["user-id"].";";
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $_GET["hash-token"] = $return["HashToken"]; //set to correct hash token
            $this->userAccount->validateEmail();
        }

        function testResetPassword(){
            $_GET["user-id"] = 8;
            $_POST["password"] = "abcdefghijk";
            $this->userAccount->resetPassword();
        }

        function testUnlockAccount(){
            $userID = 8;
            $this->userAccount->unlockaccount($userID);
        }

        function testLogout(){
            $this->userAccount->logout();
        }

    }
