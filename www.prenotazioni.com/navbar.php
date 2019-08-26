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
    echo '<li class="list-group-item"><img src="user.png" id="prof-image" alt="..." class="rounded-circle mx-auto d-block bg-primary" height="24" data-toggle="tooltip" data-placement="right" title="' . $_SESSION["username"] . '"></li>';
}

echo '</ul></div>';

?>
