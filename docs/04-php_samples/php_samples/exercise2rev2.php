<?php
  $passwords=array(
	'bart' => 'sock',
	'homer' => '%xyz34',
	'lisa' => 'nobel',
	'marge' => 'ccccccc',
	'maggie' => '' );
  $primes[0] = 1; $primes[1] = 2; $primes[2] = 3;
?>
<html>
	<head>
		<title>Example</title>
	</head>
	<body>
		<h1>Array</h1>
		<?php 
		switch($_SERVER['REQUEST_METHOD']) {
      		case 'GET': 	$index=$_GET['index'];
      						$name=$_GET['name'];
							break;
      		case 'POST': 	$index=$_POST['index'];
      						$name=$_POST['name'];
							break;
		}
		if (isset($name))
			echo "<p>Password of $name : $passwords[$name]</p>";
		else
			echo "No name provided";
		if (isset($index))
			echo "<p>The prime in position $index is	
				$primes[$index]</p>";
		else
			echo "No index provided";
		?>
	</body>
</html>
