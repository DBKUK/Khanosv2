<?php
require $_SERVER['DOCUMENT_ROOT'] . '/modules/system/db/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/modules/system/classes/oums.php';
$system = new system();
$Timeout= $system->sqlsorgu("config", "Timeout");

$compid = 1;
session_start();
if (!isset($_SESSION['user'])) {
    
    header('location: index.php');
} else {
    
    if ($Timeout > 0){
    
    $expireAfter = $Timeout; // 5 dakika kullanilmayan kisi islem yaparsa ekran kapanir.
    if (isset($_SESSION['last_action'])) {
        $secondsInactive = time() - $_SESSION['last_action'];
//Convert our minutes into seconds.
        $expireAfterSeconds = $expireAfter * 3600;
//Check to see if they have been inactive for too long.
        if ($secondsInactive >= $expireAfterSeconds) {
//User has been inactive for too long.
//Kill their session.
            session_unset();
            session_destroy();
            header('location: index.php');
        } else {
            $_SESSION['last_action'] = time();
        }
    } else {
        $_SESSION['last_action'] = time();
    }
}
}

