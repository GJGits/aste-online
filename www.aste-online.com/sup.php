<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION["username"])) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // ##### EMAIL CHECK #####

    if(isset($_POST["email"]) && $_POST["email"] != "") {
        $email=$_POST["email"];
        $email_rgx = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

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

    $rgx_pass = '/^(?=(.*\d)+)(?=(.*[!@#$%.-]){2})[0-9a-zA-Z!@#$%.-]{3,}$/';

    if(!preg_match($rgx_pass,$_POST["pass"])) {
        header("location: signup.php");
        exit();
    }

    // ##### RIPETI PASSWORD CHECK #####

    if(!isset($_POST["pass2"]) || $_POST["pass2"] == "" || ($_POST["pass2"] != $_POST["pass"])) {
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
        mysqli_stmt_store_result($statement);
        //mysqli_stmt_fetch($statement);

        if(mysqli_stmt_num_rows($statement)) {
            $_SESSION["email"] = $email;
            $_SESSION["email_error"] = "Esiste già un account con questo indirizzo";
            header("location: signup.php");
            exit();
        } else {
            mysqli_stmt_close($statement);
            $hashed_pass=password_hash($pass1, PASSWORD_BCRYPT);
            $query="INSERT INTO utenti(email,password) VALUES(?,?)";
            $statement=mysqli_prepare($link,$query);
            mysqli_stmt_bind_param($statement,"ss",$email,$hashed_pass);
            mysqli_stmt_execute($statement);
            mysqli_close($link);
            $_SESSION["username"] = $email;
            header("location: index.php");
            exit();
        }
    
    }
} else {
    header("location: index.php");
    exit();
}

?>