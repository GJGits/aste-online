<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $email=$_POST["email"];
    $password=$_POST["password"];
    require('env.php');
    $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
    
    if ($link) {
    
        $query="SELECT password FROM utenti WHERE email=?";
        $statement=mysqli_prepare($link,$query);
        if(!$statement) {
            echo mysqli_error($link);
        }
        mysqli_stmt_bind_param($statement,"s",$email);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement,$hashed_pass);
        mysqli_stmt_fetch($statement);
    
        if(password_verify($password, $hashed_pass)) {
            $_SESSION["username"] = $email;
            header("location: index.php");
            exit();
        } else {
            $_SESSION["sign-in-error"] = "Credenziali non valide";
            header("location: signin.php");
            exit();
        }
    
    }
} else {
    header("location: index.php");
    exit();
}

?>