<?php
class Type{
 
    // database connection and table name
    private $conn;
    private $table_name = "type";
 
    // object properties
    public $id;
    public $name;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // used by select drop-down list
    public function read(){
        //select all data
        $query = "SELECT
                    id, name
                FROM
                    " . $this->table_name . "
                ORDER BY
                    id";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }

    

}
?>