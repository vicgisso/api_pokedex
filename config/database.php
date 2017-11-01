<?php
class Database{
 
    // specify your own database credentials
    private $host = "ec2-18-195-20-255.eu-central-1.compute.amazonaws.com";//"localhost";
    private $db_name = "api_db";
    private $username = "root";
    private $password = "2017definitiu";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>
