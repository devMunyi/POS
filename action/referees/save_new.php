<?php
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$customer_id = $_POST['customer_id'];    $customer_id_dec = decurl($customer_id);
$added_date = $fulldate;
$referee_name = $_POST['referee_name'];
$id_no = $_POST['id_no'];
$mobile_no = make_phone_valid($_POST['mobile_no']);
$physical_address = $_POST['physical_address'];
$email_address = $_POST['email_address'];
$relationship = $_POST['relationship'];
$status = 1;


//input validations
if(input_available($referee_name) == 0){
    echo errormes("Referee name is required");
    die();
}elseif((input_length($referee_name, 3)) == 0){
    echo errormes("Referee name is too short");
    die();
}


if($customer_id > 0){
    //////---------Check if contact type exists
    $exists = checkrowexists("o_customer_referees","referee_name = \"$referee_name\" AND email_address = \"$email_address\" AND id_no = \"$id_no\" AND mobile_no = \"$mobile_no\" AND customer_id=\"$customer_id_dec\" AND  status=1");
    if($exists == 1){
        echo errormes('This referee is already added');
        die();
    }
}


if(input_available($email_address) == 0){
    echo errormes("Email is required");
    die();
}elseif(emailOk($email_address) == 0){
    echo errormes("Email is invalid");
    die();
}else{
    //check if email is unique
    $email_exists = checkrowexists("o_customer_referees", "uid > 0 AND status > 0 AND email_address = \"$email_address\"");
    if($email_exists == 1){
        die(errormes("Email In Use by Another Referee"));
    }
}


if(input_available($id_no) == 0){
    echo errormes("National Id is required");
    die();
}elseif(input_length($id_no, 5) == 0){
    echo errormes("National ID is invalid");
    die();
}else{
    //check if National ID is unique
    $id_exists = checkrowexists("o_customer_referees", "uid > 0 AND status > 0 AND id_no = \"$id_no\"");
    if($id_exists == 1){
        die(errormes("National ID In Use by Another Referee"));
    }
}


if(validate_phone($mobile_no) == 0){
    echo errormes("Referee's mobile number is invalid");
    die();
}else{
    //check if mobile number is unique
    $mobile_exists = checkrowexists("o_customer_referees", "uid > 0 AND status > 0 AND mobile_no = \"$mobile_no\"");
    if($mobile_exists == 1){
        die(errormes("Mobile Number In Use by Another Referee"));
    }
}


if($relationship > 0){

}else{
    echo errormes("Referee's relationship is required");
    die();
}



$fds = array('customer_id','added_date','referee_name','id_no','mobile_no','physical_address','email_address','relationship','status');
$vals = array("".decurl($customer_id)."","$added_date","$referee_name","$id_no","$mobile_no","$physical_address","$email_address","$relationship","$status");
$create = addtodb('o_customer_referees',$fds,$vals);
if($create == 1)
{
    echo sucmes('Referee Added Successfully');
    $proceed = 1;

}
else
{
    echo errormes('Unable to Add Referee');
}


?>
<script>
    let proceed = '<?php echo $proceed; ?>';
    if(proceed === "1"){
        setTimeout(function () {
            reload();
        },100);
        referee_list('<?php echo $customer_id; ?>','EDIT');
        clear_form('ref_form');
    }
</script>
