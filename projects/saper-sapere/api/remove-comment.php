<?php

    include '../config.php';

    header('Content-Type: application/json');
    $json = file_get_contents('php://input');
    $post = json_decode($json);

    $codVoto = $post->codVoto;
    $email = $post->email;

    session_start();

    if($email === json_decode(base64_decode($_SESSION['account']))->email) {
        $stmt = $mysqli->prepare('DELETE FROM voti WHERE codVoto = ? AND email = ?;');
        $stmt->bind_param('is', $codVoto, $email);
        $stmt->execute();
        $stmt->close();
    } else {
        die('Non sei tu! Non hackerarmi.');
    }

?>