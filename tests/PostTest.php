<?php
require_once "../php/classes/Post.php";

class PostTest extends PHPUnit_Framework_TestCase{
    public function test_getPostConditions(){
        $post = new Post();
        $post->getPostConditions();
    }

    public function test2(){
        $post = new Post();
        $_POST["Max"] = 10;
        $_POST["Filter"] = "";
        $post->getFilteredQuery();
    }

}
