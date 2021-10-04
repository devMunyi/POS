<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="saveproduct.php" method="post">
<div id="ac">
<span>Product Code : </span><input type="text" name="code" /><br>
<span>Product Name : </span><input type="text" name="name" /><br>
<span>Cost : </span><input type="text" name="cost" /><br>
<span>Price : </span><input type="text" name="price" /><br>
<span>Supplier : </span>
<br>
<select name="supplier">
	<?php
	include('../connect.php');
	$result = $db->prepare("SELECT * FROM supliers");
		$result->execute();
		for($i=0; $row = $result->fetch(); $i++){
	?>
		<option><?php echo $row['suplier_name']; ?></option>
	<?php
	}
	?>
</select><br>
<span>Qty : </span><input type="text" name="qty" /><br>
<span>&nbsp;</span><input id="btn" type="submit" value="save" />
</div>
</form>