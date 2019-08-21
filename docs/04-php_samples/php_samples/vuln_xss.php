<html>
<body>
<!-- vulnerable to XSS: try for example entering <script>alert("Vulnerable!");</script> -->
<h1>Contact Registration</h1>
<?php 
	if(isset($_GET['name'])) {
		// name is set, process it
		echo "Hello ".$_GET['name'].". Your name has been saved!";
		exit; 
	} else {
		// expression is not set, show form
?>
		<form method="get" action="vuln_xss.php">
		<p> Enter your name:
		<input type="text" name="name" >
		<input type="submit">
		</p>
		</form>
<?php 
	} // end of else branch
?>
</body>
</html>