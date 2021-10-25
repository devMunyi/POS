<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$name = $_POST['name'];
$description = $_POST['description'];
$stage_order = $_POST['stage_order'];
$permissions = $_POST['permissions'];
$can_addon = $_POST['can_addon'];
$can_deduct = $_POST['can_deduct'];
$status = 1;



if((input_length($name, 2)) == 0)
{
    die(errormes("Stage Name is Invalid"));
    exit();
}
else{
    $exists = checkrowexists('o_loan_stages',"name='$name'");
    if($exists == 1){
        die(errormes("The Stage Name Exists"));
        exit();
    }
}


///////////////Continue

$fds = array('name','description','stage_order','permissions','can_addon','can_deduct','status');
$vals = array("$name","$description","$stage_order","$permissions","$can_addon","$can_deduct","$status");
$create = addtodb('o_loan_stages',$fds,$vals);
if($create == 1)
{
    echo sucmes('Record Created Successfully');
    $proceed = 1;

}
else
{
    echo errormes('Unable Update Record');
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

