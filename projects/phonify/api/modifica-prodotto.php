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

    if(empty($_FILES['immagine']['name'])) {
        upload(false);
    } else {
        $immagine = file_get_contents($_FILES['immagine']['tmp_name']);
        upload(true);
    }

    function upload($withimage) {
        global $mysqli;
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $prezzo = floatval($_POST['prezzo']);
        $marca = $_POST['marca'];
        $RAM = $_POST['RAM'];
        $capacita = $_POST['capacita'];
        $colore = $_POST['colore'];
        $os = $_POST['os'];
        $dimensioni = $_POST['dimensioni'];
        $descrizione = $_POST['descrizione'];
        if($withimage === true) {
            global $immagine;
            $stmt = $mysqli->prepare('UPDATE cellulari SET nomeProdotto = ?, prezzo = ?, descrizione = ?, marca = ?, RAM = ?, capacita = ?, colore = ?, os = ?, dimensioni = ?, immagine = ? WHERE idProdotto = ?;');
            $stmt->bind_param('sdssssssdbi', $nome, $prezzo, $descrizione, $marca, $RAM, $capacita, $colore, $os, $dimensioni, $immagine, $id);
            //                                s       d          s           s      s        s        s      s        d           s        i
            $stmt->send_long_data(9, $immagine); // 9 because it's the 9th element in sdssssssdbi (first letter has index 0)
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $mysqli->prepare('UPDATE cellulari SET nomeProdotto = ?, prezzo = ?, descrizione = ?, marca = ?, RAM = ?, capacita = ?, colore = ?, os = ?, dimensioni = ? WHERE idProdotto = ?;');
            $stmt->bind_param('sdssssssdi', $nome, $prezzo, $descrizione, $marca, $RAM, $capacita, $colore, $os, $dimensioni, $id);
            //                                s       d          s           s      s        s        s      s        d        i
            $stmt->execute();
            $stmt->close();
        }
    }
    
    header('Location: ' . $_SERVER["HTTP_REFERER"]);
?>