<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");


$name = $_POST['name'];
$description = $_POST['description'];
$amount = $_POST['amount'];
$amount_type = $_POST['amount_type'];
$loan_stage = $_POST['loan_stage'];
$automatic = $_POST['automatic'];
$status = 1;

if((input_length($name, 2)) == 0)
{
    die(errormes("Deduction Name is required"));
    exit();
}
else{
    $addon_exists = checkrowexists('o_deductions',"name='$name'");
    if($addon_exists == 1){
        die(errormes("Deduction Name Exists"));
        exit();
    }
}
if($amount > 0){}
else{
    die(errormes("Deduction Amount should be more than 0"));
    exit();
}



$fds = array('name','description','amount','amount_type','loan_stage','automatic','status');
$vals = array("$name","$description","$amount","$amount_type","$loan_stage","$automatic","$status");
$create = addtodb('o_deductions',$fds,$vals);
if($create == 1)
{    echo sucmes('Deduction Added Successfully');
    $proceed = 1;
}
else
{
    echo errormes('Unable to Add Deduction');
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

