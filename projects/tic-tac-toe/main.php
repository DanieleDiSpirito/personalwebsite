<!DOCTYPE html>
<html lang="en">

<!-- Made by Daniele Di Spirito -->

<?php
    session_start();
    if(isset($_POST['001'])) {
        $_SESSION['001'] = ''; // is set now
    } else if(isset($_POST['002'])) {
        $_SESSION['002'] = '';
        unset($_SESSION['001']);
    }
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe</title>
    <link rel="stylesheet" href="style.css"></link>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
    <a href="index.php"><i class="bi bi-arrow-left"></i></a>
    <div id="title">
        TicTacToe <?php 
        if(isset($_SESSION['001'])) {
            echo 'CPU';
        } else {
            echo '1v1';
        }
        ?>
    </div>
    <button type="button" value="0" onclick="window.location.href = ''" style="visibility: hidden">Clicca qui per rigiocare</button>
    <?php for($i = 0; $i < 9; $i++): ?>
    <button value="1" name="<?=$i?>" style="font-size: 100px; text-align: center; font-family: 'Unbounded'">&nbsp;</button>
    <?php endfor; ?>
    <div id="sconfitta" style="display: none" class="sconfitta">Hai perso!</div>
    <div id="patta" style="display: none" class="pareggio">Patta!</div>
    <?php
        if(isset($_SESSION['001'])) {
            echo '<script src="cpu.js"></script>';
        } else {
            echo '<script src="1v1.js"></script>';
        }
    ?>
</body>
</html>
