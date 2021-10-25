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
$action = $_POST['action'];

$status = 1;

///////----------------Validation
if($loan_id > 0) {}
else{

    die(errormes("Loan code needed"));
    exit();
}


    $update_loan_stage = updatedb('o_loans',"status=\"$action\"","uid=".decurl($loan_id));
    $update_incoming_payments_status = updatedb("o_incoming_payments", "status = 0", "loan_id=".decurl($loan_id));
    if($update_loan_stage == 1 AND $update_incoming_payments_status == 1){
        $proceed = 1;
        echo sucmes("Success");
        $action_name = fetchrow('o_loan_statuses',"uid='$action'","name");
        $event = "Loan status changed to $action ($action_name) by [".$userd['name']."(".$userd['email'].")] on [$fulldate] with comment [<i>$comment</i>]";
        store_event('o_loans', decurl($loan_id),"$event");
        if($action == 0){
            $delete = "1";
        }
    }else{
        die(errormes("Oops!.An error occured. Try again"));
    }



///////------------End of validation
?>
<script>
    modal_hide();
    if('<?php echo $proceed; ?>'){
        setTimeout(function () {
            if('<?php echo $delete ?>'){
                 gotourl('loans');
            }else {
                reload();
            }
        },1000);
    }
</script>

