<?php

    include '../config.php';

    header('Content-Type: application/json');
    $json = file_get_contents('php://input');
    $post = json_decode($json);

    $valore = $post->valore;
    $commento = $post->commento;
    $data = date("Y-m-d", strtotime($post->data));
    $idArticolo = $post->idArticolo;
    $email = $post->email;

    $stmt = $mysqli->prepare('INSERT INTO voti VALUES (NULL, ?, ?, ?, ?, ?);');
    $stmt->bind_param('issis', $valore, $commento, $data, $idArticolo, $email);
    $stmt->execute();
    $stmt->close();

?>