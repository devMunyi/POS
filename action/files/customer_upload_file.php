<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

$userd = session_details();

$title = $_POST['title'];
$description = $_POST['description'];
$category = $_POST['type_'];
$file_name = $_FILES['file_']['name'];
$file_size = $_FILES['file_']['size'];
$file_tmp = $_FILES['file_']['tmp_name'];
$make_thumbnail = $_POST['make_thumbnail'];

$reference_number = $_POST['reference_number'];
$upload_location = '../../uploads_/';



if((input_available($title)) == 0)
{
    die(errormes("Title is required"));
    exit();
}
if($category > 0){

}
else{
    die(errormes("Upload type is required"));
    exit();
}

$allowed_formats = fetchrow("o_customer_document_categories","uid=$category","formats");
$allowed_formats_array = explode(",", $allowed_formats);

if($file_size > 100){
    if((file_type($file_name, $allowed_formats_array)) == 0){
        die(errormes("This file format is not allowed. Only $allowed_formats "));
        exit();
    }

}
else{
    die(errormes("File not attached or has invalid size"));
    exit();
}

$upload = upload_file($file_name,$file_tmp,$upload_location);
if($upload === 0)
{
    echo errormes("Error uploading file, please retry");
    exit();
}
$file_name_only = pathinfo($upload, PATHINFO_FILENAME);
if($make_thumbnail == 1) {
    makeThumbnails($upload_location, $upload, 100, 100, "thumb_".$file_name_only);
}

//echo errormes(makeThumbnails($upload_location, "7UpkJa8zGa.jpg",50,50,"ddd.jpg"));


$added_by = $userd['uid'];
$added_date = $fulldate;
$tbl = $_POST['tbl'];
$rec = $_POST['rec'];
$stored_address = $upload;
$status = 1;

$fds = array('title','description','category','added_by','added_date','tbl','rec','stored_address','status');
$vals = array("$title","$description","$category","$added_by","$added_date","$tbl","$rec","$stored_address","$status");
$create = addtodb('o_documents',$fds,$vals);
if($create == 1)
{
    echo sucmes('File Uploaded Successfully');
    $proceed = 1;

}
else
{
    echo errormes('Unable Upload File');
}

?>
<script>
    if('<?php echo $proceed; ?>'){
        setTimeout(function (){
            reload();
        }, 400);
        upload_list('<?php echo encurl($rec); ?>','EDIT');
    }
</script>
