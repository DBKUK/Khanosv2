<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../modules/login/login.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/khanos.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" method="POST" enctype="multipart/form-data">
      
      <h1 class="h3 mb-3 font-weight-normal">Khanos Please sign in</h1>
      <input type="password" id="inputPassword" name="Pass" class="form-control" placeholder="Password" required>
 
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    
    </form>
  </body>
</html>
