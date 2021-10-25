<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

/////----------Session Check
$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}
/////---------End of session check

$userd = session_details();
$added_by = $userd['uid'];
$message = $_POST['message'];
$message_id = decurl($_POST['message_id']);
$campaign_id = $_POST['campaign_id'];
$events = "Campaign message updated at [$fulldate] by [".$userd['name']."{".$userd['uid']."}"."$username]";
$status = 1;


////////////////validation
if(input_available($message) == 0){
    echo errormes("Message is required");
    die();
}else{
    $exists = checkrowexists("o_campaign_messages","campaign_id = $campaign_id AND uid != $message_id");
    if($exists == 1){
        echo errormes("Message for this campaign already exists");
        die();
    }
}


///////////------------------update
$update = updatedb('o_campaign_messages',"message = \"$message\"", "uid = $message_id");
if($update == 1){
    echo sucmes('Message updated successfully');
    $proceed = 1;
    store_event('o_campaign_messages', $message_id, "$events");
}
else{
    echo errormes('Unable to update message');
}

?>


<script>
    if('<?php echo $proceed ?>'){
        setTimeout(function () {
            reload();
            //gotourl('broadcasts?campaign=<?php //echo encurl($campaign_id); ?>');
        }, 1500);

    }
</script>