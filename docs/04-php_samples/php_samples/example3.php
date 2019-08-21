<?php
$ingredients[] = "eggs";
$ingredients[] = "salt";
$ingredients[2] = "wheat flour";
$ingredients[31] = "honey";
echo "<P> The array length is ", count($ingredients),".</P>";
echo "<UL> \n " ;
$c=count($ingredients);
for($i=0; $i<$c; $i++) {
  echo "<LI> ", $i+1;
  echo " ",ucwords($ingredients[$i]), " <br>"; 
}
echo "</UL> <BR> 32nd ingredient: ",$ingredients[31],"<br>";
echo "100th ingredient: ", $ingredients[100];
?>
