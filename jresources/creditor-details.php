<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../db/connect_db.inc");


$where_ =  $_POST['where_'];
$cust_phone = $_POST['cust_no'];
$rows = "";
//-----------------------------Reused Query


$creditor_ = fetchonerow("tbl_credit_limit", "cust_no = $cust_phone", "credit_amount ,cust_no, cust_name, date_created, date_updated");
$alltotal = countotal("tbl_credit_limit","$where_ AND cust_no = '$cust_phone'");


if($alltotal > 0) {
            $no = 1;
            $credit_amount = $creditor_['credit_amount'];
            $cust_no_ = $creditor_['cust_no'];
            $cust_name = $creditor_['cust_name'];                    
            $date_created = $creditor_['date_created'];     
            $date_updated = $creditor_['date_updated'];

            $row .= "<tr><td>$no</td>
                                <td><span class='text-muted font-13 font-bold'>$cust_no_</span>
                                </td>
                                <td><span>$credit_amount</span></td>
                                <td>$date_created</td>
                                <td>$date_updated</td>
                            </tr>                    ";

            //////------Paging Variable ---
            //$page_total = $page_total + 1;
            /////=======Paging Variable ---


        
    }else{
    $row = "<tr><td colspan='5'><i>No Record Found</i></td></tr>";
}
///----------Paging Option

///==========Paging Option

 echo   trim($row)."<tr style='display: none;'><td><input type='text' id='_alltotal_' value='$alltotal'><input type='text' id='_pageno_' value='$page_no'><input type='text' id ='_dob_' value = '$dob'></td></tr>";
 ?>