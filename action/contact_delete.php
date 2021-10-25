<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$contact_id = $_POST['contact_id'];
if($contact_id > 0){
    $update = updatedb('o_customer_contacts', "status=0", "uid=".decurl($contact_id));
    if($update == 1)
    {
        echo sucmes('Success deleting contact');
        $proceed = 1;
    }
    else
    {
        die(errormes('Unable to delete contact'));
        exit();
    }
}
else{
    die(errormes("Contact ID invalid"));
    exit();
}

?>

<script>
    let proceed_ = '<?php echo $proceed; ?>';
    if(proceed_ === "1"){
        setTimeout(function () {
        reload();
        },400);

        //$('#cont<?php echo $contact_id; ?>').fadeOut('fast');
    }
</script>







