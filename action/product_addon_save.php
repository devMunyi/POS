<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST, GET, PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");

$addon_id = $_POST['addon_id'];
$product_id = $_POST['product_id'];
$action = $_POST['action'];

$proceed = 0;

if($action == 'ADD'){
    $exists = checkrowexists("o_product_addons","product_id='$product_id' AND addon_id='$addon_id'");
    if($exists == 1){
        ////----Update
        $update = updatedb('o_product_addons', "status=1", "product_id='$product_id' AND addon_id='$addon_id'");
        if($update == 1)
        {
            $feedback = sucmes('Success Adding AddOn');
            $proceed = 1;

        }
        else
        {
            $feedback = errormes('Error Adding AddOn');
        }
    }
    else{
        ////----Add
        $fds = array('addon_id','product_id','date_added','status');
        $vals = array("$addon_id","$product_id","$fulldate","1");
        $create = addtodb('o_product_addons',$fds,$vals);
        if($create == 1)
        {
            $feedback = sucmes('Success Adding AddOn');
            $proceed = 1;

        }
        else
        {
            $feedback = errormes('Error Adding AddOn');
        }
    }

}
elseif($action == 'REMOVE'){

    $update = updatedb('o_product_addons', "status=0", "product_id='$product_id' AND addon_id='$addon_id'");
    if($update == 1)
    {
        $feedback =  sucmes('Success Removing AddOn');
        $proceed = 1;

    }
    else
    {
        $feedback = errormes('Error Removing AddOn');
    }
}
$final_state = fetchrow("o_product_addons","product_id='$product_id' AND addon_id='$addon_id'","status");

echo   json_encode("{\"result_\":$proceed,\"final_\":$final_state}");
?>
