<?php
//Headers 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

session_start();
include_once("../../php_functions/functions.php");
include_once("../../db/connect_db.inc");

//Get ID 
$cust_no = isset($_GET["cust_no"]) ? $_GET["cust_no"] : die(
    //json_encode(array("success" => false,"message" => "Id required"))
);

$cs = fetchonerow("tbl_credit_limit", "cust_no = $cust_no");
$alltotal = countotal("tbl_credit_limit", "cust_no = $cust_no");

if ($alltotal == 1) {
    //Service array  
    $credit_arr = array("success" => true, "count" => $alltotal);
    extract($cs);

    $credit_item = array(
        "credit_id" => $credit_id,
        "customer_no" => $cust_no,
        "credit_amount" => $credit_amount, 
        "date_created" => $date_created,
        "date_updated" => $date_updated
    );

    $credit_arr["data"] = $credit_item;

    //Turn to JSON & output 
    echo json_encode($credit_arr);
} else {
    echo json_encode(
        array("success" => false, "message" => "No record found")
    );
}
?>