<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}


$title = $_POST['title'];
$description = $_POST['description'];
$row_query = $_POST['row_query'];
$added_by = $userd['uid'];
$added_date = $fulldate;
$viewable_by = $_POST['viewable_by'];
$status = 1;

if(input_available($title) == 0){
    echo errormes("Title is required");
    die();
}elseif(input_length($title,2) == 0){
    echo errormes("Title too short");
    die();
}else{
    if((checkrowexists('o_reports', "title='$title' AND status=1")) == 1){
        echo errormes("Report will similar title exists");
        die();
    }
}


if(input_available($row_query) == 0){
    echo errormes("Query is required");
    die();
}elseif(input_length($row_query,30) == 0){
    echo errormes("Query too short");
    die();
}else{
    if((checkrowexists('o_reports', "row_query='$row_query' AND status=1")) == 1){
        echo errormes("Report will similar query exists");
        die();
    }
}


$fds = array('title','description','row_query','added_by','added_date','viewable_by','status');
$vals = array("$title","$description","$row_query","$added_by","$added_date","$viewable_by","$status");
$create = addtodb('o_reports',$fds,$vals);
if($create == 1)
{
    echo sucmes('Report Created Successfully');
    $proceed = 1;

}
else
{
    echo errormes('Unable to Save Report');
}

?>
<script>
    if("<?php echo $proceed; ?>"){
        setTimeout(function () {
            gotourl("reports");
        },1000);
    }
</script>
