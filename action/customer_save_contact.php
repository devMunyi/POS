<?php
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");

$customer_id = $_POST['customer_id'];
$contact_type = $_POST['contact_type'];
$value = $_POST['value'];
$status = 1;

if($customer_id > 0){
    $customer_id_dec = decurl($customer_id);

}

if(($contact_type) > 0){

    if((input_available($value)) == 0){
        echo errormes("Please enter value to fill details");
        die();
    } 

    //validate email
    if($contact_type == 3){
        if((emailOk($value)) == 0){
            die(errormes("Email is invalid"));
            exit();
        }
    }elseif($contact_type == 1 OR $contact_type == 2){
        $filtered_phone_val = make_phone_valid($value);
        if(validate_phone($filtered_phone_val) == 0)
        {
            die(errormes("Mobile number invalid"));
            exit();
        }
    }

    //////---------Check if contact type exists
    if($contact_type == 1){
        $exists_1 = checkrowexists("o_customer_contacts","(contact_type = 1 OR contact_type = 2) AND value= \"$filtered_phone_val\" AND status = 1"); 
    }elseif($contact_type == 2){
        $exists_2 = checkrowexists("o_customer_contacts","(contact_type = 2 OR contact_type = 1) AND value = \"$filtered_phone_val\" AND status = 1");
    }elseif($contact_type == 3){
        $exists_3 = checkrowexists("o_customer_contacts","contact_type = 3 AND value = \"$value\" AND status = 1");
    }


    if($exists_1 == 1 OR $exists_2 == 1){
            echo errormes("Phone Number Exists.");
            die();
    }elseif($exists_3 == 1){
        echo errormes("Email Exists.");
        die();
    }
        
}else{
    echo  errormes("Please select Contact Type");
    die();
}


$fds = array('customer_id','contact_type','value','status');
if($contact_type == 1 OR $contact_type == 2){
   $vals = array("$customer_id_dec","$contact_type","$filtered_phone_val","$status"); 
}else{
    $vals = array("$customer_id_dec","$contact_type","$value","$status");
}
$create = addtodb('o_customer_contacts',$fds,$vals);
if($create == 1)
{
    echo sucmes('Contact Added Successfully');
    $proceed = 1;

}
else
{
    echo errormes('Unable to Add Contact');
}


?>
<script>
    let proceed = '<?php echo $proceed; ?>';
    if(proceed === "1"){
        setTimeout(function(){
            reload();
        }, 2500);
        clear_form('contact_');
        contact_list('<?php echo $customer_id; ?>','EDIT');
    }
</script>
