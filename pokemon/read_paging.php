<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/pokemon.php';
 
// utilities
$utilities = new Utilities();
 
// instantiate database and pokemon object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$pokemon = new pokemon($db);
 
// query pokemon
$stmt = $pokemon->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // pokemon array
    $pokemon_arr=array();
    $pokemon_arr["records"]=array();
    $pokemon_arr["paging"]=array();
 
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
            "type2_id" => $type2_id,
            "evolution_id" => $evolution_id
        );
 
        array_push($pokemon_arr["records"], $pokemon_item);
    }
 
 
    // include paging
    $total_rows=$pokemon->count();
    $page_url="{$home_url}pokemon/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $pokemon_arr["paging"]=$paging;
 
    echo json_encode($pokemon_arr);
}
 
else{
    echo json_encode(
        array("message" => "No pokemon found.")
    );
}
?>