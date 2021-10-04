<?php
	include('../connect.php');
	$id=$_GET['id'];
	$result = $db->prepare("SELECT * FROM products WHERE product_id= :userid");
	$result->bindParam(':userid', $id);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="saveeditproduct.php" method="post">
<div id="ac">
<input type="hidden" name="memi" value="<?php echo $id; ?>" />
<span>Code : </span><input type="text" name="code" value="<?php echo $row['product_code']; ?>" /><br>
<span>Name : </span><input type="text" name="name" value="<?php echo $row['product_name']; ?>" /><br>
<span>Cost : </span><input type="text" name="cost" value="<?php echo $row['cost']; ?>" /><br>
<span>Price : </span><input type="text" name="price" value="<?php echo $row['price']; ?>" /><br>
<span>Supplier : </span>
<select name="supplier">
	<option><?php echo $row['supplier']; ?></option>
	<?php
	$results = $db->prepare("SELECT * FROM supliers");
		$results->bindParam(':userid', $res);
		$results->execute();
		for($i=0; $rows = $results->fetch(); $i++){
	?>
		<option><?php echo $rows['suplier_name']; ?></option>
	<?php
	}
	?>
</select><br>
<span>Qty : </span><input type="text" name="qty" value="<?php echo $row['qty']; ?>" /><br>
<span>&nbsp;</span><input id="btn" type="submit" value="save" />
</div>
</form>
<?php
}
?>