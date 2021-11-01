<?php
 include_once'db/connect_db.inc';
 session_start();
 if($_SESSION['role']!=="Admin"){
   header('location:index.php');
 }

$delete = $pdo->prepare("DELETE FROM tbl_category WHERE cat_id = '".$_GET['id']." '");
if($delete->execute()){
    header('location:category');
}


