<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'modules/system/login/login.php';
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="favicon.ico">
        <script src="assets/js/jquery-3.3.1.slim.min.js" type="text/javascript" async: false></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
        <title>Khanos Login</title>
        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="assets/css/khanos.css" rel="stylesheet">
    </head>

    <body class="text-center">
        <img src="assets/img/favicon.png" class="login-logo img">
        <div class="login">

            <form class="form-signin" method="POST" enctype="multipart/form-data">
                <input type="password" id="inputPassword" autocomplete="off" name="Pass" class="form-control"  placeholder="Password" autofocus required="required" >
                <button class="btn btn-lg btn-outline-success btn-block" type="submit">Sign in</button>
            </form>
            <div id="keyboard" ></div>
        </div>

        <script src="assets/plugins/keyboard2/lib/js/jkeyboard.js" type="text/javascript"></script>
        <link href="assets/plugins/keyboard2/lib/css/jkeyboard.css" rel="stylesheet" type="text/css"/>
        <script>
            $('#keyboard').jkeyboard({
                layout: "numbers_only",
                input: $('#inputPassword')
            });
        </script>
    </body>
</html>
