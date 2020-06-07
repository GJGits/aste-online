<?php 
    require_once "session.php";
    sessionStart();
    require('env.php');

    define("MAXSIZE", 200);

    // get user di una prenotazione
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"])) {
            $id = $_GET["id"];
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            $query="SELECT user FROM prenotazioni WHERE id=?";
            $statement=mysqli_prepare($link,$query);
            mysqli_stmt_bind_param($statement, "i", $id);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement,$user);
            mysqli_stmt_fetch($statement);
            echo $user;
            exit;
    }

    // get prenotazioni
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["table"])) {
        checkSessionValidity();
        $logged = isLoggedIn();
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            if ($link) { 
                $empty = true;
                $result = "";
                $result .= "<table class='table table-bordered table-sm text-center'><thead><th scope='col'>inizio</th><th scope='col'>fine</th><th scope='col'>numero persone</th><th>Elimina prenotazione</th></thead><tbody>";
                // 2. get user table
                $query= "SELECT id, user, inizio, fine, partecipanti FROM prenotazioni ORDER BY partecipanti DESC";
                $statement=mysqli_prepare($link,$query);
                mysqli_stmt_execute($statement);
                mysqli_stmt_bind_result($statement, $id, $user, $inizio, $fine, $partecipanti);
                while(mysqli_stmt_fetch($statement)) {
                    $empty = false;
                    $delete = $_SESSION["username"] == $user ? "<button data-id='" . $id . "' class='btn btn-danger btn-sm elimina'>elimina prenotazione</button>" : "";
                    $result .= "<tr scope='row' data-id='" . $id ."' data-inizio='" . $inizio . "' data-fine='" . $fine . "' data-partecipanti='" . $partecipanti . "'><td>" . $inizio . " </td><td>" . $fine . "</td><td>" .$partecipanti . "</td> <td>" . $delete . "</td></tr>";
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
                $inizio =  strval($_POST["hhi"]) . ":" . strval($_POST["mmi"]);
                $fine = strval($_POST["hhf"]) . ":" . strval($_POST["mmf"]);
                if ( ($_POST["hhi"] * 60 + $_POST["mmi"]) < ($_POST["hhf"] * 60 + $_POST["mmf"]) && preg_match('/^\d{1,2}:\d{1,2}$/', $inizio) && preg_match('/^\d{1,2}:\d{1,2}$/', $fine)) {
                   // 1. check se non eccedo limite
                    $tini = $_POST["hhi"] * 60 + $_POST["mmi"];
                    $tfini = $_POST["hhf"] * 60 + $_POST["mmf"];
                    $query = "SELECT (MAX(s.partecipanti) + ?) as new_tot FROM slots s where min BETWEEN ? AND ?";
                    $statement=mysqli_prepare($link,$query);
                    mysqli_stmt_bind_param($statement, "iii", $_POST["pers"], $tini, $tfini);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_bind_result($statement, $sum);
                    mysqli_stmt_fetch($statement);
                    mysqli_stmt_close($statement);
                    if($sum <= MAXSIZE) {
                    // 2. inserisco
                    $query = "INSERT INTO prenotazioni(user, inizio, fine, partecipanti, tini, tfini) VALUES (?, ?, ?, ?, ?, ?)";
                    $statement=mysqli_prepare($link, $query);
                    mysqli_stmt_bind_param($statement, "sssiii", $_SESSION["username"], $inizio, $fine, $_POST["pers"], $tini, $tfini);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_close($statement);
                    // 3. aggiorno slots
                    $query = "UPDATE slots SET partecipanti = partecipanti + ? WHERE min BETWEEN ? AND ?";
                    $statement=mysqli_prepare($link, $query);
                    mysqli_stmt_bind_param($statement, "iii", $_POST["pers"], $tini, $tfini);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_close($statement);
                    mysqli_close($link);
                    echo "" . $rows;
                    exit;  
                    } else {
                        echo "too-much";
                        exit();
                } 
                } else {
                    echo "err-time";
                    exit();
                }
                
                
               
            }
        } else { 
            echo "scaduta";
            exit;
        }

    }

    // delete prenotazione
    if($_SERVER["REQUEST_METHOD"] == "DELETE" && isset($_GET["id"])) {
        $id = $_GET["id"];
        //1. check che la prenotazione appartiene all'utente
        checkSessionValidity();
        if(isset($_SESSION["username"])) {
            $link=mysqli_connect(getDbHost(),getDbUser(),getDbPass(),getDbName());
            if ($link) { 
                $query = "SELECT user, tini, tfini, partecipanti FROM prenotazioni WHERE id=?";
                $statement=mysqli_prepare($link,$query);
                mysqli_stmt_bind_param($statement, "i", $id);
                mysqli_stmt_execute($statement);
                mysqli_stmt_bind_result($statement, $user, $tini, $tfini, $partecipanti);
                mysqli_stmt_fetch($statement);
                mysqli_stmt_close($statement);
                //2. se ok elimino prenotazione
                if ($_SESSION["username"] == $user) {
                    $query = "DELETE FROM prenotazioni WHERE id=?";
                    $statement=mysqli_prepare($link,$query);
                    mysqli_stmt_bind_param($statement, "i", $id);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_fetch($statement);
                    mysqli_stmt_close($statement);
                    // 3. aggiorno slots
                    $query = "UPDATE slots SET partecipanti = partecipanti - ? WHERE min BETWEEN ? AND ?";
                    $statement=mysqli_prepare($link, $query);
                    mysqli_stmt_bind_param($statement, "iii", $partecipanti, $tini, $tfini);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_close($statement);
                    mysqli_close($link);
                    echo "ok";
                    exit();
                } else {
                    echo "invalid";
                    exit();
                }
            }
            else {
                echo "db-error";
                exit();
            }
        }
        echo "scaduta";
        exit();
    }

    // no method selected return to index
    header("location: index.php");
    exit();
?>