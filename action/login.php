<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");
$email = $_POST['email'];   ///////////Email could be phone
$password = $_POST['password'];
$deviceid = $_POST['deviceid'];
$browsername = $_POST['browsername'];
$IPAddress = $_POST['IPAddress'];
$OS = $_POST['OS'];

$email_valid = emailOk($email);
$password_valid = input_length($password, 6);


if($email_valid == 0){
    ////-----Check if its a number
    if((validate_phone(make_phone_valid($email))) == 1){

    }
    else {
        echo errormes("Email or Mobile number is invalid");
        $result_ = 0;
        die();
    }
}
if($password_valid == 0){
    echo errormes("Password Invalid");
    die();
}

if($password_valid == 1){

    $userrecord = fetchonerow("o_users","email='$email' or phone= '".make_phone_valid($email)."'","uid, status, pass1");
    $userid = $userrecord['uid'];
    $status =$userrecord['status'];
    if($userid > 0)
    {
        if($status != 1)
        {
            $result_ = 0;
            echo errormes("Account is disabled. Please contact us");
        }
        else
        {
            ////////////Password verification
            $databasepass = $userrecord['pass1'];
            $thesalt =fetchRow('o_passes',"user='$userid'",'pass');

            ////apendsalt to inputted password
            $fullpass= $thesalt.$password;
            $encpass=hash('SHA256',$fullpass);
            ////fetch user pass from db


            if($encpass == $databasepass)
            {

                $token = generateToken($userid,$deviceid,$browsername,$IPAddress,$OS);
                if(strlen($token) == 64)
                {
                    $result_ = 1;
                    $details_ = $token;
                    $_SESSION['o-token'] = $token;
                    echo  sucmes("Success! we are taking you to the dashboard...");
                    echo "<meta http-equiv=\"refresh\" content=\"2; URL=index.php\"/>";
                }
                else
                {
                    // echo $token."Error generating token";
                    $result_ = 0;
                    echo error("Unable to generate a security token. Please click login again"); ///Unable to generate token
                }
            }
            else
            {
                $result_ = 0;
                echo errormes("Password mismatch");
            }
        }
    }
    else
    {
        $result_ = 0;
        echo errormes("Email or Mobile number does not exist.");
    }


}