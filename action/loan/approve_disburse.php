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
$comment = $_POST['comment'];

$status = 1;

///////----------------Validation
if($loan_id > 0) {}
else{

    die(errormes("Loan code needed"));
    exit();
}


    $update_loan_stage = updatedb('o_loans',"status='2'","uid=".decurl($loan_id));
    if($update_loan_stage == 1){
        $proceed = 1;
        echo sucmes("Loan moved to next stage of disbursement");
        $event = "Loan moved to disbursement by [".$userd['name']."(".$userd['email'].")] on [$fulldate] with comment [<i>$comment</i>]";
        store_event('o_loans', decurl($loan_id),"$event");
    }



///////------------End of validation



?>
<script>
    modal_hide();
    if('<?php echo $proceed; ?>'){
        setTimeout(function () {
            reload();
        },2000);
    }
</script>

