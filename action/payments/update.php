<?php
session_start();
include_once("../../php_functions/functions.php");
include_once("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$pid = decurl($_POST['pid']);
$payment_method = $_POST['payment_method'];
$mobile_number = $_POST['mobile_number'];
$amount = $_POST['amount'];
$transaction_code = $_POST['transaction_code'];
$loan_id = decurl($_POST['loan_id']);
$payment_date = $_POST['payment_date'];
$record_method = $_POST['record_method'];
$comments = $_POST['comments'];
$status = $_POST['status'];

$status = 1;


////////////////////////
if($pid > 0){}
else{
    die(errormes("Payment ID Invalid"));
    exit();
}

if($payment_method == 4){
    /*if(input_length($transaction_code,3) == 0){
        
    }*/
    $transaction_code = "N/A";
}else{
    if (input_length($transaction_code, 3) == 1) {
        $exists = checkrowexists('o_incoming_payments', "transaction_code=\"$transaction_code\" AND uid != $pid");
       
        if ($exists == 1) {
            die(errormes("Transaction code exists"));
            exit();
        }
    }else {
        //////------Invalid user ID
        die(errormes("Please enter transaction code"));
        exit();
    }
}



if($amount > 0){}
else{
    die(errormes("Amount is required"));
    exit();
}

if($loan_id > 0) {
    $exists = checkrowexists('o_loans', "uid = $loan_id AND status != 0");
    if ($exists == 0) {
        die(errormes("The loan code doesn't exist"));
        exit();
    }
    else{
        $customer_id = fetchrow('o_loans',"uid= $loan_id","customer_id");
    }
}
else{
    die(errormes("Please enter loan code"));
    exit();
}

if((input_length($payment_date, 10)) == 0)
{
    die(errormes("Payment date required"));
    exit();
}

if($payment_method == 0){
    die(errormes("Payment method required"));
    exit();
}


$update_flds = "customer_id= $customer_id, payment_method= $payment_method, mobile_number=\"$mobile_number\", amount=$amount, transaction_code=\"$transaction_code\", loan_id = $loan_id, payment_date=\"$payment_date\", record_method=\"$record_method\", comments=\"$comments\"";
$update = updatedb('o_incoming_payments',$update_flds,"uid = $pid");
if ($update == 1) {
    echo sucmes('Payment Updated Successfully');
    recalculate_loan($loan_id);
    
    $ld = fetchmaxid("o_incoming_payments", "status > 0 AND loan_id = $loan_id", "uid");
    $max_pid = $ld["uid"];

    $balance = loan_balance($loan_id);
    updatedb("o_incoming_payments", "loan_balance = $balance", "uid = $max_pid");
    updatedb("o_loans", "loan_balance = $balance", "uid = $loan_id");
    $proceed = 1;
} else {
    echo errormes('Error Updating Payment');
}
?>

<script>
    if('<?php echo $proceed; ?>'){
        setTimeout(function () {
            gotourl("incoming-payments?repayment=<?php echo encurl($pid); ?>")
        },1500);
    }
</script>
