<?php 
  session_id("unique-session");
  session_start();
  if (isset($_SESSION['count'])) {
  	$i=$_SESSION['count'];
  } else { 
  	$i=0;
  } 
?>
<html>
  <head><title>Concurrent Access Count Example with Unique Session</title></head>
  <body>
    <h1> This is your contact number:</h1>
    <p>
    <?php
      echo $i;
      $_SESSION['count']=$i+1;
      session_write_close();
      if (isset($_REQUEST['time'])) {
  	    sleep($_REQUEST['time']);
      } else {
  	    sleep(20);
      };
 
      echo "<p>Session ID: ".session_id();
    ?>
 </body>
</html>

