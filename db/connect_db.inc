<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
date_default_timezone_set("Africa/Nairobi");

//local db
/*$cleardb_server = 'localhost'; 
$cleardb_db   = 'pos_db'; // Your database name.
$cleardb_username = 'root';             // Your database username.
$cleardb_password = '';
 */ 

//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;

// Connect to DB
$con = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);


if(mysqli_connect_errno())
{
    printf('Error Establishing a database connection');
    exit();
}


try{
    $pdo = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db","$cleardb_username","$cleardb_password");
    //echo 'Connection Successfull';
}catch(PDOException $error){
    echo $error->getmessage();
}


$date=date('Y-m-d');
$date2=date('Y-M-D');
$fulldate=date('Y-m-d H:i:s');
$thisyear=date('Y');
$thismonth=date('m');
$thismonthname=date('M');
$thisday=date('d');
$thisdayname=date('D');

?>