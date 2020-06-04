<?php 
    require_once "session.php";
    sessionStart();
    require('env.php');

    define("MAXSIZE", 200);

    // get current max off value
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["inizio"]) && isset($_GET["fine"]) && isset($_GET["partecipanti"])) {
            $inizio = $_GET["inizio"];
            $fine = $_GET["fine"];
            $partecipanti = $_GET["partecipanti"];
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            $query="SELECT user FROM prenotazioni WHERE inizio=? AND fine=? AND partecipanti=?";
            $statement=mysqli_prepare($link,$query);
            mysqli_stmt_bind_param($statement, "ssi", $inizio, $fine, $partecipanti);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement,$user);
            mysqli_stmt_fetch($statement);
            echo $user;
            exit;
    }

    // get logs table
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["table"])) {
        checkSessionValidity();
        $logged = isLoggedIn();
        $personal = isset($_GET["personal"]);
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            if ($link) { 
                $empty = true;
                $result = "";
                $result .= "<table class='table table-bordered table-sm'><thead><th scope='col'>inizio</th><th scope='col'>fine</th><th scope='col'>numero persone</th></thead><tbody>";
                // 2. get user table
                $query= $logged && $personal ? "SELECT inizio, fine, partecipanti FROM prenotazioni WHERE user=? ORDER BY partecipanti DESC" : "SELECT inizio, fine, partecipanti FROM prenotazioni ORDER BY partecipanti DESC";
                $statement=mysqli_prepare($link,$query);
                if($logged && $personal)
                    mysqli_stmt_bind_param($statement, "s", $_SESSION["username"]);
                mysqli_stmt_execute($statement);
                mysqli_stmt_bind_result($statement, $inizio, $fine, $partecipanti);
                while(mysqli_stmt_fetch($statement)) {
                    $empty = false;
                    $result .= "<tr scope='row' data-inizio='" . $inizio . "' data-fine='" . $fine . "' data-partecipanti='" . $partecipanti . "'><td>" . $inizio . " </td><td>" . $fine . "</td><td>" .$partecipanti . "</td></tr>";
                }
                mysqli_stmt_close($statement);
                mysqli_close($link);
                $result .= "</tbody></table>";
                if($empty) {
                    $result = "<div class='alert alert-primary text-center' role='alert'>
                    Attualmente non &egrave; presente nessuna prenotazione!
                  </div>";
                }
                echo $result;
                exit;
            }

    }

    // post new prenotazione
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["offri"])) {
        checkSessionValidity();
        if(isset($_SESSION["username"])) {
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            if ($link) { 
                $time_rgx = '/^\d{1,2}:\d{1,2}$/';
                $inizio = $_POST["hhi"] . ":" . $_POST["mmi"];
                $fine = $_POST["hhf"] . ":" . $_POST["mmf"];
                if (strcmp($inizio, $fine) < 0 && preg_match('/^\d{1,2}:\d{1,2}$/', $inizio) && preg_match('/^\d{1,2}:\d{1,2}$/', $fine)) {
                   // 1. check se non eccedo limite
                $query = "SELECT SUM(partecipanti) AS tot FROM prenotazioni WHERE inizio<=? OR fine>=?";
                $statement=mysqli_prepare($link,$query);
                mysqli_stmt_bind_param($statement, "ss", $inizio, $fine);
                mysqli_stmt_execute($statement);
                mysqli_stmt_bind_result($statement, $sum);
                mysqli_stmt_fetch($statement);
                mysqli_stmt_close($statement);
                if($sum + $_POST["pers"] <= MAXSIZE) {
                // 2. inserisco
                $query = "INSERT INTO prenotazioni(user, inizio, fine, partecipanti) VALUES (?, ?, ?, ?)";
                $statement=mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($statement, "sssi", $_SESSION["username"], $inizio, $fine, $_POST["pers"]);
                mysqli_stmt_execute($statement);
                mysqli_stmt_close($statement);
                mysqli_close($link);
                echo "" . $rows;
                exit;  
                } else {
                    echo "too-much";
                } 
                } else {
                    echo "err-time";
                }
                
                
               
            }
        } else { 
            echo "scaduta";
            exit;
        }

    }

    // no method selected return to index
    header("location: index.php");
    exit;
?>