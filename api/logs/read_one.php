<?php 
    //Headers 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json'); 

    include_once("../../config/Database.php");
    include_once("../../models/Logs.php"); 

    //Instantiate DB & connect 
    $database = new Database(); 
    $db = $database->connect(); 

    //Instantiate Logs object 
    $log = new Logs($db); 

    //Get ID 
    $log->id = isset($_GET["id"]) ? $_GET["id"] : die(
        //json_encode(array("success" => false,"message" => "Id required"))
    );

    //log query 
    $result = $log->get_one_byid();

    //Get row count 
    $count = $result->rowCount(); 

    //check if any log 
    if($count > 0){
        //Log array 
        $logs_arr = array("success" => true, "count" => $count);
        $logs_arr['data'] = array(); 
        
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row); 

            $log_item = array(
                "id" => $id, 
                "service_address" => $service_address, 
                "response" => $response, 
                "logged_date" => $logged_date, 
                "status" => $status
             );

             //push to "data" 
             array_push($logs_arr["data"], $log_item);
        }

        //Turn to JSON & output 
        echo json_encode($logs_arr);
    }else{
        echo json_encode(
            array("success" => false, "message" => "No log found")
        );
    }

?>