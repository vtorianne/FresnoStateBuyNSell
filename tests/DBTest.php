<?php
    require_once "../php/classes/DB.php";

    class DBTest extends PHPUnit_Framework_TestCase{
        private $db;

        function setUp(){
            $this->db = new DB();
        }

        function testQuery(){
            $sql = "SELECT * FROM users;";
            $this->db->query($sql);
            $sql = "invalid SQL syntax;";
            $this->db->query($sql);
        }

        function testExecute(){
            $sql = "DELETE FROM users WHERE UserID = -1;";
            $this->db->execute($sql);
            $sql = "invalid SQL syntax;";
            $this->db->execute($sql);
        }
    }
