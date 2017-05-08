<?php

class indexTest extends PHPUnit_Framework_TestCase{
    function setUp(){
        @session_start();
    }
    
    function test(){
        $_GET['option'] = 'send-validation-email';
        $_GET["user-id"] = 8;
        include "../php/index.php";
    }
}
