<?php 
    //Headers 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json'); 

    session_start();
    include_once("../../php_functions/functions.php");
    include_once("../../configs/conn.inc");


    //Get and set string query variable;
    $offset = isset($_GET["offset"]) ? $_GET["offset"] : 0;
    $rpp = isset($_GET["rpp"]) ? $_GET["rpp"] : 25;
    $page_no = isset($_GET["page_no"]) ? $_GET["page_no"] : 1;
    $orderby = isset($_GET["orderby"]) ? $_GET["orderby"] : "logged_date";
    $dir = isset($_GET["dir"]) ? $_GET["dir"] : "DESC";
    //$search = isset($_GET["search"]) ? $_GET["search"] : "";

    $limit = "$offset, $rpp";

    //Get ID 
    $service_id = isset($_GET["service_id"]) ? $_GET["service_id"] : die();


    $ms = fetchtable('tbl_logs', "service_id = '$service_id' AND status > 0", "$orderby", "$dir", "$limit", "log, logged_date");

    ///----------Paging Option
    $alltotal = countotal("tbl_logs", "service_id = '$service_id' AND status > 0");


    if ($alltotal > 0) {
    //Service array 
    $logs_arr = array("success" => true, "count" => $alltotal, "page_no" => $page_no);
    $logs_arr['data'] = array();

    while ($row = mysqli_fetch_array($ms)) {
        extract($row);
        $log_item = array(
            "log" => $log, 
            "logged_date" => $logged_date,
        );

        //push to "data" 
        array_push($logs_arr["data"], $log_item);
    }
    //Turn to JSON & output 
    echo json_encode($logs_arr);
    } else {
        echo json_encode(
            array("success" => false, "message" => "No logs found for service id $service_id")
        );
    }
