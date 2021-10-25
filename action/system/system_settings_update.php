<?php
session_start();
include_once ("../../php_functions/functions.php");
include_once ("../../configs/conn.inc");

/////----------Session Check
$userd = session_details();
if($userd == null){
    echo errormes("Your session is invalid. Please re-login");
    exit();
}
/////---------End of session check


$name = $_POST['name'];
$file_name = $_FILES['file_']['name'];
$file_size = $_FILES['file_']['size'];
$file_tmp = $_FILES['file_']['tmp_name'];

$events = "Settings updated at [$fulldate] by [".$userd['name']."{".$userd['uid']."}"."$username]";
$make_thumbnail = $_POST['make_thumbnail'];
$upload_location = '../../dist/img/';

///////----------Validation
if((input_available($name)) == 0){
    echo errormes("Name is required");
    exit();
}elseif((input_length($name, 2)) == 0){
    echo errormes("Name is too short");
    exit();
}


$allowed_formats = fetchrow("o_customer_document_categories","uid = 1","formats");
$allowed_formats_array = explode(",", "$allowed_formats");

if($file_size > 100){
    if((file_type($file_name, $allowed_formats_array)) == 0){
        echo errormes("This file format is not allowed. Only $allowed_formats ");
        exit();
    }

}
else{
    echo errormes("File not attached or has invalid size");
    exit();
}


$upload = upload_file($file_name, $file_tmp, $upload_location);
if($upload === 0){
    echo errormes("Error uploading file, please retry");
    exit();
}

$file_name_only = pathinfo("$upload", PATHINFO_FILENAME);
if($make_thumbnail == 1) {
    makeThumbnails($upload_location, $upload, 100, 100, "thumb_".$file_name_only);
}


$stored_address = $upload;

//////-----------End of validation
$update_flds = "name=\"$name\", logo=\"$stored_address\"";
$update = updatedb('platform_settings', $update_flds, "uid=1");

if($update == 1){
    echo sucmes('Settings Updated Successfully');
    store_event('platform_settings', 1 ,"$events");
    $proceed = 1;

}else{
        echo errormes('Unable to Update Settings');
        exit();
}

?>

<script>
    let proceed = '<?php echo $proceed; ?>';
    if(proceed == "1"){
        setTimeout(function () {
            reload();
        },1000);
    }
</script>
