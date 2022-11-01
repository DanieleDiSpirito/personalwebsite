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
        if($key === 'descrizione') continue;
        if(empty($_POST[$key])) { 
            echo 'Non sono accettati campi vuoti';
            exit(0);
        }
    }
    if(!isset($_POST['marca']) or !isset($_POST['RAM']) or !isset($_POST['capacita']) or !isset($_POST['os'])) {
        echo 'Non sono accettati campi vuoti';
        exit(0);
    } 

    if(empty($_FILES['immagine']['name'])) {
        echo 'Inserire l\'immagine del prodotto';
        exit(0);
    } else {
        $filepath = $_FILES['immagine']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);

        if ($fileSize > 16777215) {
            die("File troppo grande");
        }

        $allowedTypes = [
            'image/png' => 'png',
            'image/jpeg' => 'jpg'
        ];
        
        if (!in_array($filetype, array_keys($allowedTypes))) {
            die("Solo file immagini accettate");
        }

        $immagine = file_get_contents($_FILES['immagine']['tmp_name']);
        upload();
    }

    function upload() {
        global $mysqli;
        $nome = $_POST['nome'];
        $prezzo = floatval($_POST['prezzo']);
        $marca = $_POST['marca'];
        $RAM = $_POST['RAM'];
        $capacita = $_POST['capacita'];
        $colore = $_POST['colore'];
        $os = $_POST['os'];
        $dimensioni = $_POST['dimensioni'];
        $descrizione = $_POST['descrizione'];
        global $immagine;
        $stmt = $mysqli->prepare('INSERT INTO cellulari (nomeProdotto, prezzo, descrizione, marca, RAM, capacita, colore, os, dimensioni, immagine) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $stmt->bind_param('sdssssssdb', $nome, $prezzo, $descrizione, $marca, $RAM, $capacita, $colore, $os, $dimensioni, $immagine);
        //                                s       d          s           s      s        s        s      s         d           s
        $stmt->send_long_data(9, $immagine); // 9 because it's the 9th element in sdssssssdbi (first letter has index 0)
        $stmt->execute();
        $stmt->close();
    }
    
    header('Location: ../admin/index.php');
?>