<?php
require_once "../php/classes/User.php";
require_once "../../PHPMailer-master/PHPMailerAutoload.php";
require_once "../../EmailPassword.php";
require_once "../php/views/email.php";
require_once "../php/views/passresetemail.php";
require_once "../php/views/acclocked.php";

class UserTest extends PHPUnit_Framework_TestCase{
    function setUp(){
        @session_start();
    }
    function test(){
        $user = new User();
        $user->logout();
        $user->sendEmail("vtorianne@mail.fresnostate.edu", "test", "hello world");
    }

}
