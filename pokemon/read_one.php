<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/pokemon.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pokemon object
$pokemon = new Pokemon($db);
 
// set ID property of pokemon to be edited
$pokemon->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of pokemon to be edited
$pokemon->readOne();
 
// create array
$pokemon_arr = array(
    "id" =>  $pokemon->id,
    "name" => $pokemon->name,
    "description" => $pokemon->description,
    "type1_id" => $pokemon->type1_id,
    "type1_name" => $pokemon->type1_name,
    "type2_id" => $pokemon->type2_id,
    "type2_name" => $pokemon->type2_name,
    "evolution_id" => $pokemon->evolution_id,
    "evolution_name" => $pokemon->evolution_name
);
 
// make it json format
print_r(json_encode($pokemon_arr));
?>