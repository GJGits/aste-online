<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Calculator</title>
</head>
<body>
<h1>Calculator:</h1>
<?php 
	if(isset($_GET['op1']) && isset($_GET['op2'])) {
		// variables are set, process them
		$op1=$_GET['op1']; $op2=$_GET['op2'];
		switch($_GET['operation']) {
			case '+' : $res=$op1+$op2;break;
			case '-' : $res=$op1-$op2;break;
			case '*' : $res=$op1*$op2;break;
			case '/' : $res=$op1/$op2;break;
		};
		echo "<p>$op1". $_GET['operation']." $op2 = $res</p>";
		$script= $_SERVER['PHP_SELF'] ; 
		echo "<p><a href=\"$script\">Continue</a></p>";
		echo "<p>Server IP: ".$_SERVER['REMOTE_ADDR'] ."</p>";
		exit; 
	} else {
		// variables are not set, show form
?>
		<form method="get" action="calculator.php">
		<p>
		<input type="text" name="op1" >
		<select name="operation">
			<option value="+" selected="selected" >+</option>
			<option value="-"  >-</option>
			<option value="*"  >*</option>
			<option value="/"  >/</option>
		</select>
		<input type="text" name="op2" >
		<input type="submit" value="=">
		</p>
		</form>
<?php 
	} // end of else branch
?>
</body>
</html>
