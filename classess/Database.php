<?php
    class Database{
        private  $servername = 'localhost';
        private  $username = 'root';
        private  $password = 'root'; //only Mac
        private  $db_name = 'Company';
        protected  $conn;

        public function __construct(){
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->db_name);

            if($this->conn->connect_error){
                die('Connection Failed: '.$this->conn->connect_error);
    
            }else{

                return $this->conn;
                
            }
        }

       
    }

?>