<?php
    include_once'connect_db.php';
    session_start();
    if($_SESSION['role']!=="Admin"){
        header('location:index.php');
    }

    $id = $_GET['id'];
    $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id=$id");
    $select->execute();

    if(isset($_POST['deactivate'])){
        $update = $pdo->prepare("UPDATE tbl_users SET is_active = 0 WHERE product_id=$id");
        $update->execute();
        if($update->execute()){
            echo 'Users Diactivate';
        }else{
            echo 'Something is Wrong';
        }

    }