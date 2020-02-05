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
    <title>Sign up</title>
</head>
<body>
<div class="container">
        <h1 class="text-center mt-5 mb-5">Aste Online</h1>
        <div class="row">
    <?php include "navbar.php"; ?>
<div class="col-9">
    <form class="col-6 offset-3 bg-warning p-2" action="sup.php" method="post" id="supf">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" value=<?php 
      if(isset($_SESSION["email"])) { 
        echo $_SESSION["email"];
        unset($_SESSION["email"]);
      }
    ?>>
    <?php 
        if(isset($_SESSION["email_error"])) {
          echo '<script>
            $("#email")[0].setCustomValidity("'.$_SESSION["email_error"] .'");
          </script>';
          //echo "<p class='text-danger'>".$_SESSION['email_error']."</p>";
          unset($_SESSION["email_error"]);
        }
    ?>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" name="pass" id="pass" placeholder="Password" required>
  </div>
  <b id="pass-level" class="text-danger">debole</b>
  <div class="form-group mt-3">
    <label for="pass2">Ripeti password</label>
    <input type="password" class="form-control" name="pass2" id="pass2" placeholder="Password" required>
  </div>
  <button type="submit" class="btn btn-primary btn-block mt-5">Sign up</button>
</form>

<?php include "noscript.php";?>
  
  </div>
    
</body>
</html>