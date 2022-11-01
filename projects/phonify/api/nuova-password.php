<?php

    session_start();
    if($_POST['cod'] === $_SESSION['recupero_password'] and isset($_POST['id']) and is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);
        $password = hash('sha256', $_POST['new_password']);
        include '../config.php';
        $stmt = $mysqli->prepare('UPDATE utenti SET password = ? WHERE idUtente = ?');
        $stmt->bind_param('si', $password, $id);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: ../login.php');

?>