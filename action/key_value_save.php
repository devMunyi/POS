<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");

$userd = session_details();
$tbl = $_POST['tbl'];
$record = decurl($_POST['record']);
$key_ = $_POST['key_'];
$value_ = $_POST['value_'];
$added_by = $userd['uid'];
$added_date = $fulldate;
$status = $_POST['status'];


if((input_available($key_)) == 0)
{
    die(errormes("Name is required"));
    exit();
}
if((input_available($value_)) == 0)
{
    die(errormes("Value is required"));
    exit();
}

if((input_available($tbl)) == 0)
{
    die(errormes("Table is not selected"));
    exit();
}
if($record > 0){

    ////////-------------Check of table and record exists
    $rec_exists = checkrowexists("$tbl","uid='$record'");
    if($rec_exists == 0){
        if($rec_exists == 1){
            die(errormes("Table, Record can not be found"));
            exit();
        }
    }
    ////////-------Check if record exists
    $exists = checkrowexists('o_key_values',"tbl='$tbl' AND record='$record' AND key_='$key_'");
    if($exists == 1){
        die(errormes("Record already exists"));
        exit();
    }
}
else{
    die(errormes("Record not selected"));
    exit();
}


$fds = array('tbl','record','key_','value_','added_by','added_date','status');
$vals = array("$tbl","$record","$key_","$value_","$added_by","$added_date","$status");
$create = addtodb('o_key_values',$fds,$vals);
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
