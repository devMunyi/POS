<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="savepur.php" method="post">
<div id="ac">
<span>Date : </span><input type="text" name="date" placeholder="MM/DD/YYYY" /><br>
<span>Invoice Number : </span><input type="text" name="iv" /><br>
<span>Supplier : </span><br>
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
<span>Remarks : </span><input type="text" name="remarks" /><br>
<span>&nbsp;</span><input id="btn" type="submit" value="save" />
</div>
</form>