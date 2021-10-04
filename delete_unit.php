<?php
 include_once'db/connect_db.php';
 session_start();
 if($_SESSION['role']!=="Admin"){
   header('location:index');
 }

$delete = $pdo->prepare("DELETE FROM tbl_unit WHERE unit_id = '".$_GET['id']." '");
if($delete->execute()){
    header('location:unit');
}


