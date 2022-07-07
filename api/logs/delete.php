<?php 
    //Headers 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json'); 
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With'); 

    include_once("../../config/Database.php");
    include_once("../../models/Logs.php"); 

    //Instantiate DB & connect 
    $database = new Database(); 
    $db = $database->connect(); 

    //Instantiate Log object 
    $log = new Logs($db); 

    //Get raw id data 
    $data = json_decode(file_get_contents("php://input"));

    //Set ID for column to delete
    if(!isset($data->log_id) || $data->log_id < 0){
        die(json_encode(array("success" => false, "message" => "ID for Log to delete is required")));
    }else{
        $log->id = $data->log_id;
        //update status column to a static value of 0 
        $log->status = 0;
    }
    
    //Delete log 
    if($log->delete()){
        echo json_encode(array("success" => true, "message" => "Log was deleted")); 
    }else{
        echo json_encode(array("success" => false, "message" => "Log not deleted"));
    }
?>