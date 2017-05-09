<?php
    require_once "../php/classes/UserProfile.php";

    class UserProfileTest extends PHPUnit_Framework_TestCase{

        private $userProfile;

        function setUp(){
            @session_start();
            $this->userProfile = new UserProfile();
        }

        function testGetUserProfile(){
            $_SESSION["Current_User"] = 8;
            $this->userProfile->getUserProfile(); //get current user's profile
            $_GET["user-id"] = 7;
            $this->userProfile->getUserProfile(); //get another user's profile
            //getUserProfile calls getReviews and getAverage rating so it covers those methods as well
        }

        function testReview(){
            $_SESSION["Current_User"] = 8;
            $_GET["user-id"] = 7;
            $_POST["rating"] = 5;
            $_POST["comment"] = "Great!!!";
            $this->userProfile->review();
        }
    }
