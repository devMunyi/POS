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
$period = $_POST['period'];
$period_units = $_POST['period_units'];
$min_amount = $_POST['min_amount'];
$max_amount = $_POST['max_amount'];
$pay_frequency = $_POST['pay_frequency'];
$percent_breakdown = $_POST['percent_breakdown'];
$added_date = $fulldate;
$status = 1;

///////----------------Validation
if((input_length($name, 2)) == 1){
    if((checkrowexists('o_loan_products',"name='$name'")) == 1){
        die(errormes("Product with similar name exists"));
        exit();
    }
}
else{
    die(errormes("Product name is too short"));
    exit();
}
if($period > 0){}
else{
    die(errormes("Period is required"));
    exit();
}
if($period_units == '0'){
    die(errormes("Period units required"));
    exit();
}

if($min_amount > 10){}
else{
    die(errormes("Min Amount is required"));
    exit();
}
if($max_amount > 0){}
else{
    die(errormes("Max Amount required"));
    exit();
}


///////------------End of validation


$fds = array('name','description','period','period_units','min_amount','max_amount','pay_frequency','percent_breakdown','added_date','status');
$vals = array("$name","$description","$period","$period_units","$min_amount","$max_amount","$pay_frequency","$percent_breakdown","$added_date","$status");
$create = addtodb('o_loan_products',$fds,$vals);
if($create == 1)
{
    $product_id = fetchrow('o_loan_products',"name='$name'","uid");
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
            gotourl("loan-products?product=<?php echo encurl($product_id); ?>");
        },1500);
    }
</script>

