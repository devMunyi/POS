<?php
    include_once'db/connect_db.php';

    $phone = $_GET["phone"];
    $select = $pdo->prepare("SELECT * FROM tbl_product WHERE status = 'Unpaid' AND credit_balance > 0 AND sale_type = 'Credit' AND phone = :phone ");
    $select->bindParam(":phone", $phone);
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $response=$row;
    header('Content-Type: application/json');
    echo json_encode($response);
?>