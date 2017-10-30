<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/pokemon.php';
 
// instantiate database and pokemon object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$pokemon = new Pokemon($db);
 
// query pokemon
$stmt = $pokemon->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // pokemon array
    $pokemon_arr=array();
    $pokemon_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $pokemon_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "type1_id" => $type1_id,
            "type1_name" => $type1_name,
            "type2_id" => $type2_id,
            "type2_name" => $type2_name,
            "evolution_id" => $evolution_id,
            "evolution_name" => $evolution_name
        );
 
        array_push($pokemon_arr["records"], $pokemon_item);
    }
 
    echo json_encode($pokemon_arr);
}
 
else{
    echo json_encode(
        array("message" => "No pokemon found.")
    );
}
?>
