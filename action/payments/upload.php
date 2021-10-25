<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");


$userd = session_details();
$added_by = $userd['uid'];
$file_name = $_FILES['file_']['name'];
$file_size = $_FILES['file_']['size'];
$file_tmp = $_FILES['file_']['tmp_name'];
$upload_location = '../../uploads_/incoming_payments/';


$allowed_formats = "csv";
$allowed_formats_array = explode(",", $allowed_formats);

if($file_size > 10){
    if((file_type($file_name, $allowed_formats_array)) == 0){
        die(errormes("This file format is not allowed. Only $allowed_formats files"));
    }
}else{
    die(errormes("File not attached or has invalid size"));
}


$handle = fopen($file_tmp, "r");
$i = 0;

$upload = upload_file($file_name, $file_tmp, $upload_location);
if($upload === 0){
	echo errormes("Error uploading file, please retry");
	exit();
}

//while(($data = fgetcsv($handle)) !== FALSE){
while(!feof($handle)){
	$data = fgetcsv($handle);
		if($i == 0){
			$i++;
			continue;
		}
			$loan_code = trim(decurl(intval(mysqli_real_escape_string($con, $data[0]))));
			$amount = trim(mysqli_real_escape_string($con, $data[1]));
			$payment_method = trim(intval(mysqli_real_escape_string($con, $data[2])));
			$transaction_code = trim(mysqli_real_escape_string($con, $data[3]));
			$mobile_number = trim(mysqli_real_escape_string($con, $data[4]));
			$status = 1;
			$record_method = "MANNUAL";

			if(!empty($loan_code) && !empty($amount) && !empty($payment_method) && !empty($transaction_code) && !empty($mobile_number)){
				if($loan_code > 0) {
				    $exists = checkrowexists("o_loans", "uid = $loan_code AND status > 0");
				    if ($exists == 0) {
				        die(errormes("The loan code doesn't exist"));
					    }else{
					        $customer_id = fetchrow("o_loans","uid = $loan_code","customer_id");
					        if($customer_id > 0){
					            $branch_id = fetchrow("o_customers", "uid=$customer_id", "branch");
					        }
					    }
				}else{
				    die(errormes("Please enter loan code"));
				}

				if($payment_method == 4){
					$transaction_code = "N/A";
				}else{
				    if (input_available($transaction_code) == 1) {
				        $exists = checkrowexists('o_incoming_payments', "transaction_code=\"$transaction_code\"");
				       
				        if ($exists == 1) {
				            die(errormes("Transaction code exists"));
				            exit();
				        }
				    }else {
				        //////------Invalid user ID
				        die(errormes("Please enter transaction code"));
				        exit();
				    }
				}


				if($amount > 0){}
				else{
				    die(errormes("Amount is required"));
				    exit();
				}

				if($loan_code > 0) {
				    $exists = checkrowexists('o_loans', "uid = $loan_code AND status != 0");
				    if ($exists == 0) {
				        die(errormes("The loan code doesn't exist"));
				        exit();
				    }else{
				        $customer_id = fetchrow('o_loans',"uid=$loan_code","customer_id");
				        if($customer_id > 0){
				            $branch_id = fetchrow("o_customers", "uid=$customer_id", "branch");
				        }
				    }
				}else{
				    die(errormes("Please enter loan code"));
				    exit();
				}

				$phone_number = make_phone_valid($mobile_number);

				$fds = array('customer_id','branch_id','payment_method','mobile_number','amount','transaction_code','loan_id','payment_date','added_by', 'record_method', 'status');
				$vals = array("$customer_id","$branch_id","$payment_method","$phone_number","$amount","$transaction_code","$loan_code","$date","$added_by", "$record_method", "$status");

				$create = addtodb("o_incoming_payments", $fds,$vals);
				recalculate_loan($loan_code);

				$ld = fetchmaxid("o_incoming_payments", "status > 0 AND loan_id=$loan_code", "uid");
				$max_pid = $ld["uid"];

				$balance = loan_balance($loan_code);
				updatedb("o_incoming_payments", "loan_balance = $balance", "uid = $max_pid");
				updatedb("o_loans", "loan_balance = $balance", "uid = $loan_id");
			}
		$i++;
}

if($create == 1){
	echo sucmes('File Uploaded Successfully');
	$proceed = 1;
}else{
	die(errormes('Unable Upload File'));
}


?>
<script>
    if('<?php echo $proceed; ?>'){
        setTimeout(function (){
            gotourl("incoming-payments");
        }, 1000);
    }
</script>