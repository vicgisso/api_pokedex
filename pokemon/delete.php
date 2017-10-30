<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 
// include database and object file
include_once '../config/database.php';
include_once '../objects/pokemon.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pokemon object
$pokemon = new Pokemon($db);
 
// get pokemon id
$data = json_decode(file_get_contents("php://input"));
 
// set pokemon id to be deleted
$pokemon->id = $data->id;
 
// delete the pokemon
if($pokemon->delete()){
    echo '{';
        echo '"message": "Pokemon was deleted."';
    echo '}';
}
 
// if unable to delete the pokemon
else{
    echo '{';
        echo '"message": "Unable to delete object."';
    echo '}';
}
?>