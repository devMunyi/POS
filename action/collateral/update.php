<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
$col_id = $_POST['col_id'];
$customer_id = $_POST['customer_id'];
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
if($col_id > 0){}
else{
    echo errormes("Collateral ID Invalid");
    die();
}

//////-----------End of validation

$fields ="category='$category', title='$title', description='$description', money_value='$money_value', doc_reference_no='$doc_reference_no', document_scan_address='$document_scan_address', filling_reference_no='$filling_reference_no'";
$update = updatedb('o_collateral',$fields,"uid = ".decurl($col_id));
if($update == 1)
{
    echo sucmes('Collateral Updated Successfully');
    $proceed = 1;

}
else
{
    echo errormes('Unable to Update Collateral');
}


?>
<script>
    let proceed = '<?php echo $proceed; ?>';
    if(proceed === "1"){
        setTimeout(function () {
            collateral_list('<?php echo $customer_id; ?>','EDIT');
        },200);
    }
</script>
