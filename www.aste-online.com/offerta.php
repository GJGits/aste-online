<?php 
    require_once "session.php";
    sessionStart();
    require('env.php');

    // get current max off value
    if($_SERVER['REQUEST_METHOD'] === 'GET' && count($_GET) == 0) {
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            $query="SELECT * FROM offer";
            $statement=mysqli_prepare($link,$query);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement,$email, $amount, $timestamp);
            if(mysqli_stmt_fetch($statement)) {
                echo "" . $email . "-" . $amount . "-" . $timestamp;
                exit;
            }

    }

    // get logs table
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["table"])) {
        checkSessionValidity();
        if(isset($_SESSION["username"])) {
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            if ($link) { 
                $result = "";
                 // 1. check if utente migliore offerta
                $query="SELECT user, amount FROM offer";
                $statement=mysqli_prepare($link,$query);
                mysqli_stmt_execute($statement);
                mysqli_stmt_bind_result($statement, $user, $amount);
                mysqli_stmt_fetch($statement);
                mysqli_stmt_close($statement);
                if ($_SESSION["username"] == $user) {
                    $result .= "best;";
                } else {
                    $result .= "nobest;";
                }
                $result .= "<table class='table table-bordered'><thead><th scope='col'>amount</th><th scope='col'>timestamp</th></thead><tbody>";
                // 2. get user table
                $query="SELECT amount, timestamp FROM offers_log WHERE user=? ORDER BY timestamp DESC";
                $statement=mysqli_prepare($link,$query);
                mysqli_stmt_bind_param($statement, "s", $_SESSION["username"]);
                mysqli_stmt_execute($statement);
                mysqli_stmt_bind_result($statement, $amount, $timestamp);
                while(mysqli_stmt_fetch($statement)) {
                    $result .= "<tr scope='row'><td>" . $amount . " </td><td>" . $timestamp . "</td></tr>";
                }
                mysqli_stmt_close($statement);
                mysqli_close($link);
                $result .= "</tbody></table>";
                echo $result;
                exit;
            }

        }
        else {
            echo "scaduta";
            exit;
        }
    }

    // post new offer
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["offri"])) {
        checkSessionValidity();
        if(isset($_SESSION["username"])) {
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            if ($link) { 
                // 1. update max if needed
                $query = "UPDATE offer SET user=?, amount=?, timestamp=DEFAULT where amount < ?";
                $statement=mysqli_prepare($link,$query);
                mysqli_stmt_bind_param($statement, "sdd", $_SESSION["username"], $_POST["value"], $_POST["value"]);
                mysqli_stmt_execute($statement);
                $rows = mysqli_stmt_affected_rows($statement);
                mysqli_stmt_close($statement);
                // 2. log new offer yes: valore aggiornato, no altrimenti
                $query = "INSERT INTO offers_log (user, amount, status, timestamp)
                    VALUES (?, ?, CASE 
                        WHEN (SELECT MAX(amount) FROM offer) < ? THEN 'yes' ELSE 'no' END, DEFAULT)";
                $statement=mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($statement, "sdd", $_SESSION["username"], $_POST["value"], $_POST["value"]);
                mysqli_stmt_execute($statement);
                mysqli_stmt_close($statement);
                mysqli_close($link);
                echo "" . $rows;
                exit;
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