<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");

/////----------Session Check
$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}
/////---------End of session check

$uid = $_POST['cid'];
$full_name = $_POST['full_name'];
$primary_mobile = make_phone_valid($_POST['primary_mobile']);
$email_address = $_POST['email_address'];
$physical_address = $_POST['physical_address'];
$town = $_POST['town'];
$national_id = $_POST['national_id'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$added_by = $userd['uid'];
$added_date = $fulldate;
$branch = $_POST['branch'];
$primary_product = $_POST['primary_product'];
$loan_limit = $_POST['loan_limit'];
$events = "Customer updated at [$fulldate] by [".$userd['name']."{".$userd['uid']."}"."$username]";
$status = $_POST['status'];

if($uid > 0){
    $user_dec = decurl($uid);
}
else{
    die(errormes("User Error. Please select user again"));
    exit();
}

if((input_available($email_address)) == 1){
    if((emailOk($email_address)) == 0){
        die(errormes("Email is invalid"));
        exit();
    }
    else{
        $email_exists = checkrowexists('o_customers',"email_address='$email_address' AND uid !='$user_dec'");
        if($email_exists == 1){
            die(errormes("Email Exists"));
            exit();
        }
    }
}
else{
    $email_address = null;
}
///////////--------------------Validation
if((input_available($full_name)) == 0)
{
    die(errormes("Name is invalid/required"));
    exit();
}


if((validate_phone($primary_mobile)) == 0)
{
    die(errormes("Mobile number invalid"));
    exit();
}
else{
    $phone_exists = checkrowexists('o_customers',"primary_mobile='$primary_mobile' AND uid !='$user_dec'");
    if($phone_exists == 1){
        die(errormes("Primary Mobile Number Exists"));
        exit();
    }
}
//if((input_length($national_id, 5)) == 0)
//{
//    die(errormes("National Id Required"));
//    exit();
//}
//else{
//    $id_exists = checkrowexists('o_customers',"national_id='$national_id' AND uid !='$user_dec'");
//    if($id_exists == 1){
//        die(errormes("National ID Already Exists"));
//        exit();
//    }
//}

if($gender != 'M' && $gender != 'F'){
    die(errormes("Gender is required"));
    exit();
}

if((input_length($physical_address, 10)) == 0)
{
    die(errormes("Main Address is Required"));
    exit();
}

if((input_length($dob, 10)) == 0)
{
    die(errormes("Date of birth required"));
    exit();
}
if($town > 0){}
else{
    die(errormes("Town is required"));
    exit();
}

if($branch > 0){}
else{
    die(errormes("Branch is required"));
    exit();
}



///////////===================Validation


$fds = array('full_name','primary_mobile','email_address','physical_address','town','national_id','gender','dob','added_by','added_date','branch','primary_product','loan_limit','events','status');
$vals = array("$full_name","$primary_mobile",$email_address,"$physical_address","$town","$national_id","$gender","$dob","$added_by","$added_date","$branch","$primary_product","$loan_limit","$events","$status");

$update_flds = " full_name='$full_name', primary_mobile='$primary_mobile', email_address='$email_address', physical_address='$physical_address', town='$town',  gender='$gender', dob='$dob', branch='$branch', primary_product='$primary_product', loan_limit='$loan_limit', status='$status'";
$update = updatedb('o_customers', $update_flds, "uid=".$user_dec);
if($update == 1)
{
    echo sucmes('Customer Updated Successfully');
    $customer_id = "Success updating data";
    $proceed = 1;
    store_event('o_customers', $user_dec,"$events");

}
else
{
    echo errormes('Unable to Save Customer');
}

?>

<script>
    let proceed = '<?php echo $proceed; ?>';
    if(proceed === "1"){
        setTimeout(function () {
            gotourl("customers?customer-add-edit=<?php echo $uid;?>&contact");
        },1500);
    }
</script>
