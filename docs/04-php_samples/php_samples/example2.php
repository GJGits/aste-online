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
		$index=2; $name="homer";
		echo "<p>Password of $name : $passwords[$name]</p>";
		echo "<p>The prime in position $index is	
					$primes[$index]</p>";
		?>
	</body>
</html>
