<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Expression evaluator</title>
</head>
<body>
<!-- vulnerable: try for example entering www.polito.it & dir (under windows) -->
<h1>Name resolver:</h1>
<?php 
	if(isset($_GET['name'])) {
		// name is set, process it
		system("nslookup ".$_GET['name']);
		$script= $_SERVER['PHP_SELF'] ; 
		echo "<p><a href=\"$script\">Continue</a></p>";
		exit; 
	} else {
		// expression is not set, show form
?>
		<form method="get" action="vuln_cmd.php">
		<p> Name to be resolved:
		<input type="text" name="name" >
		<input type="submit">
		</p>
		</form>
<?php 
	} // end of else branch
?>
</body>
</html>