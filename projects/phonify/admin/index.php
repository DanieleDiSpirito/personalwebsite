<?php
    session_start();
    if (isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
        $account = json_decode(base64_decode($_SESSION['session']));
    }
    
    if(!isset($account) or $account->username !== 'admin') {
        header('Location: ../');
    }

    echo 'Ciao admin';
?>