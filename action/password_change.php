<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");

$session_d = session_details();

$old_password = $_POST['old_pass'];
$new_password = $_POST['new_pass'];

if((input_length($old_password, 2)) == 0){
        die(errormes("Please enter current password"));
        exit();
}
else{

}
if((input_length($new_password, 5)) == 0){
    die(errormes("New password too short"));
    exit();
}

////////////-------------------
if(($session_d['uid']) > 0){
    $userid = $session_d['uid'];
    $userrecord = fetchonerow("o_users","uid='$userid'","pass1");
    {
        ////////////Password verification
        $databasepass = $userrecord['pass1'];
        $thesalt =fetchRow('o_passes',"user='$userid'",'pass');

        ////apendsalt to inputted password
        $fullpass= $thesalt.$old_password;
        $encpass=hash('SHA256',$fullpass);
        ////fetch user pass from db


        if($encpass == $databasepass)
        {
          ///////////Create new password
            $epass=passencrypt($new_password);
            $hash=substr($epass,0,64);
            $salt=substr($epass,64,96);
            $updateh = updatedb('o_users',"pass1='$hash'","uid='$userid'");
            $updates = updatedb('o_passes',"pass='$salt'","user='$userid'");
            if($updateh + $updates == 2)
            {
                echo sucmes('Password updated successfully');
                $proceed = 1;
                notify("SYSTEM", $userid, "Password Updated","Your Password was updated on $fulldate from your profile","#");
            }
            else
            {
                echo errormes('Error updating password');
                exit();
            }

        }
        else
        {
            $result_ = 0;
            echo errormes("Current password incorrect");
        }
    }
}
else{
    die(errormes("Session Invalid"));
    exit();
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
