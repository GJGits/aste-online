<?php 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$email=$_POST["email"];
$password=$_POST["password"];
$link=mysqli_connect("127.0.0.1","root","mypasswd","s255089");

if ($link) {

    $query="SELECT COUNT(*) AS co,email FROM utenti WHERE email=? AND PASSWORD=? GROUP BY email";
    $statement=mysqli_prepare($link,$query);
    if(!$statement) {
        echo mysqli_error($link);
    }
    mysqli_stmt_bind_param($statement,"ss",$email,$password);
    mysqli_stmt_execute($statement);
    mysqli_stmt_bind_result($statement,$co,$email);
    mysqli_stmt_fetch($statement);

    if($co==1) {
        $_SESSION["username"] = $email;
        header("location: index.php");
        exit();
    } else {
        $_SESSION["sign-in-error"] = "Credenziali non valide";
        header("location: signin.php");
        exit();
    }

}


?>