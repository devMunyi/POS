<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();

$customer_id = $_POST['customer_id'];                    $customer_id_dec = decurl($customer_id);
$category = $_POST['category'];
$title = $_POST['title'];
$description = $_POST['description'];
$money_value = $_POST['money_value'];
$doc_reference_no = $_POST['doc_reference_no'];
$filling_reference_no = $_POST['filling_reference_no'];
$document_scan_address = $_POST['digital_file_number'];
$added_date = $fulldate;
$added_by = $userd['uid'];
$status = 1;

///////----------Validation
if((($category)) > 0)
{ }
else{
    die(errormes("Type of collateral is required"));
    exit();
}
if((input_length($title, 2)) == 0){
    echo errormes("Title is required");
    die();
}

//////-----------End of validation
$fds = array('customer_id','category','title','description','money_value','document_scan_address','doc_reference_no','filling_reference_no','added_date','added_by','status');
$vals = array("$customer_id_dec","$category","$title","$description","$money_value","$document_scan_address","$doc_reference_no","$filling_reference_no","$added_date","$added_by","$status");
$create = addtodb('o_collateral',$fds,$vals);
if($create == 1)
{
    echo sucmes('Collateral added Successfully');
    $proceed = 1;

}
else
{
    echo errormes('Unable to add Collateral');
}


?>
<script>
    let proceed = '<?php echo $proceed; ?>';
    if(proceed === "1"){
        setTimeout(function () {
            reload();
        },1500);
    }
</script>
