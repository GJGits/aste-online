<?php

require_once "session.php";
sessionStart();

$navbar[true]=['Home','Logout'];
$navbar[false]=['Home','Sign-in','Sign-up'];

$link["Home"]="index.php";
$link["Logout"]="logout.php";
$link["Sign-in"]="signin.php";
$link["Sign-up"]="signup.php";

$navbar=$navbar[isLoggedIn()];

echo "<div class='col-2'><ul class='list-group'>";

foreach($navbar as $nav) {
    echo "<li class='list-group-item'><a href='$link[$nav]'>$nav</a></li>";
}

if (isLoggedIn()) {
    echo '<li class="list-group-item"><img src="user.png" alt="..." class="rounded-circle bg-primary mr-2" height="24">' .$_SESSION["username"] .'</li>';
}

echo '</ul></div>';

?>
