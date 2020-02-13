<?php

require_once "session.php";
sessionStart();

if (!isLoggedIn()) {
    header("location: index.php");
    exit;
}

?>

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
    <title>Personale</title>
</head>
<body>

    <div class="container">
    <h1 class="text-center mt-5 mb-5">Aste Online</h1>
        <div class="row">
            <?php include "navbar.php"; ?>
            <div class="container col-8">
            <div class="alert alert-success" role="alert" id="best-offer">
                
            </div>
            <h5 class="mt-5 mb-3"><b>Riepilogo offerte:</b></h5>
            <!-- TABLE HERE -->
            <div class="container" id="table">
            
            </div>
            <button class="btn btn-primary btn-block"> <a class="text-white" href="logout.php">Logout</a></button>
            </div>
        </div>
    </div>
    
</body>
</html>
