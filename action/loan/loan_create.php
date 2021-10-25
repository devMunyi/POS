<?php
session_start();
include_once("../../php_functions/functions.php");
include_once("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}

$customer_id = $_POST['customer_id'];
$product_id = $_POST['product_id'];
$loan_amount = $_POST['loan_amount'];
$application_mode = $_POST['application_mode'];
$added_by = $userd['uid'];
//$status = $_POST['status'];


////////////////////////
if ($customer_id > 0) {
    $user_ = fetchonerow('o_customers', "uid=$customer_id", "primary_product, loan_limit, branch, status");
    $loan_limit = $user_['loan_limit'];
    $cust_branch = $user_['branch'];
    $status = $user_['status'];
    if ($status != 1) {
        die(errormes("Customer status is not Active"));
        exit();
    }
    if ($loan_amount > $loan_limit) {
        die(errormes("The customer's Loan Limit is Ksh. $loan_limit"));
        exit();
    }

} else {
    //////------Invalid user ID
    die(errormes("Please select a user"));
    exit();
}


if ($loan_amount > 0) {
    if ($product_id > 0) {
        $prod = fetchonerow('o_loan_products', "uid=$product_id", "period, period_units, min_amount, max_amount, pay_frequency, percent_breakdown, status");
        $prod_period = $prod['period'];
        $prod_period_units = $prod['period_units'];
        $min_amount = $prod['min_amount'];
        $max_amount = $prod['max_amount'];
        $prod_pay_frequency = $prod['pay_frequency'];
        $prod_percent_breakdown = $prod['percent_breakdown'];
        $status = $prod['status'];
        if ($status != 1) {
            die(errormes("Loan Product is Invalid"));
            exit();
        }
        if (($min_amount > $loan_amount) || ($max_amount < $loan_amount)) {
            die(errormes("The Product allows loan amounts between $min_amount and $max_amount"));
            exit();
        }


    } else {
        die(errormes("Please select a Product"));
        exit();
    }
} else {
    die(errormes("Please enter a Valid Amount"));
    exit();
}


$has_loan = checkrowexists('o_loans', "customer_id = $customer_id AND status in (1,2,3,4,7,8)");
if ($has_loan == 1) {
    die(errormes("The customer has existing Loan"));
    exit();
}


$disbursed_amount = 0;      /////Calculated from product
$period = $prod_period;                /////From Product
$period_units = $prod_period_units;         //////From product
$payment_frequency = $prod_pay_frequency;    ///////From product
$payment_breakdown = $prod_percent_breakdown;    //////From Product
$total_instalments = total_instalments($period, $period_units,$payment_frequency);         //////Calculated from product
$total_instalments_paid = 0.00;  /////Initialization
$current_instalment = 1;         ////Initialization
$given_date = $date;         ////Initialization
$next_due_date = next_due_date($given_date, $period, $period_units,$payment_frequency);         ////Calculated from product
$final_due_date = final_due_date($given_date, $period, $period_units);         ////Calculated from product
$transaction_date = $fulldate;         ////Initialization
$added_date = $fulldate;
$loan_stage_d = fetchminid('o_product_stages',"product_id='$product_id' AND status=1","stage_order");
$loan_stage = $loan_stage_d['stage_id'];

/////////////////////


$fds = array('customer_id', 'product_id', 'loan_amount', 'disbursed_amount', 'period', 'period_units', 'payment_frequency', 'payment_breakdown', 'total_instalments', 'total_instalments_paid', 'current_instalment', 'given_date', 'next_due_date', 'final_due_date', 'added_by', 'current_branch', 'added_date', 'loan_stage', 'application_mode', 'status');
$vals = array("$customer_id", "$product_id", "$loan_amount", "$disbursed_amount", "$period", "$period_units", "$payment_frequency", "$payment_breakdown", "$total_instalments", "$total_instalments_paid", "$current_instalment", "$given_date", "$next_due_date", "$final_due_date", "$added_by", "$cust_branch", "$added_date", "$loan_stage", "$application_mode", "1");
$create = addtodb('o_loans', $fds, $vals);
updatedb("o_customers", "primary_product = $product_id", "uid = $customer_id");
if ($create == 1) {
    echo sucmes('Loan Created Successfully');
    $proceed = 1;
    $created_loan = fetchmax('o_loans',"customer_id='$customer_id' AND product_id='$product_id'","uid","uid");
    $loan_id = $created_loan['uid'];

} else {
    echo errormes('Unable Create Record');
}
?>

<script>
    let proceed = '<?php echo $proceed; ?>';
    if(proceed === "1"){
        setTimeout(function () {
            gotourl('loans?loan=<?php echo encurl($loan_id); ?>&just-created');
        },1500);
    }
</script>
