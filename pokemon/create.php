<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/pokemon.php';
 
$database = new Database();
$db = $database->getConnection();
 
$pokemon = new Pokemon($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$pokemon->name = $data->name;
$pokemon->description = $data->description;
$pokemon->type1_id = $data->type1_id;
$pokemon->type2_id = $data->type2_id;
$pokemon->evolution_id = $data->evolution_id;

// create the pokemon
if($pokemon->create()){
    echo '{';
        echo '"message": "Pokemon was created."';
    echo '}';
}
 
// if unable to create the pokemon, tell the user
else{
    echo '{';
        echo '"message": "Unable to create pokemon."';
    echo '}';
}
?>
