<?php
    include_once'db/connect_db.php';

    $id = $_GET["id"];
    $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id = :ppid ");
    $select->bindParam(":ppid", $id);
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $response=$row;
    header('Content-Type: application/json');
    echo json_encode($response);
?>