<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$loan_id = $_POST['loan_id'];
$collateral_id = $_POST['collateral_id'];
$action = $_POST['action'];

if($loan_id > 0 ){}
else{
    die(errormes("Loan ID invalid"));
    exit();
}
if($collateral_id > 0 ){}
else{
    die(errormes("Collateral invalid"));
    exit();
}

$update_flds = " loan_id='".decurl($loan_id)."', status='$action'";
$update = updatedb('o_collateral', $update_flds, "uid='".decurl($collateral_id)."'");

if($update > 0){
    echo sucmes("Success");
    $proceed = 1;
}
else{
    echo errormes("Error");
}

?>

<script>
    if('<?php echo $proceed ?>'){
        loan_collateral_list('<?php echo $loan_id; ?>');
    }
</script>
