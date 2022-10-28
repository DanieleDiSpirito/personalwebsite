<?php
    session_start();
    if (isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
        $account = json_decode(base64_decode($_SESSION['session']));
    }

    if(!isset($account) or $account->username !== 'admin') {
        echo 'Devi essere admin';
        exit(0);
    }

    include '../config.php';
    foreach(array_keys($_POST) as $key) {
        $idProdotto = intval(str_replace('quantita', '', $key));
        $quantita = intval($_POST[$key]);
        $stmt = $mysqli->prepare('UPDATE cellulari SET quantita = ? WHERE idProdotto = ?');
        $stmt->bind_param('ii', $quantita, $idProdotto);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: ' . $_SERVER["HTTP_REFERER"]);
?>