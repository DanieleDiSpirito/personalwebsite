<?php

    include '../config.php';

    header('Content-Type: application/json');
    $json = file_get_contents('php://input');
    $post = json_decode($json);

    $valore = $post->valore;
    $commento = $post->commento;
    $data = date("Y-m-d H:i:s", ($post->data/1000 + 3600));
    $idArticolo = $post->idArticolo;
    $email = $post->email;

    session_start();

    if($email === json_decode(base64_decode($_SESSION['account']))->email) {
        $stmt = $mysqli->prepare('INSERT INTO voti VALUES (NULL, ?, ?, ?, ?, ?);');
        $stmt->bind_param('issis', $valore, $commento, $data, $idArticolo, $email);
        $stmt->execute();
        $stmt->close();
    } else {
        die('Non sei tu! Non hackerarmi.');
    }

?>