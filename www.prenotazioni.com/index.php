<?php 
ini_set('session.gc_maxlifetime',120);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$logged=isset($_SESSION["username"]);

function tableColor($em) {
    $cell_color = $em == "free" ? "table-success" : "table-danger";
    $cell_color = (isset($_SESSION["username"]) && ($em == $_SESSION["username"])) ? "table-orange" : $cell_color;
    return $cell_color;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="custom.css">    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="custom-js.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Home</title>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">Prenotazioni Mediche</h1>
        <div class="row">
    <?php include "navbar.php"; ?>

    <?php 
        require('env.php');
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

            echo "<div class='col-9'><table class='table table-bordered'>
            <thead>
              <tr><th scope='col'>hour</th>";
              foreach($days as $day) {
                echo "<th scope='col'>$day</th>";
              }
            echo "</thead><tbody><tr><td>$last_hour</td>";

            while(mysqli_stmt_fetch($statement)) {
                $cell_color=tableColor($email);
                if($ora != $last_hour) {
                    echo "</tr><tr>";
                    echo "<td>$ora</td>";
                    echo "<td class='$cell_color' data-giorno='$giorno' data-ora='$ora' data-email='$email' data-timestamp='$timestamp'></td>";
                    $last_hour=$ora;
                } else {
                    echo "<td class='$cell_color' data-giorno='$giorno' data-ora='$ora' data-email='$email' data-timestamp='$timestamp'></td>";
                }
            }
            echo "</tbody></table></div></div>";


        }

        mysqli_close($link);

        if($logged) {
            echo "<div class='col-9 offset-2'>
                <div class='btn-group offset-4' role='group' aria-label='Basic example'>
                    <button type='button' class='btn btn-warning mr-5'>Cancella prenotazioni</button>
                    <button type='button' class='btn btn-warning'>Prenota</button>
          </div>
            </div>";
            echo "<form id='pre-form' hidden></form>";
        }


    ?>

    <div class="bd-callout" style="border-left-color: #f0ad4e;">
        <h5>Info su prenotazioni</h5>
        <p>Ogni prenotazione ha la durata di un ora, prenotando ad esempio lo slot 
        monday 08:00 la visita durer&agrave; dalle 08:00 alle 09:00</p> 
    </div>
    <?php include "noscript.php";?>

    </div>
</body>
</html>
