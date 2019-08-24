<?php 

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require('env.php');

    function checkSessionValidity() {
        //TODO: cambiare validità a 120 secondi
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 20)) {
            session_unset();     // unset $_SESSION variable for the run-time 
            session_destroy();   // destroy session data in storage
        } else {
            $_SESSION['LAST_ACTIVITY'] = time();
        }
    }

    function tableColor($em) {
        $cell_color = $em == "free" ? "table-success" : "table-danger";
        $cell_color = (isset($_SESSION["username"]) && ($em == $_SESSION["username"])) ? "table-orange" : $cell_color;
        return $cell_color;
    }

    function loadTable() {
        checkSessionValidity();
        $logged=$_SESSION["username"];
        $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());

        if ($link) {

            $days=["monday","tuesday","wednesday","thursday","friday"];
            $hours=["08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00"];
            
            $query="SELECT *, CASE
                WHEN giorno = 'monday' THEN 1
                WHEN giorno = 'tuesday' THEN 2
                WHEN giorno = 'wednesday' THEN 3
                WHEN giorno = 'thursday' THEN 4
                WHEN giorno = 'friday' THEN 5
                END as dayNum
                FROM prenotazioni
                ORDER BY ora,dayNum";
            
            $statement=mysqli_prepare($link,$query);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement,$email,$giorno,$ora,$timestamp,$dayNum);
            $last_hour="08:00";

            $response = "<table class='table table-bordered'>
            <thead>
              <tr><th scope='col'>hour</th>";
              foreach($days as $day) {
                $response .= "<th scope='col'>$day</th>";
              }
              $response .= "</thead><tbody><tr><td>$last_hour</td>";

            while(mysqli_stmt_fetch($statement)) {
                $cell_color=tableColor($email);
                if($ora != $last_hour) {
                    $response .= "</tr><tr>";
                    $response .= "<td>$ora</td>";
                    $response .= "<td class='$cell_color' data-giorno='$giorno' data-ora='$ora' data-email='$email' data-timestamp='$timestamp'></td>";
                    $last_hour=$ora;
                } else {
                    $response .= "<td class='$cell_color' data-giorno='$giorno' data-ora='$ora' data-email='$email' data-timestamp='$timestamp'></td>";
                }
            }
            $response .= "</tbody></table></div>";


        }

        mysqli_close($link);
        return $response;

    }

    function prenota() {
        checkSessionValidity();
        if(isset($_SESSION["username"])) {
            require('env.php');
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            $query="SELECT email FROM prenotazioni WHERE giorno=? AND ora=?";
            // check if ok
            foreach ($_POST["prenotazioni"] as $key => $value) {
                if($key !== "prenota") {
                    $tokens = explode("-",$value);
                    $giorno = $tokens[0];
                    $ora = $tokens[1] . ":" . $tokens[2];
                    $statement=mysqli_prepare($link,$query);
                    mysqli_stmt_bind_param($statement,"ss",$giorno, $ora);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_bind_result($statement,$email);
                    mysqli_stmt_fetch($statement);
                    if($email != "free") {
                        mysqli_close($link);
                        return "occupato";
                        exit; //?
                    }
                    mysqli_stmt_close($statement);
                }
            }
            // prenotazioni effettuate con successo
            $query="UPDATE prenotazioni SET email=?, timestamp=DEFAULT WHERE email='free' AND giorno=? AND ora=?";
            foreach ($_POST["prenotazioni"] as $key => $value) {
                if($key !== "prenota") {
                    $tokens = explode("-",$value);
                    $giorno = $tokens[0];
                    $ora = $tokens[1] . ":" . $tokens[2];
                    $statement=mysqli_prepare($link,$query);
                    mysqli_stmt_bind_param($statement,"sss",$_SESSION["username"],$giorno, $ora);
                    mysqli_stmt_execute($statement);
                    //mysqli_stmt_bind_result($statement,$email);
                    //mysqli_stmt_fetch($statement);
                    mysqli_stmt_close($statement);
                }
            }
            return $_SESSION["username"]. "," . date("Y-m-d H:i:s");
        } else {
            return "scaduta";
        }
    }

    function elimina() {
        checkSessionValidity();
        if(isset($_SESSION["username"])) { 
            require('env.php');
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            $query="UPDATE prenotazioni SET email='free' WHERE email=?";
            $statement=mysqli_prepare($link,$query);
            mysqli_stmt_bind_param($statement,"s",$_SESSION["username"]);
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
        } else {
            return "scaduta";
        }
    }

    function getInfo($giorno, $ora) {
        $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
        if($link) {
            $query="SELECT email, timestamp FROM prenotazioni where giorno=? and ora=?";
            $statement = mysqli_prepare($link,$query);
            mysqli_stmt_bind_param($statement,"ss",$giorno,$ora);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement,$email,$timestamp);
            mysqli_stmt_fetch($statement);
            mysqli_close($link);
            return $email . "," . $timestamp;
        }
        return "no link";
    }

    if(isset($_POST["prenota"]))
        echo prenota();
    
    if(isset($_POST["elimina"]))
        echo elimina();
    
    if(isset($_POST["load"]))
        echo loadTable();
    
    if(isset($_POST["info"])) 
        echo getInfo($_POST["giorno"], $_POST["ora"]);
?>