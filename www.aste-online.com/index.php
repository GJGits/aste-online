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
        
        <h1 class="text-center mt-5 mb-5">Aste Online</h1>
        <div class="row">
    
    <?php include "navbar.php"; ?>
    
    <div class="col-10">
    <div class="card offset-3 mb-5" style="width: 18rem;">
      <div class="card-header bg-white">
        <h5>Winslow Homer</h5>
        <span class="text-muted">The veteran in a new field</span>
      </div>
      <img class="card-img-top" src="winslow_homer_the_veteran_in_a_new_field.jpg" alt="Card image cap">
      <div class="card-body">
        <p class="card-text text-center">Offerta attuale: <b id="off-value" data-toggle="tooltip" data-placement="right" title="Tooltip on right">1.00&euro;</b></p>
       <?php if($logged): ?>
          <span>Inserire importo: </span> <input id="offerta" type="number" min="1.00" step="0.01" value="1.00"> <button class="btn btn-primary">Offri</button>
        <?php endif; ?>
      </div>
    </div>
    </div>
     
       


    <div class="bd-callout" style="border-left-color: #f0ad4e;">
        <h5>Info su aste</h5>
        <p>Ogni offerta &egrave; multiplo di 0.01 &euro;. In caso di offerte di pari entit&agrave; verr&agrave; presa in considerazione 
        solamente la prima offerta.</p> 
    </div>
    </div>
    <?php include "noscript.php";?>

    <!-- Error Modal -->
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
            </div>;

</body>
</html>
