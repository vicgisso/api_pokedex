<?php
class Pokemon{
 
    // database connection and table name
    private $conn;
    private $table_name = "pokemon";
 
    // object properties
    public $id;
    public $name;
    public $description;
    public $type1_id;
    public $type2_id;
    public $evolution_id;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // read pokemon
    function read(){
 
        // select all query
        $query = "SELECT
                type1.name as type1_name, type2.name as type2_name, evolution.name as evolution_name, p.id, p.name, p.description, p.type1_id, p.type2_id, p.evolution_id
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN type type1
		        ON p.type1_id = type1.id
                    LEFT JOIN type type2
                        ON p.type2_id = type2.id
                    LEFT JOIN pokemon evolution
                        ON p.evolution_id = evolution.id
                ORDER BY p.id ASC";
 
        // prepare query statement
        $stmt = $this->conn->prepare($query);
 
        // execute query
        $stmt->execute();
 
        return $stmt;
    }

    // used when filling up the update pokemon form
    function readOne(){
    
       // query to read single record
       $query = "SELECT
                   type1.name as type1_name, type2.name as type2_name, evolution.name as evolution_name, p.id, p.name, p.description, p.type1_id, p.type2_id, p.evolution_id
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN type type1
                        ON p.type1_id = type1.id
                    LEFT JOIN type type2
                        ON p.type2_id = type2.id
                    LEFT JOIN pokemon evolution
                        ON p.evolution_id = evolution.id
                WHERE p.id = ?
                LIMIT 0,1";
    
       // prepare query statement
       $stmt = $this->conn->prepare( $query );
    
       // bind id of pokemon to be updated
       $stmt->bindParam(1, $this->id);
    
       // execute query
       $stmt->execute();
    
       // get retrieved row
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
       // set values to object properties
       $this->name = $row['name'];
       $this->description = $row['description'];
       $this->type1_id = $row['type1_id'];
       $this->type1_name = $row['type1_name'];
       $this->type2_id = $row['type2_id'];
       $this->type2_name = $row['type2_name'];
       $this->evolution_id = $row['evolution_id'];
       $this->evolution_name = $row['evolution_name'];
    }

    // search pokemon
    function search($keywords){
    
        // select all query
        $query = "SELECT
                    type1.name as type1_name, type2.name as type2_name, evolution.name as evolution_name, p.id, p.name, p.description, p.type1_id, p.type2_id, p.evolution_id
                FROM 
                    " . $this->table_name . " p
                    LEFT JOIN type type1
                        ON p.type1_id = type1.id
                    LEFT JOIN type type2
                        ON p.type2_id = type2.id
                    LEFT JOIN pokemon evolution
                        ON p.evolution_id = evolution.id
                WHERE
                    p.name LIKE ? OR p.description LIKE ? OR evolution.name LIKE ?
                ORDER BY p.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // read pokemon with pagination
    public function readPaging($from_record_num, $records_per_page){
    
        // select query
        $query = "SELECT
                    type1.name as type1_name, type2.name as type2_name, evolution.name as evolution_name, p.id, p.name, p.description, p.type1_id, p.type2_id, p.evolution_id
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN type type1
                        ON p.type1_id = type1.id
                    LEFT JOIN type type2
                        ON p.type2_id = type2.id
                    LEFT JOIN pokemon evolution
                        ON p.evolution_id = evolution.id
                ORDER BY p.id ASC
                LIMIT ?,?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        // return values from database
        return $stmt;
    }

    // used for paging pokemon
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        return $row['total_rows'];
    }

    // create pokemon
    function create(){
    
        // query to insert record
          $query = "INSERT INTO
                    " . $this->table_name . "
                    SET
                        name=:name, description=:description, type1_id=:type1_id, type2_id=:type2_id, evolution_id=:evolution_id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
           
        // if(!is_null($data->evolution_from))
   
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam("type1_id", $this->type1_id);
        $stmt->bindParam("type2_id", $this->type2_id);
        $stmt->bindParam("evolution_id", $this->evolution_id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    // update the pokemon
    function update(){
    
        // update query
        $query = "UPDATE
                  " . $this->table_name . "
                SET
                    name=:name, description=:description, type1_id=:type1_id, type2_id=:type2_id, evolution_id=:evolution_id
                WHERE
                    id=:id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        /*$this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->type1_id=htmlspecialchars(strip_tags($this->type1_id));
        $this->type2_id=htmlspecialchars(strip_tags($this->type2_id));
        $this->evolution_id=htmlspecialchars(strip_tags($this->evolution_id));
        $this->id=htmlspecialchars(strip_tags($this->id));*/
    
        // bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':type1_id', $this->type1_id);
        $stmt->bindParam(':type2_id', $this->type2_id);
        $stmt->bindParam(':evolution_id', $this->evolution_id);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

   // delete the pokemon
   function delete(){

       // delete query
       $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
       // prepare query
       $stmt = $this->conn->prepare($query);
    
       // sanitize
       $this->id=htmlspecialchars(strip_tags($this->id));
    
       // bind id of record to delete
       $stmt->bindParam(1, $this->id);
    
       // execute query
       if($stmt->execute()){
           return true;
       }
    
       return false;
   }

}
?>
