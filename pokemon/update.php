<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/pokemon.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pokemon object
$pokemon = new Pokemon($db);

// get id of pokemon to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of pokemon to be edited
$pokemon->id = $data->id;
 
// set pokemon property values
$pokemon->name = $data->name;
$pokemon->description = $data->description;
$pokemon->type1_id = $data->type1_id;
$pokemon->type2_id = $data->type2_id;
$pokemon->evolution_id = $data->evolution_id;

// update the pokemon
if($pokemon->update()){
    echo '{';
        echo '"message": "Pokemon was updated."';
    echo '}';
}
 
// if unable to update the pokemon, tell the user
else{
    echo '{';
        echo '"message": "Unable to update pokemon."';
    echo '}';
}
?>
