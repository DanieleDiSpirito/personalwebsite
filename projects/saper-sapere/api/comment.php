<?php

    include '../config.php';

    header('Content-Type: application/json');
    $json = file_get_contents('php://input');
    $post = json_decode($json);

    $valore = $post->valore;
    $commento = $post->commento;
    $data = $post->data;
    $idArticolo = $post->idArticolo;
    $email = $post->email;

    $stmt = $mysqli->prepare('INSERT INTO voti VALUES (?, ?, ?, ?, ?);');
    $stmt->bind_param('isdis', $valore, $commento, $data, $idArticolo, $email);
    $stmt->execute();
    $stmt->close();

?>