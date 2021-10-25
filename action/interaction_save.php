<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");



$customer_id = $_POST['customer_id'];
$agent_id = 1;
//$loan_id = 1;  ///////////////------------Current active loan
$transcript = $_POST['transcript'];
$conversation_method = $_POST['conversation_method'];
$conversation_date = $fulldate;
$next_interaction = $_POST['next_interaction'];
$next_steps = $_POST['next_steps'];
$flag = $_POST['flag'];
$status = 1;



///////----------Validation
if(($customer_id > 0)){
    $l = fetchmaxid("o_loans", "customer_id = $customer_id AND status > 0", "uid");
    $loan_id = $l['uid'];
    if($loan_id > 0){
        $loan_id = $l['uid'];
    }else{
        $loan_id = 0;
    }
}
else{
    die(errormes("Please select customer"));
    exit();
}
if((input_length($transcript, 5)) == 0)
{
    die(errormes("Conversation details details too short"));
    exit();
}
if($conversation_method > 0){}
else{
    die(errormes("Conversation Method required"));
    exit();
}
if($next_interaction == 0){
    die(errormes("Next Interaction date is Invalid".datediff3($next_interaction, $date)));
    exit();
}


//////-----------End of validation
$fds = array('customer_id','agent_id','loan_id','transcript','conversation_method','conversation_date','next_interaction','next_steps','flag','status');
$vals = array("$customer_id","$agent_id","$loan_id","$transcript","$conversation_method","$conversation_date","$next_interaction","$next_steps","$flag","$status");
$create = addtodb('o_customer_conversations',$fds,$vals);
if($create == 1)
{
    echo sucmes('Conversation Created Successfully');
    $proceed = 1;

}
else
{
    echo errormes('Unable Update Record');
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
