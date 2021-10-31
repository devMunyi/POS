<?php
include_once ("../php_functions/functions.php");
include_once ("../db/connect_db.php");


if(isset($_GET['key'])){
    $search = $_GET['key'];
}


echo "<table class='table table-striped table-condensed'>";
$products_ = fetchtable('tbl_product',"product_name LIKE '%$search%' || product_code LIKE '%$search%'", "product_id", "desc", "0,10", "product_id, product_code, product_name");
while($p = mysqli_fetch_array($products_))
{
    $product_id = $p['product_id'];
    $product_code = $p['product_code'];
    $product_name = $p['product_name'];
    echo "<tr><td><a class='pointer' onclick=\"select_product('$product_name (CODE: $product_code)','$product_id');\"><span class='font-bold font-16 text-blue'>$product_name</span> <br/>
    CODE: $product_code | ID: $product_id</a></td></tr>";
}
echo "</table>";
