<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // ##### EMAIL CHECK #####

    if(isset($_POST["email"]) && $_POST["email"] != "") {
        $email=$_POST["email"];
        $email_rgx = "/(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";
        if(!preg_match($email_rgx,$email)) {
           // $_SESSION["email_error"] = "Insert a valid email address";
            header("location: signup.php");
            exit();
        } 
    } else {
       // $_SESSION["email_error"] = "Insert an email address";
        header("location: signup.php");
        exit();
    }

    // ##### PASSWORD CHECK #####

    if(!isset($_POST["pass"]) || $_POST["pass"] == "") {
        // $_SESSION["pass_error"] = "Insert a password";
        header("location: signup.php");
        exit(); 
    }

    // ##### RIPETI PASSWORD CHECK #####

    if(!isset($_POST["pass2"]) || $_POST["pass2"] == "" || ($_POST["pass2"] != $_POST["pass1"])) {
        //$_SESSION["pass2_error"] = "Passwords do not match";
        header("location: signup.php");
        exit(); 
    }

    $email=$_POST["email"];
    $pass1=$_POST["pass"];
    $pass2=$_POST["pass2"];
    
    require('env.php');
    $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
    
    // check user duplicate
    if ($link) {

        $query="SELECT COUNT(*) TOT FROM utenti WHERE email=? GROUP BY email";
        $statement=mysqli_prepare($link,$query);
        mysqli_stmt_bind_param($statement,"s",$email);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement,$co);
        mysqli_stmt_fetch($statement);

        if($co==1) {
            $_SESSION["email_error"] = "Account already exists";
            header("location: index.php");
            exit();
        } else {
            //todo: insert nuovo utente
        }
    
    }
} else {
    header("location: index.php");
    exit();
}

?>