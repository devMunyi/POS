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

$title = $_POST['title'];
$uid = $_POST['cid'];
$description = $_POST['description'];
$campaign_date = $_POST['date'];
$frequency = $_POST['frequency'];
$repetitive = $_POST['repetitive'];
$target_customers = $_POST['target_customers'];
$status = $_POST['status'];

////////////////validation

if(input_available($title) == 0){
    echo errormes("Title is required");
    die();
}elseif((input_length($title, 3)) == 0){
    echo errormes("Title is too short");
    die();
}else{
    $exists = checkrowexists('o_campaigns',"name='$title' AND status=1 AND uid!='".decurl($uid)."'");
    if($exists == 1){
        echo errormes("Campaign with similar title exists");
        die();
    }
}

if((input_length($campaign_date, 10)) == 0){
    echo errormes("Date is required");
    die();
}

if($frequency > 0){}
else{
    echo errormes("Please select frequency");
    die();
}

if($repetitive > 0){}
else{
    echo errormes("Please select if campaign should be repetitive");
    die();
}


if($target_customers > 0){}
else{
    echo errormes("Please select target customers");
    die();
}




///////////------------------Save

$fds  = "name=\"$title\", description = \"$description\", running_date = \"$campaign_date\", frequency = \"$frequency\", repetitive = \"$repetitive\", target_customers =\"$target_customers\"";
$create = updatedb('o_campaigns', $fds, "uid='".decurl($uid)."'");
if($create == 1){
    echo sucmes('Campaign Updated successfully');
    $proceed = 1;
    $last_campaign = fetchmax('o_campaigns',"name='$title'","uid","uid");
    $cid = $last_campaign['uid'];

}
else{
    echo errormes('Unable to Update campaign');
}

?>


<script>
    if('<?php echo $proceed ?>'){
        setTimeout(function () {
            gotourl('broadcasts?campaign=<?php echo encurl($cid); ?>');
        }, 1500);

    }
</script>