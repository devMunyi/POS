<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

/////----------Session Check
$userd = session_details();
if($userd == null){
    die(errormes("Your session is invalid. Please re-login"));
    exit();
}
/////---------End of session check

$uid = decurl($_POST['uid']);
$title = $_POST['title'];
$description = $_POST['description'];
$row_query = $_POST['row_query'];
$added_by = $userd['uid'];
$added_date = $fulldate;
$viewable_by = $_POST['viewable_by'];
$status = 1;

echo $status;
//echo $uid + 10;

if($uid > 0){}
else{
    echo errormes("The report id is required");
    die();
}

if(input_available($title) == 0){
    echo errormes("Title is required");
    die();
}elseif(input_length($title,2) == 0){
    echo errormes("Title too short");
    die();
}else{
    $title_exists = checkrowexists("o_reports", "title = \"$title\" AND status = 1 AND uid != $uid");
    if($title_exists == 1){
        echo errormes("Report will similar title exists");
        die();
    }
}


if(input_available($row_query) == 0){
    echo errormes("Query is required");
    die();
}elseif(input_length($row_query, 30) == 0){
    echo errormes("Query too short");
    die();
}else{
    if((checkrowexists('o_reports', "row_query=\"$row_query\" AND status=1 AND uid != $uid")) == 1){
        echo errormes("Report will similar query exists");
        die();
    }
}


$update_flds = " title=\"$title\", description=\"$description\", row_query=\"$row_query\", viewable_by=\"$viewable_by\", status=$status";
$update = updatedb('o_reports', $update_flds, "uid=$uid");
if($update == 1){
    echo sucmes('Report Updated Successfully');
    $proceed = 1;

}else{
    echo errormes('Unable to Update Report');
}

?>
<script>
    if("<?php echo $proceed; ?>"){
        setTimeout(function () {
            gotourl("reports?report=<?php echo encurl($uid)?>");
        },1500);
    }
</script>
