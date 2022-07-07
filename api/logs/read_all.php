<?php 
    //Headers 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json'); 

    session_start();
    include_once ("../php_functions/functions.php");
    include_once ("../configs/conn.inc");

    

    // //Instantiate DB & connect 
    // $database = new Database(); 
    // $db = $database->connect(); 

    // //Instantiate Logs class object 
    // $log = new Logs($db);

    // //Service query 
    // $result = $log->get_all();

    // //Get row count 
    // $count = $result->rowCount(); 

    // //check if any log 
    // if($count > 0){
    //     //log array 
    //     $logs_arr = array("success" => true, "count" => $count);
    //     $logs_arr['data'] = array(); 
        
    //     while($row = $result->fetch(PDO::FETCH_ASSOC)){
    //         extract($row);
            
    //         $log_item = array(
    //             "id" => $id, 
    //             "service_address" => $service_address, 
    //             "response" => $response, 
    //             "logged_date" => $logged_date, 
    //             "status" => $status
    //          );

    //          //push to "data" 
    //          array_push($logs_arr["data"], $log_item);
    //     }

    //     //Turn to JSON & output 
    //     echo json_encode($logs_arr);
    // }else{
    //     echo json_encode(
    //         array("success" => false, "message" => "No logs found")
    //     );
    // }

?>