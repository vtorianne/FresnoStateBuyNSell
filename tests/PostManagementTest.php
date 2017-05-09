<?php
    require_once "../php/classes/PostManagement.php";

    class PostManagementTest extends PHPUnit_Framework_TestCase{
        private $postManagement;

        function setUp(){
            @session_start();
            $this->postManagement = new PostManagement();
        }

        public function testEditPost(){
            $_GET["post-id"] = 13;
            $_POST["title"] = "Computer";
            $_POST["category"] = 3;
            $_POST["condition"] = 1;
            $_POST["price"] = 99.99;
            $this->postManagement->editPost();
            $_POST["desc"] = "It is a computer";
            $this->postManagement->editPost();
        }

        function testMarkIfSold(){
            $_SESSION["Current_User"] = 8;
            $_GET["post-id"] = 12;
            $_GET["sold"] = 1;
            $this->postManagement->markIfSold();
            $_GET["post-id"] = 13;
            $this->postManagement->markIfSold();
        }

        function testDeletePost(){
            $_SESSION["Current_User"] = 8;
            $_GET["post-id"] = 12;
            $this->postManagement->deletePost();
            $_GET["post-id"] = 35;
            $this->postManagement->deletePost();
        }

        function testAddComment(){
            $_SESSION["Current_User"] = 8;
            $_GET["post-id"] = 12;
            $_POST["comment"] = "Nice!!!";
            $this->postManagement->addComment();
        }
    }
