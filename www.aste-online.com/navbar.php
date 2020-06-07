<?php

require_once "session.php";
sessionStart();

$navbar[true]=['Home', 'Logout'];
$navbar[false]=['Home','Sign-in','Sign-up'];

$link["Home"]="index.php";
$link["Sign-in"]="signin.php";
$link["Sign-up"]="signup.php";
$link["Logout"]="logout.php";

$navbar=$navbar[isLoggedIn()];

echo "<div class='col-2'><ul class='list-group'>";

foreach($navbar as $nav) {
    echo "<li class='list-group-item'><a href='$link[$nav]'>$nav</a></li>";
}
echo '</ul></div>';

?>
