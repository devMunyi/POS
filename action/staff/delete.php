<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$member_id = $_POST['member_id'];

if($member_id > 0){
    $update = updatedb('o_users', "status=2", "uid= $member_id");
    if($update == 1)
    {
        echo sucmes('Success blocking member');
        $proceed = 1;
    }
    else
    {
        die(errormes('Unable to delete memeber'));
        die();
    }
}else{
    die(errormes("Member ID invalid"));
    exit();
}

?>

<script>
    let proceed_ = '<?php echo $proceed; ?>';
    if(proceed_ === "1"){
        setTimeout(function () {
        	gotourl("staff?staff=<?php echo encurl($member_id); ?>");
        },2000);
    }
</script>







