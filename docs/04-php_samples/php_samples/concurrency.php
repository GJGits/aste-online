<?php 
  session_start();
  if (isset($_SESSION['count'])) {
  	$i=$_SESSION['count'];
  } else { 
  	$i=0;
  } 
?>
<html>
  <head><title>Concurrent Access Count Example</title></head>
  <body>
    <h1> This is your contact number:</h1>
    <p>
    <?php
      echo $i;
      if (isset($_REQUEST['time'])) {
  	    sleep($_REQUEST['time']);
      } else {
  	    sleep(20);
      };
      $_SESSION['count']=$i+1;
      echo "<p>Session ID: ".session_id();
    ?>
 </body>
</html>
