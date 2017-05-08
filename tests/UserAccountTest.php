<?php
    require_once "../php/classes/UserAccount.php";
    require_once "../../PHPMailer-master/PHPMailerAutoload.php";
    require_once "../../EmailPassword.php";
    require_once "../php/views/email.php";
    require_once "../php/views/passresetemail.php";
    require_once "../php/views/acclocked.php";

    class UserAccountTest extends PHPUnit_Framework_TestCase{
        function setUp(){
            @session_start();
        }
        function test(){
            $userAccount = new UserAccount();
            $userAccount->logout();
            $userAccount->sendEmail("vtorianne@mail.fresnostate.edu", "test", "hello world");
        }

    }
