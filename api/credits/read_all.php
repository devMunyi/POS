<?php
//Headers 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

session_start();
include_once("../../php_functions/functions.php");
include_once("../../configs/conn.inc");


//Get and set string query variable;
$where = isset($_GET["where_"]) ? $_GET["where_"] : 'id > 0';
$offset = isset($_GET["offset"]) ? $_GET["offset"] : 0;
$rpp = isset($_GET["rpp"]) ? $_GET["rpp"] : 10;
//$page_no = isset($_GET["page_no"]) ? $_GET["page_no"] : 1;
$orderby = isset($_GET["orderby"]) ? $_GET["orderby"] : "next_run_datetime";
$dir = isset($_GET["dir"]) ? $_GET["dir"] : "ASC";
$search = isset($_GET["search"]) ? $_GET["search"] : "";

$limit = "$offset, $rpp";


//company name lookup
$company_array = array();
$company_ = fetchtable2("tbl_companies", "name LIKE \"%$search%\"", "id", "asc", "id");
$company_count = mysqli_num_rows($company_);
if($company_count > 0){
    while($company_list = mysqli_fetch_array($company_)){
        $company_id = $company_list['id'];
        array_push($company_array, $company_id);
    }
    $service_company_list = implode(", ", $company_array);
    $orservicecompany = " OR `company_name` IN ($service_company_list)";
}

//unit name lookup
$unit_array = array();
$unit_ = fetchtable2("tbl_units", "name LIKE \"%$search%\"", "id", "asc", "id");
$unit_count = mysqli_num_rows($unit_);
if($unit_count > 0){
    while($unit_list = mysqli_fetch_array($unit_)){
        $unit_id = $unit_list['id'];
        array_push($unit_array, $unit_id);
    }
    $service_unit_list = implode(", ", $unit_array);
    $orserviceunit = " OR `unit` IN ($service_unit_list)";
}


//frequency value lookup
$unit_array = array();
$unit_ = fetchtable2("tbl_units", "name LIKE \"%$search%\"", "id", "asc", "id");
$unit_count = mysqli_num_rows($unit_);
if($unit_count > 0){
    while($unit_list = mysqli_fetch_array($unit_)){
        $unit_id = $unit_list['id'];
        array_push($unit_array, $unit_id);
    }
    $service_unit_list = implode(", ", $unit_array);
    $orserviceunit = " OR `unit` IN ($service_unit_list)";
}


if ((input_available($search)) == 1) {
    $andsearch = " AND (service_title LIKE \"%$search%\" $orservicecompany OR next_run_datetime LIKE \"%$search%\" $orserviceunit OR frequency LIKE \"%$search%\")";
} else {
    $andsearch = "";
}

$ms = fetchtable('tbl_services', "$where $andsearch", "$orderby", "$dir", "$limit", "id, company_name, service_title, last_run_datetime, next_run_datetime, unit, frequency, added_at, status");

///----------Paging Option
$alltotal = countotal("tbl_services", "$where $andsearch");

if ($alltotal > 0) {
    //Service array 
    $services_arr = array("success" => true, "count" => $alltotal);
    $services_arr['data'] = array();

    while ($row = mysqli_fetch_array($ms)) {
        extract($row);
        $company_name_ = fetchrow('tbl_companies', "id='" . $company_name . "'", "name");
        $unit_ = fetchrow('tbl_units', "id='" . $unit . "'", "name");
        $service_item = array(
            "id" => $id,
            "company_name" => $company_name_, 
            "service_title" => $service_title,
            "last_run_datetime" => $last_run_datetime,
            "next_run_datetime" => $next_run_datetime,
            "unit" => $unit_,
            "frequency" => $frequency,
            "added_at" => $added_at,
            "status" => $status,
        );

        //push to "data" 
        array_push($services_arr["data"], $service_item);
    }
    //Turn to JSON & output 
    echo json_encode($services_arr);
} else {
    echo json_encode(
        array("success" => false, "message" => "No service found")
    );
}

?>
    


"SELECT f.uid as `fun_uid`, f.name AS `fun_name`, f.icon AS `fun_icon`, sf.uid AS `subfun_uid`, sf.name AS `subfun_name`, sf.icon AS `subfun_icon` FROM pr_functionalities f LEFT JOIN pr_subfunctions sf ON f.uid = sf.func_id",