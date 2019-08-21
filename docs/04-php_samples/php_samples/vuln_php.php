<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Expression evaluator</title>
</head>
<body>
<!-- vulnerable: try for example entering '3+2; phpinfo() -->
<h1>Expression evaluator:</h1>
<?php 
	if(isset($_GET['expr'])) {
		// expression is set, process it
		eval("\$res=".$_GET['expr'].";");
		echo "<p>".$_GET['expr']." = ".$res."</p>";
		$script= $_SERVER['PHP_SELF'] ; 
		echo "<p><a href=\"$script\">Continue</a></p>";
		exit; 
	} else {
		// expression is not set, show form
?>
		<form method="get" action="vuln_php.php">
		<p>
		<input type="text" name="expr" >
		<input type="submit" value="=">
		</p>
		</form>
<?php 
	} // end of else branch
?>
</body>
</html>