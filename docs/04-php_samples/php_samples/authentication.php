<?php
function verify($a,$b) 
  {return($a== "user" && $b==13);};
$user = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];
if(!isset($user) || verify($user,$password)==false){
  header('WWW-Authenticate: Basic realm="MyRealm"');
  header('HTTP/1.1 401 Unauthorized');
  echo 'Text to appear if user hits cancel';
  exit;
}
else {
  echo 'Correctly authenticated';
} 
?>