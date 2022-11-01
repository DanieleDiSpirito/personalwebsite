<?php
    session_start();
    if (isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
        $account = json_decode(base64_decode($_SESSION['session']));
    }

    if(!isset($account) or $account->username !== 'admin') {
        echo 'Devi essere admin';
        exit(0);
    }
    
    if(!is_numeric($_GET['id'])) {
        echo 'ID prodotto non valido';
        exit(0);
    }

    $id = intval($_GET['id']);

    include '../config.php';
    $stmt = $mysqli->prepare('DELETE FROM cellulari WHERE idProdotto = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    
    header('Location: ../admin/index.php');
?>