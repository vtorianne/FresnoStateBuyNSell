<?php
    //class for establishing the database connection and performing query and update operations
    class DB{
        private $db;
        private $host;
        private $dbname;
        private $username;
        private $password;

        public function __construct(){
            $this->host = "localhost";
            $this->dbname = "FresnoStateBuyNSell"; //will replace this w/ actual name of the database
            $this->username = "root";
            $password = "";
            if($password == "")
                $this->db = new PDO("mysql:host=$this->host;dbname=$this->dbname",$this->username);
            else
                $this->db = new PDO("mysql:host=$this->host;dbname=$this->dbname",$this->username,$this->password);
        }

        public function query($sql){
            $return = $this->db->query($sql);
            if(!$return)
                var_dump($this->db->errorInfo());
            else
                return $return;
        }

        public function execute($sql){
            $return = $this->db->query($sql);
            if(!$return)
                var_dump($this->db->errorInfo());
            else
                return $return;
        }




    }


?>
