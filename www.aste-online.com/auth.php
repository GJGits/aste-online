<?php 

    // SIGN-UP

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION["username"]) && isset($_POST["user"]) && isset($_POST["pass"]) && isset($_POST["pass2"]) ) {
        
        require_once "session.php";
        sessionStart();
        
        // ##### USER CHECK #####
    
        if($_POST["user"] == "") {
            echo "user-Inserire username";
            exit();
        } 
    
        // ##### PASSWORD CHECK #####
    
        if($_POST["pass"] == "") {
            echo "pass-Inserire una password";
            exit(); 
        }
    
        // ##### RIPETI PASSWORD CHECK #####
    
        if($_POST["pass2"] == "" || ($_POST["pass2"] != $_POST["pass"])) {
            echo "pass2-Le password non coincidono";
            exit(); 
        }
    
        $user=$_POST["user"];
        $pass1=$_POST["pass"];
        $pass2=$_POST["pass2"];
        
        require('env.php');
        $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
        
        // check user duplicate
        if ($link) {
    
            $query="SELECT COUNT(*) TOT FROM users WHERE email=? GROUP BY email";
            $statement=mysqli_prepare($link,$query);
            mysqli_stmt_bind_param($statement,"s",$user);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement,$co);
            mysqli_stmt_store_result($statement);
    
            if(mysqli_stmt_num_rows($statement)) {
                echo "user-Esiste già un account con questo username";
                exit();
            } else {
                mysqli_stmt_close($statement);
                $hashed_pass=password_hash($pass1, PASSWORD_BCRYPT);
                $query="INSERT INTO users(email,password) VALUES(?,?)";
                $statement=mysqli_prepare($link,$query);
                mysqli_stmt_bind_param($statement,"ss",$user,$hashed_pass);
                mysqli_stmt_execute($statement);
                mysqli_close($link);
                $_SESSION["username"] = $user;
                //header("location: index.php");
                echo "ok";
                exit();
            }
        
        }
    } 

    // login

    if(isset($_POST["user"]) && isset($_POST["password"])) {

        require_once "session.php";
        sessionStart();

        $email=$_POST["user"];
        $password=$_POST["password"];

        if ($email == "") {
            echo "user-Inserire username";
            exit;
        }

        if ($password == "") {
            echo "pass-Inserire password";
            exit;
        }

        require('env.php');
        $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
        
        if ($link) {
        
            $query="SELECT password FROM users WHERE email=?";
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
                echo "ok";
                exit();
            } else {
                echo "cred-Credenziali non valide";
                exit();
            }
        
        }
    } 

    // no method selected return to index
    header("location: index.php");
    exit;
    

?>