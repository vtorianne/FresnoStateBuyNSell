<?php
    require_once "../php/classes/PostRetrieval.php";

    class PostRetrievalTest extends PHPUnit_Framework_TestCase{
        private $postRetrieval;

        function setUp(){
            @session_start();
            $this->postRetrieval = new PostRetrieval();
        }

        function testGetPostCategories(){
            $this->postRetrieval->getPostCategories();
        }

        function testGetPostConditions(){
            $this->postRetrieval->getPostConditions();
        }

        function testGetFilteredQuery(){
            $_POST["Min"] = 1;
            $_POST["Max"] = 50;
            $_POST["New"] = true;
            $_POST["Used"] = true;
            $_POST["category"] = 1;
            $_POST["keywords"] = "test search";
            $_POST["Filter"] = "Price low to high";
            $this->postRetrieval->getFilteredQuery();
        }

        function testGetPosts(){
            $this->postRetrieval->getPosts();
            $_POST["searchSubmit"] = true;
            $_POST["Filter"] = "Price low to high";
            $this->postRetrieval->getPosts();
        }

        function testGetCurrUserPosts(){
            $_SESSION["Current_User"] = 8;
            $this->postRetrieval->getCurrUserPosts();
        }

        function testGetPostDetails(){
            $_GET["post-id"] = 12;
            $this->postRetrieval->getPostDetails();
        }

        public function testGetComments(){
            $postID = 12;
            $this->postRetrieval->getComments($postID);
        }

        function testGetCurrUserPostDetails(){
            $_SESSION["Current_User"] = 8;
            $_GET["post-id"] = 12;
            $this->postRetrieval->getCurrUserPostDetails();
            $_GET["post-id"] = 13;
            $this->postRetrieval->getCurrUserPostDetails();
        }

    }
