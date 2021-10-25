<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$other_id = $_POST['other_id'];
if($other_id > 0){
    $update = updatedb('o_key_values', "status=0", "uid=".decurl($other_id));
    if($update == 1)
    {
        echo sucmes('Success deleting record');
        $proceed = 1;

    }
    else
    {
        echo errormes('Unable to delete record');
    }
}
else{
    die(errormes("Record invalid"));
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







