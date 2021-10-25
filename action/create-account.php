<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");

$full_name = $_POST['full_name'];
$mobile = make_phone_valid($_POST['mobile']);
$email = $_POST['email'];
$password = $_POST['password'];

$full_name_ok = input_length($full_name, 3);
$mobile_ok = validate_phone($mobile, 3);
$email_ok = emailOk($email);
$password_ok = input_length($password, 6);

if($full_name_ok == 0){
    echo  errormes("Name is too short");
    die();
}
if($mobile_ok == 0){
    echo errormes("Mobile Number is Invalid");
    die();
}
if($email_ok == 0){
    echo errormes("Email Invalid");
    die();
}

if($password_ok == 0){
    echo errormes("Password too short");
    die();
}

if($full_name_ok + $mobile_ok + $email_ok + $password_ok == 4){

    $email_exists = checkrowexists("z_users","email='$email'");
    $phone_exists = checkrowexists("z_users","phone='$mobile'");

    if($email_exists == 1){
        echo errormes("Email already in use. If it is your email and you don't recall using it, please reset your password immediately");
        die();
        exit();

    }
    if($phone_exists == 1){
        echo errormes("Phone number already in use. If it is your number and you don't recall using it, please reset your password immediately");
        die();
        exit();

    }


    $epass = passencrypt($password);
    $hash = substr($epass, 0, 64);
    $salt = substr($epass, 64, 96);


    $fds = array("name","email","phone","join_date","pass1","email_verified","phone_verified","status");
    $vals = array("$full_name","$email","$mobile","$fulldate","$hash","0","0","1");
    $create = addtodb('z_users', $fds, $vals);
    if($create == 1)
    {
        $user = fetchRow('z_users', "email='$email'", "uid");
        //////-----See all projects you are a client of


        $userid = $user['uid'];
        $fdss = array('user', 'pass');
        $valss = array("$userid", "$salt");
        $savesalt = addtodb('z_passes', $fdss, $valss);

        if ($savesalt == 1) {
            $token = generateToken($userid,$deviceid,$browsername,$IPAddress,$OS);
            if(strlen($token) == 64)
            {

                $result_ = 1;
                $details_ = "$token";
                $_SESSION['z-token'] = $token;
                echo  sucmes("Success! Account created, we are taking you to the dashboard...");
                echo "<meta http-equiv=\"refresh\" content=\"2; URL=dashboard\" />";

            }
            else
            {
                $result_ = 0;
                echo  sucmes("Account created but we couldn't generate a token. Please login");
                echo "<meta http-equiv=\"refresh\" content=\"3; URL=auth-login\" />";
            }
        }
        else{
            $result_ = 0;
            echo  sucmes("Account created but there is an issue with your password. Please use forgot password");
        }
    }
    else{
      echo errormes("Unable to create account. Please retry after a few seconds");
    }





}
else{

}