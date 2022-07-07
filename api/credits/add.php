<?php 
    //Headers 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json'); 
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With'); 

    session_start();
    include_once("../../php_functions/functions.php");
    include_once("../../configs/conn.inc");

    //Get raw added data 
    $data = json_decode(file_get_contents("php://input"));



    $company_name = trim($data->company_name);
    $service_title = trim($data->service_title);
    $service_address = trim($data->service_address);
    $next_run_datetime = trim($data->next_run);
    $unit = trim($data->unit);
    $frequency = trim($data->frequency);

    ///////----------Validation
    if($company_name < 1){
        die(json_encode(array("success" => false, "message" => "Please select company name")));
    } 

    
    if((input_available($service_title)) == 0)
    {
        die(json_encode(array("success" => false, "message" => "Service title is required")));
    }
    

    if((input_available($service_address)) == 0)
    {
        die(json_encode(array("success" => false, "message" => "Service address is required")));
    }else{
        $address_exists = checkrowexists('tbl_services',"service_address='$service_address' AND unit=$unit AND frequency=$frequency AND company_name = '$company_name'");
        if($address_exists == 1){
            die(json_encode(array("success" => false, "message" => "Oops! Duplicate service address")));
        }
    }

    $thisfulldate_=date('Y-m-d H:i');
    if((input_available($next_run_datetime)) == 0)
    {
        die(json_encode(array("success" => false, "message" => "The next run date and time is required"))); 
    }else if($next_run_datetime <= $thisfulldate_)
    {
        die(json_encode(array("success" => false, "message" => "The next run must be greater than ".$thisfulldate_))); 
    }
    else{
        if((input_length($next_run_datetime, 16)) == 0){
            die(json_encode(array("success" => false, "message" => "Please enter a valid entry for next run date and time"))); 
        }
    }

    if($unit < 1){
        die(json_encode(array("success" => false, "message" => "Please select unit")));
    }

    if($frequency < 1){
        die(json_encode(array("success" => false, "message" => "Please select frequency")));
    }
    //////-----------End of validation


    $fds = array('company_name', 'service_title', 'service_address','next_run_datetime','unit','frequency');
    $vals = array($company_name,"$service_title", "$service_address","$next_run_datetime", $unit, $frequency);
    $create = addtodb('tbl_services',$fds,$vals);

    if($create == 1)
    {
        echo json_encode(array("success" => true, "message" => "Record Added Successfully")); 

    }
    else
    {
        echo json_encode(array("success" => false, "message" => "Record was not added"));
    }

?>
