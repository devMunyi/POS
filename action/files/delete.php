<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

////////////////------------------Files are not really deleted,
$file_id = $_POST['file_id'];
if($file_id > 0){
    $update = updatedb('o_documents', "status=0", "uid=".decurl($file_id));
    if($update == 1)
    {
        echo sucmes('Success deleting file');
        $proceed = 1;

    }
    else
    {
        echo errormes('Unable to delete file');
    }
}
else{
    die(errormes("File Id invalid"));
    exit();
}

?>

<script>
    if('<?php echo $proceed; ?>'){
        setTimeout(function () {
            $('#fil<?php echo $file_id; ?>').fadeOut('fast');
            modal_hide();
        },400);
        
    }
</script>







