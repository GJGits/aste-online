<?php 

    function checkHTTPS() {
        if ( empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ) { 
            // Redirect su HTTPS
            // eventuale distruzione sessione e cookie relativo
            $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $redirect);
            exit();
        }
    }

    function sessionStart($notLogged = FALSE) {
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (count($_COOKIE) == 0) {
          header("location: nocookie.html");
          exit;
        }

        if($notLogged) {
            checkHTTPS();
        }

        if ($notLogged && isset($_SESSION["username"])) {
            $header = "location: index.php";
            header($header);
            exit;
        }
        
    }

    function checkSessionValidity() {
        if(count($_COOKIE) == 0) {
            header("location: nocookie.html");
            exit;
        }
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
            session_unset();     // unset $_SESSION variable for the run-time 
            session_destroy();   // destroy session data in storage
        } else {
            $_SESSION['LAST_ACTIVITY'] = time();
        }
    }

    function isLoggedIn() {
        return isset($_SESSION["username"]);
    }

?>