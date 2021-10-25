<?php
date_default_timezone_set("Africa/Nairobi");
/*
$hostname = 'us-cdbr-east-04.cleardb.com'; 
$dbname   = 'pos_db'; // Your database name.
$username = 'bec7eb35ba2d88';             // Your database username.
$password = '9a4c03d0';

// Your databas 'r.].
//\.
//2.
//4]
//n\'
//\'/\/e password. If your database has no password, leave it empty.
// Let's connect to host
$con=mysqli_connect($hostname,$username,$password,$dbname);

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
$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);


if(mysqli_connect_errno())
{
    printf('Error Establishing a database connection');
    exit();
}

try{
    $pdo = new PDO('mysql:host=localhost;dbname=pos_db','root','');
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