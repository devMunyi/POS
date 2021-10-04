<?php
date_default_timezone_set("Africa/Nairobi");

$hostname = 'localhost'; 
$dbname   = 'pos_db'; // Your database name.
$username = 'root';             // Your database username.
$password = '';

// Your databas 'r.].
//\.
//2.
//4]
//n\'
//\'/\/e password. If your database has no password, leave it empty.
// Let's connect to host
$con=mysqli_connect($hostname,$username,$password,$dbname);
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