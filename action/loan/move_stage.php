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
if($loan_id > 0) {

    $next_stage = loan_next_stage(decurl($loan_id));

}
else{

        die(errormes("Loan code needed"));
        exit();
    }

if($next_stage['stage_details']['uid'] > 0){
    $stage_id = $next_stage['stage_details']['uid'];
    $update_loan_stage = updatedb('o_loans',"loan_stage='$stage_id'","uid=".decurl($loan_id));
    if($update_loan_stage == 1){
        $proceed = 1;
        echo sucmes("Loan moved to next stage");
        $event = "Loan moved to the next stage[".$next_stage['stage_details']['name']."] by [".$userd['name']."(".$userd['email'].")] on [$fulldate] with comment [<i>$comment</i>]";
        store_event('o_loans', decurl($loan_id),"$event");
    }
}
else{
    die(errormes("Something is wrong with the next stage. Please check the product settings"));
}

///////------------End of validation



?>
<script>
    modal_hide();
    if('<?php echo $proceed; ?>'){
        setTimeout(function () {
           loan_stages('<?php echo $loan_id; ?>');
        },400);
    }
</script>

