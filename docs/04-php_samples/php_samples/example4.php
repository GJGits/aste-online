<?php
$ingredients[] = "eggs";
$ingredients[] = "salt";
$ingredients[2] = "wheat flour";
$ingredients[31] = "honey";
echo "<P> The array length is ", count($ingredients),".</P>";
echo "<UL> \n " ;
$c=count($ingredients);
foreach ($ingredients as $key=>$value){
echo "<LI> ", $key;
echo " ",ucwords($value)," <br>"; }
echo "</UL> <BR> 32nd ingredient: ",$ingredients[31],"<br>";
echo "100th ingredient: ", $ingredients[100];
?>
