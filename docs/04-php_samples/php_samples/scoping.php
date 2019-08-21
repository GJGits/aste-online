<?php
$acc = 0;

function sum($x) {
	global $acc;
	$acc += $x;
}

function sum2($x) {
	$acc += $x;
}

sum(10); sum(10);
echo $acc;	// prints 20
echo "\n";
sum2(10); sum2(10); // error
echo $acc;  // prints 20

?>