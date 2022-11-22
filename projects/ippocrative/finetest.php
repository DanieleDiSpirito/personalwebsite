<?php
    session_start();
    if(!(isset($_SESSION['end']) and $_SESSION['end'] === true)) {
        die('
        <html style="background-color: #344060; color: white">
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <h1 style="text-align: center; font-weight: 400">Non hai finito il test</h1>
        <div style="text-align: center; font-size: 20px;">Reindirizzamento tra <span id="secondi">3</span></div>
        <script>
        setInterval(() => {
            document.location.href = "index.php";
        }, 3000);
        setInterval(() => {
            document.querySelector("span#secondi").innerHTML = document.querySelector("span#secondi").innerHTML - 1;
        }, 1000);
        </script>
        </html>
        ');
    }
    if(isset($_SESSION['session'])) {
        $account = json_decode(base64_decode($_SESSION['session']));
        include 'config.php';
        $stmt = $mysqli->prepare('UPDATE utenti_ippocrative SET idDomanda = NULL, nAiuti = 10 WHERE utenti_ippocrative.email = ?;');
        $stmt->bind_param('s', $account->email);
        $stmt->execute();
    }
    unset($_SESSION['end']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fine! 🎉</title>
</head>
<body>
    <style>
        * {
            background-color: black;
        }

        img {
            position: absolute;
            width: auto;
            height: 98%;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        span {
            color: white;
            position: absolute;
            top: 90%;
            text-align: center;
            width: 98%;
            font-size: 50px;
            background-color: transparent;
        }
    </style>
    <img src="https://media3.giphy.com/media/14cDsqOkks6O8U/giphy.gif?cid=ecf05e47mdmdr7tqfdziurfoh8tj3d7206b7h2pr04a950zz&rid=giphy.gif&ct=g">
    <span>🎉 CONGRATULAZIONI! 🎉</span>
</body>
</html>
