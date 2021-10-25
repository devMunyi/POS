<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");
$staff_id = 1;


updatedb("o_notifications","status = 2","staff_id = $staff_id");


echo   trim($alltotal);