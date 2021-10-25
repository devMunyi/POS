<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}
$staff_id = $_POST['sid'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = make_phone_valid($_POST['phone']);
$join_date = $fulldate;
$pass1 = $_POST['password'];
$user_group = $_POST['user_group'];
$national_id = $_POST['national_id'];
$branch = $_POST['branch'];
$status = $_POST['status'];

///////----------Validation
if($staff_id > 0){}
else
{
    die(errormes("Staff ID is required"));
    exit();
}
if((input_available($name)) == 0)
{
    die(errormes("Name is invalid/required"));
    exit();
}
if((validate_phone($phone)) == 0)
{
    die(errormes("Mobile Number is invalid/required"));
    exit();
}
if((emailOk($email)) == 0)
{
    die(errormes("Email invalid/required"));
    exit();
}
else{
    $user_exists = checkrowexists('o_users',"email='$email' AND uid!='".decurl($staff_id)."'");
    if($user_exists == 1){
        die(errormes("Email is in use"));
        exit();
    }
}
if($status < 1){
    die(errormes("Status required"));
    exit();
}

if($user_group < 1){
    die(errormes("User Group required"));
    exit();
}
if((validate_phone($phone)) == 0)
{
    die(errormes("Phone Number invalid/required"));
    exit();
}
else{
    $phone_exists = checkrowexists('o_users',"phone='$phone' AND uid!='".decurl($staff_id)."'");
    if($phone_exists == 1){
        die(errormes("Phone is in use"));
        exit();
    }
}
if((input_available($pass1)) == 1){
    if((input_length($pass1, 6)) == 0){

            die(errormes("Password is too short < 6"));
            exit();
           }
        else{
            $epass = passencrypt($pass1);
            $hash = substr($epass, 0, 64);
            $salt = substr($epass, 64, 96);
            $andpass = " ,pass1='$hash'";
            $updatepass = 1;
        }

}
//////-----------End of validation


$updatefds = "name='$name', email='$email', phone='$phone', national_id='$national_id',  user_group='$user_group', branch='$branch', status='$status' $andpass";
$create = updatedb('o_users',"$updatefds","uid='".decurl($staff_id)."'");
if($create == 1)
{
    echo sucmes('Record Updated Successfully');
    $proceed = 1;
    if($updatepass == 1){
        $savesalt = updatedb('o_passes', "pass='$salt'", "user='".decurl($staff_id)."'");
    }

}
else
{
    echo errormes('Unable to Update Record');
    $proceed = 0;
}



?>

<script>
    let proceed = '<?php echo $proceed; ?>';
    if(proceed === "1"){
        setTimeout(function () {
            gotourl("staff?staff=<?php echo $staff_id; ?>")
        },2000);
    }
</script>





