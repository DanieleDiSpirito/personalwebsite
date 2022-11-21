<?php
    if(session_status() === PHP_SESSION_NONE) session_start();
    if(!isset($mysqli)) include '../config.php';

    if(isset($_SESSION['session'])) {
        $stmt = $mysqli->prepare('DELETE FROM aiuti WHERE mail_utente = ?');
        $stmt->bind_param('s', $account->email);
        $stmt->execute();
        $stmt->close();
    } else {
        $_SESSION['idx_aiuti'] = array();
    }
?>