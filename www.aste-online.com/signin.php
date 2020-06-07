<?php 

require "session.php";
sessionStart(TRUE);

?>

<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="custom.css">    
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="custom-js.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Sign in</title>
</head>
<body>
<div class="container">
        <h1 class="text-center mt-5 mb-5">Aste Online</h1>
        <div class="row">
    <?php include "navbar.php"; ?>
<div class="col-9">
    <form class="col-6 offset-3 bg-warning p-2" >
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="text" class="form-control" id="user" name="user" aria-describedby="emailHelp" placeholder="Enter username" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" name="password" id="pass" placeholder="Password" required>
  </div>
  <p id="sing_err" class="text-danger text-center"><b><?php 
  ?></b></p>
  <button type="button" id="signin" class="btn btn-primary btn-block mt-5">Sign in</button>
  <b class="text-danger text-center" id="sign_err">
  </b>
</form>

<?php include "noscript.php";?>
  
  </div>
    
</body>
</html>