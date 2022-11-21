<?php
    include '../config.php';
    session_start();

    if(isset($_SESSION['aiuti']) and $_SESSION['aiuti'] <= 0) {
        echo json_encode('');
        die();
    }

    if(isset($_SESSION['session'])) {
        $account = json_decode(base64_decode($_SESSION['session']));
    }
    
    $stmt = $mysqli->prepare('SELECT risposta FROM domande WHERE id=?');
    $stmt->bind_param('i', $_SESSION['domanda']);
    $stmt->execute();
    if ($stmt->bind_result($risposta)) {
        $stmt->fetch();
        $stmt->close();
    }

    $risposta = strtolower($risposta);
    $risposta = str_replace(' ', '', $risposta);
    $risposta = str_replace('-', '', $risposta);
    $risposta = str_replace('\'', '', $risposta);

    if(isset($_SESSION['session'])) {
        $stmt = $mysqli->prepare('SELECT indice FROM aiuti WHERE mail_utente=?');
        $stmt->bind_param('s', $account->email);
        $stmt->execute();
        $stmt->bind_result($indice);
        $indici = array();
        while($stmt->fetch()) {
            $indici[] = $indice;
        }
        $stmt->close();
    } else {
        $indici = $_SESSION['idx_aiuti'];
    }

    
    if(count($indici) >= strlen($risposta)) {
        echo json_encode('');
        die();
    }

    do {
        $indice = array_rand(str_split($risposta));
    } while(in_array($indice, $indici));
    $lettera = $risposta[$indice];
    
    if(isset($_SESSION['session'])) {
        $stmt = $mysqli->prepare('INSERT INTO aiuti(mail_utente, indice) VALUES (?, ?)');
        $stmt->bind_param('si', $account->email, $indice);
        $stmt->execute();
    } else {
        $_SESSION['idx_aiuti'][] = $indice;
    }
    
    $_SESSION['aiuti'] -= 1;
    if(isset($_SESSION['session'])) {
        $stmt = $mysqli->prepare('UPDATE utenti_ippocrative SET nAiuti = ? WHERE email = ?;'); 
        $stmt->bind_param('is', $_SESSION['aiuti'], $account->email);
        $stmt->execute();
    }

    echo json_encode(array('indice' => $indice, 'lettera' => $lettera)); // response
?>