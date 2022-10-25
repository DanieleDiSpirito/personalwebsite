<?php
    session_start();
    if (isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
        $account = json_decode(base64_decode($_SESSION['session']));
    } else {
        echo 'Devi essere loggato per utilizzare il carrello';
        exit(0);
    }

    include '../config.php';
    $result = $mysqli->query('SELECT idUtente FROM utenti WHERE username = "' . $account->username . '"');
    
    if ($result->num_rows > 0) {
        if($row = $result->fetch_assoc()) {
            $id_account = $row['idUtente'];
        }
    }

    $id_prodotto = $_GET['id'];

    $stmt = $mysqli->prepare('SELECT idCarrello FROM carrelli WHERE idUtente = ? AND idProdotto = ?;');
    $stmt->bind_param('ii', $id_account, $id_prodotto);
    $stmt->execute();
    if ($stmt->bind_result($id_carrello)) {
        $stmt->fetch();
        $stmt->close();
        if (isset($id_carrello)) {
            remove_from_db($id_carrello);
        } else {
            add_to_db();
        }
    }
    header("Location: " . $_SERVER["HTTP_REFERER"]);

    function add_to_db() {
        global $mysqli, $id_account, $id_prodotto;
        $stmt = $mysqli->prepare('INSERT INTO carrelli(idUtente, idProdotto) VALUES (?, ?)');
        $stmt->bind_param('ii', $id_account, $id_prodotto);
        $stmt->execute();
    }

    function remove_from_db($id) {
        global $mysqli;
        $stmt = $mysqli->prepare('DELETE FROM carrelli WHERE idCarrello = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

?>