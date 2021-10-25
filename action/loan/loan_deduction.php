<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$action = $_POST['action'];
$loan_id = $_POST['loan_id'];
$deduction_id = $_POST['deduction_id'];

$loan = fetchonerow('o_loans',"uid='".decurl($loan_id)."'","loan_amount");



if((input_available($action)) == 0){
    die(errormes("Action Unspecified"));
    exit();
}
if($loan_id > 0){}
else{
    die(errormes("Loan ID invalid"));
    exit();
}

if($deduction_id > 0){}
else{
    die(errormes("Deduction ID invalid"));
    exit();
}


if($action == 'ADD'){
   $exists = checkrowexists('o_loan_deductions',"loan_id='".decurl($loan_id)."' AND deduction_id = '$deduction_id' AND status=1");
    $deduction_amount = deduction_amount($loan['loan_amount'], $deduction_id);
   if($exists == 1){
       //////It exists but has been removed, enable it
       errormes("Already added");
       $proceed = 1;
   }
   else{
      //////Doesn't exist, create it
       $fds = array('loan_id','deduction_id','deduction_amount','added_by','added_date','status');
       $vals = array("".decurl($loan_id)."","$deduction_id","$deduction_amount","".$userd['uid']."","$fulldate","1");
       $create = addtodb('o_loan_deductions',$fds,$vals);
       if($create == 1){
         sucmes("Added");
        $proceed = 1;
       }
       else{
           errormes("Unable to add");
       }
   }
}
elseif ($action == 'REMOVE'){
    $remove = updatedb('o_loan_deductions',"status=0","loan_id='".decurl($loan_id)."' AND deduction_id='$deduction_id'");
    if($remove == 1){
        sucmes("Removed");
        $proceed = 1;
    }
    else{
        errormes("Unable to remove");
    }
}
else{
    die(errormes("Action Invalid"));
    exit();
}

?>

<script>
    if('<?php echo $proceed ?>'){

            loan_deductions('<?php echo $loan_id; ?>');

    }
</script>
