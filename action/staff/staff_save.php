<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = make_phone_valid($_POST['phone']);
$join_date = $fulldate;
$national_id = $_POST['national_id'];
$pass1 = $_POST['password'];
$user_group = $_POST['user_group'];
$branch = $_POST['branch'];
$status = $_POST['status'];

///////----------Validation
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
    $user_exists = checkrowexists('o_users',"email='$email'");
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
    $phone_exists = checkrowexists('o_users',"phone='$phone'");
    if($phone_exists == 1){
        die(errormes("Phone is in use"));
        exit();
    }
}

    if((input_length($pass1, 6)) == 0){
        if($phone_exists == 1){
            die(errormes("Password is too short < 6"));
            exit();
        }
        else{

        }
    }

//////-----------End of validation
$epass = passencrypt($pass1);
$hash = substr($epass, 0, 64);
$salt = substr($epass, 64, 96);

$fds = array('name','email','phone','national_id','join_date','pass1','user_group','branch','status');
$vals = array("$name","$email","$phone","$national_id","$join_date","$hash","$user_group","$branch","$status");
$create = addtodb('o_users',$fds,$vals);
if($create == 1)
{
    $userid = fetchrow('o_users', "email='$email'", "uid");
    $fdss = array('user', 'pass');
    $valss = array("$userid", "$salt");
    $savesalt = addtodb('o_passes', $fdss, $valss);
    echo sucmes('Record Created Successfully');
    $proceed = 1;
    $last_staff = fetchmax('o_users',"email='$email'","uid","uid");
    $sid = $last_staff['uid'];

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
           gotourl("staff?staff=<?php echo encurl($sid); ?>")
        },2000);
    }
</script>





