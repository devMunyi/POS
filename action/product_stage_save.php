<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST, GET, PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");

$stage_id = $_POST['stage_id'];
$product_id = $_POST['product_id'];
$action = $_POST['action'];

$proceed = 0;

if($action == 'ADD'){
    $exists = checkrowexists("o_product_stages","product_id='$product_id' AND stage_id='$stage_id'");
    if($exists == 1){
        ////----Update
        $update = updatedb('o_product_stages', "status=1", "product_id='$product_id' AND stage_id='$stage_id'");
        if($update == 1)
        {
            $feedback = sucmes('Success Adding Stage');
            $proceed = 1;

        }
        else
        {
            $feedback = errormes('Error Adding Stage');
        }
    }
    else{
        ////----Add
        $fds = array('stage_id','product_id','date_added','status');
        $vals = array("$stage_id","$product_id","$fulldate","1");
        $create = addtodb('o_product_stages',$fds,$vals);
        if($create == 1)
        {
            $feedback = sucmes('Success Adding Stage');
            $proceed = 1;

        }
        else
        {
            $feedback = errormes('Error Adding Stage');
        }
    }

}
elseif($action == 'REMOVE'){

    $update = updatedb('o_product_stages', "status=0", "product_id='$product_id' AND stage_id='$stage_id'");
    if($update == 1)
    {
        $feedback =  sucmes('Success Removing Stage');
        $proceed = 1;

    }
    else
    {
        $feedback = errormes('Error Removing Stage');
    }
}
$final_state = fetchrow("o_product_stages","product_id='$product_id' AND stage_id='$stage_id'","status");

echo   json_encode("{\"result_\":$proceed,\"final_\":$final_state}");
?>
