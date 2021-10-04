<?php
	include('../connect.php');
	$id=$_GET['id'];
	$result = $db->prepare("SELECT * FROM customer WHERE customer_id= :userid");
	$result->bindParam(':userid', $id);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="saveeditcustomer.php" method="post">
<div id="ac">
<input type="hidden" name="memi" value="<?php echo $id; ?>" />
<span>Name : </span><input type="text" name="name" value="<?php echo $row['customer_name']; ?>" /><br>
<span>Address : </span><input type="text" name="address" value="<?php echo $row['address']; ?>" /><br>
<span>Contact : </span><input type="text" name="contact" value="<?php echo $row['contact']; ?>" /><br>
<span>Membership No. : </span><input type="text" name="memno" value="<?php echo $row['membership_number']; ?>" /><br>
<span>&nbsp;</span><input id="btn" type="submit" value="save" />
</div>
</form>
<?php
}
?>