<?php
    require_once "../php/classes/PostRetrieval.php";

    class PostRetrievalTest extends PHPUnit_Framework_TestCase{
        public function test_getPostConditions(){
            $postRetrieval = new PostRetrieval();
            $postRetrieval->getPostConditions();
        }

        public function test2(){
            $postRetrieval = new PostRetrieval();
            $_POST["Max"] = 10;
            $_POST["Filter"] = "";
            $postRetrieval->getFilteredQuery();
        }

    }
