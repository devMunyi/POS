<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$campaign_id = $_POST['campaign_id'];

if($campaign_id > 0){
    $update = updatedb('o_campaigns', "status=2", "uid= $campaign_id");
    if($update == 1)
    {
        echo sucmes('Success deactivating campaign');
        $proceed = 1;
        $last_campaign = fetchmax('o_campaigns',"uid='$campaign_id'","uid","uid");
        $cid = $last_campaign['uid'];

    }
    else
    {
        die(errormes('Unable to deactivate campaign'));
        die();
    }
}
else{
    die(errormes("Campaign ID invalid"));
    exit();
}

?>

<script>
    let proceed_ = '<?php echo $proceed; ?>';
    if(proceed_ === "1"){
        setTimeout(function () {
        	gotourl("broadcasts?campaign=<?php echo encurl($cid); ?>");
        },2000);
    }
</script>







