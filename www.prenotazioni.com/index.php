<?php 

require_once "session.php";
sessionStart();

$logged=isLoggedIn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="custom.css">    
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
    <script src="custom-js.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Home</title>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">Prenotazioni Mediche</h1>
        <div class="row">
    <?php 
        include "navbar.php"; 
        require "prenotazioni.php";
        echo "<div class='col-9' id='table-owner'>";
        echo "</div>";
        if($logged) {
            echo "<div class='col-9 offset-2'>
                <div class='btn-group offset-4' role='group' aria-label='Basic example'>
                    <button type='button' id='elimina' class='btn btn-warning mr-5'>Cancella prenotazioni</button>
                    <button type='button' id='prenota' class='btn btn-warning' disabled>Prenota</button>
          </div>
            </div>";
            echo '<!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-danger">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    
                  </div>
                </div>
              </div>
            </div>';
        }
    ?>


    <div class="bd-callout" style="border-left-color: #f0ad4e;">
        <h5>Info su prenotazioni</h5>
        <p>Ogni prenotazione ha la durata di un ora, prenotando ad esempio lo slot 
        monday 08:00 la visita durer&agrave; dalle 08:00 alle 09:00</p> 
    </div>
    </div>
    <?php include "noscript.php";?>
</body>
</html>
