<?php
session_start();
include_once ("../php_functions/functions.php");
include_once ("../configs/conn.inc");
$staff_id = 1;


$alltotal = countotal("o_notifications","status = 1 AND staff_id = $staff_id","uid");


echo   trim($alltotal);