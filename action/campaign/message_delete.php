<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$message_id = $_POST['message_id'];
if($message_id > 0){
    $update = updatedb('o_campaign_messages', "status=0", "uid=".decurl($message_id));
    if($update == 1)
    {
        echo sucmes('Success deleting message');
        $proceed = 1;
    }
    else
    {
        die(errormes('Unable to delete message'));
        exit();
    }
}
else{
    die(errormes("Message ID invalid"));
    exit();
}

?>

<script>
    let proceed_ = '<?php echo $proceed; ?>';
    if(proceed_ === "1"){
        setTimeout(function () {
        reload();
        },400);
    }
</script>







