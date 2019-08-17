<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$navbar[true]=['Home','Logout'];
$navbar[false]=['Home','Sign-in','Sign-up'];

$link["Home"]="index.php";
$link["Logout"]="logout.php";
$link["Sign-in"]="signin.php";
$link["Sign-up"]="signup.php";

$navbar=$navbar[isset($_SESSION["username"])];

echo "<div class='col-2 mt-5'><ul class='list-group'>";
foreach($navbar as $nav) {
    echo "<li class='list-group-item'><a href='$link[$nav]'>$nav</a></li>";
}
echo '</ul></div>';

?>
